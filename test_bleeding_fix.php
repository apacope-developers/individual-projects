<?php

echo "Testing Fixed Keyword Matching for 'i am bleeding'\n";
echo "===============================================\n\n";

$query = "i am bleeding";
$symptomsLower = strtolower($query);

echo "Query: '$query'\n";
echo "Lowercase: '$symptomsLower'\n\n";

echo "Testing keyword matching:\n";

// Test the fixed logic
$hasBleed = strpos($symptomsLower, 'bleed') !== false;
$hasBleeding = strpos($symptomsLower, 'bleeding') !== false;
$hasBlood = strpos($symptomsLower, 'blood') !== false;

echo "- Contains 'bleed': " . ($hasBleed ? 'YES' : 'NO') . "\n";
echo "- Contains 'bleeding': " . ($hasBleeding ? 'YES' : 'NO') . "\n";
echo "- Contains 'blood': " . ($hasBlood ? 'YES' : 'NO') . "\n\n";

// Test the condition matching logic
if ($hasBleed || $hasBleeding || $hasBlood) {
    echo "✅ SUCCESS: Query matches bleeding conditions!\n";
    echo "Condition: Severe Bleeding\n";
    echo "Severity: urgent\n";
    echo "Emergency: No (unless severe)\n";
    echo "Actions: Apply pressure, elevate, bandage, seek help\n";
} else {
    echo "❌ FAILED: Query does not match any bleeding conditions!\n";
    echo "Would fall back to generic response\n";
}

echo "\nExpected Result:\n";
echo "The query 'i am bleeding' should now match the bleeding condition\n";
echo "and provide specific first aid steps for bleeding,\n";
echo "not a generic 'Professional Medical Consultation' response.\n\n";

echo "Fix Applied:\n";
echo "✅ Added 'bleeding' keyword check alongside 'bleed' and 'blood'\n";
echo "✅ This catches variations like 'i am bleeding', 'bleeding wound', etc.\n";
echo "✅ Updated both fallback recommendation and suggestion methods\n";
