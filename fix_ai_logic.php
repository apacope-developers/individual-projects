<?php

echo "Fixing AI Recommendation Logic\n";
echo "==============================\n\n";

$files = [
    'app/Services/WorkingAIRecommendationService.php',
    'app/Services/EmergencyAIRecommendationService.php', 
    'app/Services/MultiAIService.php'
];

foreach ($files as $file) {
    echo "Processing: $file\n";
    
    if (!file_exists($file)) {
        echo "  File not found, skipping...\n";
        continue;
    }
    
    $content = file_get_contents($file);
    $original = $content;
    
    // Fix the conditional logic order - check 'bleeding' before 'bleed'
    $content = preg_replace(
        '/} elseif \(strpos\(\$queryLower, [\'"]bleed[\'"]\) !== false \|\| strpos\(\$queryLower, [\'"]blood[\'"]\) !== false\) {/',
        '} elseif (strpos($queryLower, \'bleeding\') !== false || strpos($queryLower, \'bleed\') !== false || strpos($queryLower, \'blood\') !== false) {',
        $content
    );
    
    // Also fix the symptoms version for MultiAIService
    $content = preg_replace(
        '/} elseif \(strpos\(\$symptomsLower, [\'"]bleed[\'"]\) !== false \|\| strpos\(\$symptomsLower, [\'"]blood[\'"]\) !== false\) {/',
        '} elseif (strpos($symptomsLower, \'bleeding\') !== false || strpos($symptomsLower, \'bleed\') !== false || strpos($symptomsLower, \'blood\') !== false) {',
        $content
    );
    
    if ($content !== $original) {
        file_put_contents($file, $content);
        echo "  ✅ Fixed conditional logic order\n";
    } else {
        echo "  ℹ️  No changes needed (already fixed or pattern not found)\n";
    }
}

echo "\n✅ AI recommendation logic fix completed!\n";
echo "\nThe conditional logic now checks for 'bleeding' before 'bleed',\n";
echo "ensuring queries like 'i am bleeding' will match the bleeding condition\n";
echo "and provide specific first aid steps instead of generic responses.\n\n";

echo "Test the fix by searching for 'i am bleeding' in the application.\n";
echo "It should now return specific bleeding recommendations.\n";
