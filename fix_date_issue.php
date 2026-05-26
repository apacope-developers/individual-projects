<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== FIXING DATE FORMAT ISSUE ===\n\n";

// Clear all records first
App\Models\Attendance::truncate();
echo "Cleared all attendance records\n";

// Test with proper date handling
$studentId = 15;
$date = '2026-04-20';

echo "Testing with proper date handling...\n";

// Use whereDate to compare only the date part
$existing = App\Models\Attendance::whereDate('date', $date)
    ->where('student_id', $studentId)
    ->first();

if ($existing) {
    echo "Found existing record using whereDate: ID {$existing->id}\n";
} else {
    echo "No existing record found\n";
}

// Now try updateOrCreate with proper date handling
try {
    $result = App\Models\Attendance::updateOrCreate(
        ['student_id' => $studentId, 'date' => $date],
        [
            'teacher_id' => 1,
            'classroom' => 'L4SWD',
            'status' => 'present'
        ]
    );
    
    echo "SUCCESS: Record created/updated with ID: {$result->id}\n";
    
    // Verify it was saved correctly
    $verify = App\Models\Attendance::whereDate('date', $date)
        ->where('student_id', $studentId)
        ->first();
    
    echo "Verified: Date stored as '{$verify->date}', Status: {$verify->status}\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== Testing multiple operations ===\n";

// Test creating multiple records
$students = [15, 16, 17];
foreach ($students as $sid) {
    try {
        $result = App\Models\Attendance::updateOrCreate(
            ['student_id' => $sid, 'date' => $date],
            [
                'teacher_id' => 1,
                'classroom' => 'L4SWD',
                'status' => $sid % 2 == 0 ? 'present' : 'absent'
            ]
        );
        echo "Student $sid: SUCCESS (ID: {$result->id})\n";
    } catch (Exception $e) {
        echo "Student $sid: ERROR - " . $e->getMessage() . "\n";
    }
}

echo "\nFinal attendance records:\n";
$all = App\Models\Attendance::all();
foreach ($all as $att) {
    echo "- Student: {$att->student_id}, Date: {$att->date}, Status: {$att->status}\n";
}
