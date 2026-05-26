<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Checking current attendance records for L4SWD today...\n";

$attendances = App\Models\Attendance::where('date', now()->toDateString())
    ->where('classroom', 'L4SWD')
    ->with('student')
    ->get();

echo "Current attendance records: " . $attendances->count() . "\n";

foreach ($attendances as $attendance) {
    echo "- " . $attendance->student->name . ": " . $attendance->status . "\n";
}

// Now let's update some records to have different statuses
echo "\nUpdating attendance records with different statuses...\n";

$students = App\Models\User::where('role', 'student')
    ->where('classroom', 'L4SWD')
    ->orderBy('name')
    ->get();

$statuses = ['present', 'absent', 'late'];

foreach ($students as $index => $student) {
    $status = $statuses[$index % 3];
    
    App\Models\Attendance::updateOrCreate(
        [
            'student_id' => $student->id,
            'date' => now()->toDateString(),
        ],
        [
            'teacher_id' => 1,
            'classroom' => 'L4SWD',
            'status' => $status
        ]
    );
    
    echo "- " . $student->name . ": " . $status . "\n";
}

echo "\nFinal attendance records:\n";

$finalAttendances = App\Models\Attendance::where('date', now()->toDateString())
    ->where('classroom', 'L4SWD')
    ->with('student')
    ->get();

foreach ($finalAttendances as $attendance) {
    echo "- " . $attendance->student->name . ": " . $attendance->status . "\n";
}
