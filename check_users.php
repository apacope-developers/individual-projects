<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Checking database schema and users...\n";

try {
    // Check if is_admin column exists
    $schema = \Illuminate\Support\Facades\Schema::getColumnListing('users');
    echo "Users table columns: " . implode(', ', $schema) . "\n";
    
    if (in_array('is_admin', $schema)) {
        echo "✅ is_admin column exists\n";
    } else {
        echo "❌ is_admin column missing\n";
    }
    
    // Check users
    $users = \App\Models\User::all();
    echo "Total users: " . $users->count() . "\n";
    
    if ($users->count() > 0) {
        $firstUser = $users->first();
        echo "First user: " . $firstUser->name . " (" . $firstUser->email . ")\n";
        if (in_array('is_admin', $schema)) {
            echo "Is admin: " . ($firstUser->is_admin ? 'true' : 'false') . "\n";
        }
    } else {
        echo "No users found in database\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}
