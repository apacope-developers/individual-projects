<?php

echo "=== Direct Gemini API Test (no Laravel) ===\n";

try {
    $apiKey = 'AIzaSyAiVE8kSD9RcTOC4S0NsRnW97amXZ0EvS8';
    
    // Test different model names
    $models = [
        'gemini-1.5-flash',
        'gemini-1.5-flash-8b',
        'gemini-pro',
        'gemini-pro-vision'
    ];
    
    foreach ($models as $model) {
        echo "\nTesting model: $model\n";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://generativelanguage.googleapis.com/v1beta/models/$model:generateContent?key=$apiKey");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => 'Hello, can you respond with just "OK"?'
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
            echo "✅ SUCCESS with model: $model\n";
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
