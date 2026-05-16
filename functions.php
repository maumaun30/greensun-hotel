<?php

if (!defined('ABSPATH')) {
    exit;
}

add_action('after_setup_theme', function () {
    if (!class_exists('ACF')) {
        include_once get_stylesheet_directory() . '/acf/acf.php';
    }
});

add_filter('acf/settings/path', function ($path) {
    return get_stylesheet_directory() . '/acf/';
});

add_filter('acf/settings/dir', function ($dir) {
    return get_stylesheet_directory_uri() . '/acf/';
});

// Local JSON sync — keep ACF field groups in version control under /acf-json/.
add_filter('acf/settings/save_json', function () {
    return get_stylesheet_directory() . '/acf-json';
});
add_filter('acf/settings/load_json', function ($paths) {
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
});

// Modular includes: CPTs, taxonomies, helpers, fonts. Add new files under /inc/.
require_once get_stylesheet_directory() . '/inc/fonts.php';
require_once get_stylesheet_directory() . '/inc/helpers/icons.php';
require_once get_stylesheet_directory() . '/inc/cpt-loader.php';

function greensun_hotel_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('editor-styles');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    add_theme_support('custom-logo');

    register_nav_menus([
        'primary' => __('Primary Menu', 'greensun-hotel'),
    ]);
}
add_action('after_setup_theme', 'greensun_hotel_setup');

function greensun_hotel_enqueue_assets()
{
    $critical_css = get_theme_file_path('/assets/css/critical.min.css');
    $main_css     = get_theme_file_path('/assets/css/main.min.css');
    $critical_js  = get_theme_file_path('/assets/js/critical.js');

    if (file_exists($critical_css)) {
        wp_enqueue_style(
            'greensun-hotel-critical',
            get_theme_file_uri('/assets/css/critical.min.css'),
            filemtime($critical_css)
        );
    }

    if (file_exists($main_css)) {
        wp_enqueue_style(
            'greensun-hotel-main',
            get_theme_file_uri('/assets/css/main.min.css'),
            ['greensun-hotel-critical'],
            filemtime($main_css)
        );
    }

    wp_enqueue_style(
        'greensun-hotel-style',
        get_stylesheet_uri(),
        ['greensun-hotel-main'],
        filemtime(get_theme_file_path('/style.css'))
    );

    if (file_exists($critical_js)) {
        wp_enqueue_script(
            'greensun-hotel-critical',
            get_theme_file_uri('/assets/js/critical.js'),
            [],
            filemtime($critical_js),
            false
        );
    }
}
add_action('wp_enqueue_scripts', 'greensun_hotel_enqueue_assets');

function greensun_hotel_editor_styles()
{
    if (file_exists(get_theme_file_path('/assets/css/main.min.css'))) {
        add_editor_style('assets/css/main.min.css');
    }
}
add_action('after_setup_theme', 'greensun_hotel_editor_styles');

function greensun_hotel_register_blocks()
{
    $blocks_dir = get_theme_file_path('/assets/js/blocks');

    if (!is_dir($blocks_dir)) {
        return;
    }

    $folders = scandir($blocks_dir);

    foreach ($folders as $folder) {
        if ($folder === '.' || $folder === '..') {
            continue;
        }

        $block_path = $blocks_dir . '/' . $folder;

        if (is_dir($block_path) && file_exists($block_path . '/block.json')) {
            register_block_type($block_path);
        }
    }
}
add_action('init', 'greensun_hotel_register_blocks');

/**
 * Enqueue Swiper.js + carousel frontend script.
 *
 * Add this to your theme's functions.php (or a dedicated assets loader file).
 * Adjust the version strings as needed.
 */
function greensun_hotel_enqueue_carousel_assets() {
    // Only load on pages that actually have the carousel block
    if ( ! has_block( 'greensun-hotel/carousel' ) ) {
        return;
    }
 
    // Swiper CSS (CDN â€” swap for local if preferred)
    wp_enqueue_style(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
        [],
        '11'
    );
 
    // Swiper JS (CDN)
    wp_enqueue_script(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
        [],
        '11',
        true // load in footer
    );
 
    // Our carousel initializer
    wp_enqueue_script(
        'greensun-hotel-carousel',
        get_template_directory_uri() . '/assets/js/greensun-hotel-carousel.js',
        [ 'swiper' ],
        wp_get_theme()->get( 'Version' ),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'greensun_hotel_enqueue_carousel_assets' );

/**
 * Enqueue booking-bar frontend JS only on pages that use the block.
 * Passes the REST URL + nonce so the bar can call /wp-json/greensun/v1/booking-search.
 */
function greensun_hotel_enqueue_booking_bar_assets() {
    if ( ! has_block( 'greensun-hotel/booking-bar' ) ) {
        return;
    }
    $handle = 'greensun-hotel-booking-bar';
    wp_enqueue_script(
        $handle,
        get_theme_file_uri( '/assets/js/booking-bar.js' ),
        [],
        filemtime( get_theme_file_path( '/assets/js/booking-bar.js' ) ),
        true
    );
    wp_localize_script( $handle, 'GreensunBooking', [
        'restUrl' => esc_url_raw( rest_url( 'greensun/v1/' ) ),
        'nonce'   => wp_create_nonce( 'wp_rest' ),
    ] );
}
add_action( 'wp_enqueue_scripts', 'greensun_hotel_enqueue_booking_bar_assets' );

