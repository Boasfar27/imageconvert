<?php

namespace App\Http\Controllers;

use App\Models\ImageConversion;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Facades\DB;
use App\Helpers\DateHelper;

class ImageConversionController extends Controller
{
    public function __construct()
    {
        // Set timezone untuk Indonesia
        date_default_timezone_set('Asia/Jakarta');
        
        // Set PHP limits berdasarkan konfigurasi
        ini_set('upload_max_filesize', '100M');
        ini_set('post_max_size', '100M');
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', '300');
        ini_set('max_input_time', '300');
    }

    public function index()
    {
        $conversions = auth()->user()->imageConversions()
            ->latest()
            ->paginate(5);
            
        // Hitung total penghematan ukuran
        $totalSaved = auth()->user()->imageConversions()
            ->sum(DB::raw('original_size - converted_size'));
        
        return view('conversions.index', compact('conversions', 'totalSaved'));
    }

    public function create()
    {
        return view('conversions.create');
    }

    public function store(Request $request)
    {
        try {
            // Log informasi request dan konfigurasi
            Log::info('Upload attempt - Detailed', [
                'file_size' => $request->file('image') ? $request->file('image')->getSize() : 'No file',
                'max_upload_size' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
                'memory_limit' => ini_get('memory_limit'),
                'content_length' => $request->header('Content-Length'),
                'server' => $_SERVER,
                'request_method' => $request->method(),
                'request_headers' => $request->headers->all(),
                'files' => $_FILES,
            ]);

            // Cek jika request melebihi post_max_size
            if (empty($_FILES) && empty($_POST) && $request->header('Content-Length') > 0) {
                Log::error('Upload failed - POST size exceeded', [
                    'content_length' => $request->header('Content-Length'),
                    'post_max_size' => ini_get('post_max_size')
                ]);
                throw new \Exception('File terlalu besar. Maksimum upload adalah ' . ini_get('post_max_size'));
            }

            // Validasi dasar
            $request->validate([
                'image' => [
                    'required',
                    'file', // Tambahkan validasi file
                    'image',
                    'mimes:jpeg,png',
                    'max:102400', // 100MB dalam kilobytes
                ],
                'quality' => 'nullable|integer|min:1|max:100'
            ], [
                'image.required' => 'Silakan pilih file gambar.',
                'image.file' => 'Upload gagal. Silakan coba lagi.',
                'image.image' => 'File harus berupa gambar.',
                'image.mimes' => 'Format file harus JPG atau PNG.',
                'image.max' => 'Ukuran file tidak boleh lebih dari 100MB.',
                'quality.integer' => 'Kualitas harus berupa angka.',
                'quality.min' => 'Kualitas minimal 1.',
                'quality.max' => 'Kualitas maksimal 100.'
            ]);

            $image = $request->file('image');
            
            // Validasi file
            if (!$image || !$image->isValid()) {
                Log::error('Invalid file upload', [
                    'error' => $image ? $image->getError() : 'No file',
                    'error_message' => $image ? $this->getUploadErrorMessage($image->getError()) : 'No file uploaded'
                ]);
                throw new \Exception('File tidak valid atau rusak. Silakan coba lagi.');
            }

            $originalName = $image->getClientOriginalName();
            $originalFormat = $image->getClientOriginalExtension();
            $originalSize = $image->getSize();

            // Log informasi file
            Log::info('File details', [
                'name' => $originalName,
                'format' => $originalFormat,
                'size' => $originalSize,
                'mime' => $image->getMimeType(),
                'error' => $image->getError()
            ]);

            // Validasi ukuran file
            if ($originalSize > 100 * 1024 * 1024) { // 100MB
                Log::warning('File too large', ['size' => $originalSize]);
                return redirect()->back()
                    ->with('error', 'Ukuran file terlalu besar. Maksimum 100MB.')
                    ->withInput();
            }
            
            // Generate nama unik untuk file
            $filename = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '-' . uniqid();
            
            // Simpan gambar original dengan penanganan error
            try {
                $originalPath = $image->storeAs('original', $filename . '.' . $originalFormat, 'public');
                if (!$originalPath) {
                    throw new \Exception('Gagal menyimpan file original');
                }
                
                // Verifikasi file tersimpan
                if (!Storage::disk('public')->exists($originalPath)) {
                    throw new \Exception('File tidak tersimpan dengan benar');
                }
            } catch (\Exception $e) {
                Log::error('Error saving original file', [
                    'error' => $e->getMessage(),
                    'file' => $originalName,
                    'path' => $originalPath ?? 'not set'
                ]);
                throw new \Exception('Gagal menyimpan file. Silakan coba lagi.');
            }
            
            // Buat instance Intervention Image
            $webpImage = Image::make($image);
            
            // Optimize gambar sebelum konversi
            if ($webpImage->width() > 2000) {
                $webpImage->resize(2000, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
            
            // Set kualitas WebP (default: 80)
            $quality = $request->input('quality', 80);
            
            // Konversi ke WebP dengan penanganan error
            try {
                $convertedPath = 'converted/' . $filename . '.webp';
                Storage::disk('public')->put(
                    $convertedPath, 
                    $webpImage->encode('webp', $quality)
                );
                
                // Dapatkan ukuran file hasil konversi
                $convertedSize = Storage::disk('public')->size($convertedPath);
                
                // Simpan data konversi
                $conversion = auth()->user()->imageConversions()->create([
                    'original_name' => $originalName,
                    'original_path' => $originalPath,
                    'converted_path' => $convertedPath,
                    'original_format' => strtoupper($originalFormat),
                    'converted_format' => 'WEBP',
                    'original_size' => $originalSize,
                    'converted_size' => $convertedSize
                ]);

                $savedSize = $originalSize - $convertedSize;
                $savedPercentage = round(($savedSize / $originalSize) * 100, 1);
                
                return redirect()->route('conversions.index')
                    ->with('success', "Berhasil mengkonversi gambar '{$originalName}' dari {$originalFormat} ke WebP! " . 
                        "Ukuran file berkurang dari " . number_format($originalSize / 1024, 1) . " KB menjadi " . 
                        number_format($convertedSize / 1024, 1) . " KB " .
                        "(menghemat {$savedPercentage}%)");
            } catch (\Exception $e) {
                // Hapus file original jika konversi gagal
                Storage::disk('public')->delete($originalPath);
                
                return redirect()->back()
                    ->with('error', "Gagal mengkonversi gambar. Silakan coba lagi dengan gambar lain.")
                    ->withInput();
            }
                    
        } catch (\Exception $e) {
            Log::error('Upload error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Gagal mengupload file: ' . $e->getMessage())
                ->withInput();
        }
    }

    private function getUploadErrorMessage($errorCode)
    {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                return 'File terlalu besar. Maksimum upload adalah ' . ini_get('upload_max_filesize');
            case UPLOAD_ERR_FORM_SIZE:
                return 'File terlalu besar untuk form ini';
            case UPLOAD_ERR_PARTIAL:
                return 'File hanya terupload sebagian. Silakan coba lagi';
            case UPLOAD_ERR_NO_FILE:
                return 'Tidak ada file yang diupload';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Folder temporary tidak ditemukan';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Gagal menyimpan file';
            case UPLOAD_ERR_EXTENSION:
                return 'Upload dihentikan oleh ekstensi PHP';
            default:
                return 'Terjadi kesalahan saat upload file';
        }
    }

    public function download($id)
    {
        $conversion = ImageConversion::findOrFail($id);
        
        // Pastikan user hanya bisa mengunduh file miliknya
        if ($conversion->user_id !== auth()->id()) {
            abort(403);
        }
        
        $path = Storage::disk('public')->path($conversion->converted_path);
        
        return response()->download(
            $path, 
            pathinfo($conversion->original_name, PATHINFO_FILENAME) . '.webp'
        );
    }

    public function destroy($id)
    {
        $conversion = ImageConversion::findOrFail($id);
        
        // Pastikan user hanya bisa menghapus file miliknya
        if ($conversion->user_id !== auth()->id()) {
            abort(403);
        }
        
        // Hapus file fisik
        Storage::disk('public')->delete([
            $conversion->original_path,
            $conversion->converted_path
        ]);
        
        // Hapus record dari database
        $conversion->delete();
        
        return redirect()->route('conversions.index')
            ->with('success', 'File konversi berhasil dihapus.');
    }
}
