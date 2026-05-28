<?php
/**
 * Room custom post type.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('init', function () {
    $labels = [
        'name'               => __('Rooms', 'greensun-hotel'),
        'singular_name'      => __('Room', 'greensun-hotel'),
        'add_new'            => __('Add New', 'greensun-hotel'),
        'add_new_item'       => __('Add New Room', 'greensun-hotel'),
        'edit_item'          => __('Edit Room', 'greensun-hotel'),
        'new_item'           => __('New Room', 'greensun-hotel'),
        'view_item'          => __('View Room', 'greensun-hotel'),
        'search_items'       => __('Search Rooms', 'greensun-hotel'),
        'not_found'          => __('No rooms found', 'greensun-hotel'),
        'not_found_in_trash' => __('No rooms found in trash', 'greensun-hotel'),
        'menu_name'          => __('Rooms', 'greensun-hotel'),
    ];

    register_post_type('room', [
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => 'rooms',
        'rewrite'             => ['slug' => 'rooms', 'with_front' => false],
        'show_in_rest'        => true,
        'menu_icon'           => 'dashicons-admin-home',
        'menu_position'       => 5,
        'supports'            => ['title', 'editor', 'thumbnail', 'excerpt', 'page-attributes'],
        'hierarchical'        => false,
        'capability_type'     => 'post',
    ]);
});
