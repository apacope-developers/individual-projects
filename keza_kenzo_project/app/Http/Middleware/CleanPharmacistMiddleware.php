<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CleanPharmacistMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'pharmacist') {
            return redirect()->route('dashboard')->with('error', 'Access denied. Pharmacist access required.');
        }

        return $next($request);
    }
}
