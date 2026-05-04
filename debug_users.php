<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Get all users and check their is_admin status
use App\Models\User;

echo "=== USER DEBUG INFORMATION ===\n\n";

// Get all users
$allUsers = User::all();
echo "Total users in database: " . $allUsers->count() . "\n\n";

foreach ($allUsers as $user) {
    echo "User ID: {$user->id}\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "is_admin: ";
    var_dump($user->is_admin);
    echo "is_admin (boolean): " . ($user->is_admin ? 'true' : 'false') . "\n";
    echo "is_admin (int): " . (int)$user->is_admin . "\n";
    echo "Created at: {$user->created_at}\n";
    echo "-------------------\n";
}

echo "\n=== QUERY RESULTS ===\n";

// Check what the admin controller query returns
$nonAdminUsers = User::where('is_admin', false)->get();
echo "Users with is_admin = false: " . $nonAdminUsers->count() . "\n";

$adminUsers = User::where('is_admin', true)->get();
echo "Users with is_admin = true: " . $adminUsers->count() . "\n";

// Try different query approaches
$explicitFalse = User::where('is_admin', '=', 0)->get();
echo "Users with is_admin = 0: " . $explicitFalse->count() . "\n";

$explicitTrue = User::where('is_admin', '=', 1)->get();
echo "Users with is_admin = 1: " . $explicitTrue->count() . "\n";

$nullCheck = User::whereNull('is_admin')->get();
echo "Users with NULL is_admin: " . $nullCheck->count() . "\n";

echo "\n=== RAW SQL QUERY ===\n";
// Show the actual SQL being executed
$query = User::where('is_admin', false)->toSql();
echo "SQL: " . $query . "\n";

?>
