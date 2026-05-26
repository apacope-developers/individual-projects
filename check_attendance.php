<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Checking attendance records for L4SWD today...\n";

$attendances = App\Models\Attendance::where('date', now()->toDateString())
    ->where('classroom', 'L4SWD')
    ->with('student')
    ->get();

echo "Total attendance records: " . $attendances->count() . "\n";

foreach ($attendances as $attendance) {
    echo "- " . $attendance->student->name . ": " . $attendance->status . "\n";
}

// Test the controller logic
echo "\nTesting controller logic...\n";

$students = App\Models\User::where('role', 'student')
    ->where('classroom', 'L4SWD')
    ->orderBy('name')
    ->get();

$attendancesCollection = App\Models\Attendance::with(['student', 'teacher'])
    ->where('date', now()->toDateString())
    ->where('classroom', 'L4SWD')
    ->get()
    ->keyBy('student_id');

echo "Students in L4SWD: " . $students->count() . "\n";
echo "Attendance records keyed by student_id: " . $attendancesCollection->count() . "\n";

foreach ($students as $student) {
    $existing = $attendancesCollection[$student->id] ?? null;
    $status = $existing->status ?? 'present';
    echo "- " . $student->name . ": " . $status . "\n";
}
