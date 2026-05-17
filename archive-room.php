<?php get_header(); ?>

<main id="site-main" class="site-main rooms-archive" role="main">

  <section class="rooms-archive__header">
    <div class="shell">
      <div class="eyebrow reveal" style="margin-bottom: 22px;">Our rooms</div>
      <h1 class="display reveal reveal--lg rooms-archive__title">
        Relax and connect <em>in your own space.</em>
      </h1>
      <p class="reveal rooms-archive__lead">
        Five distinct rooms — from quiet quarters for the working traveler to a 60 sqm family suite. All air-conditioned, all WiFi-equipped, all built for restful nights.
      </p>
    </div>
  </section>

  <section style="padding: 40px 0 120px;">
    <div class="shell">
      <?php if (have_posts()) : ?>
        <ul class="rooms-archive__stack">
          <?php $room_idx = 0; while (have_posts()) : the_post();
            $room_id  = get_the_ID();
            $price    = function_exists('get_field') ? get_field('price_per_night', $room_id) : null;
            $currency = function_exists('get_field') ? (get_field('currency', $room_id) ?: 'USD') : 'USD';
            $size     = function_exists('get_field') ? get_field('room_size', $room_id) : null;
            $guests   = function_exists('get_field') ? (int) get_field('max_guests', $room_id) : 0;
            $beds     = function_exists('get_field') ? get_field('bed_configuration', $room_id) : null;
            $tagline  = function_exists('get_field') ? get_field('tagline', $room_id) : null;
            $thumb    = get_the_post_thumbnail_url($room_id, 'full');
            $flip     = ($room_idx % 2) === 1;

            // Build amenity chip list — from ACF inclusions if available, else defaults.
            $inclusions = function_exists('get_field') ? get_field('inclusions', $room_id) : [];
            $chips      = [];
            if (!empty($inclusions) && is_array($inclusions)) {
              foreach ($inclusions as $inc) {
                $label = is_array($inc) ? ($inc['text'] ?? $inc['inclusion'] ?? reset($inc)) : $inc;
                if ($label) $chips[] = $label;
                if (count($chips) >= 6) break;
              }
            }
            if (empty($chips)) $chips = ['WiFi', 'AC', '43" TV', 'Hot shower', 'Toiletries'];

            // Window spec is a boolean derived from inclusions: any chip mentioning "window".
            $has_window = false;
            foreach ($chips as $c) { if (stripos($c, 'window') !== false) { $has_window = true; break; } }
          ?>
            <li>
              <article class="rooms-archive__row reveal reveal--lg<?php echo $flip ? ' is-flipped' : ''; ?>">
                <div class="rooms-archive__media">
                  <a href="<?php the_permalink(); ?>" class="ph kb rooms-archive__img" style="display:block; height: 520px; border-radius: 4px; overflow:hidden;">
                    <?php echo greensun_post_thumbnail_html($room_id, 'large', '(max-width: 900px) 100vw, 60vw', 'rooms-archive__img-el'); ?>
                  </a>
                  <div class="rooms-archive__spec-card">
                    <?php if ($size) : ?>
                      <div class="rooms-archive__spec">
                        <div class="display"><?php echo esc_html(preg_replace('/[^0-9.]/', '', (string) $size) ?: $size); ?></div>
                        <div class="rooms-archive__spec-unit">sqm</div>
                      </div>
                    <?php endif; ?>
                    <?php if ($guests) : ?>
                      <div class="rooms-archive__spec">
                        <div class="display"><?php echo esc_html((string) $guests); ?></div>
                        <div class="rooms-archive__spec-unit"><?php echo esc_html($guests === 1 ? 'guest' : 'guests'); ?></div>
                      </div>
                    <?php endif; ?>
                    <div class="rooms-archive__spec">
                      <div class="display"><?php echo $has_window ? 'yes' : 'no'; ?></div>
                      <div class="rooms-archive__spec-unit">window</div>
                    </div>
                  </div>
                </div>

                <div class="rooms-archive__body">
                  <div class="rooms-archive__num">
                    <?php echo esc_html(sprintf('%02d', $room_idx + 1)); ?><?php echo $beds ? ' — ' . esc_html($beds) : ''; ?>
                  </div>
                  <h2 class="display rooms-archive__name">
                    <a href="<?php the_permalink(); ?>" style="color: inherit; text-decoration: none;"><?php the_title(); ?></a>
                    <?php if ($tagline) : ?>
                      <br><em><?php echo esc_html($tagline); ?></em>
                    <?php endif; ?>
                  </h2>
                  <?php if (get_the_excerpt()) : ?>
                    <p class="rooms-archive__blurb"><?php echo esc_html(get_the_excerpt()); ?></p>
                  <?php endif; ?>

                  <?php if (!empty($chips)) : ?>
                    <div class="rooms-archive__chips">
                      <?php foreach ($chips as $c) : ?>
                        <span class="chip"><span class="dot"></span><?php echo esc_html($c); ?></span>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>

                  <div class="rooms-archive__cta-row">
                    <?php if ($price) : ?>
                      <div>
                        <div class="rooms-archive__from">from</div>
                        <div class="display rooms-archive__price">
                          <?php echo esc_html($currency . ' ' . number_format_i18n((float) $price)); ?>
                          <span class="rooms-archive__per">/ NIGHT</span>
                        </div>
                      </div>
                    <?php endif; ?>
                    <div class="rooms-archive__buttons">
                      <a href="<?php the_permalink(); ?>" class="btn btn--ghost">
                        <span class="ripple"></span>
                        <span>View details</span>
                      </a>
                      <a href="<?php echo esc_url(add_query_arg('room_type', $room_id, home_url('/booking'))); ?>" class="btn btn--sun">
                        <span class="ripple"></span>
                        <span>Book this room</span>
                        <svg width="14" height="10" viewBox="0 0 22 8" fill="none" aria-hidden="true" style="margin-left: 8px;">
                          <path d="M0 4 L20 4 M14 0 L20 4 L14 8" stroke="currentColor" stroke-width="1.4" fill="none"/>
                        </svg>
                      </a>
                    </div>
                  </div>
                </div>
              </article>
            </li>
          <?php $room_idx++; endwhile; ?>
        </ul>

        <div class="reveal" style="margin-top: 80px; display:flex; justify-content:center;">
          <?php the_posts_pagination(['mid_size' => 1, 'prev_text' => '←', 'next_text' => '→']); ?>
        </div>
      <?php else : ?>
        <p style="text-align:center; color: var(--ink-2, #3d433d);">No rooms have been published yet.</p>
      <?php endif; ?>
    </div>
  </section>

</main>

<style>
  .rooms-archive__header { padding: 160px 0 60px; }
  .rooms-archive__title {
    font-size: clamp(48px, 7vw, 96px);
    max-width: 14ch;
    margin: 0;
    line-height: 1.05;
    font-weight: 500;
  }
  .rooms-archive__title em { font-style: italic; }
  .rooms-archive__lead {
    max-width: 540px;
    margin-top: 28px;
    color: var(--ink-2, #3d433d);
    font-size: 17px;
    line-height: 1.7;
  }

  .rooms-archive__stack {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    gap: 70px;
  }
  .rooms-archive__row {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 70px;
    align-items: center;
  }
  .rooms-archive__row.is-flipped .rooms-archive__media { order: 2; }
  .rooms-archive__row.is-flipped .rooms-archive__body  { order: 1; }
  .rooms-archive__media { position: relative; }
  .rooms-archive__img-el { width: 100%; height: 100%; object-fit: cover; display: block; }

  .rooms-archive__spec-card {
    position: absolute;
    bottom: -20px;
    left: -20px;
    background: var(--paper, #f8f5e9);
    padding: 14px 18px;
    border-radius: 4px;
    border: 1px solid var(--line, #ede9d9);
    box-shadow: 0 18px 40px -20px rgba(0, 0, 0, 0.18);
    display: flex;
    gap: 28px;
    align-items: center;
  }
  .rooms-archive__row.is-flipped .rooms-archive__spec-card { left: auto; right: -20px; }
  .rooms-archive__spec { text-align: center; min-width: 56px; }
  .rooms-archive__spec .display {
    font-size: 26px;
    color: var(--forest, #1f4a3a);
    line-height: 1;
  }
  .rooms-archive__spec-unit {
    font-family: var(--font-mono, 'JetBrains Mono', monospace);
    font-size: 11px;
    color: var(--mute, #7b817b);
    letter-spacing: 0.06em;
    margin-top: 6px;
  }

  .rooms-archive__num {
    font-family: var(--font-mono, 'JetBrains Mono', monospace);
    color: var(--mute, #7b817b);
    font-size: 12px;
    letter-spacing: 0.08em;
  }
  .rooms-archive__name {
    font-size: clamp(36px, 4.4vw, 60px);
    margin-top: 12px;
    max-width: 14ch;
    line-height: 1.05;
  }
  .rooms-archive__name em {
    font-style: italic;
    font-size: 0.7em;
    color: var(--moss, #527a55);
    font-weight: 400;
  }
  .rooms-archive__blurb {
    margin-top: 22px;
    color: var(--ink-2, #3d433d);
    font-size: 16.5px;
    line-height: 1.75;
    max-width: 520px;
  }
  .rooms-archive__chips {
    margin-top: 26px;
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
  }
  .rooms-archive__cta-row {
    margin-top: 36px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    flex-wrap: wrap;
  }
  .rooms-archive__from {
    font-family: var(--font-mono, 'JetBrains Mono', monospace);
    color: var(--mute, #7b817b);
    font-size: 12px;
    letter-spacing: 0.08em;
  }
  .rooms-archive__price {
    font-size: 38px;
    color: var(--forest, #1f4a3a);
    line-height: 1.1;
    margin-top: 4px;
  }
  .rooms-archive__per {
    font-family: var(--font-sans, "Manrope", sans-serif);
    font-size: 12px;
    margin-left: 8px;
    color: var(--mute, #7b817b);
    letter-spacing: 0.18em;
    text-transform: uppercase;
  }
  .rooms-archive__buttons {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
  }

  @media (max-width: 900px) {
    .rooms-archive__header { padding: 120px 0 40px; }
    .rooms-archive__row,
    .rooms-archive__row.is-flipped {
      grid-template-columns: 1fr;
      gap: 56px;
    }
    .rooms-archive__row .rooms-archive__media,
    .rooms-archive__row.is-flipped .rooms-archive__media,
    .rooms-archive__row .rooms-archive__body,
    .rooms-archive__row.is-flipped .rooms-archive__body { order: unset; }
    .rooms-archive__media .ph { height: 360px !important; }
    .rooms-archive__spec-card,
    .rooms-archive__row.is-flipped .rooms-archive__spec-card {
      left: 16px;
      right: auto;
      bottom: -20px;
      gap: 20px;
      padding: 12px 14px;
    }
  }
</style>

<?php get_footer(); ?>
