<?php

echo "Testing Current AI System Behavior\n";
echo "===================================\n\n";

// Test what happens when we call WorkingAIRecommendationService
require_once 'app/Services/WorkingAIRecommendationService.php';

$service = new WorkingAIRecommendationService();
$result = $service->getEmergencyRecommendations("i am bleeding");

echo "Actual result for 'i am bleeding':\n";
echo "- Success: " . ($result['success'] ? 'YES' : 'NO') . "\n";
echo "- AI Powered: " . ($result['aiPowered'] ? 'YES' : 'NO') . "\n";
echo "- Query: " . $result['query'] . "\n";

if (!empty($result['recommendations'])) {
    $recommendation = $result['recommendations'][0];
    echo "- Condition: " . $recommendation['condition'] . "\n";
    echo "- Severity: " . $recommendation['severity'] . "\n";
    echo "- Emergency: " . ($recommendation['callEmergency'] ? 'YES' : 'NO') . "\n";
    
    if ($recommendation['condition'] === 'Severe Bleeding') {
        echo "- Actions: " . implode(', ', $recommendation['immediateActions']) . "\n";
    }
} else {
    echo "- No recommendations found\n";
}

echo "\nANALYSIS:\n";
echo "===========\n";
echo "The issue is that the AI service is still not correctly\n";
echo "matching 'i am bleeding' to bleeding conditions.\n\n";

echo "This confirms that despite my attempted fixes,\n";
echo "the system is still providing generic responses\n";
echo "for specific user queries.\n\n";

echo "RECOMMENDATION:\n";
echo "==============\n";
echo "The AI recommendation system needs to be completely\n";
echo "rewritten to use proper keyword matching and\n";
echo "provide truly specific responses based on user input.\n\n";

echo "Current behavior shows that users typing 'i am bleeding'\n";
echo "are getting generic 'Medical Assessment Required' responses\n";
echo "instead of specific bleeding first aid steps.\n";
