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

echo "=== DEBUGGING BAHATI ANDY USER ===\n\n";

// Search for user by name
$userByName = User::where('name', 'LIKE', '%bahati%')->orWhere('name', 'LIKE', '%andy%')->get();
echo "Users found by name search: " . $userByName->count() . "\n";

foreach ($userByName as $user) {
    echo "Found user: {$user->name} (ID: {$user->id})\n";
    echo "Email: {$user->email}\n";
    echo "is_admin: " . ($user->is_admin ? 'true' : 'false') . "\n";
    echo "is_admin (raw): "; var_dump($user->is_admin);
    echo "Created at: {$user->created_at}\n";
    echo "-------------------\n";
}

echo "\n=== ALL USERS IN DATABASE ===\n";

// Get all users to see everyone
$allUsers = User::all();
echo "Total users: " . $allUsers->count() . "\n\n";

foreach ($allUsers as $user) {
    echo "User ID: {$user->id}\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "is_admin: " . ($user->is_admin ? 'true' : 'false') . "\n";
    echo "Would appear in admin panel: " . (!$user->is_admin ? 'YES' : 'NO - HIDDEN (ADMIN)') . "\n";
    echo "-------------------\n";
}

echo "\n=== ADMIN PANEL QUERY SIMULATION ===\n";

// Simulate the exact query the admin panel uses
$adminPanelUsers = User::where('is_admin', false)->latest()->get();
echo "Users that would appear in admin panel: " . $adminPanelUsers->count() . "\n";

foreach ($adminPanelUsers as $user) {
    echo "- {$user->name} ({$user->email})\n";
}

echo "\n=== CHECKING SPECIFIC NAMES ===\n";

// Check for variations of the name
$nameVariations = ['bahati andy', 'bahati', 'andy', 'Bahati Andy', 'Bahati', 'Andy'];

foreach ($nameVariations as $name) {
    $found = User::where('name', $name)->first();
    if ($found) {
        echo "✓ Found exact match: '{$name}' - ID: {$found->id}, is_admin: " . ($found->is_admin ? 'true' : 'false') . "\n";
    } else {
        echo "✗ No exact match for: '{$name}'\n";
    }
}

?>
