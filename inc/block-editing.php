<?php
/**
 * Gutenberg editing surface for rooms & venues.
 *
 * Singles and archives keep their designed PHP templates as a fallback; when an
 * editor builds a layout in Gutenberg, that layout takes over instead. The
 * inserter is restricted to this theme's own blocks so layouts stay on-brand.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * CPTs whose editor is always limited to greensun-hotel/* blocks.
 */
function greensun_block_editing_post_types() {
    return ['room', 'venue', 'event'];
}

/**
 * Page IDs designated (in theme settings) to back the rooms/venues archives.
 * These are restricted to theme blocks too; all other Pages are untouched.
 */
function greensun_archive_layout_page_ids() {
    if (!function_exists('greensun_setting')) {
        return [];
    }
    return array_filter([
        (int) greensun_setting('rooms_archive_page', 0),
        (int) greensun_setting('venues_archive_page', 0),
        (int) greensun_setting('events_archive_page', 0),
    ]);
}

/**
 * Core blocks allowed alongside the theme blocks (structure + basic text/media).
 */
function greensun_allowed_core_blocks() {
    return [
        'core/paragraph',
        'core/heading',
        'core/image',
        'core/list',
        'core/list-item',
        'core/buttons',
        'core/button',
        'core/group',
        'core/columns',
        'core/column',
        'core/spacer',
        'core/separator',
    ];
}

/**
 * Restrict the inserter to this theme's blocks (+ a few core essentials) when
 * editing rooms, venues, and the archive-backing pages.
 */
add_filter('allowed_block_types_all', function ($allowed, $editor_context) {
    $post = $editor_context->post ?? null;
    if (!$post) {
        return $allowed;
    }
    $is_cpt   = in_array($post->post_type, greensun_block_editing_post_types(), true);
    $is_arch  = $post->post_type === 'page' && in_array((int) $post->ID, greensun_archive_layout_page_ids(), true);
    if (!$is_cpt && !$is_arch) {
        return $allowed; // leave unrelated post types and pages untouched
    }

    $theme_blocks = [];
    foreach (WP_Block_Type_Registry::get_instance()->get_all_registered() as $name => $type) {
        if (strpos($name, 'greensun-hotel/') === 0) {
            $theme_blocks[] = $name;
        }
    }

    return array_merge($theme_blocks, greensun_allowed_core_blocks());
}, 10, 2);

/**
 * True when the given post has real block content worth rendering in place of
 * the designed PHP template.
 */
function greensun_post_uses_blocks($post = null) {
    $post = get_post($post);
    if (!$post) {
        return false;
    }
    return has_blocks($post) && trim($post->post_content) !== '';
}

/**
 * Render the block layout from the Page designated for an archive (via the
 * `$setting_key` theme setting). Returns true when it rendered, false when the
 * caller should fall back to the designed PHP template.
 */
function greensun_render_archive_blocks($setting_key) {
    if (!function_exists('greensun_setting')) {
        return false;
    }
    $page_id = (int) greensun_setting($setting_key, 0);
    if (!$page_id || !greensun_post_uses_blocks($page_id)) {
        return false;
    }
    $page = get_post($page_id);
    echo apply_filters('the_content', $page->post_content);
    return true;
}
