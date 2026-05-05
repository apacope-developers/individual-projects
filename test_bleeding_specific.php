<?php

echo "Testing Specific Query 'i am bleeding'\n";
echo "====================================\n\n";

$query = "i am bleeding";
$queryLower = strtolower($query);

echo "Query: '$query'\n";
echo "Lowercase: '$queryLower'\n\n";

echo "Testing individual strpos calls:\n";
$hasBleed = strpos($queryLower, 'bleed');
$hasBleeding = strpos($queryLower, 'bleeding');
$hasBlood = strpos($queryLower, 'blood');

echo "- strpos('bleed'): " . ($hasBleed !== false ? "position $hasBleed" : 'false') . "\n";
echo "- strpos('bleeding'): " . ($hasBleeding !== false ? "position $hasBleeding" : 'false') . "\n";
echo "- strpos('blood'): " . ($hasBlood !== false ? "position $hasBlood" : 'false') . "\n\n";

echo "Testing conditional evaluation:\n";
echo "Condition 1: strpos('bleed') !== false || strpos('blood') !== false\n";
$cond1 = (strpos($queryLower, 'bleed') !== false || strpos($queryLower, 'blood') !== false);
echo "Result: " . ($cond1 ? 'TRUE' : 'FALSE') . "\n";

echo "Condition 2: strpos('bleeding') !== false || strpos('bleed') !== false || strpos('blood') !== false\n";
$cond2 = (strpos($queryLower, 'bleeding') !== false || strpos($queryLower, 'bleed') !== false || strpos($queryLower, 'blood') !== false);
echo "Result: " . ($cond2 ? 'TRUE' : 'FALSE') . "\n\n";

echo "ANALYSIS:\n";
echo "==========\n";
echo "The issue is that both conditions are TRUE,\n";
echo "but the first one (bleed OR blood) matches first,\n";
echo "so the second one (bleeding) is never evaluated.\n\n";

echo "EXPECTED BEHAVIOR:\n";
echo "====================\n";
echo "Query 'i am bleeding' should match:\n";
echo "- Condition: Severe Bleeding (FIXED)\n";
echo "- Severity: critical/urgent\n";
echo "- Actions: Apply pressure, elevate, call 912\n\n";

echo "CURRENT BEHAVIOR:\n";
echo "====================\n";
echo "Query 'i am bleeding' matches:\n";
echo "- Condition: Severe Bleeding (no 'FIXED')\n";
echo "- Because it matches first condition (bleed OR blood)\n\n";

echo "SOLUTION:\n";
echo "=========\n";
echo "The logic needs to prioritize 'bleeding' over 'bleed'\n";
echo "or use a more specific matching approach.\n";
