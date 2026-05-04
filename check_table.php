<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

echo "Checking emergency_contacts table...\n";

if (Schema::hasTable('emergency_contacts')) {
    echo "Table exists!\n";
    $columns = Schema::getColumnListing('emergency_contacts');
    echo "Columns:\n";
    foreach ($columns as $column) {
        echo "- $column\n";
    }
    
    // Check if there's any data
    $count = \Illuminate\Support\Facades\DB::table('emergency_contacts')->count();
    echo "\nTotal records: $count\n";
    
    if ($count > 0) {
        echo "\nSample data:\n";
        $records = \Illuminate\Support\Facades\DB::table('emergency_contacts')->limit(3)->get();
        foreach ($records as $record) {
            echo json_encode($record) . "\n";
        }
    }
} else {
    echo "Table does not exist!\n";
}
?>
