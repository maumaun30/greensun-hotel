<?php
/**
 * Venue custom post type.
 *
 * Venues (The Eye, courtyards, focus rooms, etc.) are first-class content
 * with their own permalinks so they can be linked to from events and from
 * other parts of the site. Their summary cards are rendered by the
 * greensun-hotel/venues-list block, which queries this CPT.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('init', function () {
    $labels = [
        'name'               => __('Venues', 'greensun-hotel'),
        'singular_name'      => __('Venue', 'greensun-hotel'),
        'add_new'            => __('Add New', 'greensun-hotel'),
        'add_new_item'       => __('Add New Venue', 'greensun-hotel'),
        'edit_item'          => __('Edit Venue', 'greensun-hotel'),
        'new_item'           => __('New Venue', 'greensun-hotel'),
        'view_item'          => __('View Venue', 'greensun-hotel'),
        'search_items'       => __('Search Venues', 'greensun-hotel'),
        'not_found'          => __('No venues found', 'greensun-hotel'),
        'not_found_in_trash' => __('No venues found in trash', 'greensun-hotel'),
        'menu_name'          => __('Venues', 'greensun-hotel'),
    ];

    register_post_type('venue', [
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => 'venues',
        'rewrite'             => ['slug' => 'venues', 'with_front' => false],
        'show_in_rest'        => true,
        'menu_icon'           => 'dashicons-format-aside',
        'menu_position'       => 7,
        'supports'            => ['title', 'editor', 'thumbnail', 'excerpt', 'page-attributes'],
        'hierarchical'        => false,
        'capability_type'     => 'post',
    ]);
});

// Order venue archive by menu_order (page-attributes drag handle) so
// editors control the sequence on /venues/.
add_action('pre_get_posts', function ($query) {
    if (is_admin() || !$query->is_main_query()) return;
    if (!$query->is_post_type_archive('venue')) return;
    $query->set('orderby', 'menu_order title');
    $query->set('order',   'ASC');
    $query->set('posts_per_page', -1);
});
