<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if user has required role
        if (in_array(Auth::user()->role, $roles)) {
            return $next($request);
        }

        // If not authorized, redirect back with error
        return redirect()->back()->with('error', 'You do not have permission to access this page.');
    }
}