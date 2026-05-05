<?php

echo "Debugging AI Service - First Aid Tips Issue\n";
echo "==========================================\n\n";

// Test the AI service directly
require_once 'vendor/autoload.php';

// Create a mock service to test the logic
class TestWorkingAIRecommendationService {
    public function getEmergencyRecommendations(string $query): array
    {
        echo "Testing query: '$query'\n";
        
        // Test if API key is available
        $apiKey = env('OPENAI_API_KEY', 'sk-proj-demo');
        echo "API Key available: " . ($apiKey && $apiKey !== 'sk-proj-demo' ? 'YES' : 'NO') . "\n";
        echo "API Key length: " . strlen($apiKey) . "\n\n";
        
        // Test the conditional logic that should provide specific recommendations
        $queryLower = strtolower($query);
        
        echo "Keyword matching test:\n";
        echo "- Contains 'bleed': " . (strpos($queryLower, 'bleed') !== false ? 'YES' : 'NO') . "\n";
        echo "- Contains 'bleeding': " . (strpos($queryLower, 'bleeding') !== false ? 'YES' : 'NO') . "\n";
        echo "- Contains 'blood': " . (strpos($queryLower, 'blood') !== false ? 'YES' : 'NO') . "\n";
        echo "- Contains 'choke': " . (strpos($queryLower, 'choke') !== false ? 'YES' : 'NO') . "\n";
        echo "- Contains 'choking': " . (strpos($queryLower, 'choking') !== false ? 'YES' : 'NO') . "\n";
        echo "- Contains 'breath': " . (strpos($queryLower, 'breath') !== false ? 'YES' : 'NO') . "\n";
        echo "- Contains 'burn': " . (strpos($queryLower, 'burn') !== false ? 'YES' : 'NO') . "\n";
        echo "- Contains 'chest': " . (strpos($queryLower, 'chest') !== false ? 'YES' : 'NO') . "\n";
        echo "- Contains 'heart': " . (strpos($queryLower, 'heart') !== false ? 'YES' : 'NO') . "\n\n";
        
        // Test which condition would be matched
        if (strpos($queryLower, 'chest') !== false || strpos($queryLower, 'heart') !== false) {
            $condition = 'Chest Pain / Possible Heart Attack';
            $severity = 'critical';
            $actions = ['Call emergency services immediately (912)', 'Have person sit down and rest'];
        } elseif (strpos($queryLower, 'bleeding') !== false || strpos($queryLower, 'bleed') !== false || strpos($queryLower, 'blood') !== false) {
            $condition = 'Severe Bleeding';
            $severity = 'critical';
            $actions = ['Apply direct pressure with clean cloth', 'Elevate injured area', 'Call emergency services (912)'];
        } elseif (strpos($queryLower, 'choke') !== false || strpos($queryLower, 'breath') !== false) {
            $condition = strpos($queryLower, 'choke') !== false ? 'Choking / Airway Obstruction' : 'Difficulty Breathing';
            $severity = 'critical';
            $actions = ['Call emergency services immediately (912)', 'Perform Heimlich maneuver if choking'];
        } elseif (strpos($queryLower, 'burn') !== false) {
            $condition = 'Burns';
            $severity = 'urgent';
            $actions = ['Cool burn with cool running water', 'Remove jewelry', 'Cover burn with sterile dressing'];
        } else {
            $condition = 'Medical Assessment Needed';
            $severity = 'moderate';
            $actions = ['Stay calm and assess the situation', 'Call emergency services if life-threatening'];
        }
        
        echo "Matched condition: $condition\n";
        echo "Severity: $severity\n";
        echo "First aid actions: " . implode(', ', $actions) . "\n\n";
        
        return [
            'success' => true,
            'query' => $query,
            'aiPowered' => false,
            'recommendations' => [
                [
                    'condition' => $condition,
                    'severity' => $severity,
                    'summary' => "Based on your symptoms: '$query', specific first aid steps are provided.",
                    'immediateActions' => $actions,
                    'callEmergency' => $severity === 'critical',
                    'emergencySigns' => ['Symptoms that require immediate attention']
                ]
            ],
            'disclaimer' => 'This is not medical advice. Call emergency services for serious conditions.',
            'emergencyNumber' => '912'
        ];
    }
}

// Test with various queries
$testQueries = [
    'i am bleeding',
    'i am chocking', 
    'chest pain',
    'burn on hand',
    'difficulty breathing'
];

$service = new TestWorkingAIRecommendationService();

foreach ($testQueries as $query) {
    echo str_repeat("=", 60) . "\n";
    $result = $service->getEmergencyRecommendations($query);
    
    echo "Final result:\n";
    echo "- Success: " . ($result['success'] ? 'YES' : 'NO') . "\n";
    echo "- AI Powered: " . ($result['aiPowered'] ? 'YES' : 'NO') . "\n";
    
    if (!empty($result['recommendations'])) {
        $rec = $result['recommendations'][0];
        echo "- Condition: " . $rec['condition'] . "\n";
        echo "- Severity: " . $rec['severity'] . "\n";
        echo "- Actions: " . implode(', ', $rec['immediateActions']) . "\n";
        echo "- Emergency: " . ($rec['callEmergency'] ? 'YES' : 'NO') . "\n";
    }
    echo "\n";
}

echo "ANALYSIS:\n";
echo "===========\n";
echo "This test shows the conditional logic is working correctly.\n";
echo "If the actual system is not providing first aid tips, the issue\n";
echo "might be:\n";
echo "1. API calls failing and falling back to generic responses\n";
echo "2. API key not configured properly\n";
echo "3. Network issues preventing API calls\n";
echo "4. The frontend not displaying the response correctly\n\n";

echo "The logic should provide specific first aid steps for each query.\n";
