<?php

echo "=== Testing OpenAI API Direct ===\n";

// Try with a demo key first to see if the endpoint works
$apiKey = 'sk-proj-demo';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'model' => 'gpt-3.5-turbo',
    'messages' => [
        [
            'role' => 'system',
            'content' => 'You are an emergency medical assistant. Provide brief, helpful responses.'
        ],
        [
            'role' => 'user',
            'content' => 'My finger is broken. What should I do?'
        ]
    ],
    'max_tokens' => 200,
    'temperature' => 0.3
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Status: $httpCode\n";
echo "Response: $response\n";

if ($httpCode === 401) {
    echo "\n❌ Invalid API key - need real OpenAI key\n";
} elseif ($httpCode === 200) {
    echo "\n✅ OpenAI API works!\n";
} else {
    echo "\n❌ OpenAI API failed\n";
}
