<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TESTING FIXED CONTROLLER LOGIC ===\n\n";

// Clear all records first
App\Models\Attendance::truncate();
echo "Cleared all attendance records\n";

// Simulate the exact controller logic
$data = [
    'date' => '2026-04-20',
    'classroom' => 'L4SWD',
    'statuses' => [
        15 => 'present',
        16 => 'absent',
        17 => 'late'
    ]
];

$teacherId = 1;
$attendanceData = [
    'teacher_id' => $teacherId,
    'classroom' => $data['classroom'] ?? null,
];

foreach ($data['statuses'] ?? [] as $studentId => $status) {
    echo "Processing student ID: $studentId, status: $status\n";
    
    // First try to find existing record using whereDate
    $existing = App\Models\Attendance::whereDate('date', $data['date'])
        ->where('student_id', $studentId)
        ->first();
    
    if ($existing) {
        echo "  - Found existing record, updating...\n";
        $existing->update(array_merge($attendanceData, ['status' => $status]));
    } else {
        echo "  - Creating new record...\n";
        App\Models\Attendance::create(array_merge(
            ['student_id' => $studentId, 'date' => $data['date']],
            $attendanceData,
            ['status' => $status]
        ));
    }
}

echo "\n=== Testing update scenario ===\n";

// Now try updating the same students with different statuses
$data['statuses'] = [
    15 => 'absent',  // Change from present to absent
    16 => 'present', // Change from absent to present
    17 => 'present'  // Change from late to present
];

foreach ($data['statuses'] ?? [] as $studentId => $status) {
    echo "Updating student ID: $studentId, new status: $status\n";
    
    $existing = App\Models\Attendance::whereDate('date', $data['date'])
        ->where('student_id', $studentId)
        ->first();
    
    if ($existing) {
        echo "  - Found existing record, updating...\n";
        $existing->update(array_merge($attendanceData, ['status' => $status]));
    } else {
        echo "  - Creating new record...\n";
        App\Models\Attendance::create(array_merge(
            ['student_id' => $studentId, 'date' => $data['date']],
            $attendanceData,
            ['status' => $status]
        ));
    }
}

echo "\nFinal attendance records:\n";
$all = App\Models\Attendance::all();
foreach ($all as $att) {
    echo "- Student: {$att->student_id}, Date: {$att->date}, Status: {$att->status}\n";
}

echo "\nSUCCESS: All operations completed without errors!\n";
