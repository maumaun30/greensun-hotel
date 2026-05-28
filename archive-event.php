<?php get_header(); ?>

<main id="site-main" class="site-main events-archive" role="main">

  <?php if (!greensun_render_archive_blocks('events_archive_page')) : // else: designed fallback below ?>

  <section class="events-archive__header">
    <div class="shell">
      <div class="eyebrow reveal" style="margin-bottom: 22px;">What's on</div>
      <h1 class="display reveal reveal--lg events-archive__title">
        Gather with us <em>this season.</em>
      </h1>
      <p class="reveal events-archive__lead">
        Long-table dinners, tastings, and quiet evenings on the terrace — open to guests and neighbours alike.
      </p>
    </div>
  </section>

  <section style="padding: 40px 0 120px;">
    <div class="shell">
      <?php if (have_posts()) : ?>
        <div class="events-archive__grid">
          <?php while (have_posts()) : the_post();
            $eid       = get_the_ID();
            $start     = function_exists('get_field') ? get_field('event_start', $eid) : '';
            $end       = function_exists('get_field') ? get_field('event_end', $eid) : '';
            $time      = function_exists('get_field') ? get_field('event_time', $eid) : '';
            $location  = function_exists('get_field') ? get_field('event_location', $eid) : '';
            $capacity  = function_exists('get_field') ? (int) get_field('event_capacity', $eid) : 0;
            $price     = function_exists('get_field') ? get_field('event_price', $eid) : null;
            $currency  = function_exists('get_field') ? (get_field('event_currency', $eid) ?: 'USD') : 'USD';
            $cta_text  = function_exists('get_field') ? (get_field('event_cta_text', $eid) ?: 'Reserve') : 'Reserve';
            $cta_url   = function_exists('get_field') ? get_field('event_cta_url', $eid) : '';
            if (!$cta_url) $cta_url = get_permalink();
            $thumb     = get_the_post_thumbnail_url($eid, 'large');
            $start_fmt = $start ? date_i18n('M j, Y', strtotime($start)) : '';
            $end_fmt   = $end   ? date_i18n('M j, Y', strtotime($end))   : '';
            $date_line = $start_fmt && $end_fmt && $start_fmt !== $end_fmt
              ? date_i18n('M j', strtotime($start)) . ' – ' . $end_fmt
              : $start_fmt;
          ?>
            <article class="events-archive__card reveal">
              <a href="<?php the_permalink(); ?>" class="ph kb events-archive__media">
                <?php echo greensun_post_thumbnail_html($eid, 'large', '(max-width: 900px) 100vw, 50vw'); ?>
                <span class="events-archive__scrim" aria-hidden="true"></span>
                <div class="events-archive__chips">
                  <?php if ($date_line) : ?>
                    <span class="chip"><span class="dot"></span><?php echo esc_html($date_line); ?></span>
                  <?php endif; ?>
                  <?php if ($capacity) : ?>
                    <span class="chip chip--moss"><span class="dot"></span><?php echo esc_html(sprintf(_n('%d seat', '%d seats', $capacity, 'greensun-hotel'), $capacity)); ?></span>
                  <?php endif; ?>
                </div>
              </a>
              <div class="events-archive__body">
                <?php if ($time || $location) : ?>
                  <div class="eyebrow events-archive__meta">
                    <?php echo esc_html(trim(implode(' · ', array_filter([$time, $location])))); ?>
                  </div>
                <?php endif; ?>
                <h2 class="display events-archive__name">
                  <a href="<?php the_permalink(); ?>" style="color: inherit; text-decoration: none;"><?php the_title(); ?></a>
                </h2>
                <?php if (get_the_excerpt()) : ?>
                  <p class="events-archive__blurb"><?php echo esc_html(get_the_excerpt()); ?></p>
                <?php endif; ?>
                <div class="events-archive__row">
                  <div>
                    <?php if ($price) : ?>
                      <div class="events-archive__from">From</div>
                      <div class="display events-archive__price">
                        <?php echo esc_html($currency . ' ' . number_format_i18n((float) $price)); ?>
                        <span class="events-archive__per">/ guest</span>
                      </div>
                    <?php else : ?>
                      <div class="events-archive__from">Entry</div>
                      <div class="display events-archive__price" style="font-size: 28px;">Complimentary</div>
                    <?php endif; ?>
                  </div>
                  <a href="<?php echo esc_url($cta_url); ?>" class="btn btn--sun">
                    <span class="ripple"></span>
                    <span><?php echo esc_html($cta_text); ?></span>
                    <svg width="14" height="10" viewBox="0 0 22 8" fill="none" aria-hidden="true" style="margin-left: 8px;">
                      <path d="M0 4 L20 4 M14 0 L20 4 L14 8" stroke="currentColor" stroke-width="1.4" fill="none"/>
                    </svg>
                  </a>
                </div>
              </div>
            </article>
          <?php endwhile; ?>
        </div>

        <div class="reveal" style="margin-top: 64px; display:flex; justify-content:center;">
          <?php the_posts_pagination(['mid_size' => 1, 'prev_text' => '←', 'next_text' => '→']); ?>
        </div>
      <?php else : ?>
        <p style="text-align:center; color: var(--ink-2, #3d433d);">No upcoming events at the moment. Please check back soon.</p>
      <?php endif; ?>
    </div>
  </section>

  <section class="events-archive__inquiry" style="background: var(--forest, #1f4a3a); color: var(--ivory, #f7f6f0); padding: 90px 0;">
    <div class="shell events-archive__inquiry-grid" style="display:grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items:center;">
      <h2 class="display reveal reveal--lg" style="font-size: clamp(36px, 5vw, 60px); color: var(--sun, #e8c46a); max-width: 14ch;">
        Want a date all <em>to yourselves?</em>
      </h2>
      <div>
        <p class="reveal" style="font-size: 17px; line-height: 1.75; color: rgba(255,255,255,.78); max-width: 480px;">
          We host private dinners, launches, and reunions in any of our venues. Tell us about your evening and we'll send back options within one business day.
        </p>
        <div class="reveal" style="margin-top: 28px;">
          <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn--sun btn--lg">
            <span class="ripple"></span>
            <span>Plan a private event</span>
            <svg width="14" height="10" viewBox="0 0 22 8" fill="none" aria-hidden="true" style="margin-left: 8px;">
              <path d="M0 4 L20 4 M14 0 L20 4 L14 8" stroke="currentColor" stroke-width="1.4" fill="none"/>
            </svg>
          </a>
        </div>
      </div>
    </div>
  </section>

  <?php endif; // greensun_render_archive_blocks ?>

</main>

<style>
  .events-archive__header { padding: 160px 0 60px; }
  .events-archive__title {
    font-size: clamp(48px, 7vw, 96px);
    max-width: 14ch;
    margin: 0;
    line-height: 1.05;
    font-weight: 500;
  }
  .events-archive__title em { font-style: italic; }
  .events-archive__lead {
    max-width: 540px;
    margin-top: 28px;
    color: var(--ink-2, #3d433d);
    font-size: 17px;
    line-height: 1.7;
  }

  .events-archive__grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 32px;
  }
  .events-archive__card {
    background: #fff;
    border-radius: 4px;
    overflow: hidden;
    border: 1px solid var(--line, #ede9d9);
    display: flex;
    flex-direction: column;
    transition: transform 380ms cubic-bezier(.16,1,.3,1), box-shadow 380ms cubic-bezier(.16,1,.3,1);
  }
  .events-archive__card:hover {
    transform: translateY(-4px);
    box-shadow: 0 24px 40px -28px rgba(0,0,0,0.2);
  }
  .events-archive__media {
    position: relative;
    aspect-ratio: 16 / 10;
    overflow: hidden;
    display: block;
    background: var(--bone, #ede9d9);
  }
  .events-archive__media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 700ms cubic-bezier(.16,1,.3,1);
  }
  .events-archive__card:hover .events-archive__media img { transform: scale(1.04); }
  .events-archive__scrim {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(13,42,32,.55), transparent 55%);
    pointer-events: none;
  }
  .events-archive__chips {
    position: absolute;
    top: 16px;
    left: 16px;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
  }
  .events-archive__chips .chip { background: rgba(255, 255, 255, 0.9); }

  .events-archive__body {
    padding: 28px;
    display: flex;
    flex-direction: column;
    flex: 1;
  }
  .events-archive__meta {
    color: var(--moss, #527a55);
    margin-bottom: 10px;
  }
  .events-archive__name {
    font-size: 32px;
    line-height: 1.1;
    margin: 0;
  }
  .events-archive__blurb {
    margin-top: 14px;
    color: var(--ink-2, #3d433d);
    line-height: 1.7;
    font-size: 15.5px;
  }
  .events-archive__row {
    margin-top: auto;
    padding-top: 24px;
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
  }
  .events-archive__from {
    font-family: var(--font-mono, 'JetBrains Mono', monospace);
    color: var(--mute, #7b817b);
    font-size: 11px;
    letter-spacing: 0.08em;
  }
  .events-archive__price {
    font-size: 32px;
    color: var(--forest, #1f4a3a);
    line-height: 1.1;
    margin-top: 2px;
  }
  .events-archive__per {
    font-family: var(--font-sans, "Manrope", sans-serif);
    font-size: 11px;
    margin-left: 6px;
    color: var(--mute, #7b817b);
    letter-spacing: 0.16em;
    text-transform: uppercase;
  }

  @media (max-width: 900px) {
    .events-archive__header { padding: 120px 0 40px; }
    .events-archive__grid { grid-template-columns: 1fr; }
    .events-archive__inquiry-grid { grid-template-columns: 1fr !important; gap: 32px !important; }
  }
</style>

<?php get_footer(); ?>
