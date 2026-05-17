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
require_once get_stylesheet_directory() . '/inc/helpers/logo.php';
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
        'primary'      => __('Primary Menu', 'greensun-hotel'),
        'footer-stay'  => __('Footer — Stay column', 'greensun-hotel'),
        'footer-hotel' => __('Footer — Hotel column', 'greensun-hotel'),
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

    // Lenis smooth scroll — loaded before critical.js so window.Lenis is ready.
    wp_enqueue_script(
        'lenis',
        'https://cdn.jsdelivr.net/npm/lenis@1.1.20/dist/lenis.min.js',
        [],
        '1.1.20',
        false
    );

    if (file_exists($critical_js)) {
        wp_enqueue_script(
            'greensun-hotel-critical',
            get_theme_file_uri('/assets/js/critical.js'),
            ['lenis'],
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
 * Enqueue hero-carousel frontend JS only on pages that use the block.
 */
function greensun_hotel_enqueue_hero_carousel_assets() {
    if ( ! has_block( 'greensun-hotel/hero-carousel' ) ) {
        return;
    }
    wp_enqueue_script(
        'greensun-hotel-hero-carousel',
        get_theme_file_uri( '/assets/js/hero-carousel.js' ),
        [],
        filemtime( get_theme_file_path( '/assets/js/hero-carousel.js' ) ),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'greensun_hotel_enqueue_hero_carousel_assets' );

/**
 * Enqueue reviews carousel JS only on pages that use the block.
 */
function greensun_hotel_enqueue_reviews_assets() {
    if ( ! has_block( 'greensun-hotel/reviews' ) ) {
        return;
    }
    wp_enqueue_script(
        'greensun-hotel-reviews',
        get_theme_file_uri( '/assets/js/reviews.js' ),
        [],
        filemtime( get_theme_file_path( '/assets/js/reviews.js' ) ),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'greensun_hotel_enqueue_reviews_assets' );

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

/**
 * Enqueue contact-form frontend JS only on pages that use the block.
 */
function greensun_hotel_enqueue_contact_form_assets() {
    if ( ! has_block( 'greensun-hotel/contact-form' ) ) {
        return;
    }
    $handle = 'greensun-hotel-contact-form';
    wp_enqueue_script(
        $handle,
        get_theme_file_uri( '/assets/js/contact-form.js' ),
        [],
        filemtime( get_theme_file_path( '/assets/js/contact-form.js' ) ),
        true
    );
    wp_localize_script( $handle, 'GreensunContact', [
        'restUrl' => esc_url_raw( rest_url( 'greensun/v1/' ) ),
        'nonce'   => wp_create_nonce( 'wp_rest' ),
    ] );
}
add_action( 'wp_enqueue_scripts', 'greensun_hotel_enqueue_contact_form_assets' );

/**
 * Enqueue gallery-grid filter + lightbox JS only when the block is on the page.
 */
function greensun_hotel_enqueue_gallery_grid_assets() {
    if ( ! has_block( 'greensun-hotel/gallery-grid' ) ) {
        return;
    }
    wp_enqueue_script(
        'greensun-hotel-gallery-grid',
        get_theme_file_uri( '/assets/js/gallery-grid.js' ),
        [],
        filemtime( get_theme_file_path( '/assets/js/gallery-grid.js' ) ),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'greensun_hotel_enqueue_gallery_grid_assets' );

/**
 * Enqueue events-teaser picker JS only on pages that use the block.
 */
function greensun_hotel_enqueue_events_teaser_assets() {
    if ( ! has_block( 'greensun-hotel/events-teaser' ) ) {
        return;
    }
    wp_enqueue_script(
        'greensun-hotel-events-teaser',
        get_theme_file_uri( '/assets/js/events-teaser.js' ),
        [],
        filemtime( get_theme_file_path( '/assets/js/events-teaser.js' ) ),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'greensun_hotel_enqueue_events_teaser_assets' );

/**
 * Enqueue the multi-step booking flow JS only on the booking template.
 */
function greensun_hotel_enqueue_booking_flow_assets() {
    if ( ! is_page_template( 'page-booking.php' ) ) {
        return;
    }
    $handle = 'greensun-hotel-booking-flow';
    wp_enqueue_script(
        $handle,
        get_theme_file_uri( '/assets/js/booking-flow.js' ),
        [],
        filemtime( get_theme_file_path( '/assets/js/booking-flow.js' ) ),
        true
    );
    wp_localize_script( $handle, 'GreensunBookingFlow', [
        'restUrl' => esc_url_raw( rest_url( 'greensun/v1/' ) ),
        'nonce'   => wp_create_nonce( 'wp_rest' ),
    ] );
}
add_action( 'wp_enqueue_scripts', 'greensun_hotel_enqueue_booking_flow_assets' );

