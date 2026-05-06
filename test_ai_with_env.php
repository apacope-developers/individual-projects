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
        
        // Remove quotes if present
        if (strpos($value, '"') === 0) $value = substr($value, 1, -1);
        if (strpos($value, "'") === 0) $value = substr($value, 1, -1);
        
        putenv("$key=$value");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

echo "=== Testing AI with Environment Variables ===\n";
echo "OPENAI_API_KEY: " . (getenv('OPENAI_API_KEY') ? 'SET' : 'NOT SET') . "\n";
echo "GEMINI_API_KEY: " . (getenv('GEMINI_API_KEY') ? 'SET' : 'NOT SET') . "\n";

// Now bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\WorkingAIRecommendationService;

echo "\n=== Testing AI Service ===\n";

try {
    $aiService = new WorkingAIRecommendationService();
    $result = $aiService->getEmergencyRecommendations("my hand is broken");
    
    echo "AI Powered: " . ($result['aiPowered'] ? 'YES' : 'NO') . "\n";
    echo "Condition: " . ($result['recommendations'][0]['condition'] ?? 'N/A') . "\n";
    
    if ($result['aiPowered']) {
        echo "✅ USING INTERNET APIs!\n";
    } else {
        echo "❌ Still using hardcoded fallback\n";
    }
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
