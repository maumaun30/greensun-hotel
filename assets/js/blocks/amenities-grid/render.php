<?php
$eyebrow       = $attributes['eyebrow']      ?? '';
$section_title = $attributes['sectionTitle'] ?? '';
$columns       = (int) ($attributes['columns'] ?? 4);
$columns       = max(2, min(6, $columns));
$items         = $attributes['items'] ?? [];
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'section greensun-amenities-grid']); ?>>
  <div class="shell">
    <header class="gs-amenities__head">
      <?php if ($eyebrow) : ?>
        <div class="eyebrow reveal" style="justify-content:center; display:inline-flex;"><?php echo esc_html($eyebrow); ?></div>
      <?php endif; ?>
      <?php if ($section_title) : ?>
        <h2 class="display reveal gs-amenities__title">
          <?php echo wp_kses_post($section_title); ?>
        </h2>
      <?php endif; ?>
      <div class="linedot reveal" aria-hidden="true">
        <span></span><span></span><span></span><span></span><span></span>
      </div>
    </header>

    <div class="gs-amenities__grid" style="--gs-cols: <?php echo esc_attr($columns); ?>;">
      <?php foreach ($items as $item) :
        $svg_id  = (int) ($item['svgId'] ?? 0);
        $svg_url = $item['svgUrl'] ?? '';
        $label   = $item['label']  ?? '';
      ?>
        <div class="gs-amenity reveal">
          <div class="gs-amenity__glyph">
            <?php if ($svg_url && function_exists('greensun_hotel_render_svg_icon')) :
              greensun_hotel_render_svg_icon($svg_id, $svg_url, 'gs-amenity__svg');
            elseif ($svg_url) : ?>
              <img src="<?php echo esc_url($svg_url); ?>" alt="" />
            <?php endif; ?>
          </div>
          <div class="gs-amenity__label"><?php echo esc_html($label); ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
