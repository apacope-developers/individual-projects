<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Testing Google Gemini API Directly ===\n\n";

// Test direct API call
$apiKey = env('GEMINI_API_KEY');
$baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

echo "API Key: " . ($apiKey ? 'Set' : 'NOT SET') . "\n";
echo "Base URL: $baseUrl\n\n";

try {
    $testPrompt = "You are an emergency medical assistant. Based on user's query: 'difficulty breathing', provide emergency recommendations. Format your response as JSON with this structure: {\"recommendations\": [{\"condition\": \"Condition Name\", \"severity\": \"critical\", \"summary\": \"Brief description\", \"immediateActions\": [\"Action 1\", \"Action 2\"], \"callEmergency\": true, \"emergencySigns\": [\"Sign 1\", \"Sign 2\"]}], \"disclaimer\": \"Medical disclaimer text\", \"emergencyNumber\": \"912\"}. Focus on life-threatening conditions first. Be concise and actionable.";
    
    echo "Testing with prompt: $testPrompt\n\n";
    
    $response = \Illuminate\Support\Facades\Http::timeout(10)
        ->post($baseUrl . '?key=' . $apiKey, [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $testPrompt
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.3,
                'maxOutputTokens' => 800,
                'topP' => 0.8,
                'topK' => 40
            ]
        ]);
    
    echo "Response Status: " . ($response->successful() ? 'SUCCESS' : 'FAILED') . "\n";
    echo "Response Code: " . $response->status() . "\n";
    
    if ($response->successful()) {
        $data = $response->json();
        echo "Response Data:\n";
        print_r($data);
        
        if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
            $text = $data['candidates'][0]['content']['parts'][0]['text'];
            echo "\nExtracted Text: $text\n";
        } else {
            echo "\nERROR: Could not extract text from response\n";
        }
    } else {
        echo "\nERROR: API call failed\n";
        echo "Error Response: " . $response->body() . "\n";
    }
    
} catch (\Exception $e) {
    echo "\nEXCEPTION: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Test Complete ===\n";
