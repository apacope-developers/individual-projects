<?php

// Test different API endpoint formats
$apiKey = 'AIzaSyAiVE8kSD9RcTOC4S0NsRnW97amXZ0EvS8';

$testFormats = [
    'v1' => 'https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent',
    'v1beta' => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent',
    'direct' => 'https://generativelanguage.googleapis.com/v1beta/generateContent'
];

$testPrompt = "You are an emergency medical assistant. Based on user's query: 'difficulty breathing', provide emergency recommendations. Format your response as JSON with this structure: {\"recommendations\": [{\"condition\": \"Condition Name\", \"severity\": \"critical\", \"summary\": \"Brief description\", \"immediateActions\": [\"Action 1\", \"Action 2\"], \"callEmergency\": true, \"emergencySigns\": [\"Sign 1\", \"Sign 2\"]}], \"disclaimer\": \"Medical disclaimer text\", \"emergencyNumber\": \"912\"}. Focus on life-threatening conditions first. Be concise and actionable.";

echo "=== Testing Google Gemini API Formats ===\n\n";

foreach ($testFormats as $format => $url) {
    echo "Testing: $format\n";
    echo "URL: $url\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
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
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    echo "HTTP Status: $httpCode\n";
    echo "Response: " . $response . "\n\n";
}

echo "\n=== Test Complete ===\n";
