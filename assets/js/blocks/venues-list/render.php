<?php
$venues = $attributes['venues'] ?? [];
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'section greensun-venues-list']); ?>>
  <div class="shell">
    <div class="venues-list__stack">
      <?php foreach ($venues as $idx => $v) :
        $flip       = ($idx % 2) === 1;
        $name       = $v['name']       ?? '';
        $area       = $v['area']       ?? '';
        $capacity   = $v['capacity']   ?? '';
        $blurb      = $v['blurb']      ?? '';
        $image_url  = $v['imageUrl']   ?? '';
        $image_alt  = $v['imageAlt']   ?? '';
        $caps       = $v['capacities'] ?? [];
        $cta_text   = $v['ctaText']    ?? '';
        $cta_url    = $v['ctaUrl']     ?? '#';
      ?>
        <article class="venues-list__row reveal reveal--lg<?php echo $flip ? ' is-flipped' : ''; ?>">
          <div class="venues-list__media">
            <div class="ph kb" style="height: 540px;">
              <?php if ($image_url) : ?>
                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" style="width:100%; height:100%; object-fit:cover;" />
              <?php endif; ?>
            </div>
            <div class="venues-list__badge"><?php echo esc_html(str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT)); ?></div>
          </div>
          <div class="venues-list__body">
            <?php if ($area || $capacity) : ?>
              <div class="eyebrow"><?php echo esc_html(trim($area . ($area && $capacity ? ' · ' : '') . $capacity)); ?></div>
            <?php endif; ?>
            <?php if ($name) : ?>
              <h2 class="display" style="font-size: clamp(40px, 5vw, 64px); margin-top: 14px; max-width: 12ch;"><?php echo esc_html($name); ?></h2>
            <?php endif; ?>
            <?php if ($blurb) : ?>
              <p style="margin-top: 22px; color: var(--ink-2, #3d433d); font-size: 16.5px; line-height: 1.8; max-width: 520px;"><?php echo esc_html($blurb); ?></p>
            <?php endif; ?>
            <?php if (!empty($caps)) :
              $col_count = max(1, min(4, count($caps)));
            ?>
              <dl class="venues-list__caps" style="grid-template-columns: repeat(<?php echo esc_attr($col_count); ?>, 1fr);">
                <?php foreach ($caps as $c) :
                  $layout = $c['layout'] ?? '';
                  $value  = $c['value']  ?? '';
                ?>
                  <div>
                    <dt style="font-family: var(--font-mono, 'JetBrains Mono', monospace); font-size: 12px; color: var(--mute, #7b817b); margin: 0;">
                      <?php echo esc_html($layout); ?>
                    </dt>
                    <dd class="display" style="font-size: 24px; color: var(--forest, #1f4a3a); margin: 4px 0 0;">
                      <?php echo esc_html($value); ?><?php echo ($value !== '' && ctype_digit((string) $value)) ? ' pax' : ''; ?>
                    </dd>
                  </div>
                <?php endforeach; ?>
              </dl>
            <?php endif; ?>
            <?php if ($cta_text) : ?>
              <div style="margin-top: 32px;">
                <a href="<?php echo esc_url($cta_url); ?>" class="btn btn--ghost">
                  <span class="ripple"></span>
                  <span><?php echo esc_html($cta_text); ?></span>
                </a>
              </div>
            <?php endif; ?>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
