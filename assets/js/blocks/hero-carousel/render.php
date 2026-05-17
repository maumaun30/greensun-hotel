<?php
$slides        = $attributes['slides']       ?? [];
$interval      = max(2000, (int) ($attributes['intervalMs'] ?? 6500));
$show_mark     = !empty($attributes['showMark']);
$mark_text     = $attributes['markText']     ?? '';
$primary_cta   = $attributes['primaryCta']   ?? '';
$primary_url   = $attributes['primaryUrl']   ?? '#';
$secondary_cta = $attributes['secondaryCta'] ?? '';
$secondary_url = $attributes['secondaryUrl'] ?? '#';

if (empty($slides)) return;

$allowed_title_tags = ['em' => [], 'i' => [], 'strong' => [], 'b' => [], 'span' => ['class' => []], 'br' => []];
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'gs-hero-carousel', 'data-interval' => $interval]); ?>>
  <div class="gs-hero-carousel__slides">
    <?php foreach ($slides as $idx => $s) :
      $image_url = $s['imageUrl'] ?? '';
      $image_alt = $s['imageAlt'] ?? '';
    ?>
      <div class="gs-hero-carousel__slide<?php echo $idx === 0 ? ' is-active' : ''; ?>" data-index="<?php echo esc_attr($idx); ?>">
        <div class="gs-hero-carousel__media kb">
          <?php if ($image_url) : ?>
            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" loading="<?php echo $idx === 0 ? 'eager' : 'lazy'; ?>" />
          <?php endif; ?>
        </div>
        <div class="gs-hero-carousel__scrim"></div>
        <div class="gs-hero-carousel__vignette"></div>
      </div>
    <?php endforeach; ?>
  </div>

  <svg class="gs-hero-carousel__leaves" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
    <g fill="oklch(0.72 0.11 130)">
      <path d="M 80 600 C 100 540, 180 520, 240 540 C 220 600, 140 640, 80 600 Z" opacity=".5" style="transform-origin:160px 580px; animation: gs-leaf1 9s ease-in-out infinite;"/>
      <path d="M 1480 200 C 1500 140, 1580 120, 1640 140 C 1620 200, 1540 240, 1480 200 Z" opacity=".4" style="transform-origin:1560px 180px; animation: gs-leaf2 11s ease-in-out infinite;"/>
      <path d="M 1200 740 C 1220 680, 1300 660, 1360 680 C 1340 740, 1260 780, 1200 740 Z" opacity=".3" style="transform-origin:1280px 720px; animation: gs-leaf1 13s ease-in-out infinite;"/>
    </g>
  </svg>

  <?php if ($show_mark && $mark_text) : ?>
    <div class="gs-hero-carousel__mark" aria-hidden="true">
      <span class="gs-hero-carousel__mark-rule"></span>
      <?php echo esc_html($mark_text); ?>
      <span class="gs-hero-carousel__mark-rule gs-hero-carousel__mark-rule--long"></span>
    </div>

    <div class="gs-hero-carousel__compass" aria-hidden="true">
      <svg viewBox="0 0 540 540">
        <g fill="none" stroke="rgba(255,255,255,.10)" stroke-width="1">
          <circle cx="270" cy="270" r="120"/>
          <circle cx="270" cy="270" r="180"/>
          <circle cx="270" cy="270" r="240"/>
        </g>
        <circle cx="270" cy="270" r="200" class="gs-hero-carousel__compass-ring" />
        <g class="gs-hero-carousel__compass-tick">
          <line x1="270" y1="62" x2="270" y2="78" stroke="rgba(216,178,90,.9)" stroke-width="1.3" stroke-linecap="round"/>
        </g>
        <g fill="rgba(255,255,255,.4)" font-family="var(--font-mono, 'JetBrains Mono', monospace)" font-size="9" letter-spacing=".2em">
          <text x="270" y="56" text-anchor="middle">N</text>
          <text x="270" y="494" text-anchor="middle">S</text>
          <text x="60"  y="274" text-anchor="middle">W</text>
          <text x="480" y="274" text-anchor="middle">E</text>
        </g>
      </svg>
    </div>
  <?php endif; ?>

  <div class="shell gs-hero-carousel__content">
    <div class="gs-hero-carousel__copy">
      <?php foreach ($slides as $idx => $s) : ?>
        <div class="gs-hero-carousel__copy-slide<?php echo $idx === 0 ? ' is-active' : ''; ?>" data-index="<?php echo esc_attr($idx); ?>">
          <?php if (!empty($s['eyebrow'])) : ?>
            <div class="eyebrow gs-hero-carousel__eyebrow"><?php echo esc_html($s['eyebrow']); ?></div>
          <?php endif; ?>
          <?php if (!empty($s['title'])) : ?>
            <h1 class="display gs-hero-carousel__title">
              <?php echo wp_kses($s['title'], $allowed_title_tags); ?>
            </h1>
          <?php endif; ?>
          <?php if (!empty($s['subtitle'])) : ?>
            <p class="gs-hero-carousel__subtitle"><?php echo esc_html($s['subtitle']); ?></p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>

      <?php if ($primary_cta || $secondary_cta) : ?>
        <div class="gs-hero-carousel__buttons">
          <?php if ($primary_cta) : ?>
            <a href="<?php echo esc_url($primary_url); ?>" class="btn btn--sun">
              <span class="ripple"></span>
              <span><?php echo esc_html($primary_cta); ?></span>
            </a>
          <?php endif; ?>
          <?php if ($secondary_cta) : ?>
            <a href="<?php echo esc_url($secondary_url); ?>" class="btn btn--ghost btn--light">
              <span class="ripple"></span>
              <span><?php echo esc_html($secondary_cta); ?></span>
            </a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="gs-hero-carousel__indicators" role="tablist" aria-label="Slides">
      <?php foreach ($slides as $idx => $s) : ?>
        <button type="button"
                class="gs-hero-carousel__indicator<?php echo $idx === 0 ? ' is-active' : ''; ?>"
                data-index="<?php echo esc_attr($idx); ?>"
                role="tab"
                aria-selected="<?php echo $idx === 0 ? 'true' : 'false'; ?>">
          <span class="gs-hero-carousel__indicator-num"><?php echo esc_html(str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT)); ?></span>
          <span class="gs-hero-carousel__indicator-bar"></span>
          <span class="gs-hero-carousel__indicator-label"><?php echo esc_html($s['accent'] ?? ''); ?></span>
        </button>
      <?php endforeach; ?>
    </div>

    <div class="gs-hero-carousel__scroll-hint" aria-hidden="true">
      <span>Scroll</span>
      <span class="gs-hero-carousel__scroll-line"></span>
    </div>
  </div>
</section>
