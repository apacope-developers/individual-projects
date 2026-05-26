<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Checking attendance records for student_id 15 on 2026-04-20...\n";

$existing = App\Models\Attendance::where('student_id', 15)
    ->where('date', '2026-04-20')
    ->first();

if ($existing) {
    echo "Existing record found: Student ID " . $existing->student_id . ", Status: " . $existing->status . "\n";
    echo "Record ID: " . $existing->id . "\n";
    echo "Created at: " . $existing->created_at . "\n";
} else {
    echo "No existing record found for student_id 15 on 2026-04-20\n";
}

echo "\nAll attendance records for today:\n";
$allToday = App\Models\Attendance::where('date', '2026-04-20')->get();
echo "Total records: " . $allToday->count() . "\n";

foreach ($allToday as $record) {
    echo "- Student ID: " . $record->student_id . ", Status: " . $record->status . "\n";
}
