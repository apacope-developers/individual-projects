<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Testing Correct Google Gemini API ===\n\n";

// Test different API endpoints
$apiKey = env('GEMINI_API_KEY');

$endpoints = [
    'v1beta' => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent',
    'v1' => 'https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent'
];

foreach ($endpoints as $version => $url) {
    echo "\n=== Testing $version ===\n";
    echo "URL: $url\n";
    
    try {
        $testPrompt = "You are an emergency medical assistant. Based on user's query: 'difficulty breathing', provide emergency recommendations. Format your response as JSON with this structure: {\"recommendations\": [{\"condition\": \"Condition Name\", \"severity\": \"critical\", \"summary\": \"Brief description\", \"immediateActions\": [\"Action 1\", \"Action 2\"], \"callEmergency\": true, \"emergencySigns\": [\"Sign 1\", \"Sign 2\"]}], \"disclaimer\": \"Medical disclaimer text\", \"emergencyNumber\": \"912\"}. Focus on life-threatening conditions first. Be concise and actionable.";
        
        $response = \Illuminate\Support\Facades\Http::timeout(10)
            ->post($url . '?key=' . $apiKey, [
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
            echo "SUCCESS: API call worked for $version!\n";
        } else {
            echo "FAILED: " . $response->body() . "\n";
        }
        
    } catch (\Exception $e) {
        echo "EXCEPTION: " . $e->getMessage() . "\n";
    }
}

echo "\n=== Test Complete ===\n";
