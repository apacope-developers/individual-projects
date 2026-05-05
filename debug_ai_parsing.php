<?php

echo "Debugging AI Response Parsing\n";
echo "============================\n\n";

// Simulate different AI response formats that might be causing issues
$testResponses = [
    // Good JSON response
    '{
        "recommendations": [
            {
                "condition": "Chest Pain",
                "severity": "critical",
                "summary": "Patient reports chest pain and shortness of breath",
                "immediateActions": ["Call 912", "Have person sit and rest"],
                "callEmergency": true,
                "emergencySigns": ["Chest pressure", "Shortness of breath"]
            }
        ],
        "disclaimer": "This is not medical advice",
        "emergencyNumber": "912"
    }',
    
    // AI response with extra text (common issue)
    'Based on your symptoms of chest pain, here are my recommendations:

{
    "recommendations": [
        {
            "condition": "Chest Pain",
            "severity": "critical"
        }
    ]
}

Please call emergency services if needed.',
    
    // Malformed JSON
    'Here are the recommendations:
    {
        "recommendations": [
            {
                "condition": "Chest Pain",
                "severity": "critical",
                "summary": "Patient reports chest pain",
                "immediateActions": ["Call emergency services"],
                "callEmergency": true,
                "emergencySigns": ["Chest pressure", "Shortness of breath"]
            }
        ],
        "disclaimer": "This is not medical advice",
        "emergencyNumber": "912"
    }'
];

echo "Testing JSON parsing logic:\n\n";

foreach ($testResponses as $index => $response) {
    echo "Test " . ($index + 1) . ":\n";
    
    // Test current parsing logic
    $jsonStart = strpos($response, '{');
    $jsonEnd = strrpos($response, '}');
    
    echo "- JSON start found at position: " . ($jsonStart !== false ? $jsonStart : 'false') . "\n";
    echo "- JSON end found at position: " . ($jsonEnd !== false ? $jsonEnd : 'false') . "\n";
    
    if ($jsonStart !== false && $jsonEnd !== false) {
        $jsonText = substr($response, $jsonStart, $jsonEnd - $jsonStart + 1);
        echo "- Extracted JSON length: " . strlen($jsonText) . " characters\n";
        
        $data = json_decode($jsonText, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            echo "- JSON parsing: SUCCESS\n";
            if (isset($data['recommendations'])) {
                echo "- Recommendations found: " . count($data['recommendations']) . "\n";
            } else {
                echo "- ERROR: No recommendations key in parsed data\n";
            }
        } else {
            echo "- JSON parsing: FAILED - " . json_last_error_msg() . "\n";
        }
    } else {
        echo "- ERROR: Could not find JSON boundaries\n";
    }
    
    echo "\n";
}

echo "Common issues identified:\n";
echo "1. AI responses may include extra text before/after JSON\n";
echo "2. JSON may be malformed\n";
echo "3. Parsing logic may be too strict\n";
echo "4. Fallback text extraction may be creating generic responses\n";
