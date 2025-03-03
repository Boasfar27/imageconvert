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
                'files_count' => $request->hasFile('images') ? count($request->file('images')) : 'No files',
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
                'images' => 'required|array',
                'images.*' => [
                    'required',
                    'file',
                    'image',
                    'mimes:jpeg,png',
                    'max:102400', // 100MB dalam kilobytes
                ],
                'quality' => 'required|integer|min:1|max:100'
            ], [
                'images.required' => 'Silakan pilih file gambar.',
                'images.array' => 'Format upload tidak valid.',
                'images.*.required' => 'File gambar tidak boleh kosong.',
                'images.*.file' => 'Upload gagal. Silakan coba lagi.',
                'images.*.image' => 'File harus berupa gambar.',
                'images.*.mimes' => 'Format file harus JPG atau PNG.',
                'images.*.max' => 'Ukuran file tidak boleh lebih dari 100MB.',
                'quality.required' => 'Kualitas harus diisi.',
                'quality.integer' => 'Kualitas harus berupa angka.',
                'quality.min' => 'Kualitas minimal 1.',
                'quality.max' => 'Kualitas maksimal 100.'
            ]);

            $quality = $request->input('quality', 80);
            $successCount = 0;
            $totalSaved = 0;
            $errors = [];

            foreach ($request->file('images') as $image) {
                try {
                    if (!$image || !$image->isValid()) {
                        Log::error('Invalid file upload', [
                            'error' => $image ? $image->getError() : 'No file',
                            'error_message' => $image ? $this->getUploadErrorMessage($image->getError()) : 'No file uploaded'
                        ]);
                        $errors[] = 'File tidak valid atau rusak: ' . $image->getClientOriginalName();
                        continue;
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

                    // Generate nama unik untuk file
                    $filename = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '-' . uniqid();
                    
                    // Simpan gambar original
                    $originalPath = $image->storeAs('original', $filename . '.' . $originalFormat, 'public');
                    if (!$originalPath || !Storage::disk('public')->exists($originalPath)) {
                        throw new \Exception('Gagal menyimpan file original');
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
                    
                    // Konversi ke WebP
                    $convertedPath = 'converted/' . $filename . '.webp';
                    Storage::disk('public')->put(
                        $convertedPath, 
                        $webpImage->encode('webp', $quality)
                    );
                    
                    // Dapatkan ukuran file hasil konversi
                    $convertedSize = Storage::disk('public')->size($convertedPath);
                    
                    // Simpan data konversi
                    auth()->user()->imageConversions()->create([
                        'original_name' => $originalName,
                        'original_path' => $originalPath,
                        'converted_path' => $convertedPath,
                        'original_format' => strtoupper($originalFormat),
                        'converted_format' => 'WEBP',
                        'original_size' => $originalSize,
                        'converted_size' => $convertedSize,
                        'quality' => $quality
                    ]);

                    $successCount++;
                    $totalSaved += ($originalSize - $convertedSize);

                } catch (\Exception $e) {
                    Log::error('Error processing file', [
                        'file' => $originalName ?? 'unknown',
                        'error' => $e->getMessage()
                    ]);
                    
                    // Hapus file original jika ada error
                    if (isset($originalPath)) {
                        Storage::disk('public')->delete($originalPath);
                    }
                    
                    $errors[] = "Gagal memproses file: " . ($originalName ?? 'unknown');
                }
            }

            if ($successCount > 0) {
                $message = "Berhasil mengkonversi {$successCount} gambar ke WebP! " . 
                    "Total penghematan ukuran: " . number_format($totalSaved / 1024, 1) . " KB";
                
                if (count($errors) > 0) {
                    $message .= "\n\nBeberapa file gagal diproses:\n" . implode("\n", $errors);
                    return redirect()->route('conversions.index')
                        ->with('warning', $message);
                }
                
                return redirect()->route('conversions.index')
                    ->with('success', $message);
            }

            return redirect()->back()
                ->with('error', "Gagal mengkonversi semua gambar:\n" . implode("\n", $errors))
                ->withInput();
                
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

    public function downloadSelected(Request $request)
    {
        $request->validate([
            'selected_files' => 'required|array',
            'selected_files.*' => 'exists:image_conversions,id'
        ]);

        $conversions = ImageConversion::whereIn('id', $request->selected_files)
            ->where('user_id', auth()->id())
            ->get();

        if ($conversions->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada file yang dipilih untuk diunduh.');
        }

        $zip = new \ZipArchive();
        $zipName = 'converted_images_' . date('Y-m-d_H-i-s') . '.zip';
        $zipPath = storage_path('app/public/temp/' . $zipName);

        // Buat direktori temp jika belum ada
        if (!Storage::disk('public')->exists('temp')) {
            Storage::disk('public')->makeDirectory('temp');
        }

        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            foreach ($conversions as $conversion) {
                $filePath = Storage::disk('public')->path($conversion->converted_path);
                if (file_exists($filePath)) {
                    $zip->addFile(
                        $filePath,
                        pathinfo($conversion->original_name, PATHINFO_FILENAME) . '.webp'
                    );
                }
            }
            $zip->close();

            return response()->download($zipPath)->deleteFileAfterSend(true);
        }

        return redirect()->back()->with('error', 'Gagal membuat file zip.');
    }

    public function downloadAll()
    {
        $conversions = auth()->user()->imageConversions;

        if ($conversions->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada file untuk diunduh.');
        }

        $zip = new \ZipArchive();
        $zipName = 'all_converted_images_' . date('Y-m-d_H-i-s') . '.zip';
        $zipPath = storage_path('app/public/temp/' . $zipName);

        // Buat direktori temp jika belum ada
        if (!Storage::disk('public')->exists('temp')) {
            Storage::disk('public')->makeDirectory('temp');
        }

        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            foreach ($conversions as $conversion) {
                $filePath = Storage::disk('public')->path($conversion->converted_path);
                if (file_exists($filePath)) {
                    $zip->addFile(
                        $filePath,
                        pathinfo($conversion->original_name, PATHINFO_FILENAME) . '.webp'
                    );
                }
            }
            $zip->close();

            return response()->download($zipPath)->deleteFileAfterSend(true);
        }

        return redirect()->back()->with('error', 'Gagal membuat file zip.');
    }

    public function destroyAll()
    {
        $conversions = auth()->user()->imageConversions;

        if ($conversions->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada file untuk dihapus.');
        }

        foreach ($conversions as $conversion) {
            // Hapus file fisik
            Storage::disk('public')->delete([
                $conversion->original_path,
                $conversion->converted_path
            ]);
        }

        // Hapus semua record dari database
        auth()->user()->imageConversions()->delete();

        return redirect()->route('conversions.index')
            ->with('success', 'Semua file konversi berhasil dihapus.');
    }
}
