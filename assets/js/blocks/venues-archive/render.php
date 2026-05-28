<?php
$eyebrow      = $attributes['eyebrow']        ?? '';
$title        = $attributes['title']          ?? '';
$lead         = $attributes['lead']           ?? '';
$inq_title    = $attributes['inquiryTitle']   ?? '';
$inq_text     = $attributes['inquiryText']    ?? '';
$inq_cta_text = $attributes['inquiryCtaText'] ?? '';
$inq_cta_url  = $attributes['inquiryCtaUrl']  ?: home_url('/contact');

$venues = new WP_Query([
    'post_type'      => 'venue',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order date',
    'order'          => 'ASC',
]);
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'venues-archive-block']); ?>>

  <div class="venues-archive__header">
    <div class="shell">
      <?php if ($eyebrow) : ?>
        <div class="eyebrow reveal" style="margin-bottom: 22px;"><?php echo esc_html($eyebrow); ?></div>
      <?php endif; ?>
      <?php if ($title) : ?>
        <h1 class="display reveal reveal--lg venues-archive__heading"><?php echo wp_kses_post($title); ?></h1>
      <?php endif; ?>
      <?php if ($lead) : ?>
        <p class="reveal venues-archive__lead"><?php echo wp_kses_post($lead); ?></p>
      <?php endif; ?>
    </div>
  </div>

  <div class="shell venues-archive__stack">
    <?php $idx = 0; if ($venues->have_posts()) : while ($venues->have_posts()) : $venues->the_post();
      $vid      = get_the_ID();
      $flip     = ($idx % 2) === 1;
      $tagline  = function_exists('get_field') ? get_field('venue_tagline', $vid) : '';
      $capacity = function_exists('get_field') ? get_field('venue_capacity', $vid) : '';
      $location = function_exists('get_field') ? get_field('venue_location', $vid) : '';
      $layouts  = function_exists('get_field') ? get_field('venue_layouts', $vid) : '';
      $cta_text = function_exists('get_field') ? (get_field('venue_cta_text', $vid) ?: 'Inquire about ' . get_the_title()) : 'Inquire about ' . get_the_title();
      $cta_url  = function_exists('get_field') ? get_field('venue_cta_url', $vid) : '';
      if (!$cta_url) $cta_url = home_url('/contact');
      $blurb    = wp_strip_all_tags(get_the_excerpt());

      $eyebrow_parts = array_filter([$tagline ?: $location, $capacity]);
      $caps = array_filter(array_map('trim', explode(',', (string) $layouts)));
      $caps = array_slice($caps, 0, 3);
    ?>
      <article class="venues-archive__row reveal reveal--lg<?php echo $flip ? ' is-flipped' : ''; ?>">
        <div class="venues-archive__media">
          <a href="<?php the_permalink(); ?>" class="ph kb venues-archive__img" style="display:block; height: 540px;">
            <?php echo greensun_post_thumbnail_html($vid, 'full', '(max-width: 900px) 100vw, 50vw', 'venues-archive__img-el'); ?>
          </a>
          <div class="venues-archive__badge"><?php echo esc_html(str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT)); ?></div>
        </div>
        <div class="venues-archive__body">
          <?php if (!empty($eyebrow_parts)) : ?>
            <div class="eyebrow"><?php echo esc_html(implode(' · ', $eyebrow_parts)); ?></div>
          <?php endif; ?>
          <h2 class="display venues-archive__title">
            <a href="<?php the_permalink(); ?>" style="color: inherit; text-decoration: none;"><?php the_title(); ?></a>
          </h2>
          <?php if ($blurb) : ?>
            <p class="venues-archive__blurb"><?php echo esc_html($blurb); ?></p>
          <?php endif; ?>
          <?php if (!empty($caps)) : ?>
            <dl class="venues-archive__caps" style="grid-template-columns: repeat(<?php echo esc_attr(count($caps)); ?>, 1fr);">
              <?php foreach ($caps as $c) :
                $label = $c;
                $value = '';
                if (preg_match('/^(.*?)\s+(\d+\+?)$/', $c, $m)) {
                  $label = trim($m[1]);
                  $value = $m[2];
                }
              ?>
                <div>
                  <dt><?php echo esc_html($label); ?></dt>
                  <?php if ($value !== '') : ?>
                    <dd class="display"><?php echo esc_html($value); ?> pax</dd>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            </dl>
          <?php endif; ?>
          <div style="margin-top: 32px;">
            <a href="<?php echo esc_url($cta_url); ?>" class="btn btn--ghost">
              <span class="ripple"></span>
              <span><?php echo esc_html($cta_text); ?></span>
              <svg width="14" height="10" viewBox="0 0 22 8" fill="none" aria-hidden="true" style="margin-left: 8px;">
                <path d="M0 4 L20 4 M14 0 L20 4 L14 8" stroke="currentColor" stroke-width="1.4" fill="none"/>
              </svg>
            </a>
          </div>
        </div>
      </article>
    <?php $idx++; endwhile; else : ?>
      <p style="text-align:center; color: var(--ink-2, #3d433d);">No venues published yet.</p>
    <?php endif; wp_reset_postdata(); ?>
  </div>

  <?php if ($inq_title || $inq_text) : ?>
    <div class="venues-archive__inquiry" style="background: var(--forest, #1f4a3a); color: var(--ivory, #f7f6f0); padding: 90px 0;">
      <div class="shell venues-archive__inquiry-grid">
        <?php if ($inq_title) : ?>
          <h2 class="display reveal reveal--lg" style="font-size: clamp(36px, 5vw, 60px); color: var(--sun, #e8c46a); max-width: 14ch;"><?php echo wp_kses_post($inq_title); ?></h2>
        <?php endif; ?>
        <div>
          <?php if ($inq_text) : ?>
            <p class="reveal" style="font-size: 17px; line-height: 1.75; color: rgba(255,255,255,.78); max-width: 480px;"><?php echo wp_kses_post($inq_text); ?></p>
          <?php endif; ?>
          <?php if ($inq_cta_text) : ?>
            <div class="reveal" style="margin-top: 28px;">
              <a href="<?php echo esc_url($inq_cta_url); ?>" class="btn btn--sun">
                <span class="ripple"></span>
                <span><?php echo esc_html($inq_cta_text); ?></span>
                <svg width="14" height="10" viewBox="0 0 22 8" fill="none" aria-hidden="true" style="margin-left: 8px;">
                  <path d="M0 4 L20 4 M14 0 L20 4 L14 8" stroke="currentColor" stroke-width="1.4" fill="none"/>
                </svg>
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>

</section>
