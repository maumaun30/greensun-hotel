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
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];
}
$rooms_query = new WP_Query($query_args);
$count_cols  = max(1, min(3, $rooms_query->post_count ?: 1));
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'section greensun-rooms-preview']); ?>>
  <div class="shell">
    <header style="text-align:center; max-width: 760px; margin: 0 auto 64px;">
      <?php if ($eyebrow) : ?>
        <div class="eyebrow reveal"><?php echo esc_html($eyebrow); ?></div>
      <?php endif; ?>
      <?php if ($section_title) : ?>
        <h2 class="display reveal" style="font-size: clamp(36px, 5vw, 72px); margin-top: 14px;">
          <?php echo wp_kses_post($section_title); ?>
        </h2>
      <?php endif; ?>
      <?php if ($subtitle) : ?>
        <p class="reveal" style="margin-top: 18px; color: var(--ink-2, #3d433d); line-height: 1.7;">
          <?php echo wp_kses_post($subtitle); ?>
        </p>
      <?php endif; ?>
    </header>

    <?php if ($rooms_query->have_posts()) : ?>
      <div class="rooms-preview__grid" style="display:grid; grid-template-columns: repeat(<?php echo esc_attr($count_cols); ?>, 1fr); gap: 28px;">
        <?php while ($rooms_query->have_posts()) : $rooms_query->the_post();
          $room_id  = get_the_ID();
          $price    = function_exists('get_field') ? get_field('price_per_night', $room_id) : null;
          $currency = function_exists('get_field') ? (get_field('currency', $room_id) ?: 'USD') : 'USD';
          $size     = function_exists('get_field') ? get_field('room_size', $room_id) : null;
          $guests   = function_exists('get_field') ? get_field('max_guests', $room_id) : null;
          $beds     = function_exists('get_field') ? get_field('bed_configuration', $room_id) : null;
          $tagline  = function_exists('get_field') ? get_field('tagline', $room_id) : null;
          $thumb    = get_the_post_thumbnail_url($room_id, 'large');
        ?>
          <article class="gs-card reveal" style="background:#fff; border-radius: var(--radius-lg, 14px); overflow:hidden; border:1px solid var(--line, #ede9d9); display:flex; flex-direction:column;">
            <a href="<?php the_permalink(); ?>" class="ph kb" style="aspect-ratio: 4 / 3; display:block;">
              <?php if ($thumb) : ?>
                <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" style="width:100%; height:100%; object-fit:cover;" />
              <?php endif; ?>
            </a>
            <div style="padding: 28px; display:flex; flex-direction:column; flex:1;">
              <?php if ($tagline) : ?>
                <div class="eyebrow" style="color: var(--moss, #527a55);"><?php echo esc_html($tagline); ?></div>
              <?php endif; ?>
              <h3 class="display" style="font-size: 32px; margin: 8px 0 0;">
                <a href="<?php the_permalink(); ?>" style="color: inherit; text-decoration: none;"><?php the_title(); ?></a>
              </h3>
              <?php if ($size || $guests || $beds) : ?>
                <ul style="list-style:none; padding:0; margin: 14px 0 0; display:flex; flex-wrap:wrap; gap: 14px; font-size: 13px; color: var(--ink-2, #3d433d);">
                  <?php if ($size)   : ?><li><?php echo esc_html($size); ?></li><?php endif; ?>
                  <?php if ($guests) : ?><li><?php echo esc_html(sprintf(_n('%d guest', '%d guests', (int)$guests, 'greensun-hotel'), (int)$guests)); ?></li><?php endif; ?>
                  <?php if ($beds)   : ?><li><?php echo esc_html($beds); ?></li><?php endif; ?>
                </ul>
              <?php endif; ?>
              <div style="margin-top:auto; padding-top: 22px; display:flex; align-items:center; justify-content:space-between; gap: 16px;">
                <?php if ($price) : ?>
                  <div>
                    <span style="font-family: var(--font-display, 'Cormorant Garamond', serif); font-size: 28px;"><?php echo esc_html($currency . ' ' . number_format_i18n((float) $price)); ?></span>
                    <span style="font-size: 12px; letter-spacing: 0.14em; text-transform: uppercase; color: var(--ink-2, #3d433d);"> / night</span>
                  </div>
                <?php endif; ?>
                <a href="<?php the_permalink(); ?>" class="btn btn--ghost">
                  <span class="ripple"></span>
                  <span>View room</span>
                </a>
              </div>
            </div>
          </article>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    <?php else : ?>
      <p style="text-align:center; color: var(--ink-2, #3d433d);">No rooms published yet.</p>
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
