<?php
$eyebrow        = $attributes['eyebrow']        ?? '';
$section_title  = $attributes['sectionTitle']   ?? '';
$subtitle       = $attributes['subtitle']       ?? '';
$cta_text       = $attributes['ctaText']        ?? '';
$cta_url        = $attributes['ctaUrl']         ?? '/events';
$featured_ids   = array_values(array_filter(array_map('intval', $attributes['featuredEvents'] ?? [])));
$fallback_count = max(1, min(6, (int) ($attributes['fallbackCount'] ?? 3)));

if (!empty($featured_ids)) {
    $query_args = [
        'post_type'      => 'event',
        'post__in'       => $featured_ids,
        'orderby'        => 'post__in',
        'posts_per_page' => count($featured_ids),
    ];
} else {
    $query_args = [
        'post_type'      => 'event',
        'posts_per_page' => $fallback_count,
        'meta_key'       => 'event_start',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'meta_query'     => [
            'relation' => 'OR',
            [
                'key'     => 'event_start',
                'value'   => current_time('Y-m-d'),
                'compare' => '>=',
                'type'    => 'DATE',
            ],
            [
                'key'     => 'event_start',
                'compare' => 'NOT EXISTS',
            ],
        ],
    ];
}
$events_query = new WP_Query($query_args);
$count_cols   = max(1, min(3, $events_query->post_count ?: 1));
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'section greensun-events-teaser']); ?>>
  <div class="shell">
    <header class="events-teaser__head" style="display:grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items:end; margin-bottom: 64px;">
      <div>
        <?php if ($eyebrow) : ?>
          <div class="eyebrow reveal"><?php echo esc_html($eyebrow); ?></div>
        <?php endif; ?>
        <?php if ($section_title) : ?>
          <h2 class="display reveal" style="font-size: clamp(36px, 5vw, 72px); margin-top: 14px; max-width: 14ch;">
            <?php echo wp_kses_post($section_title); ?>
          </h2>
        <?php endif; ?>
      </div>
      <?php if ($subtitle) : ?>
        <p class="reveal" style="color: var(--ink-2, #3d433d); line-height: 1.75; max-width: 52ch;">
          <?php echo wp_kses_post($subtitle); ?>
        </p>
      <?php endif; ?>
    </header>

    <?php if ($events_query->have_posts()) : ?>
      <div class="events-teaser__grid" style="display:grid; grid-template-columns: repeat(<?php echo esc_attr($count_cols); ?>, 1fr); gap: 28px;">
        <?php while ($events_query->have_posts()) : $events_query->the_post();
          $eid       = get_the_ID();
          $start     = function_exists('get_field') ? get_field('event_start', $eid) : '';
          $time      = function_exists('get_field') ? get_field('event_time', $eid) : '';
          $location  = function_exists('get_field') ? get_field('event_location', $eid) : '';
          $cta_t     = function_exists('get_field') ? (get_field('event_cta_text', $eid) ?: 'Reserve') : 'Reserve';
          $cta_u     = function_exists('get_field') ? get_field('event_cta_url', $eid) : '';
          if (!$cta_u) $cta_u = get_permalink();
          $thumb     = get_the_post_thumbnail_url($eid, 'large');
          $start_fmt = $start ? date_i18n('M j, Y', strtotime($start)) : '';
        ?>
          <article class="gs-card reveal" style="background:#fff; border-radius: var(--radius-lg, 14px); overflow:hidden; border:1px solid var(--line, #ede9d9); display:flex; flex-direction:column;">
            <a href="<?php the_permalink(); ?>" class="ph kb" style="aspect-ratio: 4 / 3; display:block;">
              <?php if ($thumb) : ?>
                <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" style="width:100%; height:100%; object-fit:cover;" />
              <?php endif; ?>
            </a>
            <div style="padding: 28px; display:flex; flex-direction:column; flex:1;">
              <?php if ($start_fmt || $time || $location) : ?>
                <div class="eyebrow" style="color: var(--moss, #527a55);">
                  <?php echo esc_html(trim(implode(' · ', array_filter([$start_fmt, $time, $location])))); ?>
                </div>
              <?php endif; ?>
              <h3 class="display" style="font-size: 30px; margin: 8px 0 0;">
                <a href="<?php the_permalink(); ?>" style="color: inherit; text-decoration: none;"><?php the_title(); ?></a>
              </h3>
              <?php if (get_the_excerpt()) : ?>
                <p style="margin-top: 12px; color: var(--ink-2, #3d433d); line-height: 1.6;"><?php echo esc_html(get_the_excerpt()); ?></p>
              <?php endif; ?>
              <a href="<?php echo esc_url($cta_u); ?>" class="linedot" style="margin-top:auto; padding-top: 22px; display:inline-flex; align-items:center; gap: 10px; font-size: 12px; letter-spacing: 0.18em; text-transform: uppercase; color: var(--forest, #1f4a3a); text-decoration: none;">
                <?php echo esc_html($cta_t); ?> <span aria-hidden="true">→</span>
              </a>
            </div>
          </article>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    <?php else : ?>
      <p style="text-align:center; color: var(--ink-2, #3d433d);">No upcoming events.</p>
    <?php endif; ?>

    <?php if ($cta_text) : ?>
      <div class="btn-row reveal" style="margin-top: 48px; text-align:center;">
        <a href="<?php echo esc_url($cta_url); ?>" class="btn">
          <span class="ripple"></span>
          <span><?php echo esc_html($cta_text); ?></span>
        </a>
      </div>
    <?php endif; ?>
  </div>
</section>
