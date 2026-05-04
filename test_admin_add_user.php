<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\User;
use Illuminate\Http\Request;

echo "=== TESTING ADMIN ADD USER FUNCTIONALITY ===\n\n";

// Test 1: Check if AdminController storeUser method works
echo "1. Testing AdminController storeUser method...\n";

try {
    // Create a mock request
    $mockRequest = new Request([
        'name' => 'Test User',
        'email' => 'testuser' . time() . '@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'is_admin' => false
    ]);

    // Create AdminController instance
    $adminController = new \App\Http\Controllers\AdminController();
    
    // Test the storeUser method
    $result = $adminController->storeUser($mockRequest);
    
    echo "✓ AdminController storeUser method executed successfully\n";
    echo "✓ Response type: " . get_class($result) . "\n";
    
} catch (Exception $e) {
    echo "✗ Error in AdminController storeUser: " . $e->getMessage() . "\n";
}

echo "\n2. Testing User creation in database...\n";

// Test 2: Check if user was actually created
try {
    $testUser = User::where('email', 'like', 'testuser%@example.com')->latest()->first();
    
    if ($testUser) {
        echo "✓ Test user found in database\n";
        echo "  ID: {$testUser->id}\n";
        echo "  Name: {$testUser->name}\n";
        echo "  Email: {$testUser->email}\n";
        echo "  is_admin: " . ($testUser->is_admin ? 'true' : 'false') . "\n";
        echo "  Created: {$testUser->created_at}\n";
        
        // Clean up test user
        $testUser->delete();
        echo "✓ Test user cleaned up\n";
    } else {
        echo "✗ No test user found in database\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error checking database: " . $e->getMessage() . "\n";
}

echo "\n3. Testing validation rules...\n";

// Test 3: Test validation rules
try {
    $mockRequest = new Request([
        'name' => '', // Invalid: empty name
        'email' => 'invalid-email', // Invalid: not a valid email
        'password' => '123', // Invalid: too short
        'password_confirmation' => '456', // Invalid: doesn't match
        'is_admin' => false
    ]);

    $adminController = new \App\Http\Controllers\AdminController();
    $result = $adminController->storeUser($mockRequest);
    
    echo "✗ Validation should have failed but didn't\n";
    
} catch (Illuminate\Validation\ValidationException $e) {
    echo "✓ Validation working correctly\n";
    echo "  Validation errors: " . count($e->errors()) . "\n";
} catch (Exception $e) {
    echo "✗ Unexpected error: " . $e->getMessage() . "\n";
}

echo "\n4. Testing route availability...\n";

// Test 4: Check if route is properly defined
try {
    $routes = app('router')->getRoutes();
    $userStoreRoute = null;
    
    foreach ($routes as $route) {
        if ($route->uri() === 'admin/users' && in_array('POST', $route->methods())) {
            $userStoreRoute = $route;
            break;
        }
    }
    
    if ($userStoreRoute) {
        echo "✓ POST /admin/users route found\n";
        echo "  Action: " . $userStoreRoute->getAction('uses') . "\n";
    } else {
        echo "✗ POST /admin/users route not found\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error checking routes: " . $e->getMessage() . "\n";
}

echo "\n5. Testing database connection...\n";

// Test 5: Check database connection
try {
    $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "✓ Database connection successful\n";
    echo "  Database: " . \Illuminate\Support\Facades\DB::connection()->getDatabaseName() . "\n";
    
    // Check users table
    $userCount = User::count();
    echo "  Total users in database: " . $userCount . "\n";
    
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
}

echo "\n=== RECOMMENDATIONS ===\n";
echo "If all tests pass, the issue might be:\n";
echo "1. JavaScript modal not opening properly\n";
echo "2. Form submission being blocked by browser\n";
echo "3. CSRF token issues\n";
echo "4. Middleware blocking the request\n";
echo "5. Session/Authentication issues\n";

echo "\n=== TROUBLESHOOTING STEPS ===\n";
echo "1. Check browser console for JavaScript errors\n";
echo "2. Check network tab for failed requests\n";
echo "3. Verify admin user is properly authenticated\n";
echo "4. Check if modal is actually opening when button is clicked\n";

?>
