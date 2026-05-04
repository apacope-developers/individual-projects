<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing Google AI Service...\n";

try {
    $service = app('App\Services\GoogleAIService');
    echo "Testing AI recommendation with sample symptoms...\n";
    $recommendation = $service->getFirstAidRecommendation('chest pain and difficulty breathing');
    echo "AI Recommendation:\n";
    print_r($recommendation);
    
    echo "\nTesting emergency suggestions...\n";
    $suggestions = $service->getEmergencySuggestions('chest pain');
    echo "Suggestions: " . implode(', ', $suggestions) . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
