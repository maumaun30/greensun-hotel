<?php
/**
 * Navigation helpers — HOTEL | EVENTS division switcher.
 *
 * The redesign splits the site into two "divisions": Hotel and Events
 * (the Hyatt "Locations" peg). The active division is derived from the
 * page being viewed; a segmented pill in the header lets visitors jump
 * between them. Menu items + the primary CTA adapt per division.
 *
 * @package greensun-hotel
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Which division the current request belongs to.
 *
 * Events: the venue/event CPTs, the Events landing page, the Gallery.
 * Everything else (home, rooms, booking, about, explore) is Hotel.
 *
 * @return string 'hotel' | 'events'
 */
function greensun_current_division()
{
    $division = 'hotel';

    if (
        is_post_type_archive(['venue', 'event'])
        || is_singular(['venue', 'event'])
        || is_page(['events', 'gallery'])
    ) {
        $division = 'events';
    }

    /**
     * Filter the resolved nav division.
     *
     * @param string $division 'hotel' | 'events'.
     */
    return apply_filters('greensun_current_division', $division);
}

/**
 * Menu items for a division. Mirrors the prototype's HOTEL_ITEMS /
 * EVENTS_ITEMS arrays. Returned as [url, label, key].
 *
 * @param string $division 'hotel' | 'events'.
 * @return array<int,array{url:string,label:string,key:string}>
 */
function greensun_division_nav_items($division)
{
    if ($division === 'events') {
        // The `venue` CPT (archive slug "venues") holds the redesign's event spaces.
        $items = [
            ['url' => get_post_type_archive_link('venue') ?: home_url('/venues'), 'label' => __('Event Spaces', 'greensun-hotel'), 'key' => 'venues'],
            ['url' => home_url('/explore'), 'label' => __('Explore', 'greensun-hotel'), 'key' => 'explore'],
            ['url' => home_url('/gallery'), 'label' => __('Gallery', 'greensun-hotel'), 'key' => 'gallery'],
            ['url' => home_url('/about'),   'label' => __('About', 'greensun-hotel'),   'key' => 'about'],
        ];
    } else {
        $items = [
            ['url' => home_url('/'),        'label' => __('Home', 'greensun-hotel'),    'key' => 'home'],
            ['url' => home_url('/rooms'),   'label' => __('Rooms', 'greensun-hotel'),   'key' => 'rooms'],
            ['url' => home_url('/explore'), 'label' => __('Explore', 'greensun-hotel'), 'key' => 'explore'],
            ['url' => home_url('/about'),   'label' => __('About', 'greensun-hotel'),   'key' => 'about'],
        ];
    }

    /**
     * Filter the division nav items (e.g. to drive them from a WP menu).
     *
     * @param array  $items
     * @param string $division
     */
    return apply_filters('greensun_division_nav_items', $items, $division);
}

/**
 * The primary CTA for a division: Book Now (hotel) / Inquire (events).
 *
 * @param string $division 'hotel' | 'events'.
 * @return array{label:string,url:string}
 */
function greensun_division_cta($division)
{
    if ($division === 'events') {
        return ['label' => __('Inquire', 'greensun-hotel'), 'url' => home_url('/contact')];
    }
    return ['label' => __('Book Now', 'greensun-hotel'), 'url' => home_url('/booking')];
}

/**
 * Is a given nav item the current page? Compares normalised URL paths so
 * the home item only matches the front page, not every hotel route.
 *
 * @param array $item One item from greensun_division_nav_items().
 * @return bool
 */
function greensun_nav_item_is_active($item)
{
    $item_path    = trim((string) wp_parse_url($item['url'], PHP_URL_PATH), '/');
    $current_path = trim((string) wp_parse_url(home_url(add_query_arg([])), PHP_URL_PATH), '/');

    if ($item['key'] === 'home') {
        return is_front_page() || $current_path === '';
    }

    if ($item_path === '') {
        return false;
    }

    return $current_path === $item_path || strpos($current_path, $item_path . '/') === 0;
}
