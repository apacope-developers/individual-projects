<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\WorkingAIRecommendationService;

echo "=== Testing Multiple Injury Types ===\n";

$queries = [
    "my hand is broken",
    "my ankle is broken", 
    "my wrist is sprained",
    "my leg is fractured",
    "i have a head injury"
];

try {
    $aiService = new WorkingAIRecommendationService();
    
    foreach ($queries as $query) {
        echo "\n--- Testing: '$query' ---\n";
        
        $result = $aiService->getEmergencyRecommendations($query);
        
        echo "AI Powered: " . ($result['aiPowered'] ? 'YES' : 'NO') . "\n";
        echo "Condition: " . ($result['recommendations'][0]['condition'] ?? 'N/A') . "\n";
        echo "Severity: " . ($result['recommendations'][0]['severity'] ?? 'N/A') . "\n";
        
        if ($result['recommendations'][0]['condition'] === 'Emergency Assessment Required') {
            echo "❌ GENERIC RESPONSE - Not working!\n";
        } else {
            echo "✅ SPECIFIC RESPONSE - Working!\n";
        }
    }
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
