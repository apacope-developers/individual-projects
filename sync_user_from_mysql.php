<?php

echo "=== USER SYNC FROM MYSQL TO SQLITE ===\n\n";

// First, let's try to connect to MySQL to find the user
try {
    $mysqlHost = 'localhost';
    $mysqlUser = 'root';
    $mysqlPass = '';
    $mysqlDb = 'laravel';
    
    $mysql = new PDO("mysql:host=$mysqlHost;dbname=$mysqlDb", $mysqlUser, $mysqlPass);
    $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Connected to MySQL database: $mysqlDb\n";
    
    // Search for bahati andy in MySQL
    $stmt = $mysql->prepare("SELECT * FROM users WHERE name LIKE ? OR name LIKE ? OR name LIKE ?");
    $stmt->execute(['%bahati%', '%andy%', '%bahati andy%']);
    $mysqlUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($mysqlUsers) . " matching users in MySQL:\n\n";
    
    foreach ($mysqlUsers as $user) {
        echo "MySQL User:\n";
        echo "  ID: {$user['id']}\n";
        echo "  Name: {$user['name']}\n";
        echo "  Email: {$user['email']}\n";
        echo "  is_admin: " . ($user['is_admin'] ?? 'NULL') . "\n";
        echo "  Created: {$user['created_at']}\n";
        echo "  -------------------\n";
    }
    
    if (count($mysqlUsers) > 0) {
        echo "\n=== SYNCING TO LARAVEL SQLITE ===\n";
        
        // Now connect to Laravel and sync the user
        require_once 'vendor/autoload.php';
        $app = require_once 'bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
        $response = $kernel->handle($request = Illuminate\Http\Request::capture());
        
        use App\Models\User;
        
        foreach ($mysqlUsers as $mysqlUser) {
            // Check if user already exists in SQLite
            $existingUser = User::where('email', $mysqlUser['email'])->first();
            
            if ($existingUser) {
                echo "User {$mysqlUser['email']} already exists in SQLite. Skipping.\n";
                continue;
            }
            
            try {
                // Create user in SQLite
                $newUser = User::create([
                    'name' => $mysqlUser['name'],
                    'email' => $mysqlUser['email'],
                    'password' => $mysqlUser['password'], // Keep existing password
                    'is_admin' => $mysqlUser['is_admin'] ?? false,
                    'email_verified_at' => $mysqlUser['email_verified_at'],
                    'remember_token' => $mysqlUser['remember_token'] ?? null,
                    'created_at' => $mysqlUser['created_at'],
                    'updated_at' => $mysqlUser['updated_at']
                ]);
                
                echo "✓ Synced user: {$newUser->name} to SQLite\n";
                echo "  Would appear in admin panel: " . (!$newUser->is_admin ? 'YES' : 'NO - ADMIN') . "\n";
                
            } catch (Exception $e) {
                echo "✗ Failed to sync {$mysqlUser['email']}: " . $e->getMessage() . "\n";
            }
        }
    }
    
} catch (PDOException $e) {
    echo "✗ Could not connect to MySQL: " . $e->getMessage() . "\n";
    echo "\nPossible solutions:\n";
    echo "1. Check your XAMPP MySQL credentials\n";
    echo "2. Make sure MySQL service is running\n";
    echo "3. Check database name 'laravel' exists\n";
    echo "4. Update MySQL connection details in this script\n";
}

echo "\n=== CURRENT SQLITE USERS ===\n";

// Show current SQLite users
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request = Illuminate\Http\Request::capture());

use App\Models\User;

$users = User::all();
echo "Total SQLite users: " . $users->count() . "\n";

foreach ($users as $user) {
    echo "- {$user->name} ({$user->email}) - " . ($user->is_admin ? 'ADMIN' : 'USER') . "\n";
}

?>
