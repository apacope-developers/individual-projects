<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\WorkingAIRecommendationService;

echo "=== CURRENT AI SERVICE OUTPUT ===\n";
echo "Query: 'my finger is broken'\n";
echo "=====================================\n";

try {
    $aiService = new WorkingAIRecommendationService();
    $result = $aiService->getEmergencyRecommendations("my finger is broken");
    
    echo "AI Powered: " . ($result['aiPowered'] ? 'YES' : 'NO') . "\n";
    echo "Condition: " . ($result['recommendations'][0]['condition'] ?? 'N/A') . "\n";
    echo "Severity: " . ($result['recommendations'][0]['severity'] ?? 'N/A') . "\n";
    echo "Summary: " . ($result['recommendations'][0]['summary'] ?? 'N/A') . "\n";
    echo "\nIMMEDIATE ACTIONS:\n";
    foreach (($result['recommendations'][0]['immediateActions'] ?? []) as $i => $action) {
        echo ($i + 1) . ". " . $action . "\n";
    }
    echo "\nCall Emergency: " . (($result['recommendations'][0]['callEmergency'] ?? false) ? 'YES' : 'NO') . "\n";
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n=====================================\n";
echo "Are you seeing different output than this?\n";
echo "If so, please share what you're seeing.\n";
