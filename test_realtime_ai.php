<?php

require_once 'vendor/autoload.php';

use App\Services\EmergencyAIRecommendationService;
use App\Services\WorkingAIRecommendationService;
use App\Services\MultiAIService;

echo "Testing Real-time AI Recommendation Services\n";
echo "============================================\n\n";

// Test EmergencyAIRecommendationService
echo "1. Testing EmergencyAIRecommendationService:\n";
$emergencyService = new EmergencyAIRecommendationService();
$result1 = $emergencyService->getEmergencyRecommendations("chest pain and shortness of breath");
echo "Query: chest pain and shortness of breath\n";
echo "AI Powered: " . ($result1['aiPowered'] ? 'Yes' : 'No') . "\n";
echo "Success: " . ($result1['success'] ? 'Yes' : 'No') . "\n";
if (!empty($result1['recommendations'])) {
    echo "Condition: " . $result1['recommendations'][0]['condition'] . "\n";
    echo "Severity: " . $result1['recommendations'][0]['severity'] . "\n";
}
echo "\n";

// Test WorkingAIRecommendationService
echo "2. Testing WorkingAIRecommendationService:\n";
$workingService = new WorkingAIRecommendationService();
$result2 = $workingService->getEmergencyRecommendations("severe bleeding from arm wound");
echo "Query: severe bleeding from arm wound\n";
echo "AI Powered: " . ($result2['aiPowered'] ? 'Yes' : 'No') . "\n";
echo "Success: " . ($result2['success'] ? 'Yes' : 'No') . "\n";
if (!empty($result2['recommendations'])) {
    echo "Condition: " . $result2['recommendations'][0]['condition'] . "\n";
    echo "Severity: " . $result2['recommendations'][0]['severity'] . "\n";
}
echo "\n";

// Test MultiAIService
echo "3. Testing MultiAIService:\n";
$multiService = new MultiAIService();
$result3 = $multiService->getFirstAidRecommendation("person is choking and cannot breathe");
echo "Query: person is choking and cannot breathe\n";
echo "Emergency Level: " . $result3['emergency_level'] . "\n";
echo "Condition: " . $result3['condition_name'] . "\n";
echo "Emergency Call: " . ($result3['emergency_call'] ? 'Yes' : 'No') . "\n";
echo "\n";

// Test suggestions
echo "4. Testing MultiAIService Suggestions:\n";
$suggestions = $multiService->getEmergencySuggestions("head injury after fall");
echo "Query: head injury after fall\n";
echo "Suggestions: " . implode(', ', $suggestions) . "\n";
echo "\n";

echo "All tests completed!\n";
echo "Note: If AI services are working properly, you should see real-time recommendations based on internet data, not hardcoded responses.\n";
