<?php

echo "=== Gemini API v1 Test ===\n";

try {
    $apiKey = 'AIzaSyAiVE8kSD9RcTOC4S0NsRnW97amXZ0EvS8';
    
    // Test v1 API with different model names
    $models = [
        'gemini-1.5-flash',
        'gemini-1.5-flash-latest',
        'gemini-pro',
        'gemini-pro-latest'
    ];
    
    foreach ($models as $model) {
        echo "\nTesting v1 model: $model\n";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://generativelanguage.googleapis.com/v1/models/$model:generateContent?key=$apiKey");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => 'Hello, respond with just "OK"'
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.3,
                'maxOutputTokens' => 10
            ]
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        echo "HTTP Status: $httpCode\n";
        
        if ($httpCode === 200) {
            echo "✅ SUCCESS with v1 model: $model\n";
            $data = json_decode($response, true);
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                echo "Response: " . $data['candidates'][0]['content']['parts'][0]['text'] . "\n";
            }
            break;
        } else {
            echo "❌ FAILED: $response\n";
        }
    }
    
} catch (\Exception $e) {
    echo "❌ EXCEPTION: " . $e->getMessage() . "\n";
}
