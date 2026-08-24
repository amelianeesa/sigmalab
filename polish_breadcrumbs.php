<?php
$files = glob('C:/Project/sigmalab/resources/views/**/*.blade.php');
$files = array_merge($files, glob('C:/Project/sigmalab/resources/views/*.blade.php'));

foreach ($files as $file) {
    $content = file_get_contents($file);
    $originalContent = $content;
    
    // 1. First, make sure the text-decoration-none is present everywhere inside <ol class="breadcrumb">
    if (preg_match('/<ol[^>]*class="[^"]*breadcrumb[^"]*"[^>]*>.*?<\/ol>/is', $content, $matches)) {
        $breadcrumbBlock = $matches[0];
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
    
    if (preg_match('/<nav[^>]*aria-label="breadcrumb"[^>]*>.*?<\/nav>/is', $content, $matches)) {
        $navBlock = $matches[0];
        $newNavBlock = preg_replace_callback('/<a([^>]*)>/i', function($m) {
            $attrs = $m[1];
            if (strpos($attrs, 'class=') !== false) {
                if (strpos($attrs, 'text-decoration-none') === false) {
                    return '<a' . preg_replace('/class="([^"]*)"/', 'class="$1 text-decoration-none"', $attrs) . '>';
                }
                return $m[0];
            } else {
                return '<a' . $attrs . ' class="text-decoration-none">';
            }
        }, $navBlock);
        $content = str_replace($navBlock, $newNavBlock, $content);
    }

    // Fix spacing:
    // Change <ol class="breadcrumb mb-4"> to <ol class="breadcrumb mb-1 mt-3"> if it is right above an <h1> or <h2>
    // Or just generally change the margin on the breadcrumb
    // This is a bit brute force, but works for the majority of the views.
    $content = preg_replace('/class="breadcrumb\s+mb-4"/', 'class="breadcrumb mb-1 mt-3"', $content);
    $content = preg_replace('/class="breadcrumb\s+mb-3"/', 'class="breadcrumb mb-1 mt-3"', $content);
    
    // If the h1 has mt-4 (margin-top-4), it looks weird right below the breadcrumb.
    // Let's remove mt-4 from h1/h2 that follow a breadcrumb, or just generally change them to mb-3 or mb-4.
    // e.g. <ol ... </ol> \s* <h1 class="mt-4">
    $content = preg_replace('/(<\/ol>\s*<h[1-3][^>]*)mt-4([^>]*>)/is', '$1mb-4$2', $content);
    $content = preg_replace('/(<\/nav>\s*<h[1-3][^>]*)mt-4([^>]*>)/is', '$1mb-4$2', $content);
    
    // Ensure the title has mb-4 if it doesn't already have a margin bottom (for the pages that had mt-4)
    // Actually replacing mt-4 with mb-3 is safer.
    $content = preg_replace('/(<\/ol>\s*<h[1-3][^>]*)mt-[0-9]([^>]*>)/is', '$1mb-3$2', $content);
    $content = preg_replace('/(<\/nav>\s*<h[1-3][^>]*)mt-[0-9]([^>]*>)/is', '$1mb-3$2', $content);
    
    // For pages like tindak-lanjut/index.blade.php
    $content = preg_replace('/class="breadcrumb\s+mb-0\s+mt-2"/', 'class="breadcrumb mb-1 mt-3"', $content);
    $content = preg_replace('/class="breadcrumb\s+mb-1\s+mt-0"/', 'class="breadcrumb mb-1 mt-3"', $content);

    if ($content !== $originalContent) {
        file_put_contents($file, $content);
        echo "Polished: $file\n";
    }
}
echo "Done.\n";
