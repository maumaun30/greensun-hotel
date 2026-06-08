<?php
/**
 * ACF field group: Room Details (attached to the `room` CPT).
 *
 * Registered via PHP so it ships with the theme and lives in git.
 * NOTE: `repeater` and `gallery` fields require ACF Pro. With free ACF
 * those fields will be inactive; everything else works.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key'    => 'group_room_details',
        'title'  => 'Room Details',
        'fields' => [
            [
                'key'           => 'field_room_price_per_night',
                'label'         => 'Price per night',
                'name'          => 'price_per_night',
                'type'          => 'number',
                'min'           => 0,
                'step'          => 0.01,
                'instructions'  => 'Base nightly rate. Used for display and booking calculations.',
            ],
            [
                'key'           => 'field_room_currency',
                'label'         => 'Currency',
                'name'          => 'currency',
                'type'          => 'select',
                'choices'       => ['USD' => 'USD', 'EUR' => 'EUR', 'GBP' => 'GBP', 'PHP' => 'PHP', 'JPY' => 'JPY', 'AUD' => 'AUD'],
                'default_value' => 'USD',
                'allow_null'    => 0,
                'return_format' => 'value',
            ],
            [
                'key'           => 'field_room_size',
                'label'         => 'Room size',
                'name'          => 'room_size',
                'type'          => 'text',
                'placeholder'   => 'e.g. 45 m²',
            ],
            [
                'key'           => 'field_room_max_guests',
                'label'         => 'Max guests',
                'name'          => 'max_guests',
                'type'          => 'number',
                'min'           => 1,
                'default_value' => 2,
            ],
            [
                'key'           => 'field_room_bed_configuration',
                'label'         => 'Bed configuration',
                'name'          => 'bed_configuration',
                'type'          => 'text',
                'placeholder'   => 'e.g. King + sofa bed',
            ],
            [
                'key'           => 'field_room_tagline',
                'label'         => 'Tagline',
                'name'          => 'tagline',
                'type'          => 'text',
                'instructions'  => 'Short marketing line shown under the room name in cards.',
            ],
            [
                'key'           => 'field_room_inclusions',
                'label'         => 'Inclusions',
                'name'          => 'inclusions',
                'type'          => 'repeater',
                'instructions'  => 'Bullet list shown on the room detail page. Requires ACF Pro.',
                'button_label'  => 'Add inclusion',
                'sub_fields'    => [
                    [
                        'key'   => 'field_room_inclusion_text',
                        'label' => 'Inclusion',
                        'name'  => 'text',
                        'type'  => 'text',
                    ],
                ],
            ],
            [
                'key'           => 'field_room_gallery',
                'label'         => 'Gallery (flat — legacy)',
                'name'          => 'gallery',
                'type'          => 'gallery',
                'instructions'  => 'Simple photo strip, used only when “Gallery by category” below is empty. Requires ACF Pro.',
                'return_format' => 'array',
            ],
            [
                'key'           => 'field_room_gallery_groups',
                'label'         => 'Gallery by category',
                'name'          => 'gallery_groups',
                'type'          => 'repeater',
                'instructions'  => 'Interactive multi-gallery grouped by inclusion category. Add a group per category (Bedroom / Bathroom / Workspace / Refreshments); each has its own photos and inclusion checklist. Requires ACF Pro.',
                'button_label'  => 'Add category',
                'layout'        => 'block',
                'sub_fields'    => [
                    [
                        'key'           => 'field_room_gg_category',
                        'label'         => 'Category',
                        'name'          => 'category',
                        'type'          => 'select',
                        'choices'       => [
                            'Bedroom'      => 'Bedroom',
                            'Bathroom'     => 'Bathroom',
                            'Workspace'    => 'Workspace',
                            'Refreshments' => 'Refreshments',
                        ],
                        'allow_null'    => 0,
                        'return_format' => 'value',
                    ],
                    [
                        'key'           => 'field_room_gg_gallery',
                        'label'         => 'Photos',
                        'name'          => 'gallery',
                        'type'          => 'gallery',
                        'return_format' => 'array',
                    ],
                    [
                        'key'          => 'field_room_gg_inclusions',
                        'label'        => 'Inclusions',
                        'name'         => 'inclusions',
                        'type'         => 'repeater',
                        'instructions' => 'Checklist shown beside this category’s photos.',
                        'button_label' => 'Add inclusion',
                        'sub_fields'   => [
                            [
                                'key'   => 'field_room_gg_inclusion_text',
                                'label' => 'Inclusion',
                                'name'  => 'text',
                                'type'  => 'text',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'key'           => 'field_room_ezee_room_type_id',
                'label'         => 'eZee Room Type ID',
                'name'          => 'ezee_room_type_id',
                'type'          => 'text',
                'instructions'  => 'Maps this room to eZee\'s room-type code. Required for live availability lookups.',
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'room',
                ],
            ],
        ],
        'show_in_rest' => 1,
        'menu_order'   => 0,
        'position'     => 'normal',
        'style'        => 'default',
        'label_placement' => 'top',
    ]);
});
