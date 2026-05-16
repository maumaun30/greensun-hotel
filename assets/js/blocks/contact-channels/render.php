<?php
$channels  = $attributes['channels'] ?? [];
$map_embed = $attributes['mapEmbed'] ?? '';
?>
<div <?php echo get_block_wrapper_attributes(['class' => 'greensun-contact-channels']); ?>>
  <?php foreach ($channels as $c) :
    $title = $c['title'] ?? '';
    $lines = $c['lines'] ?? '';
  ?>
    <div class="contact-channels__item reveal">
      <?php if ($title) : ?>
        <div class="contact-channels__title" style="font-family: var(--font-mono, 'JetBrains Mono', monospace); color: var(--mute, #7b817b); margin-bottom: 8px;">
          <?php echo esc_html($title); ?>
        </div>
      <?php endif; ?>
      <?php if ($lines) : ?>
        <div class="contact-channels__lines" style="line-height: 1.75; color: var(--ink, #1a1f1a);">
          <?php echo nl2br(esc_html($lines)); ?>
        </div>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>

  <?php if ($map_embed) : ?>
    <div class="contact-channels__map reveal">
      <?php echo wp_kses($map_embed, [
        'iframe' => [
          'src' => true, 'width' => true, 'height' => true, 'style' => true,
          'allow' => true, 'allowfullscreen' => true, 'loading' => true,
          'referrerpolicy' => true, 'frameborder' => true, 'title' => true,
        ],
      ]); ?>
    </div>
  <?php endif; ?>
</div>
