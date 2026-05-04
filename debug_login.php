<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Debugging login authentication...\n";

try {
    // Get the admin user
    $user = \App\Models\User::where('email', 'admin@lifeline.com')->first();
    
    if (!$user) {
        echo "❌ User not found\n";
        exit;
    }
    
    echo "✅ User found: " . $user->name . " (ID: " . $user->id . ")\n";
    echo "Email: " . $user->email . "\n";
    echo "Is admin: " . ($user->is_admin ? 'true' : 'false') . "\n";
    echo "Password hash: " . substr($user->password, 0, 20) . "...\n";
    
    // Test password verification
    $testPassword = 'admin123';
    echo "\nTesting password: '$testPassword'\n";
    
    if (\Illuminate\Support\Facades\Hash::check($testPassword, $user->password)) {
        echo "✅ Password verification successful\n";
    } else {
        echo "❌ Password verification failed\n";
    }
    
    // Test Laravel's Auth attempt
    echo "\nTesting Laravel Auth::attempt...\n";
    $credentials = [
        'email' => 'admin@lifeline.com',
        'password' => 'admin123'
    ];
    
    if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
        echo "✅ Auth::attempt successful\n";
        echo "Authenticated user: " . \Illuminate\Support\Facades\Auth::user()->name . "\n";
    } else {
        echo "❌ Auth::attempt failed\n";
    }
    
    // Check session configuration
    echo "\nSession configuration:\n";
    echo "Session driver: " . config('session.driver') . "\n";
    echo "Session lifetime: " . config('session.lifetime') . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
