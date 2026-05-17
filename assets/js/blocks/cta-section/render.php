<?php
$eyebrow         = $attributes['eyebrow']         ?? '';
$title           = $attributes['title']           ?? '';
$subtitle        = $attributes['subtitle']        ?? '';
$cta_text        = $attributes['ctaText']         ?? '';
$cta_url         = $attributes['ctaUrl']          ?? '#';
$secondary_text  = $attributes['secondaryCtaText'] ?? '';
$secondary_url   = $attributes['secondaryCtaUrl']  ?? '';
$image_url       = $attributes['imageUrl']        ?? '';
$image_alt       = $attributes['imageAlt']        ?? '';
$overlay         = max(0, min(100, (int) ($attributes['overlayOpacity'] ?? 70)));
$alpha           = $overlay / 100;
$layout          = in_array($attributes['layout'] ?? 'centered', ['centered', 'split'], true) ? $attributes['layout'] : 'centered';
$is_split        = $layout === 'split';
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'greensun-cta-section greensun-cta-section--' . esc_attr($layout), 'style' => 'position:relative; overflow:hidden;']); ?>>
  <?php if ($image_url) : ?>
    <div class="kb" style="position:absolute; inset:0; z-index:0;">
      <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" style="width:100%; height:100%; object-fit:cover;" />
    </div>
    <div style="position:absolute; inset:0; z-index:0; background: linear-gradient(95deg, rgba(13,42,32,<?php echo esc_attr($alpha); ?>), rgba(13,42,32,<?php echo esc_attr(max(0, $alpha - 0.4)); ?>));"></div>
  <?php else : ?>
    <div style="position:absolute; inset:0; background: var(--forest, #1f4a3a); z-index:0;"></div>
  <?php endif; ?>

  <?php if ($is_split) : ?>
    <div class="shell greensun-cta-section__inner greensun-cta-section__split">
      <div>
        <?php if ($eyebrow) : ?>
          <div class="eyebrow reveal" style="color: var(--sun, #e8c46a);"><?php echo esc_html($eyebrow); ?></div>
        <?php endif; ?>
        <?php if ($title) : ?>
          <h2 class="display reveal reveal--lg" style="font-size: clamp(36px, 5vw, 60px); margin-top: 14px; color: var(--ivory, #f7f6f0); max-width: 14ch;">
            <?php echo wp_kses_post($title); ?>
          </h2>
        <?php endif; ?>
      </div>
      <div>
        <?php if ($subtitle) : ?>
          <p class="reveal" style="font-size: 17px; line-height: 1.75; color: rgba(255,255,255,0.82); max-width: 480px;">
            <?php echo wp_kses_post($subtitle); ?>
          </p>
        <?php endif; ?>
        <div class="reveal greensun-cta-section__buttons" style="margin-top: 28px;">
          <?php if ($cta_text) : ?>
            <a href="<?php echo esc_url($cta_url); ?>" class="btn btn--sun">
              <span class="ripple"></span>
              <span><?php echo esc_html($cta_text); ?></span>
            </a>
          <?php endif; ?>
          <?php if ($secondary_text) : ?>
            <a href="<?php echo esc_url($secondary_url); ?>" class="btn btn--ghost btn--light">
              <span class="ripple"></span>
              <span><?php echo esc_html($secondary_text); ?></span>
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php else : ?>
    <div class="shell greensun-cta-section__inner greensun-cta-section__centered">
      <div class="greensun-cta-section__copy">
        <?php if ($eyebrow) : ?>
          <div class="eyebrow reveal" style="color: var(--sun, #e8c46a);"><?php echo esc_html($eyebrow); ?></div>
        <?php endif; ?>
        <?php if ($title) : ?>
          <h2 class="display reveal reveal--lg" style="font-size: clamp(48px, 6.8vw, 92px); margin-top: 22px; color: var(--ivory, #f7f6f0); max-width: 16ch;">
            <?php echo wp_kses_post($title); ?>
          </h2>
        <?php endif; ?>
        <?php if ($subtitle) : ?>
          <p class="reveal" style="margin-top: 28px; line-height: 1.7; color: rgba(255,255,255,0.78); max-width: 540px; font-size: 17px;">
            <?php echo wp_kses_post($subtitle); ?>
          </p>
        <?php endif; ?>
        <?php if ($cta_text || $secondary_text) : ?>
          <div class="btn-row reveal greensun-cta-section__buttons" style="margin-top: 40px;">
            <?php if ($cta_text) : ?>
              <a href="<?php echo esc_url($cta_url); ?>" class="btn btn--sun btn--lg">
                <span class="ripple"></span>
                <span><?php echo esc_html($cta_text); ?></span>
              </a>
            <?php endif; ?>
            <?php if ($secondary_text) : ?>
              <a href="<?php echo esc_url($secondary_url); ?>" class="btn btn--ghost btn--light btn--lg">
                <span class="ripple"></span>
                <span><?php echo esc_html($secondary_text); ?></span>
              </a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>
</section>
