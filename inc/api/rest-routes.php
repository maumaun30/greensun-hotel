<?php
/**
 * REST endpoints under /wp-json/greensun/v1/.
 *
 * Public, nonce-protected, rate-limited per IP via transient.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('rest_api_init', function () {
    register_rest_route('greensun/v1', '/booking-search', [
        'methods'             => 'POST',
        'callback'            => 'greensun_rest_booking_search',
        'permission_callback' => 'greensun_rest_public_permission',
        'args'                => [
            'checkin'   => ['required' => true,  'type' => 'string'],
            'checkout'  => ['required' => true,  'type' => 'string'],
            'guests'    => ['required' => false, 'type' => 'integer', 'default' => 2],
            'room_type' => ['required' => false, 'type' => 'string'],
        ],
    ]);

    register_rest_route('greensun/v1', '/booking-create', [
        'methods'             => 'POST',
        'callback'            => 'greensun_rest_booking_create',
        'permission_callback' => 'greensun_rest_public_permission',
    ]);

    register_rest_route('greensun/v1', '/booking-status', [
        'methods'             => 'GET',
        'callback'            => 'greensun_rest_booking_status',
        'permission_callback' => '__return_true',
    ]);
});

function greensun_rest_public_permission(WP_REST_Request $request) {
    $nonce = $request->get_header('X-WP-Nonce');
    if (!$nonce || !wp_verify_nonce($nonce, 'wp_rest')) {
        return new WP_Error('rest_forbidden', 'Invalid nonce.', ['status' => 403]);
    }
    if (!greensun_rest_rate_limit_ok()) {
        return new WP_Error('rest_rate_limited', 'Too many requests.', ['status' => 429]);
    }
    return true;
}

function greensun_rest_rate_limit_ok() {
    $ip  = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $key = 'greensun_rl_' . md5($ip);
    $hits = (int) get_transient($key);
    if ($hits >= 30) return false;
    set_transient($key, $hits + 1, MINUTE_IN_SECONDS);
    return true;
}

function greensun_rest_booking_search(WP_REST_Request $request) {
    $result = Greensun_EZee_Client::get_room_availability(
        sanitize_text_field($request->get_param('checkin')),
        sanitize_text_field($request->get_param('checkout')),
        (int) $request->get_param('guests'),
        $request->get_param('room_type') ? sanitize_text_field($request->get_param('room_type')) : null
    );
    if (is_wp_error($result)) {
        return new WP_REST_Response(['error' => $result->get_error_message(), 'code' => $result->get_error_code()], 400);
    }
    return rest_ensure_response($result);
}

function greensun_rest_booking_create(WP_REST_Request $request) {
    $body = $request->get_json_params() ?: $request->get_params();
    $result = Greensun_EZee_Client::create_booking($body);
    if (is_wp_error($result)) {
        return new WP_REST_Response(['error' => $result->get_error_message(), 'code' => $result->get_error_code()], 400);
    }
    return rest_ensure_response($result);
}

function greensun_rest_booking_status() {
    return rest_ensure_response([
        'configured' => Greensun_EZee_Client::is_configured(),
        'mode'       => Greensun_EZee_Client::mode(),
    ]);
}
