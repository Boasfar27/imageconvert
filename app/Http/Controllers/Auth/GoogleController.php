<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use RealRashid\SweetAlert\Facades\Alert;

class GoogleController extends Controller
{
    /**
     * Redirect the user to Google authentication page.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }
    
    /**
     * Handle the callback from Google.
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user exists
            $user = User::where('google_id', $googleUser->id)->orWhere('email', $googleUser->email)->first();
            
            if (!$user) {
                // Create a new user
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => Hash::make(md5(uniqid() . time())), // Random password
                    'email_verified_at' => now(), // Google emails are already verified
                ]);
                
                Alert::success('Success', 'Your account has been created successfully!');
            } else {
                // Update user information if needed
                if (empty($user->google_id)) {
                    $user->update(['google_id' => $googleUser->id]);
                }
                
                if (empty($user->avatar)) {
                    $user->update(['avatar' => $googleUser->avatar]);
                }
                
                if (empty($user->email_verified_at)) {
                    $user->update(['email_verified_at' => now()]);
                }
                
                Alert::success('Success', 'You have been logged in successfully!');
            }
            
            // Login user
            Auth::login($user);
            
            return redirect(RouteServiceProvider::HOME);
        } catch (Exception $e) {
            Alert::error('Error', 'Failed to authenticate with Google. Please try again.');
            return redirect()->route('login');
        }
    }
}
