<?php

// Load environment variables from .env file
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        
        if (strpos($value, '"') === 0) $value = substr($value, 1, -1);
        if (strpos($value, "'") === 0) $value = substr($value, 1, -1);
        
        putenv("$key=$value");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

echo "=== Testing All Available Gemini Models ===\n";

$apiKey = getenv('GEMINI_API_KEY');
$query = "my hand is broken";

$prompt = "You are an emergency medical assistant. Analyze the user's specific query: '{$query}' and provide targeted emergency recommendations. Format your response as JSON with condition, severity, summary, immediateActions, callEmergency, emergencySigns, disclaimer, emergencyNumber.";

$models = [
    'gemini-2.0-flash',
    'gemini-2.0-flash-lite',
    'gemini-2.5-flash-lite'
];

foreach ($models as $model) {
    echo "\n--- Testing: $model ---\n";
    
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
                        'text' => $prompt
                    ]
                ]
            ]
        ],
        'generationConfig' => [
            'temperature' => 0.3,
            'maxOutputTokens' => 400
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
            $text = $data['candidates'][0]['content']['parts'][0]['text'];
            echo "Response preview: " . substr($text, 0, 100) . "...\n";
        }
        break;
    } else {
        echo "❌ Failed: HTTP $httpCode\n";
        if ($httpCode !== 404 && $httpCode !== 503) {
            echo "Error: $response\n";
        }
    }
}
