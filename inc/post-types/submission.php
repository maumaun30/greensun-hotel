<?php
/**
 * Contact form submissions, stored as a private CPT (gs_submission).
 * Not publicly queryable; visible only in wp-admin under the Greensun menu.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('init', function () {
    register_post_type('gs_submission', [
        'labels' => [
            'name'          => __('Submissions', 'greensun-hotel'),
            'singular_name' => __('Submission', 'greensun-hotel'),
            'menu_name'     => __('Submissions', 'greensun-hotel'),
            'all_items'     => __('Submissions', 'greensun-hotel'),
            'search_items'  => __('Search submissions', 'greensun-hotel'),
            'not_found'     => __('No submissions yet', 'greensun-hotel'),
        ],
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => 'greensun-dashboard',
        'show_in_rest'        => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
        'has_archive'         => false,
        'rewrite'             => false,
        'supports'            => ['title'],
        'capability_type'     => 'post',
        'map_meta_cap'        => true,
        'capabilities'        => ['create_posts' => 'do_not_allow'],
    ]);
});

/**
 * Insert a submission record. Returns the post ID or 0 on failure.
 */
function greensun_store_submission(array $data) {
    $name    = $data['name']    ?? '';
    $subject = $data['subject'] ?? '';
    $title   = trim($name . ($subject ? ' — ' . $subject : '')) ?: __('Submission', 'greensun-hotel');

    $post_id = wp_insert_post([
        'post_type'   => 'gs_submission',
        'post_status' => 'publish',
        'post_title'  => wp_strip_all_tags($title),
    ], true);

    if (is_wp_error($post_id) || !$post_id) {
        return 0;
    }

    foreach (['name', 'email', 'phone', 'subject', 'message', 'ip', 'source'] as $key) {
        if (isset($data[$key]) && $data[$key] !== '') {
            update_post_meta($post_id, '_gs_' . $key, $data[$key]);
        }
    }
    update_post_meta($post_id, '_gs_status', 'new');

    return $post_id;
}

/* ---- Admin list columns ---- */

add_filter('manage_gs_submission_posts_columns', function ($cols) {
    return [
        'cb'           => $cols['cb'] ?? '<input type="checkbox" />',
        'title'        => __('From', 'greensun-hotel'),
        'gs_email'     => __('Email', 'greensun-hotel'),
        'gs_subject'   => __('Subject', 'greensun-hotel'),
        'gs_status'    => __('Status', 'greensun-hotel'),
        'date'         => __('Received', 'greensun-hotel'),
    ];
});

add_action('manage_gs_submission_posts_custom_column', function ($col, $post_id) {
    switch ($col) {
        case 'gs_email':
            $email = get_post_meta($post_id, '_gs_email', true);
            echo $email ? '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>' : '—';
            break;
        case 'gs_subject':
            echo esc_html(get_post_meta($post_id, '_gs_subject', true) ?: '—');
            break;
        case 'gs_status':
            $status = get_post_meta($post_id, '_gs_status', true) ?: 'new';
            $label  = $status === 'new' ? __('New', 'greensun-hotel') : __('Read', 'greensun-hotel');
            $color  = $status === 'new' ? '#b54708' : '#667085';
            echo '<span style="font-weight:' . ($status === 'new' ? '600' : '400') . ';color:' . esc_attr($color) . '">' . esc_html($label) . '</span>';
            break;
    }
}, 10, 2);

/* ---- Read-only detail metabox ---- */

add_action('add_meta_boxes_gs_submission', function () {
    add_meta_box(
        'gs_submission_detail',
        __('Submission', 'greensun-hotel'),
        'greensun_submission_detail_metabox',
        'gs_submission',
        'normal',
        'high'
    );
});

function greensun_submission_detail_metabox($post) {
    $fields = [
        __('Name', 'greensun-hotel')    => get_post_meta($post->ID, '_gs_name', true),
        __('Email', 'greensun-hotel')   => get_post_meta($post->ID, '_gs_email', true),
        __('Phone', 'greensun-hotel')   => get_post_meta($post->ID, '_gs_phone', true),
        __('Subject', 'greensun-hotel') => get_post_meta($post->ID, '_gs_subject', true),
        __('Source', 'greensun-hotel')  => get_post_meta($post->ID, '_gs_source', true),
        __('IP', 'greensun-hotel')      => get_post_meta($post->ID, '_gs_ip', true),
    ];
    $message = get_post_meta($post->ID, '_gs_message', true);
    ?>
    <table class="form-table" role="presentation">
      <?php foreach ($fields as $label => $value) : if (!$value) continue; ?>
        <tr>
          <th scope="row" style="width:140px;"><?php echo esc_html($label); ?></th>
          <td>
            <?php if ($label === __('Email', 'greensun-hotel')) : ?>
              <a href="mailto:<?php echo esc_attr($value); ?>"><?php echo esc_html($value); ?></a>
            <?php else : ?>
              <?php echo esc_html($value); ?>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
    <?php if ($message) : ?>
      <h4 style="margin:16px 0 6px;"><?php esc_html_e('Message', 'greensun-hotel'); ?></h4>
      <div style="white-space:pre-wrap;background:#f6f7f7;border:1px solid #dcdcde;border-radius:4px;padding:12px;"><?php echo esc_html($message); ?></div>
    <?php endif; ?>
    <?php
}

/* Mark a submission read when opened in the editor. */
add_action('load-post.php', function () {
    $post_id = isset($_GET['post']) ? (int) $_GET['post'] : 0;
    if ($post_id && get_post_type($post_id) === 'gs_submission') {
        if (get_post_meta($post_id, '_gs_status', true) === 'new') {
            update_post_meta($post_id, '_gs_status', 'read');
        }
    }
});

/* Unread-count bubble on the Submissions menu. */
add_action('admin_menu', function () {
    $unread = get_posts([
        'post_type'   => 'gs_submission',
        'post_status' => 'publish',
        'numberposts' => 50,
        'fields'      => 'ids',
        'meta_query'  => [['key' => '_gs_status', 'value' => 'new']],
    ]);
    $count = count($unread);
    if (!$count) return;

    global $submenu;
    if (empty($submenu['greensun-dashboard'])) return;
    foreach ($submenu['greensun-dashboard'] as &$item) {
        if (isset($item[2]) && $item[2] === 'edit.php?post_type=gs_submission') {
            $item[0] .= ' <span class="awaiting-mod"><span class="pending-count">' . (int) $count . '</span></span>';
            break;
        }
    }
    unset($item);
}, 100);
