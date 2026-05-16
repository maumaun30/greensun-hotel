<?php
/**
 * Template Name: Booking results
 *
 * Catches deep-link GET params from the booking-bar (checkin / checkout /
 * guests / room_type) and renders availability server-side via the
 * Greensun_EZee_Client. Falls back to the page's normal content when no
 * dates are supplied.
 */

get_header();

$checkin   = isset($_GET['checkin'])   ? sanitize_text_field(wp_unslash($_GET['checkin']))   : '';
$checkout  = isset($_GET['checkout'])  ? sanitize_text_field(wp_unslash($_GET['checkout']))  : '';
$guests    = isset($_GET['guests'])    ? max(1, (int) $_GET['guests'])                       : 0;
$room_type = isset($_GET['room_type']) ? sanitize_text_field(wp_unslash($_GET['room_type'])) : '';

$has_search = $checkin && $checkout && $guests;
?>

<main class="site-main">

  <section class="section greensun-booking-hero" style="padding-block: clamp(60px, 10vw, 120px); text-align:center;">
    <div class="shell">
      <div class="eyebrow reveal">Reserve your stay</div>
      <h1 class="display reveal" style="font-size: clamp(40px, 6vw, 88px); margin-top: 16px;">
        Pick your <em>nights</em>.
      </h1>
    </div>
  </section>

  <?php
  // Always show the booking-bar so guests can revise search.
  $booking_block = WP_Block_Type_Registry::get_instance()->get_registered('greensun-hotel/booking-bar');
  if ($booking_block) {
      echo do_blocks('<!-- wp:greensun-hotel/booking-bar /-->');
  }
  ?>

  <?php if ($has_search) :
    $result = Greensun_EZee_Client::get_room_availability($checkin, $checkout, $guests, $room_type ?: null);
  ?>
    <section class="section">
      <div class="shell">
        <?php if (is_wp_error($result)) : ?>
          <div class="reveal" style="text-align:center; padding: 40px; background:#fff; border:1px solid var(--line, #ede9d9); border-radius: var(--radius-lg, 14px);">
            <div class="eyebrow" style="color: #a64;">Hmm</div>
            <p style="margin-top: 12px; color: var(--ink-2, #3d433d);"><?php echo esc_html($result->get_error_message()); ?></p>
          </div>
        <?php elseif (empty($result['rooms'])) : ?>
          <p style="text-align:center; color: var(--ink-2, #3d433d);">No rooms available for those dates.</p>
        <?php else :
          $mock = !empty($result['mock']);
          $nights = $result['nights'] ?? max(1, (strtotime($checkout) - strtotime($checkin)) / DAY_IN_SECONDS);
        ?>
          <header class="reveal" style="display:flex; align-items:center; justify-content:space-between; gap: 24px; margin-bottom: 32px;">
            <div>
              <div class="eyebrow"><?php echo esc_html(date_i18n('M j', strtotime($checkin)) . ' – ' . date_i18n('M j, Y', strtotime($checkout))); ?></div>
              <div style="margin-top: 6px; color: var(--ink-2, #3d433d);"><?php echo esc_html(sprintf('%d night · %d guests', (int) $nights, (int) $guests)); ?></div>
            </div>
            <?php if ($mock) : ?>
              <span class="chip" style="background: var(--bone, #ede9d9); color: var(--ink-2, #3d433d);">Mock data</span>
            <?php endif; ?>
          </header>

          <div style="display:grid; gap: 16px;">
            <?php foreach ($result['rooms'] as $room) :
              $currency = $room['currency']      ?? 'USD';
              $rate     = (float) ($room['nightly_rate'] ?? 0);
              $total    = (float) ($room['total']        ?? ($rate * $nights));
              $thumb    = $room['thumbnail']     ?? '';
              $link     = $room['permalink']     ?? '#';
            ?>
              <article class="gs-card reveal" style="display:grid; grid-template-columns: 200px 1fr auto; gap: 24px; align-items:center; padding: 20px; background:#fff; border:1px solid var(--line, #ede9d9); border-radius: var(--radius-lg, 14px);">
                <div class="ph" style="aspect-ratio: 4 / 3; border-radius: 10px; overflow: hidden;">
                  <?php if ($thumb) : ?>
                    <img src="<?php echo esc_url($thumb); ?>" alt="" style="width:100%; height:100%; object-fit:cover;" />
                  <?php endif; ?>
                </div>
                <div>
                  <h3 class="display" style="font-size: 30px; margin: 0;">
                    <a href="<?php echo esc_url($link); ?>" style="color: inherit; text-decoration: none;"><?php echo esc_html($room['title'] ?? ''); ?></a>
                  </h3>
                  <div style="margin-top: 8px; color: var(--ink-2, #3d433d); font-size: 14px;">
                    <?php echo esc_html($currency . ' ' . number_format_i18n($rate)); ?> / night
                  </div>
                </div>
                <div style="text-align:right;">
                  <div style="font-family: var(--font-display, 'Cormorant Garamond', serif); font-size: 32px;">
                    <?php echo esc_html($currency . ' ' . number_format_i18n($total)); ?>
                  </div>
                  <div style="font-size: 11px; letter-spacing: 0.14em; text-transform: uppercase; color: var(--mute, #7b817b);">Total</div>
                  <a href="<?php echo esc_url(add_query_arg(['room' => $room['id'] ?? '', 'checkin' => $checkin, 'checkout' => $checkout, 'guests' => $guests], $link)); ?>" class="btn btn--sun" style="margin-top: 12px;">
                    <span class="ripple"></span>
                    <span>Book</span>
                  </a>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </section>
  <?php else : ?>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <section class="section">
        <div class="shell"><?php the_content(); ?></div>
      </section>
    <?php endwhile; endif; ?>
  <?php endif; ?>

</main>

<style>
  @media (max-width: 720px) {
    .page-template-page-booking .gs-card { grid-template-columns: 1fr !important; text-align: left !important; }
    .page-template-page-booking .gs-card > div:last-child { text-align: left !important; }
  }
</style>

<?php get_footer(); ?>
