<?php

echo "Debugging Keyword Matching Logic\n";
echo "================================\n\n";

$query = "i am bleeding";
$queryLower = strtolower($query);

echo "Query: '$query'\n";
echo "Lowercase: '$queryLower'\n\n";

echo "Testing strpos results:\n";
$hasBleed = strpos($queryLower, 'bleed');
$hasBleeding = strpos($queryLower, 'bleeding');
$hasBlood = strpos($queryLower, 'blood');

echo "- strpos for 'bleed': " . ($hasBleed !== false ? "position $hasBleed" : 'false') . "\n";
echo "- strpos for 'bleeding': " . ($hasBleeding !== false ? "position $hasBleeding" : 'false') . "\n";
echo "- strpos for 'blood': " . ($hasBlood !== false ? "position $hasBlood" : 'false') . "\n\n";

echo "Testing conditional logic:\n";

// Test the exact logic from services
if (strpos($queryLower, 'bleed') !== false || strpos($queryLower, 'blood') !== false) {
    echo "Condition 1 (bleed OR blood): TRUE\n";
    $result1 = "Severe Bleeding";
} elseif (strpos($queryLower, 'bleeding') !== false || strpos($queryLower, 'blood') !== false) {
    echo "Condition 2 (bleeding OR blood): TRUE\n";
    $result2 = "Severe Bleeding (FIXED)";
} else {
    echo "No condition matched\n";
    $result3 = "Medical Assessment Needed";
}

echo "Result 1: $result1\n";
echo "Result 2: $result2\n";
echo "Expected: Severe Bleeding (FIXED)\n\n";

echo "ANALYSIS:\n";
echo "==========\n";
echo "The issue is that the first condition (bleed OR blood) is already TRUE\n";
echo "because 'i am bleeding' contains 'bleed' at position 4.\n";
echo "So the first elseif matches and never reaches the second condition.\n";
echo "The second condition (bleeding OR blood) would also be TRUE,\n";
echo "but it's never evaluated because the first one already matched.\n\n";

echo "SOLUTION:\n";
echo "=========\n";
echo "Need to check for 'bleeding' BEFORE checking for 'bleed'\n";
echo "Or use more specific matching order.\n";
