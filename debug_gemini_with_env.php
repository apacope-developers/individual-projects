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

echo "=== Debugging Gemini API ===\n";
echo "Gemini API Key: " . substr(getenv('GEMINI_API_KEY'), 0, 10) . "...\n";

$apiKey = getenv('GEMINI_API_KEY');
$query = "my hand is broken";

$prompt = "You are an emergency medical assistant. Analyze the user's specific query: '{$query}' and provide targeted emergency recommendations.

IMPORTANT: Focus specifically on what the user described. If they mention 'hand injury', provide recommendations for hand injuries.

CRITICAL: Always include a disclaimer that this is not medical advice and to call emergency services for serious conditions.

Format your response as JSON with this structure:
{
    \"recommendations\": [
        {
            \"condition\": \"Specific condition name matching the user's query\",
            \"severity\": \"critical|urgent|moderate|minor\",
            \"summary\": \"Brief description directly related to user's symptoms\",
            \"immediateActions\": [\"Specific actions for the described condition\", \"Action 2\"],
            \"callEmergency\": true/false,
            \"emergencySigns\": [\"Specific signs for this condition\", \"Sign 2\"]
        }
    ],
    \"disclaimer\": \"Medical disclaimer text\",
    \"emergencyNumber\": \"912\"
}

Rules:
1. Match your response EXACTLY to what the user described
2. If user mentions specific symptoms, address those specifically
3. Provide relevant, actionable steps for the described condition
4. Be concise and focused on the user's specific situation
5. Use current medical best practices";

echo "\nTesting Gemini API call...\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://generativelanguage.googleapis.com/v1/models/gemini-pro:generateContent?key=$apiKey");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
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
        'maxOutputTokens' => 800
    ]
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Status: $httpCode\n";
echo "Response: $response\n";

if ($httpCode === 200) {
    echo "✅ Gemini API SUCCESS!\n";
    $data = json_decode($response, true);
    if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
        $text = $data['candidates'][0]['content']['parts'][0]['text'];
        echo "AI Response: $text\n";
    }
} else {
    echo "❌ Gemini API FAILED!\n";
}
