<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\WorkingAIRecommendationService;

echo "=== Testing AI Service with 'finger broken' query ===\n";

try {
    $aiService = new WorkingAIRecommendationService();
    
    // Test with finger broken query
    $query = "my finger is broken";
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
    
    echo "\n=== ISSUE ANALYSIS ===\n";
    if ($result['aiPowered'] === false) {
        echo "❌ AI is NOT using internet APIs - falling back to hardcoded logic\n";
    } else {
        echo "✅ AI is using internet APIs\n";
    }
    
    if ($recommendation['condition'] === 'Emergency Assessment Required') {
        echo "❌ Generic response - no specific finger fracture handling\n";
    } else {
        echo "✅ Specific condition detected\n";
    }
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
