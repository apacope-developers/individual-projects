<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

echo "=== Direct Gemini API Test ===\n";

try {
    $apiKey = 'AIzaSyAiVE8kSD9RcTOC4S0NsRnW97amXZ0EvS8';
    $query = "I have severe chemical burn on my arm from cleaning supplies";
    
    $prompt = "You are an emergency medical assistant. Analyze the user's specific query: '{$query}' and provide targeted emergency recommendations.

IMPORTANT: Focus specifically on what the user described. If they mention 'chemical burn', provide recommendations for chemical burns.

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
    
    echo "Testing Gemini API call...\n";
    echo "API Key: " . substr($apiKey, 0, 10) . "...\n";
    
    $response = Http::timeout(15)
        ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
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
        ]);
    
    echo "HTTP Status: " . $response->status() . "\n";
    echo "Response Body: " . $response->body() . "\n";
    
    if ($response->successful()) {
        $data = $response->json();
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
        echo "✅ Gemini API SUCCESS!\n";
        echo "AI Response: " . $text . "\n";
    } else {
        echo "❌ Gemini API FAILED!\n";
        echo "Error: " . $response->body() . "\n";
    }
    
} catch (\Exception $e) {
    echo "❌ EXCEPTION: " . $e->getMessage() . "\n";
}
