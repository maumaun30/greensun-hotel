<?php
$channels  = $attributes['channels'] ?? [];
$map_embed = $attributes['mapEmbed'] ?? '';
$map_label = $attributes['mapLabel'] ?? 'Magallanes · Makati';
$map_caption = $attributes['mapCaption'] ?? 'Green Sun';
?>
<div <?php echo get_block_wrapper_attributes(['class' => 'greensun-contact-channels']); ?>>
  <?php foreach ($channels as $c) :
    $title = $c['title'] ?? '';
    $lines = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', (string) ($c['lines'] ?? '')))));
    if (!$title && empty($lines)) continue;
  ?>
    <div class="gs-contact__item reveal">
      <?php if ($title) : ?>
        <div class="gs-contact__label"><?php echo esc_html($title); ?></div>
      <?php endif; ?>
      <?php foreach ($lines as $idx => $line) : ?>
        <?php if ($idx === 0) : ?>
          <div class="display gs-contact__lead"><?php echo esc_html($line); ?></div>
        <?php else : ?>
          <div class="gs-contact__line"><?php echo esc_html($line); ?></div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  <?php endforeach; ?>

  <div class="gs-contact__map reveal">
    <?php if ($map_embed) : ?>
      <?php echo wp_kses($map_embed, [
        'iframe' => [
          'src' => true, 'width' => true, 'height' => true, 'style' => true,
          'allow' => true, 'allowfullscreen' => true, 'loading' => true,
          'referrerpolicy' => true, 'frameborder' => true, 'title' => true,
        ],
      ]); ?>
    <?php else : ?>
      <svg viewBox="0 0 600 280" width="100%" height="100%" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
        <rect width="600" height="280" fill="oklch(0.92 0.025 130)"/>
        <g stroke="oklch(0.88 0.02 130)" stroke-width="14" fill="none">
          <path d="M -20 100 L 220 110 L 360 60 L 640 80"/>
          <path d="M -20 200 L 200 195 L 360 240 L 640 220"/>
          <path d="M 280 -20 L 290 300"/>
          <path d="M 460 -20 L 480 300"/>
        </g>
        <g stroke="oklch(0.78 0.04 130)" stroke-width="1" fill="none" opacity=".6">
          <path d="M 0 50 L 600 50"/>
          <path d="M 0 140 L 600 150"/>
          <path d="M 100 -20 L 110 300"/>
        </g>
        <g fill="oklch(0.85 0.03 130)">
          <rect x="40"  y="30"  width="170" height="60"/>
          <rect x="40"  y="120" width="170" height="60"/>
          <rect x="40"  y="210" width="170" height="60"/>
          <rect x="310" y="80"  width="120" height="120"/>
          <rect x="500" y="100" width="80"  height="60"/>
          <rect x="500" y="180" width="80"  height="60"/>
        </g>
        <g transform="translate(340 130)">
          <circle r="32" fill="var(--forest, #1f4a3a)" opacity=".15">
            <animate attributeName="r" from="14" to="40" dur="2s" repeatCount="indefinite"/>
            <animate attributeName="opacity" from=".4" to="0" dur="2s" repeatCount="indefinite"/>
          </circle>
          <circle r="14" fill="var(--forest, #1f4a3a)"/>
          <circle r="6"  fill="var(--sun, #e8c46a)"/>
        </g>
        <?php if ($map_caption) : ?>
          <text x="340" y="105" text-anchor="middle" font-family="Cormorant Garamond, serif" font-size="14" fill="var(--forest-2, #0f2018)" font-style="italic"><?php echo esc_html($map_caption); ?></text>
        <?php endif; ?>
      </svg>
    <?php endif; ?>
    <?php if ($map_label) : ?>
      <div class="gs-contact__map-label"><?php echo esc_html($map_label); ?></div>
    <?php endif; ?>
  </div>
</div>
