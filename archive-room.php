<?php get_header(); ?>

<main class="site-main">

  <section class="gs-page-hero" style="min-height: 60vh;">
    <div class="gs-page-hero__media" style="background: linear-gradient(160deg, #1f4a3a, #0f2018);"></div>
    <div class="gs-page-hero__scrim" style="background: linear-gradient(to bottom, rgba(13,42,32,.4), rgba(13,42,32,.85));"></div>
    <div class="shell gs-page-hero__content">
      <div class="eyebrow reveal" style="color: var(--sun, #e8c46a);">Rooms &amp; Suites</div>
      <h1 class="display reveal reveal--lg" style="font-size: clamp(48px, 7vw, 104px); margin-top: 22px; font-weight: 500;">
        Find your <em>quiet</em>.
      </h1>
      <p class="reveal" style="margin: 22px 0 0; max-width: 56ch; color: rgba(255,255,255,.82); line-height: 1.75;">
        Each room is a small theatre of light and stone — chosen for the view it frames and the silence it keeps.
      </p>
    </div>
  </section>

  <section class="section">
    <div class="shell">
      <?php if (have_posts()) : ?>
        <div class="rooms-archive__stack" style="display:grid; gap: 80px;">
          <?php $room_idx = 0; while (have_posts()) : the_post();
            $room_id  = get_the_ID();
            $price    = function_exists('get_field') ? get_field('price_per_night', $room_id) : null;
            $currency = function_exists('get_field') ? (get_field('currency', $room_id) ?: 'USD') : 'USD';
            $size     = function_exists('get_field') ? get_field('room_size', $room_id) : null;
            $guests   = function_exists('get_field') ? get_field('max_guests', $room_id) : null;
            $beds     = function_exists('get_field') ? get_field('bed_configuration', $room_id) : null;
            $tagline  = function_exists('get_field') ? get_field('tagline', $room_id) : null;
            $thumb    = get_the_post_thumbnail_url($room_id, 'full');
            $flip     = ($room_idx % 2) === 1;
          ?>
            <article class="rooms-archive__row reveal reveal--lg<?php echo $flip ? ' is-flipped' : ''; ?>">
              <div class="rooms-archive__media">
                <div class="ph kb" style="height: 520px; border-radius: 4px;">
                  <?php if ($thumb) : ?>
                    <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" style="width:100%; height:100%; object-fit:cover;" />
                  <?php endif; ?>
                </div>
                <div class="rooms-archive__spec-card">
                  <?php if ($size) : ?>
                    <div class="rooms-archive__spec">
                      <div class="display"><?php echo esc_html($size); ?></div>
                      <div class="rooms-archive__spec-unit">size</div>
                    </div>
                  <?php endif; ?>
                  <?php if ($guests) : ?>
                    <div class="rooms-archive__spec">
                      <div class="display"><?php echo esc_html((string) $guests); ?></div>
                      <div class="rooms-archive__spec-unit"><?php echo esc_html((int) $guests === 1 ? 'guest' : 'guests'); ?></div>
                    </div>
                  <?php endif; ?>
                  <?php if ($beds) : ?>
                    <div class="rooms-archive__spec">
                      <div class="display" style="font-size: 18px;"><?php echo esc_html($beds); ?></div>
                      <div class="rooms-archive__spec-unit">bed</div>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
              <div class="rooms-archive__body">
                <div style="font-family: var(--font-mono, 'JetBrains Mono', monospace); color: var(--mute, #7b817b);">
                  <?php echo esc_html(sprintf('%02d', $room_idx + 1)); ?><?php echo $beds ? ' — ' . esc_html($beds) : ''; ?>
                </div>
                <h2 class="display" style="font-size: clamp(36px, 4.4vw, 60px); margin-top: 12px; max-width: 14ch;">
                  <a href="<?php the_permalink(); ?>" style="color: inherit; text-decoration: none;"><?php the_title(); ?></a>
                  <?php if ($tagline) : ?>
                    <br><em style="font-size: 0.7em; color: var(--moss, #527a55);"><?php echo esc_html($tagline); ?></em>
                  <?php endif; ?>
                </h2>
                <?php if (get_the_excerpt()) : ?>
                  <p style="margin-top: 22px; color: var(--ink-2, #3d433d); font-size: 16.5px; line-height: 1.75; max-width: 520px;">
                    <?php echo esc_html(get_the_excerpt()); ?>
                  </p>
                <?php endif; ?>
                <div class="rooms-archive__cta-row">
                  <?php if ($price) : ?>
                    <div>
                      <div style="font-family: var(--font-mono, 'JetBrains Mono', monospace); color: var(--mute, #7b817b); font-size: 12px;">from</div>
                      <div class="display" style="font-size: 38px; color: var(--forest, #1f4a3a);">
                        <?php echo esc_html($currency . ' ' . number_format_i18n((float) $price)); ?>
                        <span style="font-family: var(--font-sans); font-size: 12px; margin-left: 8px; color: var(--mute, #7b817b); letter-spacing: 0.18em;">/ NIGHT</span>
                      </div>
                    </div>
                  <?php endif; ?>
                  <div style="display:flex; gap: 12px; flex-wrap: wrap;">
                    <a href="<?php the_permalink(); ?>" class="btn btn--ghost">
                      <span class="ripple"></span>
                      <span>View details</span>
                    </a>
                    <a href="<?php echo esc_url(add_query_arg('room_type', $room_id, home_url('/booking'))); ?>" class="btn btn--sun">
                      <span class="ripple"></span>
                      <span>Book this room</span>
                    </a>
                  </div>
                </div>
              </div>
            </article>
          <?php $room_idx++; endwhile; ?>
        </div>

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
  .rooms-archive__row {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 70px;
    align-items: center;
  }
  .rooms-archive__row.is-flipped .rooms-archive__media { order: 2; }
  .rooms-archive__row.is-flipped .rooms-archive__body  { order: 1; }
  .rooms-archive__media { position: relative; }
  .rooms-archive__media .ph { overflow: hidden; }
  .rooms-archive__spec-card {
    position: absolute;
    bottom: -20px;
    left: -20px;
    background: var(--paper, #f8f5e9);
    padding: 14px 18px;
    border-radius: 4px;
    border: 1px solid var(--line, #ede9d9);
    box-shadow: 0 18px 40px -20px rgba(0,0,0,.18);
    display: flex;
    gap: 28px;
    align-items: center;
  }
  .rooms-archive__row.is-flipped .rooms-archive__spec-card { left: auto; right: -20px; }
  .rooms-archive__spec { text-align: center; }
  .rooms-archive__spec .display { font-size: 24px; color: var(--forest, #1f4a3a); line-height: 1; }
  .rooms-archive__spec-unit { font-family: var(--font-mono, 'JetBrains Mono', monospace); font-size: 11px; color: var(--mute, #7b817b); margin-top: 4px; }
  .rooms-archive__cta-row {
    margin-top: 36px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    flex-wrap: wrap;
  }

  @media (max-width: 900px) {
    .rooms-archive__row,
    .rooms-archive__row.is-flipped {
      grid-template-columns: 1fr;
      gap: 40px;
    }
    .rooms-archive__row .rooms-archive__media,
    .rooms-archive__row.is-flipped .rooms-archive__media,
    .rooms-archive__row .rooms-archive__body,
    .rooms-archive__row.is-flipped .rooms-archive__body {
      order: unset;
    }
    .rooms-archive__media .ph { height: 360px !important; }
    .rooms-archive__spec-card,
    .rooms-archive__row.is-flipped .rooms-archive__spec-card {
      left: 16px;
      right: auto;
      bottom: -20px;
    }
  }
</style>

<?php get_footer(); ?>
