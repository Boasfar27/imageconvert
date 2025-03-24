<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageConversionController;
use App\Http\Controllers\DonationController;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

Route::get('/', function () {
    return view('landing');
})->name('landingpage');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        // Get total conversions count
        $totalConversions = $user->imageConversions()->count();
        
        // Get paginated conversions
        $conversions = $user->imageConversions()
            ->latest()
            ->paginate(5);
        
        $totalSizeReduction = $user->imageConversions()
            ->sum(DB::raw('original_size - converted_size'));
            
        $todayConversions = $user->imageConversions()
            ->whereDate('created_at', today())
            ->count();
        
        // Get remaining conversions
        $remainingConversions = $user->remainingConversions();
        $isLimited = $user->role === 0;
        
        // Check if the user just verified their email
        if (request()->query('verified') == 1) {
            Alert::success('Verification Successful', 'Your email has been successfully verified. You now have full access to all features!');
        }
            
        return view('dashboard', compact('conversions', 'totalSizeReduction', 'todayConversions', 'totalConversions', 'remainingConversions', 'isLimited'));
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/conversions', [ImageConversionController::class, 'index'])->name('conversions.index');
    Route::get('/conversions/create', [ImageConversionController::class, 'create'])->name('conversions.create');
    Route::post('/conversions', [ImageConversionController::class, 'store'])->name('conversions.store');
    Route::get('/conversions/{conversion}/download', [ImageConversionController::class, 'download'])->name('conversions.download');
    Route::delete('/conversions/{conversion}', [ImageConversionController::class, 'destroy'])->name('conversions.destroy');
    Route::post('/conversions/download-selected', [ImageConversionController::class, 'downloadSelected'])->name('conversions.download-selected');
    Route::get('/conversions/download-all', [ImageConversionController::class, 'downloadAll'])->name('conversions.download-all');
    Route::delete('/conversions', [ImageConversionController::class, 'destroyAll'])->name('conversions.destroy-all');
    
    // Donation routes
    Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');
    Route::post('/donations', [DonationController::class, 'donate'])->name('donations.donate');
    Route::post('/donations/upgrade', [DonationController::class, 'upgrade'])->name('donations.upgrade');
    Route::get('/donations/history', [DonationController::class, 'history'])->name('donations.history');
    Route::get('/donations/{donation}/payment-proof', [DonationController::class, 'showPaymentProof'])->name('donations.payment-proof');
    Route::get('/donations/{donation}/view-proof', [DonationController::class, 'showPaymentProofPage'])->name('donations.view-proof');
    
    // Admin actions for donations
    Route::middleware(['auth'])->group(function () {
        Route::post('/donations/{donation}/approve', [DonationController::class, 'approve'])
            ->middleware(\App\Http\Middleware\AdminMiddleware::class)
            ->name('donations.approve');
        Route::post('/donations/{donation}/reject', [DonationController::class, 'reject'])
            ->middleware(\App\Http\Middleware\AdminMiddleware::class)
            ->name('donations.reject');
    });
});

require __DIR__.'/auth.php';
