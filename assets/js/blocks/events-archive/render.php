<?php
$eyebrow      = $attributes['eyebrow']        ?? '';
$title        = $attributes['title']          ?? '';
$lead         = $attributes['lead']           ?? '';
$per_page     = max(1, min(24, (int) ($attributes['perPage'] ?? 12)));
$inq_title    = $attributes['inquiryTitle']   ?? '';
$inq_text     = $attributes['inquiryText']    ?? '';
$inq_cta_text = $attributes['inquiryCtaText'] ?? '';
$inq_cta_url  = $attributes['inquiryCtaUrl']  ?: home_url('/contact');

$paged  = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
$events = new WP_Query([
    'post_type'      => 'event',
    'post_status'    => 'publish',
    'posts_per_page' => $per_page,
    'orderby'        => 'menu_order date',
    'order'          => 'ASC',
    'paged'          => $paged,
]);
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'events-archive-block']); ?>>

  <div class="events-archive__header">
    <div class="shell">
      <?php if ($eyebrow) : ?>
        <div class="eyebrow reveal" style="margin-bottom: 22px;"><?php echo esc_html($eyebrow); ?></div>
      <?php endif; ?>
      <?php if ($title) : ?>
        <h1 class="display reveal reveal--lg events-archive__title"><?php echo wp_kses_post($title); ?></h1>
      <?php endif; ?>
      <?php if ($lead) : ?>
        <p class="reveal events-archive__lead"><?php echo wp_kses_post($lead); ?></p>
      <?php endif; ?>
    </div>
  </div>

  <div class="shell" style="padding: 40px 0 120px;">
    <?php if ($events->have_posts()) : ?>
      <div class="events-archive__grid">
        <?php while ($events->have_posts()) : $events->the_post();
          $eid      = get_the_ID();
          $start    = function_exists('get_field') ? get_field('event_start', $eid) : '';
          $end      = function_exists('get_field') ? get_field('event_end', $eid) : '';
          $time     = function_exists('get_field') ? get_field('event_time', $eid) : '';
          $location = function_exists('get_field') ? get_field('event_location', $eid) : '';
          $capacity = function_exists('get_field') ? (int) get_field('event_capacity', $eid) : 0;
          $price    = function_exists('get_field') ? get_field('event_price', $eid) : null;
          $currency = function_exists('get_field') ? (get_field('event_currency', $eid) ?: 'USD') : 'USD';
          $cta_text = function_exists('get_field') ? (get_field('event_cta_text', $eid) ?: 'Reserve') : 'Reserve';
          $cta_url  = function_exists('get_field') ? get_field('event_cta_url', $eid) : '';
          if (!$cta_url) $cta_url = get_permalink();
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

      <?php if ($events->max_num_pages > 1) : ?>
        <div class="reveal" style="margin-top: 64px; display:flex; justify-content:center;">
          <?php echo paginate_links([
            'total'     => $events->max_num_pages,
            'current'   => $paged,
            'mid_size'  => 1,
            'prev_text' => '←',
            'next_text' => '→',
          ]); ?>
        </div>
      <?php endif; ?>
    <?php else : ?>
      <p style="text-align:center; color: var(--ink-2, #3d433d);">No upcoming events at the moment. Please check back soon.</p>
    <?php endif; wp_reset_postdata(); ?>
  </div>

  <?php if ($inq_title || $inq_text) : ?>
    <div class="events-archive__inquiry" style="background: var(--forest, #1f4a3a); color: var(--ivory, #f7f6f0); padding: 90px 0;">
      <div class="shell events-archive__inquiry-grid" style="display:grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items:center;">
        <?php if ($inq_title) : ?>
          <h2 class="display reveal reveal--lg" style="font-size: clamp(36px, 5vw, 60px); color: var(--sun, #e8c46a); max-width: 14ch;"><?php echo wp_kses_post($inq_title); ?></h2>
        <?php endif; ?>
        <div>
          <?php if ($inq_text) : ?>
            <p class="reveal" style="font-size: 17px; line-height: 1.75; color: rgba(255,255,255,.78); max-width: 480px;"><?php echo wp_kses_post($inq_text); ?></p>
          <?php endif; ?>
          <?php if ($inq_cta_text) : ?>
            <div class="reveal" style="margin-top: 28px;">
              <a href="<?php echo esc_url($inq_cta_url); ?>" class="btn btn--sun btn--lg">
                <span class="ripple"></span>
                <span><?php echo esc_html($inq_cta_text); ?></span>
                <svg width="14" height="10" viewBox="0 0 22 8" fill="none" aria-hidden="true" style="margin-left: 8px;">
                  <path d="M0 4 L20 4 M14 0 L20 4 L14 8" stroke="currentColor" stroke-width="1.4" fill="none"/>
                </svg>
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>

</section>
