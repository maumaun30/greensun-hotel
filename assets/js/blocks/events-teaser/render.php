<?php
$eyebrow       = $attributes['eyebrow']      ?? '';
$section_title = $attributes['sectionTitle'] ?? '';
$subtitle      = $attributes['subtitle']     ?? '';
$events        = $attributes['events']       ?? [];
$count         = max(1, min(3, count($events)));
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'section greensun-events-teaser']); ?>>
  <div class="shell">
    <header class="events-teaser__head" style="display:grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items:end; margin-bottom: 64px;">
      <div>
        <?php if ($eyebrow) : ?>
          <div class="eyebrow reveal"><?php echo esc_html($eyebrow); ?></div>
        <?php endif; ?>
        <?php if ($section_title) : ?>
          <h2 class="display reveal" style="font-size: clamp(36px, 5vw, 72px); margin-top: 14px; max-width: 14ch;">
            <?php echo wp_kses_post($section_title); ?>
          </h2>
        <?php endif; ?>
      </div>
      <?php if ($subtitle) : ?>
        <p class="reveal" style="color: var(--ink-2, #3d433d); line-height: 1.75; max-width: 52ch;">
          <?php echo wp_kses_post($subtitle); ?>
        </p>
      <?php endif; ?>
    </header>
    <div class="events-teaser__grid" style="display:grid; grid-template-columns: repeat(<?php echo esc_attr($count); ?>, 1fr); gap: 28px;">
      <?php foreach ($events as $ev) :
        $image_url = $ev['imageUrl']   ?? '';
        $image_alt = $ev['imageAlt']   ?? '';
        $title     = $ev['title']      ?? '';
        $desc      = $ev['description']?? '';
        $cta_text  = $ev['ctaText']    ?? '';
        $cta_url   = $ev['ctaUrl']     ?? '#';
      ?>
        <article class="gs-card reveal" style="background:#fff; border-radius: var(--radius-lg, 14px); overflow:hidden; border:1px solid var(--line, #ede9d9);">
          <div class="ph kb" style="aspect-ratio: 4 / 3;">
            <?php if ($image_url) : ?>
              <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" style="width:100%; height:100%; object-fit:cover;" />
            <?php endif; ?>
          </div>
          <div style="padding: 28px;">
            <?php if ($title) : ?>
              <h3 class="display" style="font-size: 30px; margin: 0;"><?php echo esc_html($title); ?></h3>
            <?php endif; ?>
            <?php if ($desc) : ?>
              <p style="margin-top: 10px; color: var(--ink-2, #3d433d); line-height: 1.6;"><?php echo esc_html($desc); ?></p>
            <?php endif; ?>
            <?php if ($cta_text) : ?>
              <a href="<?php echo esc_url($cta_url); ?>" class="linedot" style="margin-top: 22px; display:inline-flex; align-items:center; gap: 10px; font-size: 12px; letter-spacing: 0.18em; text-transform: uppercase; color: var(--forest, #1f4a3a); text-decoration: none;">
                <?php echo esc_html($cta_text); ?> <span aria-hidden="true">→</span>
              </a>
            <?php endif; ?>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
