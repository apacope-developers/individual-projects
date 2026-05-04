<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;

echo "=== DATABASE CONFIGURATION CHECK ===\n\n";

// Check current database configuration
echo "DB_CONNECTION: " . env('DB_CONNECTION', 'not set') . "\n";
echo "DB_HOST: " . env('DB_HOST', 'not set') . "\n";
echo "DB_PORT: " . env('DB_PORT', 'not set') . "\n";
echo "DB_DATABASE: " . env('DB_DATABASE', 'not set') . "\n";
echo "DB_USERNAME: " . env('DB_USERNAME', 'not set') . "\n";
echo "DB_PASSWORD: " . (env('DB_PASSWORD') ? '*** SET ***' : 'not set') . "\n\n";

// Check current connection
try {
    $pdo = DB::connection()->getPdo();
    echo "Current connection: " . DB::connection()->getName() . "\n";
    echo "Database file/path: " . DB::connection()->getDatabaseName() . "\n";
    echo "Connection successful: YES\n\n";
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}

// Check if there are multiple database files
$databaseDir = __DIR__ . '/database';
echo "Scanning database directory: {$databaseDir}\n";

if (is_dir($databaseDir)) {
    $files = scandir($databaseDir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $fullPath = $databaseDir . '/' . $file;
            echo "Found: {$file} (" . (is_file($fullPath) ? 'file' : 'directory') . ")\n";
        }
    }
} else {
    echo "Database directory not found\n";
}

echo "\n=== POSSIBLE DATABASE LOCATIONS ===\n";

// Check for common database files
$possibleLocations = [
    __DIR__ . '/database/database.sqlite',
    __DIR__ . '/database/laravel.sqlite',
    __DIR__ . '/database/users.sqlite',
    __DIR__ . '/storage/app/database.sqlite',
    __DIR__ . '/storage/database.sqlite'
];

foreach ($possibleLocations as $location) {
    if (file_exists($location)) {
        echo "✓ Found database file: {$location}\n";
        echo "  Size: " . filesize($location) . " bytes\n";
        echo "  Modified: " . date('Y-m-d H:i:s', filemtime($location)) . "\n";
    } else {
        echo "✗ Not found: {$location}\n";
    }
}

?>
