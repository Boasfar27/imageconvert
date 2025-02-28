<?php

namespace App\Http\Controllers;

use App\Models\ImageConversion;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageConversionController extends Controller
{
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
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png|max:5120', // Maksimum 5MB
            'quality' => 'nullable|integer|min:1|max:100' // Opsional: kualitas WebP
        ]);

        try {
            $image = $request->file('image');
            $originalName = $image->getClientOriginalName();
            $originalFormat = $image->getClientOriginalExtension();
            $originalSize = $image->getSize();
            
            // Generate nama unik untuk file
            $filename = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '-' . uniqid();
            
            // Simpan gambar original
            $originalPath = $image->storeAs('original', $filename . '.' . $originalFormat, 'public');
            
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
            
            // Konversi ke WebP
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
            
            return redirect()->route('conversions.index')
                ->with('success', 'Gambar berhasil dikonversi ke WebP! Ukuran file berkurang ' . 
                    number_format(($originalSize - $convertedSize) / 1024, 1) . ' KB (' .
                    number_format((($originalSize - $convertedSize) / $originalSize) * 100, 1) . '%)');
                    
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengkonversi gambar: ' . $e->getMessage())
                ->withInput();
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
