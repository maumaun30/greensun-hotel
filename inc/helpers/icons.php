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

if (!function_exists('greensun_layout_glyph')) {
    /**
     * Inline SVG diagram glyph for an event-space seating layout.
     * Simple top-down schematic per configuration (24×24 viewBox).
     *
     * @param string $layout One of: theatre, banquet, classroom, boardroom, ushape, cocktail, cabaret.
     * @return string SVG markup (empty string if unknown).
     */
    function greensun_layout_glyph(string $layout): string
    {
        $open = '<svg class="gs-layout-glyph" width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true" stroke="currentColor" stroke-width="1.2" stroke-linecap="round">';
        $close = '</svg>';

        $glyphs = [
            // rows of seats facing a stage (top bar)
            'theatre'   => '<rect x="5" y="3" width="14" height="2" rx="1" fill="currentColor" stroke="none"/><g><circle cx="7" cy="9" r="1"/><circle cx="12" cy="9" r="1"/><circle cx="17" cy="9" r="1"/><circle cx="7" cy="13" r="1"/><circle cx="12" cy="13" r="1"/><circle cx="17" cy="13" r="1"/><circle cx="7" cy="17" r="1"/><circle cx="12" cy="17" r="1"/><circle cx="17" cy="17" r="1"/></g>',
            // round tables
            'banquet'   => '<circle cx="7" cy="7" r="2.6"/><circle cx="17" cy="7" r="2.6"/><circle cx="7" cy="17" r="2.6"/><circle cx="17" cy="17" r="2.6"/>',
            // rows of tables with seats behind
            'classroom' => '<rect x="5" y="6" width="14" height="2" rx="1"/><rect x="5" y="14" width="14" height="2" rx="1"/><circle cx="8" cy="10.5" r="0.9"/><circle cx="12" cy="10.5" r="0.9"/><circle cx="16" cy="10.5" r="0.9"/><circle cx="8" cy="18.5" r="0.9"/><circle cx="12" cy="18.5" r="0.9"/><circle cx="16" cy="18.5" r="0.9"/>',
            // single boardroom table
            'boardroom' => '<rect x="7" y="6" width="10" height="12" rx="2"/><circle cx="5" cy="9" r="0.9"/><circle cx="5" cy="15" r="0.9"/><circle cx="19" cy="9" r="0.9"/><circle cx="19" cy="15" r="0.9"/><circle cx="12" cy="4" r="0.9"/><circle cx="12" cy="20" r="0.9"/>',
            // U-shape
            'ushape'    => '<path d="M7 4 V18 M17 4 V18 M7 18 H17"/>',
            // cocktail — scattered standing tables
            'cocktail'  => '<circle cx="7" cy="7" r="1.6"/><circle cx="16" cy="9" r="1.6"/><circle cx="9" cy="16" r="1.6"/><circle cx="17" cy="17" r="1.6"/>',
            // cabaret — half-moon tables facing front
            'cabaret'   => '<rect x="5" y="3" width="14" height="2" rx="1" fill="currentColor" stroke="none"/><path d="M5 11 a3 3 0 0 1 6 0"/><path d="M13 11 a3 3 0 0 1 6 0"/><path d="M5 19 a3 3 0 0 1 6 0"/><path d="M13 19 a3 3 0 0 1 6 0"/>',
        ];

        $key = strtolower($layout);
        if (!isset($glyphs[$key])) {
            return '';
        }
        return $open . $glyphs[$key] . $close;
    }
}
