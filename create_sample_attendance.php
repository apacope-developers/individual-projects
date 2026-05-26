<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Creating sample attendance records for L4SWD today...\n";

$students = App\Models\User::where('role', 'student')
    ->where('classroom', 'L4SWD')
    ->orderBy('name')
    ->get();

$statuses = ['present', 'absent', 'late'];
$statusCounts = ['present' => 0, 'absent' => 0, 'late' => 0];

foreach ($students as $index => $student) {
    // Assign different statuses for demonstration
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
    
    $statusCounts[$status]++;
    echo "- " . $student->name . ": " . $status . "\n";
}

echo "\nSummary:\n";
echo "- Present: " . $statusCounts['present'] . "\n";
echo "- Absent: " . $statusCounts['absent'] . "\n";
echo "- Late: " . $statusCounts['late'] . "\n";
echo "Total: " . array_sum($statusCounts) . "\n";
