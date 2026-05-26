<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class LoginThrottleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $email = $request->input('email');
        $ip = $request->ip();
        
        // Rate limiting by IP
        $ipKey = "login_attempts:{$ip}";
        $ipAttempts = Cache::get($ipKey, 0);
        
        // Rate limiting by email
        $emailKey = "login_attempts:email:{$email}";
        $emailAttempts = Cache::get($emailKey, 0);
        
        // Check if IP is blocked
        if ($ipAttempts >= 10) {
            return back()->withErrors([
                'email' => 'Too many login attempts from this IP. Please try again later.'
            ]);
        }
        
        // Check if email is blocked
        if ($emailAttempts >= 5) {
            return back()->withErrors([
                'email' => 'Too many failed attempts for this email. Please try again later.'
            ]);
        }
        
        $response = $next($request);
        
        // If authentication failed, increment counters
        if (!Auth::check()) {
            Cache::put($ipKey, $ipAttempts + 1, now()->addMinutes(15));
            Cache::put($emailKey, $emailAttempts + 1, now()->addMinutes(15));
            
            // Update user's failed attempts
            if ($email) {
                $user = User::where('email', $email)->first();
                if ($user) {
                    $user->failed_login_attempts++;
                    $user->last_login_ip = $ip;
                    
                    // Lock account after 5 failed attempts
                    if ($user->failed_login_attempts >= 5) {
                        $user->locked_until = now()->addMinutes(30);
                    }
                    
                    $user->save();
                }
            }
        } else {
            // Reset counters on successful login
            Cache::forget($ipKey);
            Cache::forget($emailKey);
            
            // Update user's last login
            $user = Auth::user();
            $user->last_login_at = now();
            $user->last_login_ip = $ip;
            $user->failed_login_attempts = 0;
            $user->locked_until = null;
            $user->save();
        }
        
        return $response;
    }
}
