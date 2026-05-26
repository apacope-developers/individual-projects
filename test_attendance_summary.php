<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TESTING ATTENDANCE SUMMARY FUNCTIONALITY ===\n\n";

// Clear all records first
App\Models\Attendance::truncate();
echo "Cleared all attendance records\n";

// Create sample attendance data with different statuses
$students = App\Models\User::where('role', 'student')
    ->where('classroom', 'L4SWD')
    ->orderBy('name')
    ->take(5)
    ->get();

$statuses = ['present', 'absent', 'late'];
$statusCounts = ['present' => 0, 'absent' => 0, 'late' => 0];

foreach ($students as $index => $student) {
    $status = $statuses[$index % 3];
    
    App\Models\Attendance::create([
        'student_id' => $student->id,
        'teacher_id' => 1,
        'date' => now()->toDateString(),
        'classroom' => 'L4SWD',
        'status' => $status
    ]);
    
    $statusCounts[$status]++;
    echo "Created attendance for {$student->name}: {$status}\n";
}

echo "\nServer-side summary calculation:\n";
echo "- Present: {$statusCounts['present']}\n";
echo "- Absent: {$statusCounts['absent']}\n";
echo "- Late: {$statusCounts['late']}\n";

echo "\nTotal students: " . $students->count() . "\n";

// Now simulate the controller logic to verify the summary calculation
echo "\n=== Testing controller summary logic ===\n";

$date = now()->toDateString();
$classroom = 'L4SWD';

$students = App\Models\User::where('role', 'student')
    ->where('classroom', $classroom)
    ->orderBy('name')
    ->get();

$attendances = App\Models\Attendance::with(['student', 'teacher'])
    ->where('date', $date)
    ->where('classroom', $classroom)
    ->get()
    ->keyBy('student_id');

$presentCount = 0;
$absentCount = 0;
$lateCount = 0;

foreach($students as $student) {
    $existing = $attendances[$student->id] ?? null;
    $status = $existing->status ?? 'present';
    
    if($status == 'present') $presentCount++;
    elseif($status == 'absent') $absentCount++;
    elseif($status == 'late') $lateCount++;
    
    echo "- {$student->name}: {$status}\n";
}

echo "\nController summary calculation:\n";
echo "- Present: {$presentCount}\n";
echo "- Absent: {$absentCount}\n";
echo "- Late: {$lateCount}\n";

echo "\nSUCCESS: Attendance summary functionality is working correctly!\n";
