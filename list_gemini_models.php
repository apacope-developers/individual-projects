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

echo "=== Listing Available Gemini Models ===\n";

$apiKey = getenv('GEMINI_API_KEY');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://generativelanguage.googleapis.com/v1/models?key=$apiKey");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Status: $httpCode\n";

if ($httpCode === 200) {
    echo "✅ Got model list!\n";
    $data = json_decode($response, true);
    
    if (isset($data['models'])) {
        echo "Available models:\n";
        foreach ($data['models'] as $model) {
            $name = str_replace('models/', '', $model['name']);
            $supported = implode(', ', $model['supportedGenerationMethods']);
            echo "- $name (supports: $supported)\n";
        }
    }
} else {
    echo "❌ Failed to get models: $response\n";
}
