<?php
$eyebrow      = $attributes['eyebrow']        ?? '';
$title        = $attributes['title']          ?? '';
$subtitle     = $attributes['subtitle']       ?? '';
$cta_text     = $attributes['ctaText']        ?? '';
$cta_url      = $attributes['ctaUrl']         ?? '#';
$stats        = $attributes['stats']          ?? [];
$primary_url  = $attributes['primaryImageUrl']   ?? '';
$primary_alt  = $attributes['primaryImageAlt']   ?? '';
$secondary_url = $attributes['secondaryImageUrl'] ?? '';
$secondary_alt = $attributes['secondaryImageAlt'] ?? '';
$badge_num    = $attributes['badgeNumber']    ?? '';
$badge_cap    = $attributes['badgeCaption']   ?? '';
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'section greensun-about-teaser']); ?>>
  <div class="shell gs-about__layout">

    <div class="gs-about__col">
      <?php if ($eyebrow) : ?>
        <div class="eyebrow reveal"><?php echo esc_html($eyebrow); ?></div>
      <?php endif; ?>
      <?php if ($title) : ?>
        <h2 class="display reveal reveal--lg gs-about__title">
          <?php echo wp_kses_post($title); ?>
        </h2>
      <?php endif; ?>
      <?php if ($subtitle) : ?>
        <p class="reveal gs-about__body">
          <?php echo wp_kses_post($subtitle); ?>
        </p>
      <?php endif; ?>

      <?php if (!empty($stats) && is_array($stats)) : ?>
        <div class="reveal gs-about__stats">
          <?php foreach ($stats as $stat) :
            $value = $stat['value'] ?? '';
            $label = $stat['label'] ?? '';
            if (!$value && !$label) continue;
          ?>
            <div class="gs-about__stat">
              <div class="display gs-about__stat-num"><?php echo esc_html($value); ?></div>
              <div class="gs-about__stat-label"><?php echo esc_html($label); ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if ($cta_text) : ?>
        <div class="reveal" style="margin-top: 44px;">
          <a href="<?php echo esc_url($cta_url); ?>" class="btn">
            <span class="ripple"></span>
            <span><?php echo esc_html($cta_text); ?></span>
            <svg width="14" height="10" viewBox="0 0 22 8" fill="none" aria-hidden="true" style="margin-left: 8px;">
              <path d="M0 4 L20 4 M14 0 L20 4 L14 8" stroke="currentColor" stroke-width="1.4" fill="none"/>
            </svg>
          </a>
        </div>
      <?php endif; ?>
    </div>

    <div class="gs-about__visual">
      <?php if ($primary_url) : ?>
        <div class="ph reveal kb gs-about__img gs-about__img--primary">
          <img src="<?php echo esc_url($primary_url); ?>" alt="<?php echo esc_attr($primary_alt); ?>" loading="lazy" />
        </div>
      <?php endif; ?>
      <?php if ($secondary_url) : ?>
        <div class="ph reveal kb gs-about__img gs-about__img--secondary">
          <img src="<?php echo esc_url($secondary_url); ?>" alt="<?php echo esc_attr($secondary_alt); ?>" loading="lazy" />
        </div>
      <?php endif; ?>
      <?php if ($badge_num) : ?>
        <div class="reveal gs-about__badge" aria-hidden="true">
          <div class="display gs-about__badge-num"><?php echo esc_html($badge_num); ?></div>
          <?php if ($badge_cap) : ?>
            <div class="gs-about__badge-cap"><?php echo esc_html($badge_cap); ?></div>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

  </div>
</section>
