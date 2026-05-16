<?php
$eyebrow   = $attributes['eyebrow']      ?? '';
$title     = $attributes['sectionTitle'] ?? '';
$address   = $attributes['address']      ?? '';
$phone     = $attributes['phone']        ?? '';
$email     = $attributes['email']        ?? '';
$hours     = $attributes['hours']        ?? '';
$map_embed = $attributes['mapEmbed']     ?? '';

$nl2br_simple = function ($s) {
    return nl2br(esc_html($s));
};
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'section greensun-contact-info']); ?>>
  <div class="shell">
    <?php if ($eyebrow) : ?>
      <div class="eyebrow reveal"><?php echo esc_html($eyebrow); ?></div>
    <?php endif; ?>
    <?php if ($title) : ?>
      <h2 class="display reveal" style="font-size: clamp(36px, 5vw, 64px); margin-top: 14px; max-width: 20ch;">
        <?php echo wp_kses_post($title); ?>
      </h2>
    <?php endif; ?>
    <div class="contact-info__grid" style="margin-top: 56px; display:grid; grid-template-columns: repeat(4, 1fr); gap: 40px;">
      <?php if ($address) : ?>
        <div class="reveal">
          <div class="eyebrow">Address</div>
          <div style="margin-top: 12px; line-height: 1.7; color: var(--ink, #1a1f1a);"><?php echo $nl2br_simple($address); ?></div>
        </div>
      <?php endif; ?>
      <?php if ($phone) : ?>
        <div class="reveal">
          <div class="eyebrow">Phone</div>
          <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>" style="display:block; margin-top: 12px; color: var(--ink, #1a1f1a); text-decoration: none;">
            <?php echo esc_html($phone); ?>
          </a>
        </div>
      <?php endif; ?>
      <?php if ($email) : ?>
        <div class="reveal">
          <div class="eyebrow">Email</div>
          <a href="mailto:<?php echo esc_attr($email); ?>" style="display:block; margin-top: 12px; color: var(--ink, #1a1f1a); text-decoration: none;">
            <?php echo esc_html($email); ?>
          </a>
        </div>
      <?php endif; ?>
      <?php if ($hours) : ?>
        <div class="reveal">
          <div class="eyebrow">Hours</div>
          <div style="margin-top: 12px; line-height: 1.7; color: var(--ink, #1a1f1a);"><?php echo $nl2br_simple($hours); ?></div>
        </div>
      <?php endif; ?>
    </div>

    <?php if ($map_embed) : ?>
      <div class="reveal" style="margin-top: 56px; aspect-ratio: 16 / 7; border-radius: var(--radius-lg, 14px); overflow: hidden; border: 1px solid var(--line, #ede9d9);">
        <?php echo wp_kses($map_embed, [
          'iframe' => [
            'src'             => true,
            'width'           => true,
            'height'          => true,
            'style'           => true,
            'allow'           => true,
            'allowfullscreen' => true,
            'loading'         => true,
            'referrerpolicy'  => true,
            'frameborder'     => true,
            'title'           => true,
          ],
        ]); ?>
      </div>
    <?php endif; ?>
  </div>
</section>
