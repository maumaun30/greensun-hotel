<?php
/**
 * ACF field group: Venue Details (attached to the `venue` CPT).
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key'    => 'group_venue_details',
        'title'  => 'Venue Details',
        'fields' => [
            [
                'key'           => 'field_venue_tagline',
                'label'         => 'Tagline',
                'name'          => 'venue_tagline',
                'type'          => 'text',
                'placeholder'   => 'e.g. Our flagship event hall',
                'instructions'  => 'Short line shown beneath the venue name on the Events page.',
            ],
            [
                'key'           => 'field_venue_capacity',
                'label'         => 'Capacity',
                'name'          => 'venue_capacity',
                'type'          => 'text',
                'placeholder'   => 'e.g. 120 seated · 180 standing',
            ],
            [
                'key'           => 'field_venue_area',
                'label'         => 'Floor area',
                'name'          => 'venue_area',
                'type'          => 'text',
                'placeholder'   => 'e.g. 280 m²',
            ],
            [
                'key'           => 'field_venue_location',
                'label'         => 'Location',
                'name'          => 'venue_location',
                'type'          => 'text',
                'placeholder'   => 'e.g. 2F · East wing',
            ],
            [
                'key'           => 'field_venue_layouts',
                'label'         => 'Layout options',
                'name'          => 'venue_layouts',
                'type'          => 'text',
                'placeholder'   => 'e.g. Banquet · Theater · Classroom',
                'instructions'  => 'Comma-separated list of room configurations supported.',
            ],
            [
                'key'           => 'field_venue_features',
                'label'         => 'Features',
                'name'          => 'venue_features',
                'type'          => 'repeater',
                'instructions'  => 'Bullet-list points shown on the venue detail page.',
                'sub_fields'    => [
                    [
                        'key'   => 'field_venue_feature_text',
                        'label' => 'Feature',
                        'name'  => 'text',
                        'type'  => 'text',
                    ],
                ],
                'min'           => 0,
                'layout'        => 'table',
                'button_label'  => 'Add feature',
            ],
            [
                'key'           => 'field_venue_brochure',
                'label'         => 'Brochure URL',
                'name'          => 'venue_brochure',
                'type'          => 'url',
                'instructions'  => 'Optional PDF/spec sheet link.',
            ],
            [
                'key'           => 'field_venue_cta_text',
                'label'         => 'Inquiry CTA text',
                'name'          => 'venue_cta_text',
                'type'          => 'text',
                'default_value' => 'Send an inquiry',
            ],
            [
                'key'           => 'field_venue_cta_url',
                'label'         => 'Inquiry CTA URL',
                'name'          => 'venue_cta_url',
                'type'          => 'url',
                'instructions'  => 'Where the inquiry button links. Defaults to /contact.',
            ],
            [
                'key'           => 'field_venue_gallery',
                'label'         => 'Gallery',
                'name'          => 'venue_gallery',
                'type'          => 'gallery',
                'instructions'  => 'Optional photos for the venue detail page. Requires ACF Pro.',
                'return_format' => 'array',
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'venue',
                ],
            ],
        ],
        'show_in_rest'    => 1,
        'menu_order'      => 0,
        'position'        => 'normal',
        'style'           => 'default',
        'label_placement' => 'top',
    ]);

});
