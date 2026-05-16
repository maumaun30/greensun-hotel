<?php
/**
 * SVG icon helper — inlines uploaded SVGs from the media library when possible,
 * falls back to an <img> tag. Used by amenities-grid, why-choose-us, and any block
 * with media-uploaded icons.
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('greensun_hotel_render_svg_icon')) {
    function greensun_hotel_render_svg_icon(int $svg_id, string $svg_url, string $class = 'greensun-hotel-icon'): string
    {
        if (!$svg_id && !$svg_url) {
            return '';
        }

        if ($svg_id) {
            $file_path = get_attached_file($svg_id);
            if ($file_path && file_exists($file_path) && get_post_mime_type($svg_id) === 'image/svg+xml') {
                $svg = file_get_contents($file_path); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
                $svg = preg_replace('/<\?xml[^>]*\?>/i', '', $svg);
                $svg = preg_replace('/<!DOCTYPE[^>]*>/i', '', $svg);
                $svg = preg_replace('/<svg/', '<svg class="' . esc_attr($class) . '"', $svg, 1);
                return trim($svg);
            }
        }

        if ($svg_url) {
            return '<img src="' . esc_url($svg_url) . '" alt="" class="' . esc_attr($class) . '" aria-hidden="true" />';
        }

        return '';
    }
}
