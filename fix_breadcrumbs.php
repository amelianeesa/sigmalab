<?php
$files = glob('C:/Project/sigmalab/resources/views/**/*.blade.php');
$files = array_merge($files, glob('C:/Project/sigmalab/resources/views/*.blade.php'));

foreach ($files as $file) {
    $content = file_get_contents($file);
    $originalContent = $content;
    
    // 1. Ensure text-decoration-none is present on all breadcrumb links
    // First, find breadcrumb blocks to safely replace inside them
    if (preg_match('/<ol[^>]*class="[^"]*breadcrumb[^"]*"[^>]*>.*?<\/ol>/is', $content, $matches)) {
        $breadcrumbBlock = $matches[0];
        // Replace <a href="..."> with <a href="..." class="text-decoration-none">
        // If class already exists, we will handle it carefully, but let's just use regex for <a> tags inside breadcrumb-item
        
        $newBreadcrumbBlock = preg_replace_callback('/<a([^>]*)>/i', function($m) {
            $attrs = $m[1];
            if (strpos($attrs, 'class=') !== false) {
                if (strpos($attrs, 'text-decoration-none') === false) {
                    return '<a' . preg_replace('/class="([^"]*)"/', 'class="$1 text-decoration-none"', $attrs) . '>';
                }
                return $m[0];
            } else {
                return '<a' . $attrs . ' class="text-decoration-none">';
            }
        }, $breadcrumbBlock);
        
        $content = str_replace($breadcrumbBlock, $newBreadcrumbBlock, $content);
    }

    // 2. Swap <h1>/<h2>/<h3> with breadcrumb if breadcrumb is AFTER it
    // Often it looks like:
    // <h1 class="...">Title</h1>
    // <ol class="breadcrumb...">...</ol>
    // Or wrapped in a nav:
    // <h1 ...>...</h1>
    // <nav aria-label="breadcrumb"> <ol>...</ol> </nav>
    
    // Let's do a more robust approach: Find the title (h1/h2/h3) and the breadcrumb (nav or ol).
    // If title comes before breadcrumb, swap them.
    
    // Pattern 1: Title then <ol class="breadcrumb">
    $pattern1 = '/(<h[1-3][^>]*>.*?<\/h[1-3]>)\s*(<ol[^>]*class="[^"]*breadcrumb[^"]*"[^>]*>.*?<\/ol>)/is';
    $content = preg_replace($pattern1, "$2\n    $1", $content);
    
    // Pattern 2: Title then <nav aria-label="breadcrumb">
    $pattern2 = '/(<h[1-3][^>]*>.*?<\/h[1-3]>)\s*(<nav[^>]*aria-label="breadcrumb"[^>]*>.*?<\/nav>)/is';
    $content = preg_replace($pattern2, "$2\n    $1", $content);
    
    // Pattern 3: <div ...> Title </div> then breadcrumb
    // Sometimes it's wrapped in div. Let's just check if we swapped anything.
    if ($content !== $originalContent) {
        file_put_contents($file, $content);
        echo "Updated: $file\n";
    }
}
echo "Done.\n";
