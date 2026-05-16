<?php
$submit_text   = $attributes['submitText']   ?? 'Check availability';
$action_url    = $attributes['actionUrl']    ?? '';
$show_room     = !empty($attributes['showRoomType']);

if (empty($action_url)) {
    $action_url = home_url('/booking');
}

$today    = date('Y-m-d');
$tomorrow = date('Y-m-d', strtotime('+1 day'));

$rooms = get_posts([
    'post_type'      => 'room',
    'posts_per_page' => 50,
    'orderby'        => 'menu_order title',
    'order'          => 'ASC',
]);
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'section greensun-booking-bar', 'style' => 'padding-block: 24px;']); ?>>
  <div class="shell">
    <form class="booking-bar reveal" action="<?php echo esc_url($action_url); ?>" method="get" style="display:grid; grid-template-columns: <?php echo $show_room ? '1fr 1fr 1fr 1fr auto' : '1fr 1fr 1fr auto'; ?>; gap: 14px; align-items:end; padding: 22px; background:#fff; border:1px solid var(--line, #ede9d9); border-radius: var(--radius-lg, 14px); box-shadow: 0 10px 40px rgba(15,32,24,0.06);">
      <label class="field">
        <span style="display:block; font-size: 11px; letter-spacing: 0.18em; text-transform: uppercase; color: var(--mute, #7b817b); margin-bottom: 6px;">Check-in</span>
        <input type="date" name="checkin" min="<?php echo esc_attr($today); ?>" value="<?php echo esc_attr($today); ?>" required style="width:100%; border:0; border-bottom:1px solid var(--line); padding: 8px 0; font: inherit; background: transparent;" />
      </label>
      <label class="field">
        <span style="display:block; font-size: 11px; letter-spacing: 0.18em; text-transform: uppercase; color: var(--mute, #7b817b); margin-bottom: 6px;">Check-out</span>
        <input type="date" name="checkout" min="<?php echo esc_attr($tomorrow); ?>" value="<?php echo esc_attr($tomorrow); ?>" required style="width:100%; border:0; border-bottom:1px solid var(--line); padding: 8px 0; font: inherit; background: transparent;" />
      </label>
      <label class="field">
        <span style="display:block; font-size: 11px; letter-spacing: 0.18em; text-transform: uppercase; color: var(--mute, #7b817b); margin-bottom: 6px;">Guests</span>
        <select name="guests" style="width:100%; border:0; border-bottom:1px solid var(--line); padding: 8px 0; font: inherit; background: transparent;">
          <?php for ($g = 1; $g <= 6; $g++) : ?>
            <option value="<?php echo esc_attr($g); ?>" <?php selected($g, 2); ?>><?php echo esc_html(sprintf(_n('%d guest', '%d guests', $g, 'greensun-hotel'), $g)); ?></option>
          <?php endfor; ?>
        </select>
      </label>
      <?php if ($show_room) : ?>
        <label class="field">
          <span style="display:block; font-size: 11px; letter-spacing: 0.18em; text-transform: uppercase; color: var(--mute, #7b817b); margin-bottom: 6px;">Room type</span>
          <select name="room_type" style="width:100%; border:0; border-bottom:1px solid var(--line); padding: 8px 0; font: inherit; background: transparent;">
            <option value="">Any</option>
            <?php foreach ($rooms as $room) :
              $ezee_id = function_exists('get_field') ? get_field('ezee_room_type_id', $room->ID) : '';
              $value   = $ezee_id ?: $room->post_name;
            ?>
              <option value="<?php echo esc_attr($value); ?>"><?php echo esc_html(get_the_title($room)); ?></option>
            <?php endforeach; ?>
          </select>
        </label>
      <?php endif; ?>
      <button type="submit" class="btn btn--sun">
        <span class="ripple"></span>
        <span><?php echo esc_html($submit_text); ?></span>
      </button>
    </form>
  </div>
</section>
