<?php get_header(); ?>

<main class="site-main">

  <?php while (have_posts()) : the_post();
    $eid       = get_the_ID();
    $start     = function_exists('get_field') ? get_field('event_start', $eid) : '';
    $end       = function_exists('get_field') ? get_field('event_end', $eid) : '';
    $time      = function_exists('get_field') ? get_field('event_time', $eid) : '';
    $location  = function_exists('get_field') ? get_field('event_location', $eid) : '';
    $capacity  = function_exists('get_field') ? get_field('event_capacity', $eid) : null;
    $price     = function_exists('get_field') ? get_field('event_price', $eid) : null;
    $currency  = function_exists('get_field') ? (get_field('event_currency', $eid) ?: 'USD') : 'USD';
    $cta_text  = function_exists('get_field') ? (get_field('event_cta_text', $eid) ?: 'Reserve') : 'Reserve';
    $cta_url   = function_exists('get_field') ? get_field('event_cta_url', $eid) : '';
    if (!$cta_url) $cta_url = '#reserve';
    $gallery   = function_exists('get_field') ? get_field('event_gallery', $eid) : [];
    $thumb     = get_the_post_thumbnail_url($eid, 'full');
    $start_fmt = $start ? date_i18n('F j, Y', strtotime($start)) : '';
    $end_fmt   = $end   ? date_i18n('F j, Y', strtotime($end))   : '';
    $date_line = $start_fmt . ($end_fmt && $end_fmt !== $start_fmt ? ' – ' . $end_fmt : '');
  ?>

    <section class="gs-hero" style="position:relative; min-height: 60vh; display:flex; align-items:flex-end; overflow:hidden;">
      <?php if ($thumb) : ?>
        <div class="kb" style="position:absolute; inset:0; z-index:0;">
          <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" style="width:100%; height:100%; object-fit:cover;" />
          <div style="position:absolute; inset:0; background: linear-gradient(180deg, rgba(15,32,24,0) 30%, rgba(15,32,24,0.65) 100%);"></div>
        </div>
      <?php else : ?>
        <div style="position:absolute; inset:0; background: var(--forest, #1f4a3a); z-index:0;"></div>
      <?php endif; ?>
      <div class="shell" style="position:relative; z-index:1; padding-block: 80px; color: var(--ivory, #f7f6f0);">
        <?php if ($date_line) : ?>
          <div class="eyebrow reveal" style="color: var(--sun, #e8c46a);"><?php echo esc_html($date_line); ?></div>
        <?php endif; ?>
        <h1 class="display reveal" style="font-size: clamp(48px, 7vw, 96px); margin-top: 16px; max-width: 20ch;">
          <?php the_title(); ?>
        </h1>
      </div>
    </section>

    <section class="section">
      <div class="shell" style="display:grid; grid-template-columns: 1.4fr 1fr; gap: 80px; align-items:start;">

        <div class="event-detail__body">
          <?php if (get_the_content()) : ?>
            <div class="reveal" style="font-size: 18px; line-height: 1.8; color: var(--ink, #1a1f1a); max-width: 60ch;">
              <?php the_content(); ?>
            </div>
          <?php endif; ?>

          <?php if (!empty($gallery) && is_array($gallery)) : ?>
            <div class="reveal" style="margin-top: 56px; display:grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
              <?php foreach ($gallery as $i => $image) :
                $url = is_array($image) ? ($image['sizes']['large'] ?? $image['url'] ?? '') : '';
                $alt = is_array($image) ? ($image['alt'] ?? '') : '';
                if (!$url) continue;
                $is_wide = ($i % 3 === 0);
              ?>
                <div class="ph" style="aspect-ratio: <?php echo $is_wide ? '16 / 10' : '4 / 5'; ?>; <?php echo $is_wide ? 'grid-column: 1 / -1;' : ''; ?>">
                  <img src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr($alt); ?>" style="width:100%; height:100%; object-fit:cover;" />
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

        <aside class="event-detail__sidebar reveal" style="position: sticky; top: 100px;">
          <div style="background:#fff; border:1px solid var(--line, #ede9d9); border-radius: var(--radius-lg, 14px); padding: 32px;">
            <div class="eyebrow">Details</div>
            <dl style="margin-top: 18px; display:grid; grid-template-columns: auto 1fr; gap: 14px 18px; font-size: 14px;">
              <?php if ($date_line) : ?>
                <dt style="color: var(--mute, #7b817b); letter-spacing: 0.08em; text-transform: uppercase; font-size: 11px;">When</dt>
                <dd style="margin: 0; color: var(--ink, #1a1f1a);"><?php echo esc_html($date_line); ?><?php echo $time ? '<br><span style="color:var(--ink-2,#3d433d);">' . esc_html($time) . '</span>' : ''; ?></dd>
              <?php endif; ?>
              <?php if ($location) : ?>
                <dt style="color: var(--mute, #7b817b); letter-spacing: 0.08em; text-transform: uppercase; font-size: 11px;">Where</dt>
                <dd style="margin: 0;"><?php echo esc_html($location); ?></dd>
              <?php endif; ?>
              <?php if ($capacity) : ?>
                <dt style="color: var(--mute, #7b817b); letter-spacing: 0.08em; text-transform: uppercase; font-size: 11px;">Seats</dt>
                <dd style="margin: 0;"><?php echo esc_html(sprintf(_n('%d guest', '%d guests', (int)$capacity, 'greensun-hotel'), (int)$capacity)); ?></dd>
              <?php endif; ?>
              <dt style="color: var(--mute, #7b817b); letter-spacing: 0.08em; text-transform: uppercase; font-size: 11px;">Price</dt>
              <dd style="margin: 0; font-family: var(--font-display, 'Cormorant Garamond', serif); font-size: 22px;">
                <?php echo $price ? esc_html($currency . ' ' . number_format_i18n((float) $price)) . ' <span style="font-family:inherit; font-size: 12px; letter-spacing: 0.14em; text-transform: uppercase; color: var(--ink-2, #3d433d);">/ guest</span>' : 'Complimentary'; ?>
              </dd>
            </dl>

            <a id="reserve" href="<?php echo esc_url($cta_url); ?>" class="btn btn--sun" style="margin-top: 28px; width:100%; justify-content:center;">
              <span class="ripple"></span>
              <span><?php echo esc_html($cta_text); ?></span>
            </a>
          </div>
        </aside>

      </div>
    </section>

  <?php endwhile; ?>

</main>

<style>
  @media (max-width: 1000px) {
    .single-event main .shell { grid-template-columns: 1fr !important; }
    .event-detail__sidebar { position: static !important; }
  }
</style>

<?php get_footer(); ?>
