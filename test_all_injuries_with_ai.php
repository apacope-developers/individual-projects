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

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\WorkingAIRecommendationService;

echo "=== Testing AI Service with Internet APIs ===\n";

$queries = [
    "my hand is broken",
    "my ankle is broken", 
    "my wrist is sprained",
    "my leg is fractured",
    "i have a head injury",
    "my finger is broken"
];

try {
    $aiService = new WorkingAIRecommendationService();
    
    foreach ($queries as $query) {
        echo "\n--- Testing: '$query' ---\n";
        
        $result = $aiService->getEmergencyRecommendations($query);
        
        echo "AI Powered: " . ($result['aiPowered'] ? 'YES ✅' : 'NO ❌') . "\n";
        echo "Condition: " . ($result['recommendations'][0]['condition'] ?? 'N/A') . "\n";
        echo "Severity: " . ($result['recommendations'][0]['severity'] ?? 'N/A') . "\n";
        
        if ($result['aiPowered']) {
            echo "✅ USING INTERNET APIs - Working!\n";
        } else {
            echo "❌ FALLBACK - Not working!\n";
        }
    }
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
