<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Doctor Creation Debug ===\n";

// Test 1: Check if Doctor model works
try {
    $doctorsCount = \App\Models\Doctor::count();
    echo "Current doctors count: $doctorsCount\n";
} catch (Exception $e) {
    echo "ERROR accessing Doctor model: " . $e->getMessage() . "\n";
}

// Test 2: Try to create a doctor manually
try {
    $testData = [
        'first_name' => 'Test',
        'last_name' => 'Doctor',
        'email' => 'test' . time() . '@example.com',
        'phone' => '250' . time(),
        'whatsapp' => null,
        'gender' => 'other',
        'date_of_birth' => '1980-01-01',
        'specialty' => 'General Practice',
        'hospital_clinic' => 'Test Hospital',
        'province' => 'Kigali',
        'district' => 'Kigali',
        'address' => 'Test Address',
        'emergency_contact_name' => 'Emergency Contact',
        'emergency_contact_phone' => '250123456789',
        'status' => 'pending',
        'is_available' => true,
    ];

    echo "Attempting to create doctor with data:\n";
    print_r($testData);

    $doctor = \App\Models\Doctor::create($testData);
    echo "SUCCESS: Doctor created with ID: " . $doctor->id . "\n";
    
    // Verify it was saved
    $newCount = \App\Models\Doctor::count();
    echo "New doctors count: $newCount\n";
    
} catch (Exception $e) {
    echo "ERROR creating doctor: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

// Test 3: Check database connection
try {
    \DB::connection()->getPdo();
    echo "Database connection: OK\n";
} catch (Exception $e) {
    echo "Database connection ERROR: " . $e->getMessage() . "\n";
}

echo "=== End Debug ===\n";
?>
