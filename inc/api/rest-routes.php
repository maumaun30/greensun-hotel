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

    register_rest_route('greensun/v1', '/contact', [
        'methods'             => 'POST',
        'callback'            => 'greensun_rest_contact',
        'permission_callback' => 'greensun_rest_public_permission',
        'args'                => [
            'name'      => ['required' => true,  'type' => 'string'],
            'email'     => ['required' => true,  'type' => 'string'],
            'phone'     => ['required' => false, 'type' => 'string'],
            'subject'   => ['required' => false, 'type' => 'string'],
            'space'     => ['required' => false, 'type' => 'string'],
            'message'   => ['required' => true,  'type' => 'string'],
            'marketing' => ['required' => false, 'type' => 'string'],
            '_hp'       => ['required' => false, 'type' => 'string'],
        ],
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

function greensun_rest_contact(WP_REST_Request $request) {
    // Honeypot — silently succeed if filled so bots think they got through.
    if (!empty($request->get_param('_hp'))) {
        return rest_ensure_response(['ok' => true]);
    }

    $name    = sanitize_text_field((string) $request->get_param('name'));
    $email   = sanitize_email((string) $request->get_param('email'));
    $phone   = sanitize_text_field((string) $request->get_param('phone'));
    $subject_in = sanitize_text_field((string) $request->get_param('subject'));
    $space      = sanitize_text_field((string) $request->get_param('space'));
    $marketing  = (string) $request->get_param('marketing') === '1' ? 'yes' : 'no';
    $message = wp_strip_all_tags((string) $request->get_param('message'));

    if (!is_email($email)) {
        return new WP_REST_Response(['error' => 'Please provide a valid email address.'], 400);
    }
    if (mb_strlen($message) < 2) {
        return new WP_REST_Response(['error' => 'Please add a message.'], 400);
    }

    // Persist the lead first so nothing is lost even if email delivery fails.
    $submission_id = 0;
    if (function_exists('greensun_store_submission')) {
        $submission_id = greensun_store_submission([
            'name'      => $name,
            'email'     => $email,
            'phone'     => $phone,
            'subject'   => $subject_in,
            'space'     => $space,
            'marketing' => $marketing,
            'message'   => $message,
            'ip'        => $_SERVER['REMOTE_ADDR'] ?? '',
            'source'    => 'contact-form',
        ]);
    }

    $to       = apply_filters('greensun_contact_to', get_option('admin_email'));
    $subj_tag = $subject_in ?: 'Enquiry';
    $subject  = sprintf('[%s] %s — %s', wp_specialchars_decode(get_bloginfo('name'), ENT_QUOTES), $subj_tag, $name);
    $body     = sprintf(
        "Name: %s\nEmail: %s\nPhone: %s\nSubject: %s\nEvent space: %s\nMarketing opt-in: %s\n\nMessage:\n%s",
        $name, $email, $phone ?: '—', $subject_in ?: '—', $space ?: '—', $marketing, $message
    );
    $headers = ['Reply-To: ' . $name . ' <' . $email . '>'];

    $sent = wp_mail($to, $subject, $body, $headers);
    if (!$sent && $submission_id) {
        update_post_meta($submission_id, '_gs_mail_failed', '1');
    }

    // The lead is captured if either the email sent or it was stored.
    if (!$sent && !$submission_id) {
        return new WP_REST_Response(['error' => 'Could not send. Please email us directly.'], 500);
    }
    return rest_ensure_response(['ok' => true]);
}

function greensun_rest_booking_status() {
    return rest_ensure_response([
        'configured' => Greensun_EZee_Client::is_configured(),
        'mode'       => Greensun_EZee_Client::mode(),
    ]);
}
