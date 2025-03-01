<?php

namespace App\Http\Controllers;

use App\Models\ImageConversion;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageConversionController extends Controller
{
    public function __construct()
    {
        // Set PHP limits berdasarkan konfigurasi
        ini_set('upload_max_filesize', config('upload.max_file_size'));
        ini_set('post_max_size', config('upload.post_max_size'));
        ini_set('max_execution_time', config('upload.max_execution_time'));
        ini_set('memory_limit', config('upload.memory_limit'));
    }

    public function index()
    {
        $conversions = auth()->user()->imageConversions()
            ->latest()
            ->paginate(10);
            
        // Hitung total penghematan ukuran
        $totalSaved = $conversions->sum(function ($conversion) {
            return $conversion->original_size - $conversion->converted_size;
        });
        
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
            Log::info('Upload attempt', [
                'file_size' => $request->file('image') ? $request->file('image')->getSize() : 'No file',
                'max_upload_size' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
                'memory_limit' => ini_get('memory_limit'),
                'content_length' => $request->header('Content-Length')
            ]);

            // Cek jika request melebihi post_max_size
            if (empty($_FILES) && empty($_POST) && $request->header('Content-Length') > 0) {
                throw new \Exception('File terlalu besar. Maksimum upload adalah ' . ini_get('post_max_size'));
            }

            $request->validate([
                'image' => [
                    'required',
                    'image',
                    'mimes:jpeg,png',
                    'max:10240', // 10MB dalam kilobytes
                    function ($attribute, UploadedFile $value, $fail) {
                        if ($value->getError() !== UPLOAD_ERR_OK) {
                            $fail($this->getUploadErrorMessage($value->getError()));
                        }
                    },
                ],
                'quality' => 'nullable|integer|min:1|max:100'
            ], [
                'image.required' => 'Silakan pilih file gambar.',
                'image.image' => 'File harus berupa gambar.',
                'image.mimes' => 'Format file harus JPG atau PNG.',
                'image.max' => 'Ukuran file tidak boleh lebih dari 10MB.',
                'quality.integer' => 'Kualitas harus berupa angka.',
                'quality.min' => 'Kualitas minimal 1.',
                'quality.max' => 'Kualitas maksimal 100.'
            ]);

            $image = $request->file('image');
            $originalName = $image->getClientOriginalName();
            $originalFormat = $image->getClientOriginalExtension();
            $originalSize = $image->getSize();

            // Log informasi file
            Log::info('File details', [
                'name' => $originalName,
                'format' => $originalFormat,
                'size' => $originalSize
            ]);

            // Validasi ukuran file
            if ($originalSize > 10 * 1024 * 1024) {
                Log::warning('File too large', ['size' => $originalSize]);
                return redirect()->back()
                    ->with('error', 'Ukuran file terlalu besar. Maksimum 10MB.')
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
            } catch (\Exception $e) {
                Log::error('Error saving original file', [
                    'error' => $e->getMessage(),
                    'file' => $originalName
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
