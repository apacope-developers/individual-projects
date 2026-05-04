<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Admin Route Test ===\n";

// Test 1: Check if admin middleware exists
try {
    $middleware = new \App\Http\Middleware\AdminMiddleware();
    echo "AdminMiddleware class: OK\n";
} catch (Exception $e) {
    echo "ERROR with AdminMiddleware: " . $e->getMessage() . "\n";
}

// Test 2: Check if there are admin users
try {
    $adminUsers = \App\Models\User::where('is_admin', true)->get();
    echo "Admin users count: " . $adminUsers->count() . "\n";
    foreach ($adminUsers as $admin) {
        echo "- " . $admin->name . " (email: " . $admin->email . ")\n";
    }
} catch (Exception $e) {
    echo "ERROR checking admin users: " . $e->getMessage() . "\n";
}

// Test 3: Check routes
try {
    $routes = \Route::getRoutes();
    $adminRoutes = [];
    foreach ($routes as $route) {
        if (strpos($route->uri(), 'admin') !== false) {
            $adminRoutes[] = $route->uri();
        }
    }
    echo "Admin routes found:\n";
    foreach ($adminRoutes as $route) {
        echo "- " . $route . "\n";
    }
} catch (Exception $e) {
    echo "ERROR checking routes: " . $e->getMessage() . "\n";
}

echo "=== End Test ===\n";
?>
