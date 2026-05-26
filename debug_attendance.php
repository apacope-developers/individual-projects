<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEBUGGING ATTENDANCE ISSUE ===\n\n";

// Check current attendance records
echo "Current attendance records:\n";
$attendances = App\Models\Attendance::all();
echo "Total records: " . $attendances->count() . "\n";

foreach ($attendances as $att) {
    echo "- ID: {$att->id}, Student: {$att->student_id}, Date: {$att->date}, Status: {$att->status}\n";
}

echo "\n=== Testing updateOrCreate ===\n";

// Test the exact scenario that's failing
$studentId = 15;
$date = '2026-04-20';

echo "Testing student_id: $studentId, date: $date\n";

// First, check if record exists
$existing = App\Models\Attendance::where('student_id', $studentId)->where('date', $date)->first();
if ($existing) {
    echo "Found existing record: ID {$existing->id}\n";
} else {
    echo "No existing record found\n";
}

// Now try updateOrCreate
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
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    
    // Let's also try a manual approach
    echo "\n=== Trying manual approach ===\n";
    
    try {
        $existing = App\Models\Attendance::where('student_id', $studentId)->where('date', $date)->first();
        
        if ($existing) {
            echo "Updating existing record...\n";
            $existing->teacher_id = 1;
            $existing->classroom = 'L4SWD';
            $existing->status = 'present';
            $existing->save();
            echo "Updated record ID: {$existing->id}\n";
        } else {
            echo "Creating new record...\n";
            $new = new App\Models\Attendance();
            $new->student_id = $studentId;
            $new->date = $date;
            $new->teacher_id = 1;
            $new->classroom = 'L4SWD';
            $new->status = 'present';
            $new->save();
            echo "Created new record ID: {$new->id}\n";
        }
        
    } catch (Exception $e2) {
        echo "Manual approach also failed: " . $e2->getMessage() . "\n";
    }
}

echo "\n=== Final state ===\n";
$finalAttendances = App\Models\Attendance::all();
echo "Total records: " . $finalAttendances->count() . "\n";

foreach ($finalAttendances as $att) {
    echo "- ID: {$att->id}, Student: {$att->student_id}, Date: {$att->date}, Status: {$att->status}\n";
}
