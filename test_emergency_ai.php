<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test the Emergency AI Recommendation Service
use App\Services\EmergencyAIRecommendationService;

echo "=== Testing Emergency AI Recommendation Service ===\n\n";

$aiService = new EmergencyAIRecommendationService();

// Test cases
$testQueries = [
    'chest pain',
    'severe bleeding',
    'choking',
    'difficulty breathing',
    'head injury',
    'broken arm'
];

foreach ($testQueries as $query) {
    echo "Testing query: '$query'\n";
    echo str_repeat('-', 50) . "\n";
    
    $recommendations = $aiService->getEmergencyRecommendations($query);
    
    echo "Success: " . ($recommendations['success'] ? 'Yes' : 'No') . "\n";
    echo "AI Powered: " . ($recommendations['aiPowered'] ? 'Yes' : 'No') . "\n";
    echo "Recommendations count: " . count($recommendations['recommendations']) . "\n\n";
    
    foreach ($recommendations['recommendations'] as $rec) {
        echo "Condition: " . $rec['condition'] . "\n";
        echo "Severity: " . $rec['severity'] . "\n";
        echo "Call Emergency: " . ($rec['callEmergency'] ? 'Yes' : 'No') . "\n";
        echo "Summary: " . $rec['summary'] . "\n";
        echo "Actions: " . implode(', ', array_slice($rec['immediateActions'], 0, 2)) . "...\n";
        echo "\n";
    }
    
    echo "Disclaimer: " . $recommendations['disclaimer'] . "\n";
    echo str_repeat('=', 50) . "\n\n";
}

echo "=== Test Complete ===\n";
