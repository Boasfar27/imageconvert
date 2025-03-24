<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            Auth::login($user);
            
            // Trigger the verification email
            $user->sendEmailVerificationNotification();

            Alert::success('Registration Successful', 'Your account has been created successfully. Please verify your email address.')
                ->toast()
                ->position('top-end')
                ->timerProgressBar()
                ->autoClose(5000);

            return redirect()->route('verification.notice');
        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());
            
            Alert::error('Registration Failed', 'There was a problem creating your account. Please try again.')
                ->toast()
                ->position('top-end')
                ->timerProgressBar()
                ->autoClose(5000);
            
            return back()->withErrors(['error' => 'Registration failed. Please try again.'])->withInput();
        }
    }
}
