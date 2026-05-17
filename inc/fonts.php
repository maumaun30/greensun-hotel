<?php
/**
 * Google Fonts: Cormorant Garamond (display), Manrope (sans), JetBrains Mono (mono).
 *
 * Loaded non-blocking via the rel="preload" + onload swap pattern, with a
 * <noscript> stylesheet fallback. Editor still gets the same stylesheet
 * synchronously so the block editor visually matches the front-end.
 */

if (!defined('ABSPATH')) {
    exit;
}

const GREENSUN_HOTEL_FONTS_URL = 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=JetBrains+Mono:wght@400;500&family=Manrope:wght@300;400;500;600;700&display=swap';

/**
 * Front-end: emit preconnects + non-blocking stylesheet link directly in <head>.
 * We bypass wp_enqueue_style here because we need rel="preload" + onload-swap,
 * which the enqueue API doesn't model.
 */
add_action('wp_head', function () {
    if (is_admin()) return;
    $url = esc_url(GREENSUN_HOTEL_FONTS_URL);
    ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="<?php echo $url; ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="<?php echo $url; ?>"></noscript>
    <?php
}, 4);

// Editor: synchronous load so the block editor renders in the right typefaces.
add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_style(
        'greensun-hotel-fonts-editor',
        GREENSUN_HOTEL_FONTS_URL,
        [],
        null
    );
});
