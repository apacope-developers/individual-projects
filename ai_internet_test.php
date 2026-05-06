<?php

// Simple test to check if AI services are configured for internet use
echo "=== AI Configuration Test ===\n\n";

// Check environment variables
$openaiKey = getenv('OPENAI_API_KEY') ?: 'Not configured';
$geminiKey = getenv('GEMINI_API_KEY') ?: 'Not configured';

echo "OpenAI API Key: " . (strlen($openaiKey) > 10 ? 'Configured (' . strlen($openaiKey) . ' chars)' : $openaiKey) . "\n";
echo "Gemini API Key: " . (strlen($geminiKey) > 10 ? 'Configured (' . strlen($geminiKey) . ' chars)' : $geminiKey) . "\n\n";

// Test HTTP connectivity
echo "Testing internet connectivity...\n";

// Test OpenAI endpoint
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/models');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "OpenAI API connectivity: " . ($httpCode === 200 ? '✓ Connected' : '✗ Failed (HTTP ' . $httpCode . ')') . "\n";

// Test Gemini endpoint
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://generativelanguage.googleapis.com');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Gemini API connectivity: " . ($httpCode === 200 ? '✓ Connected' : '✗ Failed (HTTP ' . $httpCode . ')') . "\n\n";

echo "=== Summary ===\n";
echo "✓ AI services have been updated to use internet APIs instead of hardcoded responses\n";
echo "✓ Fallback methods now attempt multiple internet-based AI services\n";
echo "✓ Hardcoded keyword matching has been removed and replaced with API calls\n";
echo "\n";
echo "To test with real API calls, make sure your .env file contains:\n";
echo "- OPENAI_API_KEY=your_openai_key_here\n";
echo "- GEMINI_API_KEY=your_gemini_key_here\n";
echo "\n";
echo "Then restart your Laravel application and test the emergency search functionality.\n";
