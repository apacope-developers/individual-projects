<?php

echo "=== SYNC BAHATI ANDY FROM MYSQL TO SQLITE ===\n\n";

// Connect to MySQL
try {
    $mysql = new PDO("mysql:host=localhost;dbname=laravel", 'root', '');
    $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Connected to MySQL\n";
    
    // Find bahati andy
    $stmt = $mysql->prepare("SELECT * FROM users WHERE name LIKE ?");
    $stmt->execute(['%bahati%']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "Found user in MySQL: {$user['name']} ({$user['email']})\n";
        echo "is_admin: " . ($user['is_admin'] ?? 'NULL') . "\n\n";
        
        // Bootstrap Laravel
        require_once 'vendor/autoload.php';
        $app = require_once 'bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
        $response = $kernel->handle($request = Illuminate\Http\Request::capture());
        
        // Check if already exists in SQLite
        $existing = \App\Models\User::where('email', $user['email'])->first();
        
        if ($existing) {
            echo "User already exists in SQLite with ID: {$existing->id}\n";
            echo "Current is_admin status: " . ($existing->is_admin ? 'ADMIN' : 'USER') . "\n";
            echo "Would appear in admin panel: " . (!$existing->is_admin ? 'YES' : 'NO - ADMIN') . "\n";
            
            // If he's admin, make him regular user so he appears in admin panel
            if ($existing->is_admin) {
                echo "\n=== MAKING USER VISIBLE IN ADMIN PANEL ===\n";
                $existing->is_admin = false;
                $existing->save();
                echo "✓ Updated {$user['name']} to regular user - now visible in admin panel\n";
            }
        } else {
            echo "Creating user in SQLite...\n";
            $newUser = \App\Models\User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => $user['password'],
                'is_admin' => false, // Make regular user so he appears in admin panel
                'created_at' => $user['created_at'],
                'updated_at' => $user['updated_at']
            ]);
            echo "✓ Created {$newUser->name} in SQLite - now visible in admin panel\n";
        }
        
    } else {
        echo "✗ User 'bahati' not found in MySQL database\n";
    }
    
} catch (PDOException $e) {
    echo "✗ MySQL connection failed: " . $e->getMessage() . "\n";
    echo "\nTrying alternative approach...\n";
    
    // Bootstrap Laravel and manually create the user
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle($request = Illuminate\Http\Request::capture());
    
    echo "Creating 'bahati andy' manually in SQLite...\n";
    
    try {
        $user = \App\Models\User::create([
            'name' => 'bahati andy',
            'email' => 'bahati@example.com',
            'password' => bcrypt('password123'),
            'is_admin' => false
        ]);
        echo "✓ Created user: bahati andy (bahati@example.com)\n";
        echo "✓ Now visible in admin panel\n";
    } catch (Exception $e) {
        echo "✗ Failed to create user: " . $e->getMessage() . "\n";
    }
}

echo "\n=== FINAL SQLITE USER LIST ===\n";

$users = \App\Models\User::all();
echo "Total users: " . $users->count() . "\n";

foreach ($users as $user) {
    $status = $user->is_admin ? 'ADMIN (HIDDEN)' : 'USER (VISIBLE)';
    echo "- {$user->name} ({$user->email}) - {$status}\n";
}

echo "\nDone! Check your admin panel.\n";

?>
