<?php
$eyebrow   = $attributes['eyebrow']        ?? '';
$title     = $attributes['title']          ?? '';
$subtitle  = $attributes['subtitle']       ?? '';
$cta_text  = $attributes['ctaText']        ?? '';
$cta_url   = $attributes['ctaUrl']         ?? '#';
$image_url = $attributes['imageUrl']       ?? '';
$image_alt = $attributes['imageAlt']       ?? '';
$overlay   = max(0, min(100, (int) ($attributes['overlayOpacity'] ?? 55)));
$alpha     = $overlay / 100;
$layout    = in_array($attributes['layout'] ?? 'centered', ['centered', 'split'], true) ? $attributes['layout'] : 'centered';
$is_split  = $layout === 'split';
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'section greensun-cta-section greensun-cta-section--' . esc_attr($layout), 'style' => 'position:relative; overflow:hidden;']); ?>>
  <?php if ($image_url) : ?>
    <div class="kb" style="position:absolute; inset:0; z-index:0;">
      <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" style="width:100%; height:100%; object-fit:cover;" />
      <div style="position:absolute; inset:0; background: rgba(15,32,24,<?php echo esc_attr($alpha); ?>);"></div>
    </div>
  <?php else : ?>
    <div style="position:absolute; inset:0; background: var(--forest, #1f4a3a); z-index:0;"></div>
  <?php endif; ?>

  <?php if ($is_split) : ?>
    <div class="shell greensun-cta-section__split" style="position:relative; z-index:1; color: var(--ivory, #f7f6f0); padding-block: 60px; display:grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items:center;">
      <div>
        <?php if ($eyebrow) : ?>
          <div class="eyebrow reveal" style="color: var(--sun, #e8c46a);"><?php echo esc_html($eyebrow); ?></div>
        <?php endif; ?>
        <?php if ($title) : ?>
          <h2 class="display reveal reveal--lg" style="font-size: clamp(36px, 5vw, 60px); margin-top: 14px; color: var(--sun, #e8c46a); max-width: 14ch;">
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
        <?php if ($cta_text) : ?>
          <div class="reveal" style="margin-top: 28px;">
            <a href="<?php echo esc_url($cta_url); ?>" class="btn btn--sun">
              <span class="ripple"></span>
              <span><?php echo esc_html($cta_text); ?></span>
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php else : ?>
    <div class="shell" style="position:relative; z-index:1; text-align:center; color: var(--ivory, #f7f6f0); padding-block: 40px;">
      <?php if ($eyebrow) : ?>
        <div class="eyebrow reveal" style="color: var(--sun, #e8c46a);"><?php echo esc_html($eyebrow); ?></div>
      <?php endif; ?>
      <?php if ($title) : ?>
        <h2 class="display reveal" style="font-size: clamp(40px, 6vw, 88px); margin-top: 22px; max-width: 18ch; margin-inline: auto;">
          <?php echo wp_kses_post($title); ?>
        </h2>
      <?php endif; ?>
      <?php if ($subtitle) : ?>
        <p class="reveal" style="margin-top: 22px; line-height: 1.75; max-width: 56ch; margin-inline: auto; opacity: 0.92;">
          <?php echo wp_kses_post($subtitle); ?>
        </p>
      <?php endif; ?>
      <?php if ($cta_text) : ?>
        <div class="btn-row reveal" style="margin-top: 42px;">
          <a href="<?php echo esc_url($cta_url); ?>" class="btn btn--sun">
            <span class="ripple"></span>
            <span><?php echo esc_html($cta_text); ?></span>
          </a>
        </div>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</section>
