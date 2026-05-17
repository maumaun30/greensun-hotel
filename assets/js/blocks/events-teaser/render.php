<?php
$eyebrow        = $attributes['eyebrow']        ?? '';
$section_title  = $attributes['sectionTitle']   ?? '';
$subtitle       = $attributes['subtitle']       ?? '';
$cta_text       = $attributes['ctaText']        ?? '';
$cta_url        = $attributes['ctaUrl']         ?? '/events';
$featured_ids   = array_values(array_filter(array_map('intval', $attributes['featuredEvents'] ?? [])));
$fallback_count = max(1, min(6, (int) ($attributes['fallbackCount'] ?? 4)));

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

$events = [];
if ($events_query->have_posts()) {
    while ($events_query->have_posts()) {
        $events_query->the_post();
        $eid = get_the_ID();
        $events[] = [
            'id'        => $eid,
            'title'     => get_the_title(),
            'permalink' => get_permalink($eid),
            'thumb'     => get_the_post_thumbnail_url($eid, 'full'),
            'excerpt'   => wp_strip_all_tags(get_the_excerpt()),
            'start'     => function_exists('get_field') ? get_field('event_start',    $eid) : '',
            'time'      => function_exists('get_field') ? get_field('event_time',     $eid) : '',
            'location'  => function_exists('get_field') ? get_field('event_location', $eid) : '',
            'capacity'  => function_exists('get_field') ? (int) get_field('event_capacity', $eid) : 0,
        ];
    }
    wp_reset_postdata();
}
$count = count($events);
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'section greensun-events-teaser']); ?>>
  <div class="shell">
    <header class="gs-events__head">
      <?php if ($eyebrow) : ?>
        <div class="eyebrow reveal" style="display:inline-flex; justify-content:center;"><?php echo esc_html($eyebrow); ?></div>
      <?php endif; ?>
      <?php if ($section_title) : ?>
        <h2 class="display reveal gs-events__title"><?php echo wp_kses_post($section_title); ?></h2>
      <?php endif; ?>
      <div class="linedot reveal" aria-hidden="true"><span></span><span></span><span></span><span></span><span></span></div>
      <?php if ($subtitle) : ?>
        <p class="reveal gs-events__subtitle"><?php echo wp_kses_post($subtitle); ?></p>
      <?php endif; ?>
    </header>

    <?php if ($count > 0) : ?>
      <div class="gs-events__split" data-active="0">

        <div class="gs-events__stage">
          <?php foreach ($events as $idx => $e) : ?>
            <a class="gs-events__slide<?php echo $idx === 0 ? ' is-active' : ''; ?>"
               data-index="<?php echo esc_attr($idx); ?>"
               href="<?php echo esc_url($e['permalink']); ?>">
              <?php if ($e['thumb']) : ?>
                <img class="kb" src="<?php echo esc_url($e['thumb']); ?>" alt="<?php echo esc_attr($e['title']); ?>" loading="lazy" />
              <?php endif; ?>
              <span class="gs-events__scrim" aria-hidden="true"></span>
              <div class="gs-events__caption">
                <div class="gs-events__counter">
                  <?php echo esc_html(str_pad((string)($idx + 1), 2, '0', STR_PAD_LEFT)); ?> / <?php echo esc_html(str_pad((string)$count, 2, '0', STR_PAD_LEFT)); ?>
                </div>
                <h3 class="display gs-events__caption-title"><?php echo esc_html($e['title']); ?></h3>
                <div class="gs-events__chips">
                  <?php if ($e['capacity']) : ?>
                    <span class="chip gs-events__chip"><span class="dot"></span><?php echo esc_html(sprintf(_n('%d guest', '%d guests', $e['capacity'], 'greensun-hotel'), $e['capacity'])); ?></span>
                  <?php endif; ?>
                  <?php if ($e['location']) : ?>
                    <span class="chip gs-events__chip"><span class="dot"></span><?php echo esc_html($e['location']); ?></span>
                  <?php endif; ?>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>

        <div class="gs-events__list">
          <div class="gs-events__list-inner">
            <?php foreach ($events as $idx => $e) :
              $start_fmt = $e['start'] ? date_i18n('M j, Y', strtotime($e['start'])) : '';
            ?>
              <button type="button"
                      class="gs-events__pick<?php echo $idx === 0 ? ' is-active' : ''; ?>"
                      data-index="<?php echo esc_attr($idx); ?>"
                      aria-controls="gs-events-slide-<?php echo esc_attr($idx); ?>">
                <div class="gs-events__pick-head">
                  <h4 class="display gs-events__pick-title"><?php echo esc_html($e['title']); ?></h4>
                  <span class="gs-events__pick-num"><?php echo esc_html(str_pad((string)($idx + 1), 2, '0', STR_PAD_LEFT)); ?></span>
                </div>
                <?php if ($e['excerpt'] || $start_fmt) : ?>
                  <p class="gs-events__pick-body">
                    <?php echo esc_html($e['excerpt'] ?: $start_fmt); ?>
                  </p>
                <?php endif; ?>
              </button>
            <?php endforeach; ?>
          </div>

          <?php if ($cta_text) : ?>
            <a class="btn btn--ghost reveal gs-events__cta" href="<?php echo esc_url($cta_url); ?>">
              <span class="ripple"></span>
              <span><?php echo esc_html($cta_text); ?></span>
              <svg width="14" height="10" viewBox="0 0 22 8" fill="none" aria-hidden="true" style="margin-left: 8px;">
                <path d="M0 4 L20 4 M14 0 L20 4 L14 8" stroke="currentColor" stroke-width="1.4" fill="none"/>
              </svg>
            </a>
          <?php endif; ?>
        </div>

      </div>
    <?php else : ?>
      <p style="text-align:center; color: var(--ink-2, #3d433d);">No upcoming events.</p>
    <?php endif; ?>
  </div>
</section>
