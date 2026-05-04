<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Emergency Contacts Count: " . \App\Models\EmergencyContact::count() . "\n";

$contacts = \App\Models\EmergencyContact::all();
foreach ($contacts as $contact) {
    echo "Contact: " . $contact->name . " - " . $contact->phone . " (Default: " . ($contact->is_default ? 'Yes' : 'No') . ")\n";
}
?>
