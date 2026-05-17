<?php
$eyebrow       = $attributes['eyebrow']       ?? '';
$section_title = $attributes['sectionTitle']  ?? '';
$show_filters  = !empty($attributes['showFilters']);
$categories    = $attributes['categories']    ?? [];
$images        = $attributes['images']        ?? [];

// Build the filter list from configured categories ∪ image categories.
$image_cats = array_values(array_unique(array_filter(array_map(function ($im) {
    return $im['category'] ?? '';
}, $images))));
$filter_cats = array_values(array_unique(array_filter(array_merge((array) $categories, $image_cats))));
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'greensun-gallery-grid']); ?>>
  <div class="shell">
    <header class="gs-gallery__head">
      <?php if ($eyebrow) : ?>
        <div class="eyebrow reveal" style="justify-content:center; display:inline-flex;"><?php echo esc_html($eyebrow); ?></div>
      <?php endif; ?>
      <?php if ($section_title) : ?>
        <h2 class="display reveal gs-gallery__title">
          <?php echo wp_kses_post($section_title); ?>
        </h2>
      <?php endif; ?>
    </header>

    <?php if ($show_filters && !empty($filter_cats)) : ?>
      <div class="gs-gallery__filters reveal" role="tablist" aria-label="Filter gallery">
        <button type="button" class="chip gs-gallery__chip is-active" data-filter="all" role="tab" aria-selected="true">
          <span class="dot"></span>All
        </button>
        <?php foreach ($filter_cats as $cat) : ?>
          <button type="button" class="chip gs-gallery__chip" data-filter="<?php echo esc_attr(sanitize_title($cat)); ?>" role="tab" aria-selected="false">
            <span class="dot"></span><?php echo esc_html($cat); ?>
          </button>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($images)) : ?>
      <div class="gs-gallery__grid">
        <?php foreach ($images as $idx => $im) :
          $url      = $im['url']      ?? '';
          $full     = $im['full']     ?? $url;
          $alt      = $im['alt']      ?? '';
          $caption  = $im['caption']  ?? '';
          $category = $im['category'] ?? '';
          $col_span = max(1, min(2, (int) ($im['colSpan'] ?? 1)));
          $row_span = max(1, min(2, (int) ($im['rowSpan'] ?? 1)));
          if (!$url) continue;
          $delay = ($idx % 4) * 60;
        ?>
          <button type="button"
                  class="gs-gallery__item reveal"
                  style="grid-column: span <?php echo esc_attr($col_span); ?>; grid-row: span <?php echo esc_attr($row_span); ?>; transition-delay: <?php echo esc_attr($delay); ?>ms;"
                  data-category="<?php echo esc_attr(sanitize_title($category)); ?>"
                  data-full="<?php echo esc_url($full); ?>"
                  data-alt="<?php echo esc_attr($alt); ?>"
                  data-caption="<?php echo esc_attr($caption); ?>"
                  aria-label="<?php echo esc_attr($alt ?: __('Open image', 'greensun-hotel')); ?>">
            <img src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr($alt); ?>" loading="lazy" />
            <span class="gs-gallery__overlay" aria-hidden="true"></span>
            <?php if ($category) : ?>
              <span class="chip gs-gallery__cat"><span class="dot"></span><?php echo esc_html($category); ?></span>
            <?php endif; ?>
          </button>
        <?php endforeach; ?>
      </div>

      <div class="gs-gallery__empty" hidden>Nothing in this category yet.</div>

      <!-- Lightbox -->
      <div class="gs-gallery__lightbox" hidden role="dialog" aria-modal="true" aria-label="Gallery viewer">
        <button type="button" class="gs-gallery__lb-close" aria-label="Close">×</button>
        <button type="button" class="gs-gallery__lb-prev"  aria-label="Previous">‹</button>
        <button type="button" class="gs-gallery__lb-next"  aria-label="Next">›</button>
        <figure class="gs-gallery__lb-figure">
          <img class="gs-gallery__lb-img" src="" alt="" />
          <figcaption class="gs-gallery__lb-cap"></figcaption>
        </figure>
      </div>
    <?php else : ?>
      <p style="text-align:center; color: var(--ink-2, #3d433d);">No images yet.</p>
    <?php endif; ?>
  </div>
</section>
