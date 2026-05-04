<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\User;

echo "=== CREATING TEST USERS ===\n\n";

// Create some test regular users
$testUsers = [
    [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => bcrypt('password123'),
        'is_admin' => false
    ],
    [
        'name' => 'Jane Smith',
        'email' => 'jane@example.com', 
        'password' => bcrypt('password123'),
        'is_admin' => false
    ],
    [
        'name' => 'Robert Johnson',
        'email' => 'robert@example.com',
        'password' => bcrypt('password123'),
        'is_admin' => false
    ]
];

foreach ($testUsers as $userData) {
    // Check if user already exists
    $existingUser = User::where('email', $userData['email'])->first();
    
    if ($existingUser) {
        echo "User {$userData['email']} already exists. Skipping.\n";
        continue;
    }
    
    try {
        $user = User::create($userData);
        echo "✓ Created user: {$user->name} ({$user->email})\n";
    } catch (Exception $e) {
        echo "✗ Failed to create {$userData['email']}: " . $e->getMessage() . "\n";
    }
}

echo "\n=== UPDATED USER COUNTS ===\n";

$allUsers = User::all();
$nonAdminUsers = User::where('is_admin', false)->get();
$adminUsers = User::where('is_admin', true)->get();

echo "Total users: " . $allUsers->count() . "\n";
echo "Regular users (is_admin = false): " . $nonAdminUsers->count() . "\n";
echo "Admin users (is_admin = true): " . $adminUsers->count() . "\n";

echo "\n=== REGULAR USERS LIST ===\n";
foreach ($nonAdminUsers as $user) {
    echo "- {$user->name} ({$user->email})\n";
}

echo "\nDone! You can now visit the admin panel to see the regular users.\n";

?>
