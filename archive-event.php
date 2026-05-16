<?php get_header(); ?>

<main class="site-main">

  <section class="section greensun-events-archive-hero" style="padding-block: clamp(80px, 14vw, 160px); text-align:center;">
    <div class="shell">
      <div class="eyebrow reveal">What's on</div>
      <h1 class="display reveal" style="font-size: clamp(48px, 7vw, 104px); margin-top: 18px;">
        Gather with <em>us</em>.
      </h1>
      <p class="reveal" style="margin: 22px auto 0; max-width: 56ch; color: var(--ink-2, #3d433d); line-height: 1.75;">
        Long-table dinners, tastings, and quiet evenings on the terrace — open to guests and neighbours alike.
      </p>
    </div>
  </section>

  <section class="section">
    <div class="shell">
      <?php if (have_posts()) : ?>
        <div class="events-archive__grid" style="display:grid; grid-template-columns: repeat(2, 1fr); gap: 32px;">
          <?php while (have_posts()) : the_post();
            $eid       = get_the_ID();
            $start     = function_exists('get_field') ? get_field('event_start', $eid) : '';
            $end       = function_exists('get_field') ? get_field('event_end', $eid) : '';
            $time      = function_exists('get_field') ? get_field('event_time', $eid) : '';
            $location  = function_exists('get_field') ? get_field('event_location', $eid) : '';
            $price     = function_exists('get_field') ? get_field('event_price', $eid) : null;
            $currency  = function_exists('get_field') ? (get_field('event_currency', $eid) ?: 'USD') : 'USD';
            $cta_text  = function_exists('get_field') ? (get_field('event_cta_text', $eid) ?: 'Reserve') : 'Reserve';
            $cta_url   = function_exists('get_field') ? get_field('event_cta_url', $eid) : '';
            if (!$cta_url) $cta_url = get_permalink();
            $thumb     = get_the_post_thumbnail_url($eid, 'large');
            $start_fmt = $start ? date_i18n('M j, Y', strtotime($start)) : '';
            $end_fmt   = $end   ? date_i18n('M j, Y', strtotime($end))   : '';
            $date_line = $start_fmt . ($end_fmt && $end_fmt !== $start_fmt ? ' – ' . $end_fmt : '');
          ?>
            <article class="gs-card reveal" style="background:#fff; border-radius: var(--radius-lg, 14px); overflow:hidden; border:1px solid var(--line, #ede9d9); display:flex; flex-direction:column;">
              <a href="<?php the_permalink(); ?>" class="ph kb" style="aspect-ratio: 16 / 10; display:block;">
                <?php if ($thumb) : ?>
                  <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" style="width:100%; height:100%; object-fit:cover;" />
                <?php endif; ?>
              </a>
              <div style="padding: 32px; display:flex; flex-direction:column; flex:1;">
                <?php if ($date_line || $time || $location) : ?>
                  <div class="eyebrow" style="color: var(--moss, #527a55);">
                    <?php echo esc_html(trim(implode(' · ', array_filter([$date_line, $time, $location])))); ?>
                  </div>
                <?php endif; ?>
                <h2 class="display" style="font-size: 38px; margin: 8px 0 0;">
                  <a href="<?php the_permalink(); ?>" style="color: inherit; text-decoration: none;"><?php the_title(); ?></a>
                </h2>
                <?php if (get_the_excerpt()) : ?>
                  <p style="margin-top: 14px; color: var(--ink-2, #3d433d); line-height: 1.7;"><?php echo esc_html(get_the_excerpt()); ?></p>
                <?php endif; ?>
                <div style="margin-top:auto; padding-top: 26px; display:flex; align-items:center; justify-content:space-between; gap: 16px;">
                  <?php if ($price) : ?>
                    <div>
                      <span style="font-family: var(--font-display, 'Cormorant Garamond', serif); font-size: 26px;"><?php echo esc_html($currency . ' ' . number_format_i18n((float) $price)); ?></span>
                      <span style="font-size: 12px; letter-spacing: 0.14em; text-transform: uppercase; color: var(--ink-2, #3d433d);"> / guest</span>
                    </div>
                  <?php else : ?>
                    <div class="eyebrow" style="color: var(--moss, #527a55);">Complimentary</div>
                  <?php endif; ?>
                  <a href="<?php echo esc_url($cta_url); ?>" class="btn">
                    <span class="ripple"></span>
                    <span><?php echo esc_html($cta_text); ?></span>
                  </a>
                </div>
              </div>
            </article>
          <?php endwhile; ?>
        </div>

        <div class="reveal" style="margin-top: 56px; display:flex; justify-content:center;">
          <?php the_posts_pagination(['mid_size' => 1, 'prev_text' => '←', 'next_text' => '→']); ?>
        </div>
      <?php else : ?>
        <p style="text-align:center; color: var(--ink-2, #3d433d);">No upcoming events at the moment.</p>
      <?php endif; ?>
    </div>
  </section>

</main>

<style>
  @media (max-width: 900px) {
    .events-archive__grid { grid-template-columns: 1fr !important; }
  }
</style>

<?php get_footer(); ?>
