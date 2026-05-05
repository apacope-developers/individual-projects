<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Testing Google Gemini API Format ===\n\n";

$apiKey = env('GEMINI_API_KEY');

// Test the correct API format from Google documentation
$testFormats = [
    'Format 1' => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent',
    'Format 2' => 'https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent',
    'Format 3' => 'https://generativelanguage.googleapis.com/v1beta/generateContent?key=' . $apiKey,
    'Format 4' => 'https://generativelanguage.googleapis.com/v1/generateContent?key=' . $apiKey
];

$testPrompt = "You are an emergency medical assistant. Based on user's query: 'difficulty breathing', provide emergency recommendations. Format your response as JSON with this structure: {\"recommendations\": [{\"condition\": \"Condition Name\", \"severity\": \"critical\", \"summary\": \"Brief description\", \"immediateActions\": [\"Action 1\", \"Action 2\"], \"callEmergency\": true, \"emergencySigns\": [\"Sign 1\", \"Sign 2\"]}], \"disclaimer\": \"Medical disclaimer text\", \"emergencyNumber\": \"912\"}. Focus on life-threatening conditions first. Be concise and actionable.";

foreach ($testFormats as $format => $url) {
    echo "\n=== Testing Format: $format ===\n";
    echo "URL: $url\n";
    
    try {
        $response = \Illuminate\Support\Facades\Http::timeout(10)
            ->post($url, [
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
            echo "SUCCESS: This format works!\n";
            break;
        } else {
            echo "FAILED: " . $response->body() . "\n";
        }
        
    } catch (\Exception $e) {
        echo "EXCEPTION: " . $e->getMessage() . "\n";
    }
}

echo "\n=== Test Complete ===\n";
