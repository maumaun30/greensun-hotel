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
    <?php if ($eyebrow) : ?>
      <div class="eyebrow reveal"><?php echo esc_html($eyebrow); ?></div>
    <?php endif; ?>
    <?php if ($section_title) : ?>
      <h2 class="display reveal reveal--lg" style="font-size: clamp(36px, 5vw, 64px); margin-top: 22px; max-width: 18ch;">
        <?php echo wp_kses_post($section_title); ?>
      </h2>
    <?php endif; ?>

    <div class="values-grid__grid" style="display:grid; grid-template-columns: repeat(<?php echo esc_attr($columns); ?>, 1fr); gap: 28px; margin-top: 60px;">
      <?php foreach ($items as $i => $item) :
        $title = $item['title'] ?? '';
        $body  = $item['body']  ?? '';
      ?>
        <article class="values-grid__card reveal" style="background: var(--paper, #f8f5e9); padding: 36px; border-radius: 4px; border: 1px solid var(--line, #ede9d9); height: 100%;">
          <div class="display" style="font-size: 56px; color: var(--sun-2, #d8a04c); line-height: 1;">
            <?php echo esc_html(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)); ?>
          </div>
          <?php if ($title) : ?>
            <h3 class="display" style="font-size: 26px; margin: 6px 0 0;"><?php echo esc_html($title); ?></h3>
          <?php endif; ?>
          <?php if ($body) : ?>
            <p style="margin-top: 14px; color: var(--ink-2, #3d433d); font-size: 15.5px; line-height: 1.75;"><?php echo esc_html($body); ?></p>
          <?php endif; ?>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
