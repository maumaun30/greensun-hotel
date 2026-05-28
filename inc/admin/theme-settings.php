<?php
/**
 * Settings → Greensun Theme.
 * One place for brand tagline, contact info, and social links — used by
 * header, footer, contact blocks, etc.
 *
 * Reader helper:
 *   greensun_setting('phone'); // any of the keys below
 */

if (!defined('ABSPATH')) {
    exit;
}

function greensun_theme_setting_keys() {
    return [
        'brand_tagline'      => ['label' => 'Brand tagline (footer)',        'type' => 'textarea'],
        'address'            => ['label' => 'Address',                       'type' => 'textarea'],
        'phone'              => ['label' => 'Phone',                         'type' => 'text'],
        'email'              => ['label' => 'Email',                         'type' => 'text'],
        'social_facebook'    => ['label' => 'Facebook URL',                  'type' => 'url'],
        'social_instagram'   => ['label' => 'Instagram URL',                 'type' => 'url'],
        'social_tripadvisor' => ['label' => 'Tripadvisor URL',               'type' => 'url'],
        'footer_legal_html'  => ['label' => 'Footer bottom-row links (HTML)','type' => 'textarea'],
        'rooms_archive_page'  => ['label' => 'Rooms archive layout (Page)',  'type' => 'page'],
        'venues_archive_page' => ['label' => 'Venues archive layout (Page)', 'type' => 'page'],
        'events_archive_page' => ['label' => 'Events archive layout (Page)', 'type' => 'page'],
    ];
}

function greensun_setting($key, $default = '') {
    $value = get_option('greensun_' . $key, null);
    return $value !== null && $value !== '' ? $value : $default;
}

add_action('admin_menu', function () {
    add_submenu_page(
        'greensun-dashboard',
        'Theme Settings',
        'Theme Settings',
        'manage_options',
        'greensun-theme',
        'greensun_theme_settings_page'
    );
});

add_action('admin_init', function () {
    foreach (greensun_theme_setting_keys() as $key => $meta) {
        $sanitizer = $meta['type'] === 'url'
            ? 'esc_url_raw'
            : ($meta['type'] === 'page' ? 'absint'
            : ($meta['type'] === 'textarea' ? function ($v) { return wp_kses_post(wp_unslash($v)); } : 'sanitize_text_field'));
        register_setting('greensun_theme', 'greensun_' . $key, [
            'type'              => 'string',
            'sanitize_callback' => $sanitizer,
        ]);
    }
});

function greensun_theme_settings_page() {
    if (!current_user_can('manage_options')) return;
    ?>
    <div class="wrap">
      <h1>Greensun Theme</h1>
      <p>Brand and contact details shown in the header, footer, and content blocks.</p>
      <form method="post" action="options.php">
        <?php settings_fields('greensun_theme'); ?>
        <table class="form-table" role="presentation">
          <?php foreach (greensun_theme_setting_keys() as $key => $meta) :
              $option = 'greensun_' . $key;
              $value  = (string) get_option($option, '');
          ?>
            <tr>
              <th scope="row"><label for="<?php echo esc_attr($option); ?>"><?php echo esc_html($meta['label']); ?></label></th>
              <td>
                <?php if ($meta['type'] === 'page') : ?>
                  <?php wp_dropdown_pages([
                      'name'              => $option,
                      'id'                => $option,
                      'selected'          => (int) $value,
                      'show_option_none'  => '— Use designed template —',
                      'option_none_value' => 0,
                  ]); ?>
                  <p class="description">Pick a Page built with theme blocks to replace this archive's design, or leave as default.</p>
                <?php elseif ($meta['type'] === 'textarea') : ?>
                  <textarea name="<?php echo esc_attr($option); ?>" id="<?php echo esc_attr($option); ?>" rows="3" class="large-text"><?php echo esc_textarea($value); ?></textarea>
                <?php elseif ($meta['type'] === 'url') : ?>
                  <input type="url" name="<?php echo esc_attr($option); ?>" id="<?php echo esc_attr($option); ?>" value="<?php echo esc_attr($value); ?>" class="regular-text" />
                <?php else : ?>
                  <input type="text" name="<?php echo esc_attr($option); ?>" id="<?php echo esc_attr($option); ?>" value="<?php echo esc_attr($value); ?>" class="regular-text" />
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </table>
        <?php submit_button('Save settings'); ?>
      </form>
    </div>
    <?php
}
