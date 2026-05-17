<?php
$submit_text = $attributes['submitText'] ?? 'Check availability';
$action_url  = $attributes['actionUrl']  ?? '';
$show_room   = !empty($attributes['showRoomType']);

if (empty($action_url)) {
    $action_url = home_url('/booking');
}

// Defaults: tomorrow → +2 nights
$arrival_ts   = strtotime('+1 day midnight');
$departure_ts = strtotime('+3 days midnight');
$arrival      = date('Y-m-d', $arrival_ts);
$departure    = date('Y-m-d', $departure_ts);

$rooms = get_posts([
    'post_type'      => 'room',
    'posts_per_page' => 50,
    'orderby'        => 'menu_order title',
    'order'          => 'ASC',
]);

$room_data = array_map(function ($room) {
    $id       = $room->ID;
    $price    = function_exists('get_field') ? (int) get_field('price_per_night', $id) : 0;
    $currency = function_exists('get_field') ? (get_field('currency', $id) ?: 'PHP') : 'PHP';
    $size     = function_exists('get_field') ? get_field('room_size', $id) : '';
    $beds     = function_exists('get_field') ? get_field('bed_configuration', $id) : '';
    $ezee_id  = function_exists('get_field') ? get_field('ezee_room_type_id', $id) : '';
    $thumb_id = get_post_thumbnail_id($id);
    $thumb    = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'thumbnail') : '';
    return [
        'id'    => $ezee_id ?: $room->post_name,
        'name'  => get_the_title($room),
        'size'  => $size,
        'beds'  => $beds,
        'price' => $price,
        'currency' => $currency,
        'thumb' => $thumb,
    ];
}, $rooms);

$initial_format = function ($ts) {
    return [
        'day' => date('j M', $ts),
        'sub' => date('l, Y', $ts),
    ];
};
$arr_fmt = $initial_format($arrival_ts);
$dep_fmt = $initial_format($departure_ts);
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'greensun-booking-bar']); ?>>
  <div class="shell">
    <form
      class="booking-bar reveal"
      action="<?php echo esc_url($action_url); ?>"
      method="get"
      data-rooms="<?php echo esc_attr(wp_json_encode(array_values($room_data))); ?>"
      data-arrival="<?php echo esc_attr($arrival); ?>"
      data-departure="<?php echo esc_attr($departure); ?>"
    >
      <input type="hidden" name="checkin"   value="<?php echo esc_attr($arrival); ?>">
      <input type="hidden" name="checkout"  value="<?php echo esc_attr($departure); ?>">
      <input type="hidden" name="guests"    value="2">
      <input type="hidden" name="room_type" value="">

      <!-- Arrival -->
      <div class="bb-box" data-field="arrival">
        <button type="button" class="bb-box__trigger" aria-haspopup="dialog" aria-expanded="false">
          <span class="bb-box__icon">
            <svg width="20" height="20" viewBox="0 0 22 22" fill="none" aria-hidden="true">
              <rect x="2.5" y="4.5" width="17" height="15" rx="2" stroke="currentColor" stroke-width="1.4"/>
              <path d="M2.5 9 H19.5 M7 2 V6 M15 2 V6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
            </svg>
          </span>
          <span class="bb-box__text">
            <span class="bb-box__label">Arrival</span>
            <span class="bb-box__value display" data-bind="arrival-day"><?php echo esc_html($arr_fmt['day']); ?></span>
            <span class="bb-box__sub mono" data-bind="arrival-sub"><?php echo esc_html($arr_fmt['sub']); ?></span>
          </span>
          <span class="bb-box__chev" aria-hidden="true">
            <svg viewBox="0 0 14 14" fill="none"><path d="M3 5 L7 9 L11 5" stroke="currentColor" stroke-width="1.4" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </span>
        </button>
        <div class="bb-popover" role="dialog" aria-label="Choose arrival date" hidden>
          <div class="bb-cal" data-cal="arrival"></div>
        </div>
      </div>

      <!-- Departure -->
      <div class="bb-box" data-field="departure">
        <button type="button" class="bb-box__trigger" aria-haspopup="dialog" aria-expanded="false">
          <span class="bb-box__icon">
            <svg width="20" height="20" viewBox="0 0 22 22" fill="none" aria-hidden="true">
              <rect x="2.5" y="4.5" width="17" height="15" rx="2" stroke="currentColor" stroke-width="1.4"/>
              <path d="M2.5 9 H19.5 M7 2 V6 M15 2 V6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
              <path d="M9 14 L13 14 M11.5 12.5 L13 14 L11.5 15.5" stroke="currentColor" stroke-width="1.4" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </span>
          <span class="bb-box__text">
            <span class="bb-box__label">Departure</span>
            <span class="bb-box__value display" data-bind="departure-day"><?php echo esc_html($dep_fmt['day']); ?></span>
            <span class="bb-box__sub mono" data-bind="departure-sub">2 nights</span>
          </span>
          <span class="bb-box__chev" aria-hidden="true">
            <svg viewBox="0 0 14 14" fill="none"><path d="M3 5 L7 9 L11 5" stroke="currentColor" stroke-width="1.4" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </span>
        </button>
        <div class="bb-popover" role="dialog" aria-label="Choose departure date" hidden>
          <div class="bb-cal" data-cal="departure"></div>
        </div>
      </div>

      <!-- Guests -->
      <div class="bb-box" data-field="guests">
        <button type="button" class="bb-box__trigger" aria-haspopup="dialog" aria-expanded="false">
          <span class="bb-box__icon">
            <svg width="20" height="20" viewBox="0 0 22 22" fill="none" aria-hidden="true">
              <circle cx="11" cy="8" r="3.2" stroke="currentColor" stroke-width="1.4"/>
              <path d="M4 19 C4 14.5 7.5 12.5 11 12.5 C14.5 12.5 18 14.5 18 19" stroke="currentColor" stroke-width="1.4" fill="none" stroke-linecap="round"/>
            </svg>
          </span>
          <span class="bb-box__text">
            <span class="bb-box__label">Guests</span>
            <span class="bb-box__value display" data-bind="guests-value">2</span>
            <span class="bb-box__sub mono" data-bind="guests-sub">Guests</span>
          </span>
          <span class="bb-box__chev" aria-hidden="true">
            <svg viewBox="0 0 14 14" fill="none"><path d="M3 5 L7 9 L11 5" stroke="currentColor" stroke-width="1.4" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </span>
        </button>
        <div class="bb-popover" role="dialog" aria-label="Choose number of guests" hidden>
          <div class="bb-stepper">
            <div class="bb-stepper__head">
              <div class="display" style="font-size:22px;">Guests</div>
              <div class="mono" style="color:var(--mute);">Up to 4 per room</div>
            </div>
            <div class="bb-stepper__ctrl">
              <button type="button" class="bb-step" data-step="-1" aria-label="Decrease guests">−</button>
              <div class="bb-stepper__num display" data-bind="guests-num">2</div>
              <button type="button" class="bb-step" data-step="+1" aria-label="Increase guests">+</button>
            </div>
          </div>
        </div>
      </div>

      <?php if ($show_room) : ?>
      <!-- Room -->
      <div class="bb-box bb-box--wide" data-field="room">
        <button type="button" class="bb-box__trigger" aria-haspopup="dialog" aria-expanded="false">
          <span class="bb-box__icon">
            <svg width="20" height="20" viewBox="0 0 22 22" fill="none" aria-hidden="true">
              <path d="M3 17 V8 M3 12 H19 V17 M19 12 V10 a2 2 0 0 0 -2 -2 H11 V12" stroke="currentColor" stroke-width="1.4" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
              <circle cx="7" cy="11" r="1.2" stroke="currentColor" stroke-width="1.2"/>
            </svg>
          </span>
          <span class="bb-box__text">
            <span class="bb-box__label">Room</span>
            <span class="bb-box__value display" data-bind="room-value">Any room</span>
            <span class="bb-box__sub mono" data-bind="room-sub">See all <?php echo (int) count($room_data); ?> options</span>
          </span>
          <span class="bb-box__chev" aria-hidden="true">
            <svg viewBox="0 0 14 14" fill="none"><path d="M3 5 L7 9 L11 5" stroke="currentColor" stroke-width="1.4" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </span>
        </button>
        <div class="bb-popover bb-popover--wide" role="dialog" aria-label="Choose room type" hidden>
          <div class="bb-rooms" data-rooms-list></div>
        </div>
      </div>
      <?php endif; ?>

      <button type="submit" class="bb-submit">
        <span><?php echo esc_html($submit_text); ?></span>
        <svg width="22" height="8" viewBox="0 0 22 8" fill="none" aria-hidden="true">
          <path d="M0 4 L20 4 M14 0 L20 4 L14 8" stroke="currentColor" stroke-width="1.4" fill="none"/>
        </svg>
      </button>
    </form>
  </div>
</section>
