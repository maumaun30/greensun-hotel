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
require_once get_stylesheet_directory() . '/inc/helpers/images.php';
require_once get_stylesheet_directory() . '/inc/helpers/logo.php';
require_once get_stylesheet_directory() . '/inc/helpers/nav.php';
require_once get_stylesheet_directory() . '/inc/block-editing.php';
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

    // Lenis smooth scroll + critical.js are both deferred so they don't
    // block the first paint. critical.js depends on Lenis being defined
    // but listens for DOMContentLoaded, by which point both deferred
    // scripts have executed.
    wp_enqueue_script(
        'lenis',
        'https://cdn.jsdelivr.net/npm/lenis@1.1.20/dist/lenis.min.js',
        [],
        '1.1.20',
        [
            'in_footer' => false,
            'strategy'  => 'defer',
        ]
    );

    if (file_exists($critical_js)) {
        wp_enqueue_script(
            'greensun-hotel-critical',
            get_theme_file_uri('/assets/js/critical.js'),
            ['lenis'],
            filemtime($critical_js),
            [
                'in_footer' => false,
                'strategy'  => 'defer',
            ]
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

/**
 * Room multi-gallery script — only on single room pages.
 */
function greensun_hotel_enqueue_room_gallery_assets() {
    if ( ! is_singular( 'room' ) ) {
        return;
    }
    $path = get_theme_file_path( '/assets/js/room-gallery.js' );
    if ( ! file_exists( $path ) ) {
        return;
    }
    wp_enqueue_script(
        'greensun-hotel-room-gallery',
        get_theme_file_uri( '/assets/js/room-gallery.js' ),
        [],
        filemtime( $path ),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'greensun_hotel_enqueue_room_gallery_assets' );

/**
 * Venue (event-space) detail script — gallery lightbox; only on single venue pages.
 */
function greensun_hotel_enqueue_venue_detail_assets() {
    if ( ! is_singular( 'venue' ) ) {
        return;
    }
    $path = get_theme_file_path( '/assets/js/venue-detail.js' );
    if ( ! file_exists( $path ) ) {
        return;
    }
    wp_enqueue_script(
        'greensun-hotel-venue-detail',
        get_theme_file_uri( '/assets/js/venue-detail.js' ),
        [],
        filemtime( $path ),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'greensun_hotel_enqueue_venue_detail_assets' );


/**
 * A11y + perf: lightweight global filters.
 *
 * - aria-current="page" on the active nav menu item so screen readers
 *   announce the current page (and CSS can style independently of the
 *   .current-menu-item class).
 * - decoding="async" + lazy default for content images, so the browser
 *   can paint other things while images decode.
 */
add_filter('nav_menu_link_attributes', function ($atts, $item) {
    if (!empty($item->current) || in_array('current-menu-item', (array) $item->classes, true) || in_array('current_page_item', (array) $item->classes, true)) {
        $atts['aria-current'] = 'page';
    }
    return $atts;
}, 10, 2);

add_filter('wp_get_attachment_image_attributes', function ($attr) {
    if (empty($attr['decoding'])) $attr['decoding'] = 'async';
    if (empty($attr['loading']))  $attr['loading']  = 'lazy';
    return $attr;
});

/**
 * Body class flag: `gs-light-top` is added on pages that don't have a
 * full-bleed dark hero behind the fixed header. Used by critical.css to
 * force the header into its solid (scrolled) style so menu items stay
 * readable on light backgrounds.
 */
add_filter('body_class', function ($classes) {
    if (is_admin()) return $classes;

    // Templates that render their own dark hero section at the top.
    if (is_front_page() || is_404() || is_singular(['room', 'event', 'venue'])) {
        return $classes;
    }

    // single.php renders a dark cover hero when the post has a featured image.
    if (is_singular('post') && has_post_thumbnail()) {
        return $classes;
    }

    // Singular pages whose first block is a dark hero block.
    if (is_singular()) {
        $post = get_post();
        if ($post && has_blocks($post->post_content)) {
            $blocks = parse_blocks($post->post_content);
            // Skip empty leading "blocks" (whitespace-only).
            foreach ($blocks as $b) {
                if (empty($b['blockName'])) continue;
                if (in_array($b['blockName'], [
                    'greensun-hotel/hero-carousel',
                    'greensun-hotel/page-hero',
                ], true)) {
                    return $classes;
                }
                break;
            }
        }
    }

    $classes[] = 'gs-light-top';
    return $classes;
});

add_filter('the_content', function ($content) {
    if (is_admin()) return $content;
    // Add decoding="async" to <img> tags in post content that don't already declare it.
    return preg_replace_callback('/<img\b(?![^>]*\bdecoding=)([^>]*)>/i', function ($m) {
        return '<img decoding="async"' . $m[1] . '>';
    }, $content);
}, 20);
