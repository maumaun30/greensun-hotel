<?php
/**
 * Brand fonts — self-hosted (Green Sun 2022 kit).
 *
 * Primary: Roca Two (Thin + Bold)  ·  Secondary: Codec Pro (Regular + Italic).
 * The @font-face declarations live in assets/css/critical.css (loaded before
 * first paint) and assets/css/main.css (also added to the block editor via
 * add_editor_style), so no external font request is made. Files live in
 * assets/fonts/.
 *
 * We preload the two most-used faces (Roca Two Bold for display, Codec Pro
 * Regular for body) so headings/body don't flash a fallback.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_head', function () {
    if (is_admin()) {
        return;
    }
    $bold = esc_url(get_theme_file_uri('/assets/fonts/RocaTwo-Bold.ttf'));
    $body = esc_url(get_theme_file_uri('/assets/fonts/CodecPro-Regular.ttf'));
    ?>
    <link rel="preload" href="<?php echo $body; ?>" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="<?php echo $bold; ?>" as="font" type="font/ttf" crossorigin>
    <?php
}, 4);
