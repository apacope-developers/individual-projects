<?php

// Test the emergency recommendations endpoint
$url = 'http://localhost:8000/public/emergency/recommendations';
$data = ['query' => 'my ankle is broken'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "=== Emergency Endpoint Test ===\n";
echo "URL: $url\n";
echo "Query: {$data['query']}\n";
echo "HTTP Status: $httpCode\n";
echo "Response:\n";
echo $response . "\n\n";

if ($httpCode === 200) {
    echo "✅ SUCCESS: Endpoint is working correctly!\n";
} else {
    echo "❌ ERROR: Endpoint returned HTTP $httpCode\n";
}
