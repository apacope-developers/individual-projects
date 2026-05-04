<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== DATABASE CONNECTION DEBUG ===\n\n";

// Check database connection
try {
    $pdo = DB::connection()->getPdo();
    echo "✓ Database connection successful\n";
    echo "Database name: " . DB::connection()->getDatabaseName() . "\n";
    echo "Connection name: " . DB::connection()->getName() . "\n\n";
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    exit;
}

// Check if users table exists
try {
    $tableExists = DB::getSchemaBuilder()->hasTable('users');
    echo "Users table exists: " . ($tableExists ? 'YES' : 'NO') . "\n";
    
    if ($tableExists) {
        // Get table structure
        $columns = DB::getSchemaBuilder()->getColumnListing('users');
        echo "Users table columns: " . implode(', ', $columns) . "\n\n";
        
        // Count users using raw SQL
        $rawCount = DB::select('SELECT COUNT(*) as count FROM users')[0]->count;
        echo "Raw SQL user count: " . $rawCount . "\n";
        
        // Get all users using raw SQL
        $rawUsers = DB::select('SELECT * FROM users');
        echo "Raw SQL users found: " . count($rawUsers) . "\n\n";
        
        foreach ($rawUsers as $user) {
            echo "Raw User ID: {$user->id}\n";
            echo "Raw Name: {$user->name}\n";
            echo "Raw Email: {$user->email}\n";
            echo "Raw is_admin: ";
            var_dump($user->is_admin);
            echo "Raw Created at: {$user->created_at}\n";
            echo "-------------------\n";
        }
    }
} catch (Exception $e) {
    echo "Error checking users table: " . $e->getMessage() . "\n";
}

echo "\n=== LARAVEL MODEL CHECK ===\n";

// Check Laravel model
try {
    $modelUsers = User::all();
    echo "Laravel model user count: " . $modelUsers->count() . "\n";
    
    foreach ($modelUsers as $user) {
        echo "Model User ID: {$user->id}\n";
        echo "Model Name: {$user->name}\n";
        echo "Model Email: {$user->email}\n";
        echo "Model is_admin: " . ($user->is_admin ? 'true' : 'false') . "\n";
        echo "-------------------\n";
    }
} catch (Exception $e) {
    echo "Error with Laravel model: " . $e->getMessage() . "\n";
}

echo "\n=== RECENT USER CREATION ATTEMPTS ===\n";

// Check if our test users exist by email
$testEmails = ['john@example.com', 'jane@example.com', 'robert@example.com'];

foreach ($testEmails as $email) {
    $user = User::where('email', $email)->first();
    if ($user) {
        echo "✓ Found test user: {$email} (ID: {$user->id})\n";
    } else {
        echo "✗ Missing test user: {$email}\n";
    }
}

?>
