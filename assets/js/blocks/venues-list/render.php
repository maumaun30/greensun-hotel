<?php
$featured_ids   = array_values(array_filter(array_map('intval', $attributes['featuredVenues'] ?? [])));
$fallback_count = max(1, min(12, (int) ($attributes['fallbackCount'] ?? 6)));

if (!empty($featured_ids)) {
    $query_args = [
        'post_type'      => 'venue',
        'post__in'       => $featured_ids,
        'orderby'        => 'post__in',
        'posts_per_page' => count($featured_ids),
    ];
} else {
    $query_args = [
        'post_type'      => 'venue',
        'posts_per_page' => $fallback_count,
        'orderby'        => 'menu_order title',
        'order'          => 'ASC',
    ];
}
$venues_query = new WP_Query($query_args);
if (!$venues_query->have_posts()) return;
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'section greensun-venues-list']); ?>>
  <div class="shell">
    <div class="venues-list__stack">
      <?php $idx = 0; while ($venues_query->have_posts()) : $venues_query->the_post();
        $vid       = get_the_ID();
        $flip      = ($idx % 2) === 1;
        $name      = get_the_title();
        $tagline   = function_exists('get_field') ? get_field('venue_tagline', $vid) : '';
        $capacity  = function_exists('get_field') ? get_field('venue_capacity', $vid) : '';
        $area      = function_exists('get_field') ? get_field('venue_area', $vid) : '';
        $location  = function_exists('get_field') ? get_field('venue_location', $vid) : '';
        $layouts   = function_exists('get_field') ? get_field('venue_layouts', $vid) : '';
        $blurb     = wp_strip_all_tags(get_the_excerpt());
        $thumb     = get_the_post_thumbnail_url($vid, 'full');
        $cta_text  = function_exists('get_field') ? (get_field('venue_cta_text', $vid) ?: 'View venue') : 'View venue';

        $eyebrow_parts = array_filter([$tagline, $location]);
        $caps = array_filter(array_map('trim', explode(',', (string) $layouts)));
      ?>
        <article class="venues-list__row reveal reveal--lg<?php echo $flip ? ' is-flipped' : ''; ?>">
          <div class="venues-list__media">
            <a href="<?php the_permalink(); ?>" class="ph kb" style="display:block; height: 540px;">
              <?php if ($thumb) : ?>
                <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($name); ?>" style="width:100%; height:100%; object-fit:cover;" loading="lazy" />
              <?php endif; ?>
            </a>
            <div class="venues-list__badge"><?php echo esc_html(str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT)); ?></div>
          </div>
          <div class="venues-list__body">
            <?php if (!empty($eyebrow_parts)) : ?>
              <div class="eyebrow"><?php echo esc_html(implode(' · ', $eyebrow_parts)); ?></div>
            <?php endif; ?>
            <h2 class="display venues-list__title">
              <a href="<?php the_permalink(); ?>" style="color: inherit; text-decoration: none;"><?php echo esc_html($name); ?></a>
            </h2>
            <?php if ($blurb) : ?>
              <p class="venues-list__blurb"><?php echo esc_html($blurb); ?></p>
            <?php endif; ?>
            <?php if ($capacity || $area || !empty($caps)) :
              $items = [];
              if ($capacity) $items[] = ['Capacity', $capacity];
              if ($area)     $items[] = ['Floor area', $area];
              foreach ($caps as $c) {
                if (count($items) >= 4) break;
                $items[] = [$c, ''];
              }
              $col_count = max(1, min(4, count($items)));
            ?>
              <dl class="venues-list__caps" style="grid-template-columns: repeat(<?php echo esc_attr($col_count); ?>, 1fr);">
                <?php foreach ($items as $entry) : list($k, $v) = $entry; ?>
                  <div>
                    <dt><?php echo esc_html($k); ?></dt>
                    <?php if ($v !== '') : ?>
                      <dd class="display"><?php echo esc_html($v); ?></dd>
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              </dl>
            <?php endif; ?>
            <div style="margin-top: 32px; display:flex; gap: 14px; flex-wrap: wrap;">
              <a href="<?php the_permalink(); ?>" class="btn btn--ghost">
                <span class="ripple"></span>
                <span><?php echo esc_html($cta_text); ?></span>
              </a>
            </div>
          </div>
        </article>
      <?php $idx++; endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>
