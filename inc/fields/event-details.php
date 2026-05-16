<?php
/**
 * ACF field group: Event Details (attached to the `event` CPT).
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key'    => 'group_event_details',
        'title'  => 'Event Details',
        'fields' => [
            [
                'key'           => 'field_event_start',
                'label'         => 'Start date',
                'name'          => 'event_start',
                'type'          => 'date_picker',
                'display_format'=> 'F j, Y',
                'return_format' => 'Y-m-d',
                'first_day'     => 1,
                'required'      => 1,
            ],
            [
                'key'           => 'field_event_end',
                'label'         => 'End date',
                'name'          => 'event_end',
                'type'          => 'date_picker',
                'display_format'=> 'F j, Y',
                'return_format' => 'Y-m-d',
                'first_day'     => 1,
                'instructions'  => 'Leave blank for single-day events.',
            ],
            [
                'key'           => 'field_event_time',
                'label'         => 'Time',
                'name'          => 'event_time',
                'type'          => 'text',
                'placeholder'   => 'e.g. 6:30 PM – 10:00 PM',
            ],
            [
                'key'           => 'field_event_location',
                'label'         => 'Location',
                'name'          => 'event_location',
                'type'          => 'text',
                'placeholder'   => 'e.g. Orchard terrace',
            ],
            [
                'key'           => 'field_event_capacity',
                'label'         => 'Capacity',
                'name'          => 'event_capacity',
                'type'          => 'number',
                'min'           => 1,
                'instructions'  => 'Optional. Maximum number of guests.',
            ],
            [
                'key'           => 'field_event_price',
                'label'         => 'Price per guest',
                'name'          => 'event_price',
                'type'          => 'number',
                'min'           => 0,
                'step'          => 0.01,
                'instructions'  => 'Leave blank for complimentary events.',
            ],
            [
                'key'           => 'field_event_currency',
                'label'         => 'Currency',
                'name'          => 'event_currency',
                'type'          => 'select',
                'choices'       => ['USD' => 'USD', 'EUR' => 'EUR', 'GBP' => 'GBP', 'PHP' => 'PHP', 'JPY' => 'JPY', 'AUD' => 'AUD'],
                'default_value' => 'USD',
                'allow_null'    => 0,
                'return_format' => 'value',
            ],
            [
                'key'           => 'field_event_cta_text',
                'label'         => 'CTA text',
                'name'          => 'event_cta_text',
                'type'          => 'text',
                'default_value' => 'Reserve',
            ],
            [
                'key'           => 'field_event_cta_url',
                'label'         => 'CTA URL',
                'name'          => 'event_cta_url',
                'type'          => 'url',
                'instructions'  => 'Where the Reserve button links to. Leave blank to use the event\'s detail page.',
            ],
            [
                'key'           => 'field_event_gallery',
                'label'         => 'Gallery',
                'name'          => 'event_gallery',
                'type'          => 'gallery',
                'instructions'  => 'Optional photos for the event detail page. Requires ACF Pro.',
                'return_format' => 'array',
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'event',
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
