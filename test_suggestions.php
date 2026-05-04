<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing Emergency Suggestions...\n";

try {
    $controller = app('App\Http\Controllers\AIController');
    
    // Create a mock request
    $request = new \Illuminate\Http\Request();
    $request->merge(['query' => 'chest pain']);
    
    echo "Testing getEmergencySuggestions...\n";
    $response = $controller->getEmergencySuggestions($request);
    
    echo "Response Status: " . $response->getStatusCode() . "\n";
    echo "Response Data:\n";
    print_r(json_decode($response->getContent(), true));
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}
