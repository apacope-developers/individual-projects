<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\WorkingAIRecommendationService;

echo "=== Testing AI Service with Internet API ===\n";

try {
    $aiService = new WorkingAIRecommendationService();
    
    // Test with a query that's not in hardcoded logic to force API usage
    $query = "I have severe chemical burn on my arm from cleaning supplies";
    echo "Testing query: '$query'\n";
    
    $result = $aiService->getEmergencyRecommendations($query);
    
    echo "AI Powered: " . ($result['aiPowered'] ? 'Yes' : 'No') . "\n";
    echo "Success: " . ($result['success'] ? 'Yes' : 'No') . "\n";
    
    if (!empty($result['recommendations'])) {
        $recommendation = $result['recommendations'][0];
        echo "Condition: " . $recommendation['condition'] . "\n";
        echo "Severity: " . $recommendation['severity'] . "\n";
        echo "Summary: " . $recommendation['summary'] . "\n";
        echo "Immediate Actions:\n";
        foreach ($recommendation['immediateActions'] as $i => $action) {
            echo "  " . ($i + 1) . ". " . $action . "\n";
        }
        echo "Call Emergency: " . ($recommendation['callEmergency'] ? 'Yes' : 'No') . "\n";
    }
    
    echo "\n=== API USAGE ANALYSIS ===\n";
    if ($result['aiPowered'] === true) {
        echo "✅ SUCCESS: AI is using internet APIs (Gemini/OpenAI)\n";
    } else {
        echo "❌ STILL FALLING BACK: AI is not using internet APIs\n";
    }
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
