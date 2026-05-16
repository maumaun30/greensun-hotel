<?php
$image_url          = $attributes['imageUrl'] ?? '';
$image_alt          = $attributes['imageAlt'] ?? '';
$title              = $attributes['title'] ?? '';
$subtitle           = $attributes['subtitle'] ?? '';
$primary_btn_text   = $attributes['primaryButtonText'] ?? '';
$primary_btn_url    = $attributes['primaryButtonUrl'] ?? '#';
$secondary_btn_text = $attributes['secondaryButtonText'] ?? '';
$secondary_btn_url  = $attributes['secondaryButtonUrl'] ?? '#';
$overlay_opacity    = $attributes['overlayOpacity'] ?? 55;
$eyebrow            = $attributes['eyebrow'] ?? '';
$accent             = $attributes['accent'] ?? '';
$ken_burns          = !empty($attributes['kenBurns']);

$overlay_top    = round(($overlay_opacity / 100) * 0.3, 2);
$overlay_bottom = round($overlay_opacity / 100, 2);

$wrapper_class = 'swiper-slide greensun-hotel-carousel-slide gs-hero';
if ($ken_burns) {
    $wrapper_class .= ' kb';
}
?>
<div <?php echo get_block_wrapper_attributes(['class' => $wrapper_class]); ?>>

  <?php if ($image_url) : ?>
    <div class="gs-hero__bg ph<?php echo $ken_burns ? ' kb' : ''; ?>">
      <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" />
    </div>
  <?php endif; ?>

  <div
    class="greensun-hotel-carousel-slide__overlay"
    style="position:absolute; inset:0; z-index:1; background: linear-gradient(180deg, rgba(0,0,0,<?php echo esc_attr($overlay_top); ?>) 0%, rgba(0,0,0,<?php echo esc_attr($overlay_bottom); ?>) 100%);"
    aria-hidden="true"
  ></div>

  <div class="gs-hero__content shell" style="display:flex; flex-direction:column; justify-content:center; min-height:100vh; padding-top:120px; padding-bottom:120px;">

    <?php if ($eyebrow) : ?>
      <div class="eyebrow reveal" style="color: var(--sun); margin-bottom: 24px;">
        <?php echo esc_html($eyebrow); ?>
      </div>
    <?php endif; ?>

    <?php if ($title) : ?>
      <h1 class="display reveal" style="font-size: clamp(48px, 7vw, 96px); max-width: 18ch;">
        <?php echo wp_kses_post($title); ?>
      </h1>
    <?php endif; ?>

    <?php if ($accent) : ?>
      <div class="display reveal" style="font-style: italic; font-size: clamp(22px, 2.4vw, 32px); color: var(--sun); margin-top: 14px;">
        <?php echo esc_html($accent); ?>
      </div>
    <?php endif; ?>

    <?php if ($subtitle) : ?>
      <p class="reveal" style="font-size: 18px; max-width: 52ch; line-height: 1.6; margin-top: 28px; opacity: 0.9;">
        <?php echo wp_kses_post($subtitle); ?>
      </p>
    <?php endif; ?>

    <?php if ($primary_btn_text || $secondary_btn_text) : ?>
      <div class="btn-row reveal" style="margin-top: 40px;">
        <?php if ($primary_btn_text) : ?>
          <a href="<?php echo esc_url($primary_btn_url); ?>" class="btn btn--sun">
            <span class="ripple"></span>
            <span><?php echo esc_html($primary_btn_text); ?></span>
          </a>
        <?php endif; ?>
        <?php if ($secondary_btn_text) : ?>
          <a href="<?php echo esc_url($secondary_btn_url); ?>" class="btn btn--ghost btn--light">
            <span class="ripple"></span>
            <span><?php echo esc_html($secondary_btn_text); ?></span>
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>

  </div>

</div>
