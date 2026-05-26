<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test creating attendance records
$student = App\Models\User::where('role', 'student')->where('classroom', 'L4SWD')->first();

if ($student) {
    // Create a sample attendance record
    $attendance = App\Models\Attendance::updateOrCreate(
        [
            'student_id' => $student->id,
            'date' => now()->toDateString(),
        ],
        [
            'teacher_id' => 1,
            'classroom' => 'L4SWD',
            'status' => 'present'
        ]
    );
    
    echo "Created/updated attendance for: " . $student->name . "\n";
    
    // Test retrieving attendance records
    $attendances = App\Models\Attendance::where('date', now()->toDateString())
        ->where('classroom', 'L4SWD')
        ->with('student')
        ->get()
        ->keyBy('student_id');
    
    echo "Attendance records for L4SWD today: " . $attendances->count() . "\n";
    
    foreach ($attendances as $attendance) {
        echo "- " . $attendance->student->name . ": " . $attendance->status . "\n";
    }
} else {
    echo "No students found in L4SWD classroom\n";
}
