<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$doctors = App\Models\Doctor::active()->get();
echo "Active doctors count: " . $doctors->count() . "\n";

foreach ($doctors as $doctor) {
    echo "Doctor: " . $doctor->full_name . "\n";
    echo "Specialty: " . $doctor->specialty . "\n";
    echo "Phone: " . $doctor->phone . "\n";
    echo "Hospital: " . $doctor->hospital_clinic . "\n";
    echo "Location: " . $doctor->location . "\n";
    echo "WhatsApp: " . ($doctor->whatsapp ?? 'N/A') . "\n";
    echo "Status: " . $doctor->status . "\n";
    echo "Available: " . ($doctor->is_available ? 'Yes' : 'No') . "\n";
    echo "---\n";
}
?>
