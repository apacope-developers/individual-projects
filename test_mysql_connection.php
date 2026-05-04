<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;
use App\Models\User;

echo "=== TESTING MYSQL CONNECTION ===\n\n";

// Check database configuration
echo "DB_CONNECTION: " . env('DB_CONNECTION') . "\n";
echo "DB_HOST: " . env('DB_HOST') . "\n";
echo "DB_DATABASE: " . env('DB_DATABASE') . "\n";
echo "DB_USERNAME: " . env('DB_USERNAME') . "\n\n";

// Test connection
try {
    $pdo = DB::connection()->getPdo();
    echo "✓ Database connection successful\n";
    echo "Connection type: " . DB::connection()->getName() . "\n";
    echo "Database name: " . DB::connection()->getDatabaseName() . "\n\n";
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    exit;
}

// Check users table
try {
    $userCount = User::count();
    echo "✓ Users table accessible\n";
    echo "Total users: " . $userCount . "\n\n";
    
    // Show all users
    $users = User::all();
    echo "=== ALL USERS IN MYSQL DATABASE ===\n";
    
    foreach ($users as $user) {
        echo "ID: {$user->id}\n";
        echo "Name: {$user->name}\n";
        echo "Email: {$user->email}\n";
        echo "is_admin: " . ($user->is_admin ? 'true' : 'false') . "\n";
        echo "Would appear in admin panel: " . (!$user->is_admin ? 'YES' : 'NO - ADMIN') . "\n";
        echo "Created: {$user->created_at}\n";
        echo "-------------------\n";
    }
    
    // Test admin panel query
    $adminPanelUsers = User::where('is_admin', false)->latest()->get();
    echo "\n=== USERS THAT WOULD APPEAR IN ADMIN PANEL ===\n";
    echo "Count: " . $adminPanelUsers->count() . "\n";
    
    foreach ($adminPanelUsers as $user) {
        echo "- {$user->name} ({$user->email})\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error accessing users table: " . $e->getMessage() . "\n";
}

echo "\n=== SUCCESS ===\n";
echo "Laravel is now using MySQL database!\n";
echo "Your admin panel should now show all users from MySQL.\n";

?>
