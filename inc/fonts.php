<?php
/**
 * Google Fonts: Cormorant Garamond (display), Manrope (sans), JetBrains Mono (mono).
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'greensun-hotel-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=JetBrains+Mono:wght@400;500&family=Manrope:wght@300;400;500;600;700&display=swap',
        [],
        null
    );
});

add_filter('wp_resource_hints', function ($urls, $relation_type) {
    if ($relation_type === 'preconnect') {
        $urls[] = ['href' => 'https://fonts.gstatic.com', 'crossorigin'];
        $urls[] = ['href' => 'https://fonts.googleapis.com'];
    }
    return $urls;
}, 10, 2);

// Editor: load the same fonts so the block editor visually matches the front-end.
add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_style(
        'greensun-hotel-fonts-editor',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=JetBrains+Mono:wght@400;500&family=Manrope:wght@300;400;500;600;700&display=swap',
        [],
        null
    );
});
