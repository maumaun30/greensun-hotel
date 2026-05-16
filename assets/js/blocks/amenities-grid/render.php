<?php
$eyebrow       = $attributes['eyebrow']      ?? '';
$section_title = $attributes['sectionTitle'] ?? '';
$columns       = (int) ($attributes['columns'] ?? 4);
$columns       = max(2, min(6, $columns));
$items         = $attributes['items'] ?? [];
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'section greensun-amenities-grid']); ?>>
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
    <div class="amenities-list" style="display:grid; grid-template-columns: repeat(<?php echo esc_attr($columns); ?>, 1fr); gap: 40px 32px;">
      <?php foreach ($items as $item) :
        $svg_id  = (int) ($item['svgId'] ?? 0);
        $svg_url = $item['svgUrl'] ?? '';
        $label   = $item['label']  ?? '';
      ?>
        <div class="amenity reveal" style="text-align:center;">
          <div class="amenity__icon" style="width:72px; height:72px; margin-inline:auto; border-radius:999px; background: var(--bone, #ede9d9); display:flex; align-items:center; justify-content:center; margin-bottom:18px; color: var(--forest, #1f4a3a);">
            <?php if ($svg_url && function_exists('greensun_hotel_render_svg_icon')) :
              greensun_hotel_render_svg_icon($svg_id, $svg_url, 'amenity__svg');
            elseif ($svg_url) : ?>
              <img src="<?php echo esc_url($svg_url); ?>" alt="" style="width:34px; height:34px; object-fit:contain;" />
            <?php endif; ?>
          </div>
          <div class="amenity__label" style="font-size: 15px; color: var(--ink, #1a1f1a); line-height: 1.5;">
            <?php echo esc_html($label); ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
