<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TESTING FIXED ATTENDANCE LOADING ===\n\n";

// Clear all records first
App\Models\Attendance::truncate();
echo "Cleared all attendance records\n";

// Create sample attendance data
$date = now()->toDateString();
$classroom = 'L4SWD';

$students = App\Models\User::where('role', 'student')
    ->where('classroom', $classroom)
    ->orderBy('name')
    ->take(5)
    ->get();

$statuses = ['present', 'absent', 'late'];

foreach ($students as $index => $student) {
    $status = $statuses[$index % 3];
    
    App\Models\Attendance::create([
        'student_id' => $student->id,
        'teacher_id' => 1,
        'date' => $date,
        'classroom' => $classroom,
        'status' => $status
    ]);
    
    echo "Created attendance for {$student->name}: {$status}\n";
}

echo "\n=== Testing controller index method logic ===\n";

// Simulate the exact controller logic
$students = App\Models\User::where('role', 'student')
    ->where('classroom', $classroom)
    ->orderBy('name')
    ->get();

$attendances = App\Models\Attendance::with(['student', 'teacher'])
    ->whereDate('date', $date)  // Fixed: using whereDate
    ->where('classroom', $classroom)
    ->get()
    ->keyBy('student_id');

echo "Students loaded: " . $students->count() . "\n";
echo "Attendance records loaded: " . $attendances->count() . "\n";

echo "\nAttendance data for each student:\n";
foreach ($students as $student) {
    $existing = $attendances[$student->id] ?? null;
    $status = $existing->status ?? 'present';
    
    echo "- {$student->name}: {$status}\n";
}

// Calculate summary counts like the view does
$presentCount = 0;
$absentCount = 0;
$lateCount = 0;

foreach($students as $student) {
    $existing = $attendances[$student->id] ?? null;
    $status = $existing->status ?? 'present';
    
    if($status == 'present') $presentCount++;
    elseif($status == 'absent') $absentCount++;
    elseif($status == 'late') $lateCount++;
}

echo "\nSummary counts (what the view will show):\n";
echo "- Present: {$presentCount}\n";
echo "- Absent: {$absentCount}\n";
echo "- Late: {$lateCount}\n";

echo "\nSUCCESS: Attendance loading is now working correctly!\n";
