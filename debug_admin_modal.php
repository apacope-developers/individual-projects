<?php

echo "=== DEBUGGING ADMIN MODAL ISSUE ===\n\n";

// Check if the admin dashboard view exists and has the modal
$adminDashboardPath = __DIR__ . '/resources/views/admin/dashboard.blade.php';

if (file_exists($adminDashboardPath)) {
    echo "✓ Admin dashboard file exists\n";
    
    $content = file_get_contents($adminDashboardPath);
    
    // Check for Add User button
    if (strpos($content, 'onclick="openAddUserModal()"') !== false) {
        echo "✓ Add User button found with correct onclick\n";
    } else {
        echo "✗ Add User button onclick not found\n";
    }
    
    // Check for modal div
    if (strpos($content, 'id="addUserModal"') !== false) {
        echo "✓ Add User Modal div found\n";
    } else {
        echo "✗ Add User Modal div not found\n";
    }
    
    // Check for openAddUserModal function
    if (strpos($content, 'function openAddUserModal()') !== false) {
        echo "✓ openAddUserModal function found\n";
    } else {
        echo "✗ openAddUserModal function not found\n";
    }
    
    // Check for form action
    if (strpos($content, 'action="/admin/users"') !== false) {
        echo "✓ Form action is correct\n";
    } else {
        echo "✗ Form action not found or incorrect\n";
    }
    
    // Check for CSRF token
    if (strpos($content, '@csrf') !== false) {
        echo "✓ CSRF token found\n";
    } else {
        echo "✗ CSRF token not found\n";
    }
    
    echo "\n=== EXTRACTING MODAL HTML ===\n";
    
    // Extract the modal HTML
    preg_match('/<div id="addUserModal".*?<\/div>\s*<\/div>\s*<\/div>/s', $content, $matches);
    
    if (isset($matches[0])) {
        echo "Modal HTML found:\n";
        echo substr($matches[0], 0, 500) . "...\n";
    } else {
        echo "Modal HTML not found with regex\n";
    }
    
} else {
    echo "✗ Admin dashboard file not found\n";
}

echo "\n=== CHECKING JAVASCRIPT FUNCTIONS ===\n";

// Extract JavaScript functions
if (isset($content)) {
    preg_match('/function openAddUserModal\(\).*?^}/m', $content, $openFunc);
    if (isset($openFunc[0])) {
        echo "openAddUserModal function:\n";
        echo $openFunc[0] . "\n";
    }
    
    preg_match('/function closeAddUserModal\(\).*?^}/m', $content, $closeFunc);
    if (isset($closeFunc[0])) {
        echo "closeAddUserModal function:\n";
        echo $closeFunc[0] . "\n";
    }
}

echo "\n=== POTENTIAL ISSUES ===\n";
echo "1. JavaScript errors in browser console\n";
echo "2. Modal CSS display issues\n";
echo "3. Event listener conflicts\n";
echo "4. Form submission blocked\n";
echo "5. Admin middleware blocking request\n";

echo "\n=== RECOMMENDATIONS ===\n";
echo "1. Check browser console for JavaScript errors\n";
echo "2. Verify button click event is firing\n";
echo "3. Test modal visibility with browser dev tools\n";
echo "4. Check network tab for form submission attempts\n";

?>
