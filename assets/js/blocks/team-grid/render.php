<?php
$eyebrow       = $attributes['eyebrow']      ?? '';
$section_title = $attributes['sectionTitle'] ?? '';
$columns       = max(2, min(4, (int) ($attributes['columns'] ?? 4)));
$members       = $attributes['members']      ?? [];
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'section greensun-team-grid']); ?>>
  <div class="shell">
    <header class="gs-team__head">
      <?php if ($eyebrow) : ?>
        <div class="eyebrow reveal"><?php echo esc_html($eyebrow); ?></div>
      <?php endif; ?>
      <?php if ($section_title) : ?>
        <h2 class="display reveal reveal--lg gs-team__title">
          <?php echo wp_kses_post($section_title); ?>
        </h2>
      <?php endif; ?>
    </header>

    <div class="gs-team__grid" style="--gs-cols: <?php echo esc_attr($columns); ?>;">
      <?php foreach ($members as $m) :
        $url  = $m['imageUrl'] ?? '';
        $alt  = $m['imageAlt'] ?? '';
        $name = $m['name']     ?? '';
        $role = $m['role']     ?? '';
      ?>
        <article class="gs-team__card reveal">
          <div class="gs-team__media ph kb">
            <?php if ($url) : ?>
              <img src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr($alt); ?>" loading="lazy" />
            <?php endif; ?>
          </div>
          <?php if ($name) : ?>
            <h3 class="display gs-team__name"><?php echo esc_html($name); ?></h3>
          <?php endif; ?>
          <?php if ($role) : ?>
            <div class="gs-team__role"><?php echo esc_html($role); ?></div>
          <?php endif; ?>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
