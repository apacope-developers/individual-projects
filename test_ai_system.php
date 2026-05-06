<?php

require_once __DIR__ . '/bootstrap/app.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\EmergencyAIRecommendationService;
use App\Services\GoogleAIService;
use App\Services\FreeAIService;

echo "=== Testing AI Services with Internet APIs ===\n\n";

// Test EmergencyAIRecommendationService
echo "1. Testing EmergencyAIRecommendationService:\n";
$emergencyService = new EmergencyAIRecommendationService();
$testQuery = "chest pain and difficulty breathing";
$result = $emergencyService->getEmergencyRecommendations($testQuery);

echo "Query: $testQuery\n";
echo "AI Powered: " . ($result['aiPowered'] ? 'Yes' : 'No') . "\n";
echo "Success: " . ($result['success'] ? 'Yes' : 'No') . "\n";
if (!empty($result['recommendations'])) {
    echo "Condition: " . $result['recommendations'][0]['condition'] . "\n";
    echo "Severity: " . $result['recommendations'][0]['severity'] . "\n";
    echo "Summary: " . $result['recommendations'][0]['summary'] . "\n";
}
echo "\n";

// Test GoogleAIService
echo "2. Testing GoogleAIService:\n";
$googleService = new GoogleAIService();
$googleResult = $googleService->getFirstAidRecommendation($testQuery);

echo "Query: $testQuery\n";
echo "Emergency Level: " . $googleResult['emergency_level'] . "\n";
echo "Condition Name: " . $googleResult['condition_name'] . "\n";
echo "Immediate Action: " . $googleResult['immediate_action'] . "\n";
echo "Steps Count: " . count($googleResult['steps']) . "\n";
echo "\n";

// Test FreeAIService
echo "3. Testing FreeAIService:\n";
$freeService = new FreeAIService();
$freeResult = $freeService->getFirstAidRecommendation($testQuery);

echo "Query: $testQuery\n";
echo "Emergency Level: " . $freeResult['emergency_level'] . "\n";
echo "Condition Name: " . $freeResult['condition_name'] . "\n";
echo "Immediate Action: " . $freeResult['immediate_action'] . "\n";
echo "Steps Count: " . count($freeResult['steps']) . "\n";
echo "\n";

// Test suggestions
echo "4. Testing Emergency Suggestions:\n";
$suggestions = $googleService->getEmergencySuggestions("burn");
echo "Suggestions for 'burn': " . implode(', ', $suggestions) . "\n";
echo "\n";

echo "=== Test Complete ===\n";
echo "If you see specific, detailed responses above (not generic hardcoded text),\n";
echo "then your AI system is now using internet APIs successfully!\n";
