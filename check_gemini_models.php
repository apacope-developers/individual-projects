<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Http;

echo "=== Check Available Gemini Models ===\n";

try {
    $apiKey = 'AIzaSyAiVE8kSD9RcTOC4S0NsRnW97amXZ0EvS8';
    
    echo "Checking available models...\n";
    
    $response = Http::timeout(15)
        ->get("https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}");
    
    echo "HTTP Status: " . $response->status() . "\n";
    
    if ($response->successful()) {
        $data = $response->json();
        echo "✅ Available Models:\n";
        
        foreach ($data['models'] as $model) {
            echo "- " . $model['name'] . " (supports: " . implode(', ', $model['supportedGenerationMethods']) . ")\n";
        }
    } else {
        echo "❌ Failed to get models: " . $response->body() . "\n";
    }
    
} catch (\Exception $e) {
    echo "❌ EXCEPTION: " . $e->getMessage() . "\n";
}
