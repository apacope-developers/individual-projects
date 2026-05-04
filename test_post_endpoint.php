<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing POST endpoint with raw request...\n";

try {
    // Simulate a POST request like the web server would receive
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_SERVER['CONTENT_TYPE'] = 'application/json';
    $_POST = [];
    
    // Get raw JSON input
    $jsonInput = '{"symptoms":"chest pain and difficulty breathing"}';
    $_SERVER['HTTP_CONTENT_TYPE'] = 'application/json';
    
    // Parse the JSON input manually like Laravel would
    $data = json_decode($jsonInput, true);
    
    echo "Parsed data: ";
    print_r($data);
    
    // Create request with the data
    $request = new \Illuminate\Http\Request();
    $request->merge($data);
    
    echo "Request data: ";
    print_r($request->all());
    
    $controller = app('App\Http\Controllers\AIController');
    $response = $controller->getFirstAidRecommendation($request);
    
    echo "Response Status: " . $response->getStatusCode() . "\n";
    echo "Response Data:\n";
    print_r(json_decode($response->getContent(), true));
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
