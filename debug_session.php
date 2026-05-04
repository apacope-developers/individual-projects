<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Start session
session_start();

echo "=== SESSION DEBUG ===\n";
echo "Session ID: " . session_id() . "\n";
echo "Emergency contacts in session: " . (isset($_SESSION['emergency_contacts']) ? 'YES' : 'NO') . "\n";

if (isset($_SESSION['emergency_contacts'])) {
    echo "Contacts count: " . count($_SESSION['emergency_contacts']) . "\n";
    foreach ($_SESSION['emergency_contacts'] as $index => $contact) {
        echo "Contact $index: " . json_encode($contact) . "\n";
    }
} else {
    echo "No emergency contacts in session\n";
}

echo "\n=== LARAVEL SESSION ===\n";
$laravelContacts = session('emergency_contacts');
echo "Laravel emergency contacts: " . ($laravelContacts ? 'YES' : 'NO') . "\n";

if ($laravelContacts) {
    echo "Laravel contacts count: " . count($laravelContacts) . "\n";
    foreach ($laravelContacts as $index => $contact) {
        echo "Laravel Contact $index: " . json_encode($contact) . "\n";
    }
}

?>
