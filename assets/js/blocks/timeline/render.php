<?php
$eyebrow       = $attributes['eyebrow']      ?? '';
$section_title = $attributes['sectionTitle'] ?? '';
$entries       = $attributes['entries']      ?? [];
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'section greensun-timeline']); ?>>
  <div class="shell timeline__grid">
    <div class="timeline__head">
      <?php if ($eyebrow) : ?>
        <div class="eyebrow reveal"><?php echo esc_html($eyebrow); ?></div>
      <?php endif; ?>
      <?php if ($section_title) : ?>
        <h2 class="display reveal reveal--lg" style="font-size: clamp(40px, 5.4vw, 72px); margin-top: 22px; max-width: 14ch;">
          <?php echo wp_kses_post($section_title); ?>
        </h2>
      <?php endif; ?>
    </div>
    <div class="timeline__list">
      <?php foreach ($entries as $entry) :
        $year  = $entry['year']  ?? '';
        $title = $entry['title'] ?? '';
        $body  = $entry['body']  ?? '';
      ?>
        <article class="timeline__entry reveal">
          <div class="timeline__year display"><?php echo esc_html($year); ?></div>
          <div>
            <h3 class="display" style="font-size: 26px; margin: 0;"><?php echo esc_html($title); ?></h3>
            <?php if ($body) : ?>
              <p style="margin-top: 10px; color: var(--ink-2, #3d433d); font-size: 16px; line-height: 1.75;"><?php echo esc_html($body); ?></p>
            <?php endif; ?>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
