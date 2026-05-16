<?php
$eyebrow       = $attributes['eyebrow']       ?? '';
$section_title = $attributes['sectionTitle']  ?? '';
$columns       = max(2, min(4, (int) ($attributes['columns'] ?? 3)));
$images        = $attributes['images']        ?? [];
$show_captions = !empty($attributes['showCaptions']);
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'section greensun-gallery-grid']); ?>>
  <div class="shell">
    <header style="text-align:center; max-width: 760px; margin: 0 auto 56px;">
      <?php if ($eyebrow) : ?>
        <div class="eyebrow reveal"><?php echo esc_html($eyebrow); ?></div>
      <?php endif; ?>
      <?php if ($section_title) : ?>
        <h2 class="display reveal" style="font-size: clamp(36px, 5vw, 64px); margin-top: 14px;">
          <?php echo wp_kses_post($section_title); ?>
        </h2>
      <?php endif; ?>
    </header>

    <?php if (!empty($images)) : ?>
      <div class="gallery-grid__masonry" style="column-count: <?php echo esc_attr($columns); ?>; column-gap: 14px;">
        <?php foreach ($images as $im) :
          $url     = $im['url']     ?? '';
          $full    = $im['full']    ?? $url;
          $alt     = $im['alt']     ?? '';
          $caption = $im['caption'] ?? '';
          if (!$url) continue;
        ?>
          <figure class="reveal" style="break-inside: avoid; margin: 0 0 14px;">
            <a href="<?php echo esc_url($full); ?>" target="_blank" rel="noopener" style="display:block;">
              <img src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr($alt); ?>" loading="lazy" style="width:100%; display:block; border-radius: 10px;" />
            </a>
            <?php if ($show_captions && $caption) : ?>
              <figcaption style="margin-top: 8px; font-size: 12px; color: var(--mute, #7b817b); letter-spacing: 0.04em;">
                <?php echo esc_html($caption); ?>
              </figcaption>
            <?php endif; ?>
          </figure>
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      <p style="text-align:center; color: var(--ink-2, #3d433d);">No images yet.</p>
    <?php endif; ?>
  </div>
</section>
