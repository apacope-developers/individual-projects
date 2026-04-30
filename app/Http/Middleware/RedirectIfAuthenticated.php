<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\RedirectIfAuthenticated as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Closure;

class RedirectIfAuthenticated extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        return $next($request);
    }
}
