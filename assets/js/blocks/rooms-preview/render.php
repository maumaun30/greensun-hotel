<?php
$eyebrow        = $attributes['eyebrow']       ?? '';
$section_title  = $attributes['sectionTitle']  ?? '';
$subtitle       = $attributes['subtitle']      ?? '';
$cta_text       = $attributes['ctaText']       ?? '';
$cta_url        = $attributes['ctaUrl']        ?? '#';
$featured_ids   = array_values(array_filter(array_map('intval', $attributes['featuredRooms'] ?? [])));
$fallback_count = max(1, min(6, (int) ($attributes['fallbackCount'] ?? 3)));

if (!empty($featured_ids)) {
    $query_args = [
        'post_type'      => 'room',
        'post__in'       => $featured_ids,
        'orderby'        => 'post__in',
        'posts_per_page' => count($featured_ids),
    ];
} else {
    $query_args = [
        'post_type'      => 'room',
        'posts_per_page' => $fallback_count,
        'orderby'        => 'menu_order date',
        'order'          => 'DESC',
    ];
}
$rooms_query = new WP_Query($query_args);
$count_cols  = max(1, min(3, $rooms_query->post_count ?: 1));
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'section greensun-rooms-preview', 'style' => 'background: var(--paper, #f8f5e9);']); ?>>
  <div class="shell">

    <header class="gs-rooms__head">
      <div>
        <?php if ($eyebrow) : ?>
          <div class="eyebrow reveal"><?php echo esc_html($eyebrow); ?></div>
        <?php endif; ?>
        <?php if ($section_title) : ?>
          <h2 class="display reveal reveal--lg gs-rooms__title">
            <?php echo wp_kses_post($section_title); ?>
          </h2>
        <?php endif; ?>
        <?php if ($subtitle) : ?>
          <p class="reveal gs-rooms__subtitle"><?php echo wp_kses_post($subtitle); ?></p>
        <?php endif; ?>
      </div>
      <?php if ($cta_text) : ?>
        <a href="<?php echo esc_url($cta_url); ?>" class="btn btn--ghost reveal">
          <span class="ripple"></span>
          <span><?php echo esc_html($cta_text); ?></span>
          <svg width="14" height="10" viewBox="0 0 22 8" fill="none" aria-hidden="true" style="margin-left: 8px;">
            <path d="M0 4 L20 4 M14 0 L20 4 L14 8" stroke="currentColor" stroke-width="1.4" fill="none"/>
          </svg>
        </a>
      <?php endif; ?>
    </header>

    <?php if ($rooms_query->have_posts()) : ?>
      <div class="gs-rooms__grid" style="--gs-cols: <?php echo esc_attr($count_cols); ?>;">
        <?php while ($rooms_query->have_posts()) : $rooms_query->the_post();
          $room_id  = get_the_ID();
          $price    = function_exists('get_field') ? get_field('price_per_night', $room_id) : null;
          $currency = function_exists('get_field') ? (get_field('currency', $room_id) ?: 'USD') : 'USD';
          $size     = function_exists('get_field') ? get_field('room_size', $room_id) : null;
          $guests   = function_exists('get_field') ? get_field('max_guests', $room_id) : null;
          $beds     = function_exists('get_field') ? get_field('bed_configuration', $room_id) : null;
          $thumb    = get_the_post_thumbnail_url($room_id, 'large');
        ?>
          <a class="gs-room reveal" href="<?php the_permalink(); ?>">
            <div class="gs-room__media ph kb">
              <?php if ($thumb) : ?>
                <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" />
              <?php endif; ?>
              <span class="gs-room__scrim" aria-hidden="true"></span>
              <div class="gs-room__chips">
                <?php if ($size) : ?>
                  <span class="chip"><span class="dot"></span><?php echo esc_html($size); ?></span>
                <?php endif; ?>
                <?php if ($guests) : ?>
                  <span class="chip chip--moss"><span class="dot"></span><?php echo esc_html(sprintf(_n('%d guest', '%d guests', (int)$guests, 'greensun-hotel'), (int)$guests)); ?></span>
                <?php endif; ?>
              </div>
              <span class="gs-room__pill">
                View room
                <svg width="14" height="10" viewBox="0 0 22 8" fill="none" aria-hidden="true">
                  <path d="M0 4 L20 4 M14 0 L20 4 L14 8" stroke="currentColor" stroke-width="1.4" fill="none"/>
                </svg>
              </span>
            </div>
            <div class="gs-room__body">
              <div class="gs-room__left">
                <h3 class="display gs-room__title"><?php the_title(); ?></h3>
                <?php if ($beds || $guests) : ?>
                  <div class="gs-room__meta">
                    <?php echo esc_html(trim(implode(' · ', array_filter([
                      $beds,
                      $guests ? 'Sleeps ' . (int) $guests : '',
                    ])))); ?>
                  </div>
                <?php endif; ?>
              </div>
              <?php if ($price) : ?>
                <div class="gs-room__price">
                  <div class="gs-room__from">from</div>
                  <div class="display gs-room__rate"><?php echo esc_html($currency . ' ' . number_format_i18n((float) $price)); ?></div>
                  <div class="gs-room__per">per night</div>
                </div>
              <?php endif; ?>
            </div>
          </a>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    <?php else : ?>
      <p style="text-align:center; color: var(--ink-2, #3d433d);">No rooms published yet.</p>
    <?php endif; ?>

  </div>
</section>
