<?php

echo "=== Finding Working Gemini Model ===\n";

$apiKey = 'AIzaSyAiVE8kSD9RcTOC4S0NsRnW97amXZ0EvS8';

// Test different model variations
$models = [
    'gemini-1.5-flash',
    'gemini-1.5-flash-001',
    'gemini-1.5-flash-002',
    'gemini-1.5-flash-latest',
    'gemini-pro',
    'gemini-pro-vision',
    'gemini-1.0-pro'
];

foreach ($models as $model) {
    echo "\nTesting: $model\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://generativelanguage.googleapis.com/v1beta/models/$model:generateContent?key=$apiKey");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'contents' => [
            [
                'parts' => [
                    [
                        'text' => 'Say "OK" if you can read this'
                    ]
                ]
            ]
        ],
        'generationConfig' => [
            'temperature' => 0.1,
            'maxOutputTokens' => 10
        ]
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        echo "✅ SUCCESS: $model works!\n";
        $data = json_decode($response, true);
        if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
            echo "Response: " . $data['candidates'][0]['content']['parts'][0]['text'] . "\n";
        }
        break;
    } else {
        echo "❌ Failed: HTTP $httpCode\n";
    }
}
