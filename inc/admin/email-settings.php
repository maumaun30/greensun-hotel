<?php
/**
 * Brevo SMTP relay + Email settings page.
 *
 * Routes all wp_mail() through Brevo's transactional SMTP so contact and
 * booking emails deliver reliably. Credentials are read from wp-config
 * constants first (recommended), falling back to options for convenience:
 *
 *   define('GREENSUN_BREVO_LOGIN', 'xxxx@smtp-brevo.com');
 *   define('GREENSUN_BREVO_KEY',   'your-smtp-key');
 */

if (!defined('ABSPATH')) {
    exit;
}

function greensun_brevo_login() {
    if (defined('GREENSUN_BREVO_LOGIN') && GREENSUN_BREVO_LOGIN) return (string) GREENSUN_BREVO_LOGIN;
    return (string) get_option('greensun_brevo_login', '');
}

function greensun_brevo_key() {
    if (defined('GREENSUN_BREVO_KEY') && GREENSUN_BREVO_KEY) return (string) GREENSUN_BREVO_KEY;
    return (string) get_option('greensun_brevo_key', '');
}

function greensun_brevo_is_configured() {
    return greensun_brevo_login() !== '' && greensun_brevo_key() !== '';
}

/* Route wp_mail through Brevo SMTP when configured and enabled. */
add_action('phpmailer_init', function ($phpmailer) {
    if (!get_option('greensun_brevo_enabled', '1')) return;
    if (!greensun_brevo_is_configured()) return;

    $phpmailer->isSMTP();
    $phpmailer->Host       = 'smtp-relay.brevo.com';
    $phpmailer->Port       = 587;
    $phpmailer->SMTPAuth   = true;
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->Username   = greensun_brevo_login();
    $phpmailer->Password   = greensun_brevo_key();
});

/* Apply the configured From name/address to all outgoing mail. */
add_filter('wp_mail_from', function ($from) {
    $email = get_option('greensun_brevo_from_email', '');
    return $email ?: $from;
});
add_filter('wp_mail_from_name', function ($name) {
    $configured = get_option('greensun_brevo_from_name', '');
    return $configured ?: $name;
});

add_action('admin_init', function () {
    register_setting('greensun_email', 'greensun_brevo_enabled',    ['type' => 'string', 'sanitize_callback' => function ($v) { return $v ? '1' : ''; }]);
    register_setting('greensun_email', 'greensun_brevo_login',      ['type' => 'string', 'sanitize_callback' => 'sanitize_text_field']);
    register_setting('greensun_email', 'greensun_brevo_key',        ['type' => 'string', 'sanitize_callback' => 'sanitize_text_field']);
    register_setting('greensun_email', 'greensun_brevo_from_email', ['type' => 'string', 'sanitize_callback' => 'sanitize_email']);
    register_setting('greensun_email', 'greensun_brevo_from_name',  ['type' => 'string', 'sanitize_callback' => 'sanitize_text_field']);
});

add_action('admin_menu', function () {
    add_submenu_page(
        'greensun-dashboard',
        'Email (Brevo)',
        'Email (Brevo)',
        'manage_options',
        'greensun-email',
        'greensun_email_settings_page'
    );
});

function greensun_email_settings_page() {
    if (!current_user_can('manage_options')) return;

    $test_result = null;
    if (isset($_POST['greensun_email_test']) && check_admin_referer('greensun_email_test')) {
        $to   = sanitize_email((string) ($_POST['greensun_test_to'] ?? get_option('admin_email')));
        $sent = wp_mail(
            $to,
            'Greensun test email',
            "This is a test email from your Greensun theme, sent via Brevo SMTP.\n\nIf you received this, delivery is working.",
            ['Content-Type: text/plain; charset=UTF-8']
        );
        $test_result = $sent ? ['ok' => true, 'to' => $to] : ['ok' => false];
    }

    $login_const = defined('GREENSUN_BREVO_LOGIN') && GREENSUN_BREVO_LOGIN;
    $key_const   = defined('GREENSUN_BREVO_KEY') && GREENSUN_BREVO_KEY;
    $enabled     = get_option('greensun_brevo_enabled', '1');
    ?>
    <div class="wrap">
      <h1>Email (Brevo)</h1>
      <p>Sends all site email through Brevo's SMTP relay for reliable delivery. For best security, define the credentials in <code>wp-config.php</code>:</p>
      <pre style="background:#f6f7f7;border:1px solid #dcdcde;padding:10px;border-radius:4px;">define('GREENSUN_BREVO_LOGIN', 'xxxx@smtp-brevo.com');
define('GREENSUN_BREVO_KEY',   'your-smtp-key');</pre>

      <form method="post" action="options.php">
        <?php settings_fields('greensun_email'); ?>
        <table class="form-table" role="presentation">
          <tr>
            <th scope="row">Enabled</th>
            <td><label><input type="checkbox" name="greensun_brevo_enabled" value="1" <?php checked($enabled, '1'); ?>> Route email through Brevo SMTP</label></td>
          </tr>
          <tr>
            <th scope="row"><label for="greensun_brevo_from_name">From name</label></th>
            <td><input type="text" name="greensun_brevo_from_name" id="greensun_brevo_from_name" value="<?php echo esc_attr(get_option('greensun_brevo_from_name', '')); ?>" class="regular-text" placeholder="<?php echo esc_attr(get_bloginfo('name')); ?>"></td>
          </tr>
          <tr>
            <th scope="row"><label for="greensun_brevo_from_email">From email</label></th>
            <td>
              <input type="email" name="greensun_brevo_from_email" id="greensun_brevo_from_email" value="<?php echo esc_attr(get_option('greensun_brevo_from_email', '')); ?>" class="regular-text" placeholder="hello@greensun.example">
              <p class="description">Must be a sender verified in your Brevo account.</p>
            </td>
          </tr>
          <tr>
            <th scope="row"><label for="greensun_brevo_login">SMTP login</label></th>
            <td>
              <?php if ($login_const) : ?>
                <code>Set in wp-config.php</code>
              <?php else : ?>
                <input type="text" name="greensun_brevo_login" id="greensun_brevo_login" value="<?php echo esc_attr(get_option('greensun_brevo_login', '')); ?>" class="regular-text" autocomplete="off">
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th scope="row"><label for="greensun_brevo_key">SMTP key</label></th>
            <td>
              <?php if ($key_const) : ?>
                <code>Set in wp-config.php</code>
              <?php else : ?>
                <input type="password" name="greensun_brevo_key" id="greensun_brevo_key" value="<?php echo esc_attr(get_option('greensun_brevo_key', '')); ?>" class="regular-text" autocomplete="off">
                <p class="description">Stored in the database. Prefer the wp-config constant above.</p>
              <?php endif; ?>
            </td>
          </tr>
        </table>
        <?php submit_button('Save settings'); ?>
      </form>

      <hr>
      <h2>Send a test email</h2>
      <p>Status: <strong><?php echo greensun_brevo_is_configured() ? 'Credentials present' : 'Not configured'; ?></strong></p>
      <form method="post">
        <?php wp_nonce_field('greensun_email_test'); ?>
        <p>
          <input type="email" name="greensun_test_to" value="<?php echo esc_attr(get_option('admin_email')); ?>" class="regular-text">
          <button type="submit" name="greensun_email_test" class="button">Send test</button>
        </p>
      </form>
      <?php if ($test_result !== null) : ?>
        <div class="notice <?php echo $test_result['ok'] ? 'notice-success' : 'notice-error'; ?>">
          <p><?php echo $test_result['ok']
            ? 'Test email sent to ' . esc_html($test_result['to']) . '. Check the inbox (and spam).'
            : 'wp_mail() returned false. Check credentials and the verified sender.'; ?></p>
        </div>
      <?php endif; ?>
    </div>
    <?php
}
