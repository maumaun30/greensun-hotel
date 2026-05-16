<?php
$title         = $attributes['title']        ?? '';
$body          = $attributes['body']         ?? '';
$stats         = $attributes['stats']        ?? [];
$show_ornament = !empty($attributes['showOrnament']);

$col_count = max(2, min(4, count($stats) >= 4 ? 2 : (count($stats) > 0 ? count($stats) : 2)));
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'gs-stay-smart']); ?>>
  <?php if ($show_ornament) : ?>
    <svg class="gs-stay-smart__ornament" viewBox="0 0 400 400" aria-hidden="true">
      <path d="M 60 320 C 100 180, 220 60, 360 80 C 360 240, 240 360, 80 360 C 60 350, 56 332, 60 320 Z" fill="var(--sun, #e8c46a)"/>
    </svg>
  <?php endif; ?>

  <div class="shell gs-stay-smart__grid">
    <?php if ($title) : ?>
      <h2 class="display reveal reveal--lg gs-stay-smart__title">
        <?php echo wp_kses_post($title); ?>
      </h2>
    <?php endif; ?>
    <div>
      <?php if ($body) : ?>
        <p class="reveal gs-stay-smart__body"><?php echo wp_kses_post($body); ?></p>
      <?php endif; ?>
      <?php if (!empty($stats)) : ?>
        <div class="gs-stay-smart__stats reveal" style="grid-template-columns: repeat(<?php echo esc_attr($col_count); ?>, 1fr);">
          <?php foreach ($stats as $s) :
            $value = $s['value'] ?? '';
            $label = $s['label'] ?? '';
          ?>
            <div class="gs-stay-smart__stat">
              <div class="display gs-stay-smart__stat-value"><?php echo esc_html($value); ?></div>
              <div class="gs-stay-smart__stat-label"><?php echo esc_html($label); ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
