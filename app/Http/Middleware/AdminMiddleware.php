<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in and has admin role (role value 2)
        // Make sure we compare integers explicitly 
        if (!Auth::check() || (int)Auth::user()->role !== 2) {
            abort(403, 'Unauthorized action. Admin access required.');
        }

        return $next($request);
    }
} 