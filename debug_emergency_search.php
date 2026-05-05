<?php

echo "Debugging Emergency Search on Landing Page\n";
echo "==========================================\n\n";

// Test the PublicEmergencyController directly
require_once 'vendor/autoload.php';

// Test the actual API endpoint
echo "1. Testing Public Emergency API Endpoint\n";
echo str_repeat("-", 50) . "\n";

$testQueries = [
    'i am bleeding',
    'chest pain',
    'person is choking',
    'burns on hand',
    'difficulty breathing'
];

foreach ($testQueries as $query) {
    echo "Testing query: '$query'\n";
    
    // Simulate the API call
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost/public/emergency/recommendations');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['query' => $query]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "HTTP Status: $httpCode\n";
    
    if ($response) {
        $data = json_decode($response, true);
        if (isset($data['success'])) {
            echo "Success: " . ($data['success'] ? 'YES' : 'NO') . "\n";
            echo "Message: " . ($data['message'] ?? 'N/A') . "\n";
            
            if ($data['success'] && isset($data['data'])) {
                $recommendationData = $data['data'];
                echo "AI Powered: " . ($recommendationData['aiPowered'] ?? 'NO') . "\n";
                echo "Public Access: " . ($recommendationData['publicAccess'] ?? 'NO') . "\n";
                
                if (!empty($recommendationData['recommendations'])) {
                    $rec = $recommendationData['recommendations'][0];
                    echo "Condition: " . $rec['condition'] . "\n";
                    echo "Severity: " . $rec['severity'] . "\n";
                    echo "Actions: " . count($rec['immediateActions']) . " steps\n";
                    echo "First Action: " . ($rec['immediateActions'][0] ?? 'N/A') . "\n";
                } else {
                    echo "❌ No recommendations found\n";
                }
            }
        } else {
            echo "❌ Invalid JSON response\n";
            echo "Raw response: " . substr($response, 0, 200) . "...\n";
        }
    } else {
        echo "❌ No response received\n";
    }
    
    echo "\n" . str_repeat("-", 50) . "\n\n";
}

echo "2. Checking AI Service Configuration\n";
echo str_repeat("-", 50) . "\n";

// Check if the AI service is properly configured
$apiKey = env('OPENAI_API_KEY', 'not-set');
echo "OpenAI API Key: " . ($apiKey !== 'not-set' ? 'CONFIGURED' : 'NOT CONFIGURED') . "\n";
echo "API Key Length: " . strlen($apiKey) . "\n";

if ($apiKey === 'not-set' || strlen($apiKey) < 20) {
    echo "❌ AI API key is not properly configured\n";
    echo "This is likely why the emergency search is not providing AI recommendations\n";
    echo "The system is falling back to generic responses\n";
} else {
    echo "✅ AI API key appears to be configured\n";
}

echo "\n3. Testing AI Service Directly\n";
echo str_repeat("-", 50) . "\n";

// Test the WorkingAIRecommendationService directly
try {
    $service = new \App\Services\WorkingAIRecommendationService();
    
    foreach ($testQueries as $query) {
        echo "Testing AI service with: '$query'\n";
        
        $result = $service->getEmergencyRecommendations($query);
        
        echo "AI Service Success: " . ($result['success'] ? 'YES' : 'NO') . "\n";
        echo "AI Powered: " . ($result['aiPowered'] ?? 'NO') . "\n";
        
        if (!empty($result['recommendations'])) {
            $rec = $result['recommendations'][0];
            echo "Condition: " . $rec['condition'] . "\n";
            echo "Summary: " . substr($rec['summary'], 0, 100) . "...\n";
        } else {
            echo "❌ No recommendations from AI service\n";
        }
        
        echo "\n";
    }
} catch (Exception $e) {
    echo "❌ AI Service Error: " . $e->getMessage() . "\n";
}

echo "4. Checking Fallback Logic\n";
echo str_repeat("-", 50) . "\n";

// Test the fallback logic directly
$controller = new \App\Http\Controllers\PublicEmergencyController();

foreach ($testQueries as $query) {
    echo "Testing fallback for: '$query'\n";
    
    // Use reflection to access private method
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('getEmergencyFallback');
    $method->setAccessible(true);
    
    $fallback = $method->invoke($controller, $query);
    
    echo "Fallback Success: " . ($fallback['success'] ? 'YES' : 'NO') . "\n";
    echo "AI Powered: " . ($fallback['aiPowered'] ?? 'NO') . "\n";
    
    if (!empty($fallback['recommendations'])) {
        $rec = $fallback['recommendations'][0];
        echo "Condition: " . $rec['condition'] . "\n";
        echo "Actions: " . count($rec['immediateActions']) . " steps\n";
    }
    
    echo "\n";
}

echo "5. Summary and Recommendations\n";
echo str_repeat("=", 50) . "\n";

echo "ISSUES IDENTIFIED:\n";
echo "==================\n";
echo "1. Check if API endpoint is accessible\n";
echo "2. Verify AI API key configuration\n";
echo "3. Test AI service connectivity\n";
echo "4. Ensure fallback logic is working\n";
echo "5. Check JavaScript error logs\n\n";

echo "NEXT STEPS:\n";
echo "============\n";
echo "1. Configure OpenAI API key in .env file\n";
echo "2. Test API connectivity\n";
echo "3. Check browser console for JavaScript errors\n";
echo "4. Verify network requests in browser dev tools\n";
echo "5. Test with different emergency queries\n\n";

echo "The emergency search should provide specific AI recommendations\n";
echo "based on what users type. If it's showing generic responses,\n";
echo "the issue is likely with API configuration or connectivity.\n";
