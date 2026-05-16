<?php
$eyebrow    = $attributes['eyebrow']        ?? '';
$title      = $attributes['title']          ?? '';
$subtitle   = $attributes['subtitle']       ?? '';
$image_url  = $attributes['imageUrl']       ?? '';
$image_alt  = $attributes['imageAlt']       ?? '';
$min_h      = max(50, min(100, (int) ($attributes['minHeight']      ?? 92)));
$overlay    = max(0,  min(100, (int) ($attributes['overlayOpacity'] ?? 70))) / 100;
$ken_burns  = !empty($attributes['kenBurns']);

$allowed_tags = ['em' => [], 'i' => [], 'strong' => [], 'b' => [], 'br' => [], 'span' => ['class' => []]];
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'gs-page-hero', 'style' => 'min-height:' . esc_attr($min_h) . 'vh;']); ?>>
  <div class="gs-page-hero__media<?php echo $ken_burns ? ' kb' : ''; ?>">
    <?php if ($image_url) : ?>
      <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" />
    <?php endif; ?>
  </div>
  <div class="gs-page-hero__scrim" style="background: linear-gradient(to bottom, rgba(13,42,32,<?php echo esc_attr($overlay * 0.55); ?>), rgba(13,42,32,<?php echo esc_attr($overlay); ?>));"></div>

  <div class="shell gs-page-hero__content">
    <?php if ($eyebrow) : ?>
      <div class="eyebrow reveal" style="color: var(--sun, #e8c46a);"><?php echo esc_html($eyebrow); ?></div>
    <?php endif; ?>
    <?php if ($title) : ?>
      <h1 class="display reveal reveal--lg" style="font-size: clamp(54px, 8vw, 130px); margin-top: 28px; max-width: 14ch; font-weight: 500; text-shadow: 0 2px 32px rgba(0,0,0,.45);">
        <?php echo wp_kses($title, $allowed_tags); ?>
      </h1>
    <?php endif; ?>
    <?php if ($subtitle) : ?>
      <p class="reveal" style="max-width: 620px; margin-top: 28px; color: rgba(255,255,255,.82); font-size: 18px; line-height: 1.75;">
        <?php echo wp_kses_post($subtitle); ?>
      </p>
    <?php endif; ?>
  </div>
</section>
