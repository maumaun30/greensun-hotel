<?php
/**
 * Template Name: Booking (multi-step)
 *
 * Multi-step reservation wizard: dates & guests → choose room →
 * your details → review & confirm. Pre-fills from booking-bar deep-link
 * GET params (checkin / checkout / guests / room_type) and POSTs the
 * final payload to /wp-json/greensun/v1/booking-create via
 * assets/js/booking-flow.js.
 */

get_header();

$prefill = [
    'checkin'   => isset($_GET['checkin'])   ? sanitize_text_field(wp_unslash($_GET['checkin']))   : '',
    'checkout'  => isset($_GET['checkout'])  ? sanitize_text_field(wp_unslash($_GET['checkout']))  : '',
    'guests'    => isset($_GET['guests'])    ? max(1, (int) $_GET['guests'])                       : 2,
    'room_type' => isset($_GET['room_type']) ? sanitize_text_field(wp_unslash($_GET['room_type'])) : '',
];
if (!$prefill['checkin'])  $prefill['checkin']  = date_i18n('Y-m-d', strtotime('+1 day'));
if (!$prefill['checkout']) $prefill['checkout'] = date_i18n('Y-m-d', strtotime('+3 day'));

$rooms_q = new WP_Query([
    'post_type'      => 'room',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order title',
    'order'          => 'ASC',
]);

$rooms_data = [];
if ($rooms_q->have_posts()) {
    while ($rooms_q->have_posts()) {
        $rooms_q->the_post();
        $rid       = get_the_ID();
        $rooms_data[] = [
            'id'        => $rid,
            'title'     => get_the_title(),
            'price'     => function_exists('get_field') ? (float) get_field('price_per_night', $rid) : 0.0,
            'currency'  => function_exists('get_field') ? (get_field('currency', $rid) ?: 'USD') : 'USD',
            'size'      => function_exists('get_field') ? get_field('room_size', $rid) : '',
            'beds'      => function_exists('get_field') ? get_field('bed_configuration', $rid) : '',
            'sleeps'    => function_exists('get_field') ? (int) get_field('max_guests', $rid) : 0,
            'tagline'   => function_exists('get_field') ? get_field('tagline', $rid) : '',
            'thumb'     => get_the_post_thumbnail_url($rid, 'medium_large'),
            'permalink' => get_permalink($rid),
            'ezee_id'   => function_exists('get_field') ? get_field('ezee_room_type_id', $rid) : '',
        ];
    }
    wp_reset_postdata();
}

$steps = ['Dates & guests', 'Choose a room', 'Your details', 'Payment', 'Confirm'];
?>

<main id="site-main" class="site-main booking-flow" role="main" data-prefill="<?php echo esc_attr(wp_json_encode($prefill)); ?>" data-rooms="<?php echo esc_attr(wp_json_encode($rooms_data)); ?>">

  <section class="booking-flow__header">
    <div class="shell">
      <div class="eyebrow reveal" style="margin-bottom: 22px;">Reserve your stay</div>
      <h1 class="display reveal reveal--lg booking-flow__title">
        Book a room <em>step by step.</em>
      </h1>
    </div>
  </section>

  <section style="padding-block: 30px 120px;">
    <div class="shell booking-flow__layout" style="display:grid; grid-template-columns: 1.5fr 1fr; gap: 70px; align-items:start;">

      <div class="booking-flow__main">

        <ol class="bf-stepper" data-step="0">
          <?php foreach ($steps as $idx => $label) : ?>
            <li class="bf-stepper__item" data-index="<?php echo esc_attr($idx); ?>">
              <div class="bf-stepper__bar"></div>
              <div class="bf-stepper__row">
                <span class="bf-stepper__circle"><?php echo esc_html($idx + 1); ?></span>
                <span class="bf-stepper__label"><?php echo esc_html($label); ?></span>
              </div>
            </li>
          <?php endforeach; ?>
        </ol>

        <div class="bf-steps">

          <section class="bf-step is-active" data-step="0">
            <h2 class="display" style="font-size: 36px; max-width: 18ch;">When are you planning to stay?</h2>
            <div class="bf-dates">
              <div class="field bf-field">
                <label for="bf-arrival">Arrival</label>
                <input id="bf-arrival" type="date" name="checkin" value="<?php echo esc_attr($prefill['checkin']); ?>" />
              </div>
              <div class="field bf-field">
                <label for="bf-departure">Departure</label>
                <input id="bf-departure" type="date" name="checkout" value="<?php echo esc_attr($prefill['checkout']); ?>" />
              </div>
            </div>
            <div class="bf-guests">
              <div class="bf-guests__label">Number of guests</div>
              <div class="bf-guests__row">
                <?php foreach ([1,2,3,4] as $n) : ?>
                  <button type="button" class="bf-guest-pill<?php echo ((int) $prefill['guests'] === $n ? ' is-active' : ''); ?>" data-guests="<?php echo esc_attr($n); ?>">
                    <?php echo esc_html($n . ' ' . ($n === 1 ? 'guest' : 'guests')); ?>
                  </button>
                <?php endforeach; ?>
              </div>
            </div>
            <div class="bf-actions" style="margin-top: 48px;">
              <button type="button" class="btn btn--sun" data-action="next">
                <span class="ripple"></span><span>Continue</span>
              </button>
            </div>
          </section>

          <section class="bf-step" data-step="1">
            <h2 class="display" style="font-size: 36px; max-width: 18ch;">Choose your room.</h2>
            <p class="bf-room-meta muted" style="margin-top: 8px; color: var(--mute, #7b817b);"></p>
            <?php if (empty($rooms_data)) : ?>
              <p style="margin-top: 36px; color: var(--ink-2, #3d433d);">No rooms have been published yet. Please check back soon.</p>
            <?php else : ?>
              <div class="bf-rooms">
                <?php foreach ($rooms_data as $r) :
                  $is_pre = $prefill['room_type'] !== '' && (string) $r['id'] === (string) $prefill['room_type'];
                ?>
                  <button type="button" class="bf-room<?php echo $is_pre ? ' is-active' : ''; ?>" data-room-id="<?php echo esc_attr($r['id']); ?>">
                    <span class="bf-room__media ph">
                      <?php if ($r['thumb']) : ?>
                        <img src="<?php echo esc_url($r['thumb']); ?>" alt="" loading="lazy" />
                      <?php endif; ?>
                    </span>
                    <span class="bf-room__body">
                      <span class="display bf-room__title"><?php echo esc_html($r['title']); ?></span>
                      <span class="bf-room__meta muted">
                        <?php echo esc_html(trim(implode(' · ', array_filter([
                            $r['size'],
                            $r['beds'],
                            $r['sleeps'] ? 'Sleeps ' . $r['sleeps'] : '',
                        ])))); ?>
                      </span>
                      <?php if ($r['tagline']) : ?>
                        <span class="bf-room__tagline"><?php echo esc_html($r['tagline']); ?></span>
                      <?php endif; ?>
                    </span>
                    <span class="bf-room__price">
                      <span class="bf-room__from">from</span>
                      <span class="display bf-room__rate"><?php echo esc_html($r['currency'] . ' ' . number_format_i18n($r['price'])); ?></span>
                      <span class="bf-room__per">/ night</span>
                    </span>
                  </button>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
            <div class="bf-actions">
              <button type="button" class="btn btn--ghost" data-action="prev"><span class="ripple"></span><span>Back</span></button>
              <button type="button" class="btn btn--sun" data-action="next" data-needs-room="1"><span class="ripple"></span><span>Continue</span></button>
            </div>
          </section>

          <section class="bf-step" data-step="2">
            <h2 class="display" style="font-size: 36px; max-width: 18ch;">Tell us about you.</h2>
            <div class="bf-details">
              <div class="field bf-field"><label for="bf-first">First name</label><input id="bf-first" type="text" name="firstName" /></div>
              <div class="field bf-field"><label for="bf-last">Last name</label><input id="bf-last" type="text" name="lastName" /></div>
              <div class="field bf-field"><label for="bf-email">Email</label><input id="bf-email" type="email" name="email" placeholder="you@example.com" /></div>
              <div class="field bf-field"><label for="bf-phone">Phone</label><input id="bf-phone" type="tel" name="phone" /></div>
              <div class="field bf-field bf-field--full"><label for="bf-notes">Special requests (optional)</label><textarea id="bf-notes" name="notes" rows="3"></textarea></div>
            </div>
            <div class="bf-actions">
              <button type="button" class="btn btn--ghost" data-action="prev"><span class="ripple"></span><span>Back</span></button>
              <button type="button" class="btn btn--sun" data-action="next"><span class="ripple"></span><span>Continue</span></button>
            </div>
          </section>

          <section class="bf-step" data-step="3">
            <h2 class="display" style="font-size: 36px; max-width: 18ch;">How would you like to pay?</h2>

            <div class="bf-payment">
              <div class="bf-payment__label">Payment method</div>
              <div class="bf-payment__row">
                <button type="button" class="bf-pay is-active" data-payment="card">Credit / debit card</button>
                <button type="button" class="bf-pay" data-payment="gcash">GCash</button>
                <button type="button" class="bf-pay" data-payment="paypal">PayPal</button>
                <button type="button" class="bf-pay" data-payment="onsite">Pay at hotel</button>
              </div>
            </div>

            <div class="bf-card" data-card-fields>
              <div class="field bf-field bf-field--full"><label for="bf-card-name">Name on card</label><input id="bf-card-name" type="text" name="cardName" autocomplete="cc-name" /></div>
              <div class="field bf-field bf-field--full"><label for="bf-card-number">Card number</label><input id="bf-card-number" type="text" name="cardNumber" inputmode="numeric" autocomplete="cc-number" placeholder="1234 5678 9012 3456" maxlength="19" /></div>
              <div class="field bf-field"><label for="bf-card-exp">Expiry</label><input id="bf-card-exp" type="text" name="cardExp" inputmode="numeric" autocomplete="cc-exp" placeholder="MM/YY" maxlength="5" /></div>
              <div class="field bf-field"><label for="bf-card-cvc">CVC</label><input id="bf-card-cvc" type="text" name="cardCvc" inputmode="numeric" autocomplete="cc-csc" placeholder="123" maxlength="4" /></div>
            </div>

            <div class="bf-promo">
              <div class="field bf-field bf-promo__field"><label for="bf-promo">Promo code</label><input id="bf-promo" type="text" name="promo" placeholder="Have a code?" autocapitalize="characters" /></div>
              <button type="button" class="btn btn--ghost btn--sm bf-promo__apply" data-action="apply-promo"><span class="ripple"></span><span>Apply</span></button>
            </div>
            <p class="bf-promo__msg" data-promo-msg role="status" aria-live="polite" hidden></p>
            <p class="bf-payment__note muted">Payments are simulated in this preview — no card is charged. Try a demo code: <code>GREENSUN10</code>, <code>STAY3PAY2</code>, or <code>WELCOME500</code>.</p>

            <div class="bf-actions">
              <button type="button" class="btn btn--ghost" data-action="prev"><span class="ripple"></span><span>Back</span></button>
              <button type="button" class="btn btn--sun" data-action="next"><span class="ripple"></span><span>Continue</span></button>
            </div>
          </section>

          <section class="bf-step" data-step="4">
            <h2 class="display" style="font-size: 36px; max-width: 18ch;">Review and confirm.</h2>
            <dl class="bf-review">
              <div class="bf-review__row"><dt>Guest</dt><dd data-field="name">—</dd></div>
              <div class="bf-review__row"><dt>Email</dt><dd data-field="email">—</dd></div>
              <div class="bf-review__row"><dt>Phone</dt><dd data-field="phone">—</dd></div>
              <div class="bf-review__row"><dt>Arrival</dt><dd data-field="arrival">—</dd></div>
              <div class="bf-review__row"><dt>Departure</dt><dd data-field="departure">—</dd></div>
              <div class="bf-review__row"><dt>Stay</dt><dd data-field="stay">—</dd></div>
              <div class="bf-review__row"><dt>Room</dt><dd data-field="room">—</dd></div>
              <div class="bf-review__row"><dt>Payment</dt><dd data-field="payment">—</dd></div>
              <div class="bf-review__row" data-review-promo hidden><dt>Promo</dt><dd data-field="promo">—</dd></div>
              <div class="bf-review__row"><dt>Notes</dt><dd data-field="notes">—</dd></div>
            </dl>
            <div class="bf-actions bf-actions--between">
              <button type="button" class="btn btn--ghost" data-action="prev"><span class="ripple"></span><span>Back</span></button>
              <button type="button" class="btn btn--sun btn--lg" data-action="confirm">
                <span class="ripple"></span><span data-confirm-label>Confirm reservation</span>
              </button>
            </div>
            <div class="bf-error" hidden></div>
          </section>

          <section class="bf-step bf-step--done" data-step="5">
            <div class="bf-done">
              <div class="bf-done__check" aria-hidden="true">
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                  <path d="M14 24 L21 31 L36 16" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
              <h2 class="display" style="font-size: clamp(40px, 5vw, 60px);">Reservation confirmed.</h2>
              <dl class="bf-done__receipt">
                <div><dt>Reference</dt><dd class="bf-done__ref" data-field="reference">—</dd></div>
                <div><dt>Total</dt><dd class="display" data-field="total">—</dd></div>
              </dl>
              <p class="bf-done__body">Your confirmation email is on its way to <strong data-field="email">your inbox</strong>. We'll see you on <span data-field="arrival">—</span>.</p>
              <div class="bf-actions" style="justify-content:center;">
                <a class="btn" href="<?php echo esc_url(home_url('/')); ?>"><span class="ripple"></span><span>Back to home</span></a>
                <a class="btn btn--ghost" href="<?php echo esc_url(home_url('/contact')); ?>"><span class="ripple"></span><span>Add a request</span></a>
              </div>
            </div>
          </section>

        </div>
      </div>

      <aside class="booking-flow__summary">
        <div class="bf-summary">
          <div class="bf-summary__eyebrow">Your stay</div>
          <h3 class="display bf-summary__title" data-summary="room">Select a room</h3>
          <div class="bf-summary__meta" data-summary="meta">—</div>

          <div class="bf-summary__divider"></div>

          <dl class="bf-summary__lines">
            <div><dt data-summary="line-label">Room</dt><dd data-summary="subtotal">—</dd></div>
            <div class="bf-summary__discount" data-summary-discount hidden><dt data-summary="discount-label">Promo</dt><dd data-summary="discount">—</dd></div>
            <div><dt>Taxes &amp; fees (12%)</dt><dd data-summary="tax">—</dd></div>
          </dl>

          <div class="bf-summary__divider"></div>

          <div class="bf-summary__total">
            <span>Total</span>
            <span class="display" data-summary="total">—</span>
          </div>

          <div class="bf-summary__perk">
            <strong>Direct-rate guarantee.</strong> Find a lower rate elsewhere within 24h of booking — we'll match it.
          </div>
        </div>
      </aside>

    </div>
  </section>

</main>

<?php get_footer(); ?>
