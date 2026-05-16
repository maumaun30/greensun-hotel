<?php
$quote     = $attributes['quote']     ?? '';
$author    = $attributes['author']    ?? '';
$role      = $attributes['role']      ?? '';
$alignment = in_array($attributes['alignment'] ?? 'center', ['left', 'center', 'right'], true)
    ? $attributes['alignment']
    : 'center';

$margin_inline = $alignment === 'center'
    ? 'margin-inline: auto;'
    : ($alignment === 'right' ? 'margin-left: auto; margin-right: 0;' : 'margin-left: 0; margin-right: auto;');
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'section greensun-pull-quote', 'style' => 'text-align:' . esc_attr($alignment) . ';']); ?>>
  <div class="shell">
    <?php if ($quote) : ?>
      <blockquote class="display reveal" style="font-size: clamp(32px, 4.8vw, 64px); line-height: 1.2; margin: 0; max-width: 22ch; <?php echo $margin_inline; ?>">
        <span aria-hidden="true" style="display:block; font-size: 0.6em; color: var(--sun, #e8c46a); line-height: 1; margin-bottom: 12px;">“</span>
        <?php echo wp_kses_post($quote); ?>
      </blockquote>
    <?php endif; ?>
    <?php if ($author || $role) : ?>
      <div class="reveal" style="margin-top: 28px;">
        <?php if ($author) : ?>
          <div style="font-size: 14px; font-weight: 600; color: var(--ink, #1a1f1a);"><?php echo esc_html($author); ?></div>
        <?php endif; ?>
        <?php if ($role) : ?>
          <div class="eyebrow" style="margin-top: 6px;"><?php echo esc_html($role); ?></div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
