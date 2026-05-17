<?php
$eyebrow       = $attributes['eyebrow']      ?? '';
$section_title = $attributes['sectionTitle'] ?? '';
$background    = $attributes['background']   ?? 'sage';
$columns       = max(2, min(4, (int) ($attributes['columns'] ?? 3)));
$items         = $attributes['items']        ?? [];

$bg_map = [
    'sage'  => 'var(--sage-2, #e0e9d8)',
    'paper' => 'var(--paper, #f8f5e9)',
    'bone'  => 'var(--bone, #ede9d9)',
    'none'  => 'transparent',
];
$bg = $bg_map[$background] ?? $bg_map['sage'];
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'section greensun-values-grid', 'style' => 'background:' . esc_attr($bg) . ';']); ?>>
  <div class="shell">
    <header class="gs-values__head">
      <?php if ($eyebrow) : ?>
        <div class="eyebrow reveal"><?php echo esc_html($eyebrow); ?></div>
      <?php endif; ?>
      <?php if ($section_title) : ?>
        <h2 class="display reveal reveal--lg gs-values__title">
          <?php echo wp_kses_post($section_title); ?>
        </h2>
      <?php endif; ?>
    </header>

    <div class="gs-values__grid" style="--gs-cols: <?php echo esc_attr($columns); ?>;">
      <?php foreach ($items as $i => $item) :
        $title = $item['title'] ?? '';
        $body  = $item['body']  ?? '';
      ?>
        <article class="gs-values__card reveal">
          <div class="display gs-values__num">
            <?php echo esc_html(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)); ?>
          </div>
          <?php if ($title) : ?>
            <h3 class="display gs-values__title-card"><?php echo esc_html($title); ?></h3>
          <?php endif; ?>
          <?php if ($body) : ?>
            <p class="gs-values__body"><?php echo esc_html($body); ?></p>
          <?php endif; ?>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
