<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageConversionController;

Route::get('/', function () {
    return view('landing');
})->name('landingpage');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('conversions.index');
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
