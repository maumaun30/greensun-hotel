<?php
/**
 * Settings → Booking (eZee) page.
 * Stores credentials in wp_options. Includes a "Test connection" button.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', function () {
    add_submenu_page(
        'greensun-dashboard',
        'Booking (eZee)',
        'Booking (eZee)',
        'manage_options',
        'greensun-ezee',
        'greensun_ezee_settings_page'
    );
});

add_action('admin_init', function () {
    register_setting('greensun_ezee', 'greensun_ezee_endpoint',   ['type' => 'string', 'sanitize_callback' => 'esc_url_raw']);
    register_setting('greensun_ezee', 'greensun_ezee_hotel_code', ['type' => 'string', 'sanitize_callback' => 'sanitize_text_field']);
    register_setting('greensun_ezee', 'greensun_ezee_auth_code',  ['type' => 'string', 'sanitize_callback' => 'sanitize_text_field']);
    register_setting('greensun_ezee', 'greensun_ezee_mode',       ['type' => 'string', 'sanitize_callback' => function ($v) {
        return in_array($v, ['live', 'mock'], true) ? $v : 'mock';
    }]);
});

function greensun_ezee_settings_page() {
    if (!current_user_can('manage_options')) return;

    $test_result = null;
    if (isset($_POST['greensun_ezee_test']) && check_admin_referer('greensun_ezee_test')) {
        $test_result = Greensun_EZee_Client::test_connection();
    }

    $endpoint   = (string) get_option('greensun_ezee_endpoint', '');
    $hotel_code = (string) get_option('greensun_ezee_hotel_code', '');
    $auth_code  = (string) get_option('greensun_ezee_auth_code', '');
    $mode       = (string) get_option('greensun_ezee_mode', 'mock');
    ?>
    <div class="wrap">
      <h1>Booking (eZee)</h1>
      <p>Credentials for the eZee Reservation/PMS API. While in <strong>Mock</strong> mode, the booking-bar returns canned availability based on Room CPT entries — useful before live credentials are available.</p>

      <form method="post" action="options.php">
        <?php settings_fields('greensun_ezee'); ?>
        <table class="form-table" role="presentation">
          <tr>
            <th scope="row"><label for="greensun_ezee_mode">Mode</label></th>
            <td>
              <select name="greensun_ezee_mode" id="greensun_ezee_mode">
                <option value="mock" <?php selected($mode, 'mock'); ?>>Mock (no API calls)</option>
                <option value="live" <?php selected($mode, 'live'); ?>>Live (calls eZee)</option>
              </select>
            </td>
          </tr>
          <tr>
            <th scope="row"><label for="greensun_ezee_endpoint">Endpoint URL</label></th>
            <td><input type="url" name="greensun_ezee_endpoint" id="greensun_ezee_endpoint" value="<?php echo esc_attr($endpoint); ?>" class="regular-text" placeholder="https://live.ipms247.com/..."></td>
          </tr>
          <tr>
            <th scope="row"><label for="greensun_ezee_hotel_code">Hotel code</label></th>
            <td><input type="text" name="greensun_ezee_hotel_code" id="greensun_ezee_hotel_code" value="<?php echo esc_attr($hotel_code); ?>" class="regular-text"></td>
          </tr>
          <tr>
            <th scope="row"><label for="greensun_ezee_auth_code">Auth code</label></th>
            <td><input type="text" name="greensun_ezee_auth_code" id="greensun_ezee_auth_code" value="<?php echo esc_attr($auth_code); ?>" class="regular-text" autocomplete="off"></td>
          </tr>
        </table>
        <?php submit_button('Save settings'); ?>
      </form>

      <hr>
      <h2>Test connection</h2>
      <form method="post">
        <?php wp_nonce_field('greensun_ezee_test'); ?>
        <p><button type="submit" name="greensun_ezee_test" class="button">Test connection</button></p>
      </form>
      <?php if ($test_result !== null) : ?>
        <div class="notice <?php echo is_wp_error($test_result) ? 'notice-error' : 'notice-success'; ?>">
          <?php if (is_wp_error($test_result)) : ?>
            <p><strong>Error:</strong> <?php echo esc_html($test_result->get_error_message()); ?></p>
          <?php else : ?>
            <p><strong>OK.</strong> HTTP <?php echo esc_html((string) ($test_result['http_status'] ?? '—')); ?> · mode: <?php echo esc_html($test_result['mode']); ?></p>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
    <?php
}
