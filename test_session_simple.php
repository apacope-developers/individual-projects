<?php
// Simple session test
session_start();

echo "Setting session data...\n";
$_SESSION['test'] = 'Hello World';
$_SESSION['emergency_contacts'] = [
    ['id' => 'test_1', 'name' => 'Test Contact', 'phone' => '123456', 'type' => 'test']
];

echo "Session data set. Now reading...\n";
echo "Test value: " . $_SESSION['test'] . "\n";
echo "Emergency contacts: " . json_encode($_SESSION['emergency_contacts']) . "\n";
?>
