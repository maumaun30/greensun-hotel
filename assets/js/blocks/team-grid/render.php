<?php
$eyebrow       = $attributes['eyebrow']      ?? '';
$section_title = $attributes['sectionTitle'] ?? '';
$columns       = max(2, min(4, (int) ($attributes['columns'] ?? 3)));
$members       = $attributes['members']      ?? [];
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'section greensun-team-grid']); ?>>
  <div class="shell">
    <header style="text-align:center; max-width: 720px; margin: 0 auto 64px;">
      <?php if ($eyebrow) : ?>
        <div class="eyebrow reveal"><?php echo esc_html($eyebrow); ?></div>
      <?php endif; ?>
      <?php if ($section_title) : ?>
        <h2 class="display reveal" style="font-size: clamp(36px, 5vw, 64px); margin-top: 14px;">
          <?php echo wp_kses_post($section_title); ?>
        </h2>
      <?php endif; ?>
    </header>
    <div class="team-grid__grid" style="display:grid; grid-template-columns: repeat(<?php echo esc_attr($columns); ?>, 1fr); gap: 36px;">
      <?php foreach ($members as $m) :
        $url  = $m['imageUrl'] ?? '';
        $alt  = $m['imageAlt'] ?? '';
        $name = $m['name']     ?? '';
        $role = $m['role']     ?? '';
      ?>
        <article class="reveal" style="text-align:center;">
          <div class="ph kb" style="aspect-ratio: 3 / 4; border-radius: var(--radius-lg, 14px); overflow:hidden; margin-bottom: 22px;">
            <?php if ($url) : ?>
              <img src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr($alt); ?>" style="width:100%; height:100%; object-fit:cover;" />
            <?php endif; ?>
          </div>
          <?php if ($name) : ?>
            <div class="display" style="font-size: 28px; line-height: 1.1;"><?php echo esc_html($name); ?></div>
          <?php endif; ?>
          <?php if ($role) : ?>
            <div class="eyebrow" style="margin-top: 8px;"><?php echo esc_html($role); ?></div>
          <?php endif; ?>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
