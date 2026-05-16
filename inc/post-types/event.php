<?php
/**
 * Event custom post type.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('init', function () {
    $labels = [
        'name'               => __('Events', 'greensun-hotel'),
        'singular_name'      => __('Event', 'greensun-hotel'),
        'add_new'            => __('Add New', 'greensun-hotel'),
        'add_new_item'       => __('Add New Event', 'greensun-hotel'),
        'edit_item'          => __('Edit Event', 'greensun-hotel'),
        'new_item'           => __('New Event', 'greensun-hotel'),
        'view_item'          => __('View Event', 'greensun-hotel'),
        'search_items'       => __('Search Events', 'greensun-hotel'),
        'not_found'          => __('No events found', 'greensun-hotel'),
        'not_found_in_trash' => __('No events found in trash', 'greensun-hotel'),
        'menu_name'          => __('Events', 'greensun-hotel'),
    ];

    register_post_type('event', [
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => 'events',
        'rewrite'             => ['slug' => 'events', 'with_front' => false],
        'show_in_rest'        => true,
        'menu_icon'           => 'dashicons-calendar-alt',
        'menu_position'       => 6,
        'supports'            => ['title', 'editor', 'thumbnail', 'excerpt'],
        'hierarchical'        => false,
        'capability_type'     => 'post',
    ]);
});

// Sort event archive by upcoming start date ascending; hide past events.
add_action('pre_get_posts', function ($query) {
    if (is_admin() || !$query->is_main_query()) return;
    if (!$query->is_post_type_archive('event')) return;

    $query->set('orderby', 'meta_value');
    $query->set('meta_key', 'event_start');
    $query->set('order', 'ASC');
    $query->set('meta_query', [
        'relation' => 'OR',
        [
            'key'     => 'event_start',
            'value'   => current_time('Y-m-d'),
            'compare' => '>=',
            'type'    => 'DATE',
        ],
        [
            'key'     => 'event_start',
            'compare' => 'NOT EXISTS',
        ],
    ]);
});
