<?php
/**
 * Top-level "Greensun" admin menu that consolidates the theme's admin
 * surfaces: a summary dashboard plus the Submissions, Theme, Booking (eZee),
 * and Email (Brevo) settings pages as sub-items.
 *
 * Registered at priority 9 so the parent exists before the settings pages
 * (priority 10) attach their submenus.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', function () {
    add_menu_page(
        'Greensun',
        'Greensun',
        'manage_options',
        'greensun-dashboard',
        'greensun_dashboard_page',
        'dashicons-palmtree',
        3
    );
    add_submenu_page(
        'greensun-dashboard',
        'Dashboard',
        'Dashboard',
        'manage_options',
        'greensun-dashboard',
        'greensun_dashboard_page'
    );
}, 9);

function greensun_dashboard_page() {
    if (!current_user_can('manage_options')) return;

    $total  = (int) wp_count_posts('gs_submission')->publish;
    $unread = count(get_posts([
        'post_type'   => 'gs_submission',
        'post_status' => 'publish',
        'numberposts' => 100,
        'fields'      => 'ids',
        'meta_query'  => [['key' => '_gs_status', 'value' => 'new']],
    ]));
    $recent = get_posts([
        'post_type'   => 'gs_submission',
        'post_status' => 'publish',
        'numberposts' => 8,
    ]);

    $ezee_mode  = get_option('greensun_ezee_mode', 'mock');
    $brevo_on   = function_exists('greensun_brevo_is_configured') && greensun_brevo_is_configured();
    ?>
    <div class="wrap">
      <h1>Greensun</h1>
      <p>Overview of bookings, enquiries, and theme configuration.</p>

      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin:20px 0 30px;">
        <div class="card" style="padding:18px;">
          <div style="font-size:12px;text-transform:uppercase;letter-spacing:.06em;color:#667085;">Submissions</div>
          <div style="font-size:32px;font-weight:600;"><?php echo esc_html((string) $total); ?></div>
          <div style="color:#b54708;"><?php echo esc_html($unread . ' new'); ?></div>
        </div>
        <div class="card" style="padding:18px;">
          <div style="font-size:12px;text-transform:uppercase;letter-spacing:.06em;color:#667085;">Booking (eZee)</div>
          <div style="font-size:20px;font-weight:600;text-transform:capitalize;"><?php echo esc_html($ezee_mode); ?> mode</div>
          <a href="<?php echo esc_url(admin_url('admin.php?page=greensun-ezee')); ?>">Configure</a>
        </div>
        <div class="card" style="padding:18px;">
          <div style="font-size:12px;text-transform:uppercase;letter-spacing:.06em;color:#667085;">Email (Brevo)</div>
          <div style="font-size:20px;font-weight:600;"><?php echo $brevo_on ? 'Connected' : 'Not configured'; ?></div>
          <a href="<?php echo esc_url(admin_url('admin.php?page=greensun-email')); ?>">Configure</a>
        </div>
      </div>

      <h2>Recent submissions</h2>
      <?php if ($recent) : ?>
        <table class="widefat striped">
          <thead><tr><th>From</th><th>Email</th><th>Subject</th><th>Status</th><th>Received</th></tr></thead>
          <tbody>
            <?php foreach ($recent as $s) :
              $status = get_post_meta($s->ID, '_gs_status', true) ?: 'new';
            ?>
              <tr>
                <td><a href="<?php echo esc_url(get_edit_post_link($s->ID)); ?>"><?php echo esc_html(get_the_title($s) ?: '(no name)'); ?></a></td>
                <td><?php echo esc_html(get_post_meta($s->ID, '_gs_email', true) ?: '—'); ?></td>
                <td><?php echo esc_html(get_post_meta($s->ID, '_gs_subject', true) ?: '—'); ?></td>
                <td><?php echo $status === 'new' ? '<strong style="color:#b54708;">New</strong>' : 'Read'; ?></td>
                <td><?php echo esc_html(get_the_date('M j, Y g:i a', $s)); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <p style="margin-top:12px;"><a class="button" href="<?php echo esc_url(admin_url('edit.php?post_type=gs_submission')); ?>">View all submissions</a></p>
      <?php else : ?>
        <p>No submissions yet.</p>
      <?php endif; ?>
    </div>
    <?php
}
