<?php
/**
 * Thin wrapper around the eZee Reservation/PMS API.
 *
 * Credentials live in wp_options (set via Settings → Booking (eZee)):
 *   - greensun_ezee_endpoint   (base URL of the eZee endpoint)
 *   - greensun_ezee_hotel_code (per-property code)
 *   - greensun_ezee_auth_code  (auth/API key)
 *   - greensun_ezee_mode       ('live' | 'mock' — when 'mock', returns canned data)
 *
 * Public methods return associative arrays on success or WP_Error on failure.
 *
 * The actual request payload shape (Absolute / Frontdesk / Centrix) is product-
 * specific. The TODOs below mark where the live request body needs to be filled
 * in once credentials and product flavor are confirmed.
 */

if (!defined('ABSPATH')) {
    exit;
}

class Greensun_EZee_Client {

    const CACHE_GROUP   = 'greensun_ezee';
    const CACHE_MINUTES = 5;

    public static function is_configured() {
        return (bool) get_option('greensun_ezee_endpoint')
            && (bool) get_option('greensun_ezee_hotel_code')
            && (bool) get_option('greensun_ezee_auth_code');
    }

    public static function mode() {
        $m = get_option('greensun_ezee_mode', 'mock');
        return in_array($m, ['live', 'mock'], true) ? $m : 'mock';
    }

    /**
     * Search availability across the date range.
     *
     * @param string   $checkin       Y-m-d
     * @param string   $checkout      Y-m-d
     * @param int      $guests
     * @param string|null $room_type  eZee room type ID (optional)
     * @return array|WP_Error
     */
    public static function get_room_availability($checkin, $checkout, $guests, $room_type = null) {
        $err = self::validate_dates($checkin, $checkout);
        if (is_wp_error($err)) return $err;

        if (self::mode() === 'mock' || !self::is_configured()) {
            return self::mock_availability($checkin, $checkout, $guests, $room_type);
        }

        $cache_key = 'avail_' . md5(wp_json_encode(compact('checkin', 'checkout', 'guests', 'room_type')));
        $cached    = get_transient(self::CACHE_GROUP . '_' . $cache_key);
        if ($cached !== false) return $cached;

        // TODO: replace with real eZee request shape once product flavor confirmed.
        $payload = [
            'HotelCode'    => get_option('greensun_ezee_hotel_code'),
            'AuthCode'     => get_option('greensun_ezee_auth_code'),
            'StartDate'    => $checkin,
            'EndDate'      => $checkout,
            'RoomTypeCode' => $room_type,
            'Adults'       => (int) $guests,
        ];

        $response = wp_remote_post(get_option('greensun_ezee_endpoint'), [
            'timeout' => 15,
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => wp_json_encode($payload),
        ]);

        if (is_wp_error($response)) return $response;
        $code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);
        if ($code < 200 || $code >= 300) {
            return new WP_Error('ezee_http', "eZee returned HTTP $code", $body);
        }

        $normalized = self::normalize_availability($body);
        set_transient(self::CACHE_GROUP . '_' . $cache_key, $normalized, self::CACHE_MINUTES * MINUTE_IN_SECONDS);
        return $normalized;
    }

    /**
     * Create a booking. Stubbed in mock mode.
     *
     * @param array $payload
     * @return array|WP_Error
     */
    public static function create_booking($payload) {
        if (self::mode() === 'mock' || !self::is_configured()) {
            return [
                'reference' => 'MOCK-' . strtoupper(wp_generate_password(8, false)),
                'status'    => 'confirmed',
                'mock'      => true,
            ];
        }

        // TODO: real eZee booking-create call.
        $response = wp_remote_post(get_option('greensun_ezee_endpoint'), [
            'timeout' => 30,
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => wp_json_encode(array_merge([
                'HotelCode' => get_option('greensun_ezee_hotel_code'),
                'AuthCode'  => get_option('greensun_ezee_auth_code'),
            ], $payload)),
        ]);

        if (is_wp_error($response)) return $response;
        $code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);
        if ($code < 200 || $code >= 300) {
            return new WP_Error('ezee_http', "eZee returned HTTP $code", $body);
        }
        return $body ?: [];
    }

    /**
     * Quick connection probe used by the Settings page "Test connection" button.
     *
     * @return array|WP_Error
     */
    public static function test_connection() {
        if (!self::is_configured()) {
            return new WP_Error('ezee_unconfigured', 'Endpoint, hotel code, and auth code are all required.');
        }
        // TODO: swap for a real ping endpoint when known.
        $response = wp_remote_get(get_option('greensun_ezee_endpoint'), ['timeout' => 10]);
        if (is_wp_error($response)) return $response;
        return [
            'http_status' => wp_remote_retrieve_response_code($response),
            'mode'        => self::mode(),
        ];
    }

    private static function validate_dates($checkin, $checkout) {
        $ci = strtotime($checkin);
        $co = strtotime($checkout);
        if (!$ci || !$co) {
            return new WP_Error('ezee_bad_dates', 'Invalid check-in or check-out date.');
        }
        if ($ci < strtotime('today')) {
            return new WP_Error('ezee_past_date', 'Check-in cannot be in the past.');
        }
        if ($co <= $ci) {
            return new WP_Error('ezee_bad_range', 'Check-out must be after check-in.');
        }
        return null;
    }

    private static function normalize_availability($body) {
        // TODO: map real eZee response to this shape once product flavor confirmed.
        return [
            'rooms'  => $body['Rooms']   ?? [],
            'rates'  => $body['Rates']   ?? [],
            'meta'   => $body['Meta']    ?? [],
            'raw'    => $body,
        ];
    }

    private static function mock_availability($checkin, $checkout, $guests, $room_type) {
        $rooms_query = new WP_Query([
            'post_type'      => 'room',
            'posts_per_page' => 6,
        ]);
        $nights = max(1, (strtotime($checkout) - strtotime($checkin)) / DAY_IN_SECONDS);
        $rooms  = [];

        while ($rooms_query->have_posts()) {
            $rooms_query->the_post();
            $id          = get_the_ID();
            $rate        = function_exists('get_field') ? (float) get_field('price_per_night', $id) : 250;
            $currency    = function_exists('get_field') ? (get_field('currency', $id) ?: 'USD') : 'USD';
            $ezee_id     = function_exists('get_field') ? get_field('ezee_room_type_id', $id) : '';
            if ($room_type && $ezee_id && $room_type !== $ezee_id) continue;
            $rooms[] = [
                'id'            => $id,
                'title'         => get_the_title(),
                'permalink'     => get_permalink(),
                'thumbnail'     => get_the_post_thumbnail_url($id, 'large'),
                'currency'      => $currency,
                'nightly_rate'  => $rate,
                'total'         => $rate * $nights,
                'ezee_room_type'=> $ezee_id,
                'mock'          => true,
            ];
        }
        wp_reset_postdata();

        return [
            'rooms'   => $rooms,
            'nights'  => $nights,
            'checkin' => $checkin,
            'checkout'=> $checkout,
            'guests'  => (int) $guests,
            'mock'    => true,
        ];
    }
}
