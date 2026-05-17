<?php
/**
 * Image helpers.
 *
 * greensun_post_thumbnail_html() — wraps wp_get_attachment_image() so
 * featured images render with the right srcset / sizes / width / height /
 * decoding / loading attrs in one call. Prevents CLS and gives the
 * browser real responsive picks instead of a single src.
 */

if (!defined('ABSPATH')) {
    exit;
}

function greensun_post_thumbnail_html($post_id = null, $size = 'large', $sizes_attr = null, $class = '') {
    $post_id = $post_id ?: get_the_ID();
    $thumb_id = get_post_thumbnail_id($post_id);
    if (!$thumb_id) return '';

    $attrs = [
        'decoding' => 'async',
        'loading'  => 'lazy',
        'class'    => trim('gs-img ' . $class),
    ];
    if ($sizes_attr) {
        $attrs['sizes'] = $sizes_attr;
    }

    return wp_get_attachment_image($thumb_id, $size, false, $attrs);
}
