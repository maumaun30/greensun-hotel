<?php
$eyebrow   = $attributes['eyebrow']   ?? '';
$title     = $attributes['title']     ?? '';
$subtitle  = $attributes['subtitle']  ?? '';
$cta_text  = $attributes['ctaText']   ?? '';
$cta_url   = $attributes['ctaUrl']    ?? '#';
$image_url = $attributes['imageUrl']  ?? '';
$image_alt = $attributes['imageAlt']  ?? '';
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'section greensun-about-teaser']); ?>>
  <div class="shell" style="display:grid; grid-template-columns: 1.05fr 1fr; gap: 90px; align-items:center;">
    <div>
      <?php if ($eyebrow) : ?>
        <div class="eyebrow reveal"><?php echo esc_html($eyebrow); ?></div>
      <?php endif; ?>
      <?php if ($title) : ?>
        <h2 class="display reveal" style="font-size: clamp(40px, 5.4vw, 76px); margin-top: 22px; max-width: 14ch;">
          <?php echo wp_kses_post($title); ?>
        </h2>
      <?php endif; ?>
      <?php if ($subtitle) : ?>
        <p class="reveal" style="margin-top: 22px; color: var(--ink-2); line-height: 1.75; max-width: 52ch;">
          <?php echo wp_kses_post($subtitle); ?>
        </p>
      <?php endif; ?>
      <?php if ($cta_text) : ?>
        <div class="btn-row reveal" style="margin-top: 42px;">
          <a href="<?php echo esc_url($cta_url); ?>" class="btn">
            <span class="ripple"></span>
            <span><?php echo esc_html($cta_text); ?></span>
          </a>
        </div>
      <?php endif; ?>
    </div>
    <div class="ph reveal kb" style="aspect-ratio: 4 / 5; border-radius: var(--radius-lg);">
      <?php if ($image_url) : ?>
        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" />
      <?php endif; ?>
    </div>
  </div>
</section>
