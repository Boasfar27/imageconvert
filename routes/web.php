<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageConversionController;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('landing');
})->name('landingpage');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $conversions = $user->imageConversions()
            ->latest()
            ->take(5)
            ->get();
        
        $totalSizeReduction = $user->imageConversions()
            ->sum(DB::raw('original_size - converted_size'));
            
        $todayConversions = $user->imageConversions()
            ->whereDate('created_at', today())
            ->count();
            
        return view('dashboard', compact('conversions', 'totalSizeReduction', 'todayConversions'));
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/conversions', [ImageConversionController::class, 'index'])->name('conversions.index');
    Route::get('/conversions/create', [ImageConversionController::class, 'create'])->name('conversions.create');
    Route::post('/conversions', [ImageConversionController::class, 'store'])->name('conversions.store');
    Route::get('/conversions/{conversion}/download', [ImageConversionController::class, 'download'])->name('conversions.download');
    Route::delete('/conversions/{conversion}', [ImageConversionController::class, 'destroy'])->name('conversions.destroy');
});

require __DIR__.'/auth.php';
