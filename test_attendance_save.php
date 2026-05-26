<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing attendance save functionality...\n";

try {
    // Test the exact same logic as in the controller
    $testData = [
        'date' => '2026-04-20',
        'classroom' => 'L4SWD',
        'statuses' => [15 => 'present']
    ];
    
    $teacherId = 1;
    
    $attendanceData = [
        'teacher_id' => $teacherId,
        'classroom' => $testData['classroom'] ?? null,
    ];
    
    foreach ($testData['statuses'] ?? [] as $studentId => $status) {
        echo "Processing student ID: $studentId, status: $status\n";
        
        $result = App\Models\Attendance::updateOrCreate(
            ['student_id' => $studentId, 'date' => $testData['date']],
            array_merge($attendanceData, ['status' => $status])
        );
        
        echo "Record created/updated with ID: " . $result->id . "\n";
    }
    
    echo "Test successful!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
