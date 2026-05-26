<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Creating sample attendance records for L4SWD today...\n";

// Clear existing records first
App\Models\Attendance::where('date', now()->toDateString())
    ->where('classroom', 'L4SWD')
    ->delete();

$students = App\Models\User::where('role', 'student')
    ->where('classroom', 'L4SWD')
    ->orderBy('name')
    ->get();

$statuses = ['present', 'absent', 'late'];

foreach ($students as $index => $student) {
    $status = $statuses[$index % 3];
    
    try {
        App\Models\Attendance::create([
            'student_id' => $student->id,
            'teacher_id' => 1,
            'date' => now()->toDateString(),
            'classroom' => 'L4SWD',
            'status' => $status
        ]);
        echo "- " . $student->name . ": " . $status . "\n";
    } catch (Exception $e) {
        echo "Error for " . $student->name . ": " . $e->getMessage() . "\n";
    }
}

echo "\nTotal attendance records: " . App\Models\Attendance::where('date', now()->toDateString())->where('classroom', 'L4SWD')->count() . "\n";
