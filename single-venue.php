<?php get_header(); ?>

<main id="site-main" class="site-main" role="main">

  <?php while (have_posts()) : the_post(); ?>

    <?php if (greensun_post_uses_blocks()) : // editor-built Gutenberg layout takes over ?>
      <?php the_content(); ?>
      <?php continue; endif; ?>

  <?php
    $vid       = get_the_ID();
    $tagline   = function_exists('get_field') ? get_field('venue_tagline', $vid) : '';
    $capacity  = function_exists('get_field') ? get_field('venue_capacity', $vid) : '';
    $area      = function_exists('get_field') ? get_field('venue_area', $vid) : '';
    $location  = function_exists('get_field') ? get_field('venue_location', $vid) : '';
    $layouts   = function_exists('get_field') ? get_field('venue_layouts', $vid) : '';
    $features  = function_exists('get_field') ? get_field('venue_features', $vid) : [];
    $brochure  = function_exists('get_field') ? get_field('venue_brochure', $vid) : '';
    $cta_text  = function_exists('get_field') ? (get_field('venue_cta_text', $vid) ?: 'Send an inquiry') : 'Send an inquiry';
    $cta_url   = function_exists('get_field') ? get_field('venue_cta_url', $vid) : '';
    if (!$cta_url) $cta_url = home_url('/contact');
    $gallery   = function_exists('get_field') ? get_field('venue_gallery', $vid) : [];
    $thumb     = get_the_post_thumbnail_url($vid, 'full');
    $phone     = function_exists('greensun_setting') ? greensun_setting('phone', '') : '';

    $eyebrow_parts = array_filter([$tagline, $location]);
    $eyebrow_text  = implode(' · ', $eyebrow_parts);
    $caps = array_filter(array_map('trim', explode(',', (string) $layouts)));

    $excerpt_lead = wp_strip_all_tags(get_the_excerpt());
    $first_sentence = $excerpt_lead ? rtrim(preg_split('/[\.\!\?]/', $excerpt_lead)[0], '.') . '.' : '';
  ?>

    <section class="gs-page-hero single-venue__hero" style="min-height: 78vh; min-height: 560px;">
      <div class="gs-page-hero__media kb">
        <?php if ($thumb) : ?>
          <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" />
        <?php endif; ?>
      </div>
      <div class="gs-page-hero__scrim" style="background: linear-gradient(to bottom, rgba(13,42,32,.45), rgba(13,42,32,.85));"></div>
      <div class="shell gs-page-hero__content">
        <?php if ($eyebrow_text) : ?>
          <div class="eyebrow reveal" style="color: var(--sun, #e8c46a);"><?php echo esc_html($eyebrow_text); ?></div>
        <?php endif; ?>
        <h1 class="display reveal reveal--lg" style="font-size: clamp(54px, 8vw, 120px); margin-top: 22px; font-weight: 500;">
          <?php the_title(); ?>
        </h1>
      </div>
    </section>

    <section class="section--tight" style="padding-top: 90px;">
      <div class="shell single-venue__layout" style="display:grid; grid-template-columns: 1.4fr 1fr; gap: 80px; align-items: start;">

        <div class="single-venue__body">
          <?php if ($first_sentence) : ?>
            <h2 class="display reveal" style="font-size: 36px; max-width: 24ch; margin: 0;">
              <?php echo esc_html($first_sentence); ?>
            </h2>
          <?php endif; ?>

          <?php if (get_the_content()) : ?>
            <div class="reveal" style="margin-top: 24px; color: var(--ink-2, #3d433d); font-size: 16.5px; line-height: 1.85;">
              <?php the_content(); ?>
            </div>
          <?php endif; ?>

          <?php
            $specs = array_filter([
              $capacity ? ['Capacity',   $capacity] : null,
              $area     ? ['Floor area', $area]     : null,
              $location ? ['Location',   $location] : null,
            ]);
            $layout_specs = array_slice($caps, 0, 6 - count($specs));
            $col_count = max(1, min(3, count($specs) + count($layout_specs)));
            if ($specs || $layout_specs) :
          ?>
            <div class="reveal single-venue__specs" style="margin-top: 50px; display:grid; grid-template-columns: repeat(<?php echo esc_attr($col_count); ?>, 1fr); gap: 20px;">
              <?php foreach ($specs as $spec) : list($k, $v) = $spec; ?>
                <div style="border-top: 1px solid var(--line, #ede9d9); padding-top: 14px;">
                  <div style="font-family: var(--font-mono, 'JetBrains Mono', monospace); color: var(--mute, #7b817b); font-size: 12px;"><?php echo esc_html($k); ?></div>
                  <div style="margin-top: 4px; font-weight: 500;"><?php echo esc_html($v); ?></div>
                </div>
              <?php endforeach; ?>
              <?php foreach ($layout_specs as $layout) : ?>
                <div style="border-top: 1px solid var(--line, #ede9d9); padding-top: 14px;">
                  <div style="font-family: var(--font-mono, 'JetBrains Mono', monospace); color: var(--mute, #7b817b); font-size: 12px;">Layout</div>
                  <div style="margin-top: 4px; font-weight: 500;"><?php echo esc_html($layout); ?></div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <?php if (!empty($features) && is_array($features)) : ?>
            <div class="reveal" style="margin-top: 56px;">
              <div class="eyebrow">Features</div>
              <ul style="list-style:none; padding:0; margin: 18px 0 0; display:grid; grid-template-columns: repeat(2, 1fr); gap: 12px 24px;">
                <?php foreach ($features as $f) :
                  $label = is_array($f) ? ($f['text'] ?? '') : (string) $f;
                  if (!$label) continue;
                ?>
                  <li style="display:flex; gap: 12px; align-items: baseline;">
                    <span aria-hidden="true" style="color: var(--moss, #527a55);">·</span>
                    <span style="line-height: 1.6;"><?php echo esc_html($label); ?></span>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>
        </div>

        <aside class="single-venue__sidebar reveal" style="position: sticky; top: 100px;">
          <div style="background: var(--paper, #f8f5e9); border:1px solid var(--line, #ede9d9); border-radius: 4px; padding: 32px; box-shadow: 0 24px 40px -28px rgba(0,0,0,.15);">
            <div style="font-family: var(--font-mono, 'JetBrains Mono', monospace); color: var(--mute, #7b817b); font-size: 12px;">Plan your event</div>
            <div class="display" style="font-size: 32px; color: var(--forest, #1f4a3a); margin-top: 4px; line-height: 1.1;">
              <?php echo esc_html(get_the_title()); ?>
            </div>
            <?php if ($capacity) : ?>
              <div style="font-family: var(--font-mono, 'JetBrains Mono', monospace); color: var(--mute, #7b817b); font-size: 12px; margin-top: 6px;"><?php echo esc_html($capacity); ?></div>
            <?php endif; ?>

            <div style="height: 1px; background: var(--line, #ede9d9); margin: 28px 0;"></div>

            <ul style="list-style:none; padding:0; margin:0; display:grid; gap: 12px; color: var(--ink-2, #3d433d); font-size: 14.5px;">
              <?php
                $perks = array_filter([
                  'Dedicated event coordinator',
                  'A/V and lighting on request',
                  'In-house catering team',
                  'Walk-throughs by appointment',
                ]);
                foreach ($perks as $perk) :
              ?>
                <li style="display:flex; gap: 12px; align-items: center;">
                  <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true" style="flex: 0 0 18px;">
                    <circle cx="9" cy="9" r="9" fill="var(--moss, #527a55)" opacity=".15"/>
                    <path d="M5 9 L8 12 L13 6" stroke="var(--moss, #527a55)" stroke-width="1.6" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  <span><?php echo esc_html($perk); ?></span>
                </li>
              <?php endforeach; ?>
            </ul>

            <a href="<?php echo esc_url($cta_url); ?>" class="btn btn--sun btn--lg" style="margin-top: 28px; width:100%; justify-content:center;">
              <span class="ripple"></span>
              <span><?php echo esc_html($cta_text); ?></span>
              <svg width="14" height="10" viewBox="0 0 22 8" fill="none" aria-hidden="true" style="margin-left: 8px;">
                <path d="M0 4 L20 4 M14 0 L20 4 L14 8" stroke="currentColor" stroke-width="1.4" fill="none"/>
              </svg>
            </a>

            <?php if ($brochure) : ?>
              <a href="<?php echo esc_url($brochure); ?>" target="_blank" rel="noopener" class="btn btn--ghost" style="margin-top: 12px; width:100%; justify-content:center;">
                <span class="ripple"></span>
                <span>Download brochure</span>
              </a>
            <?php endif; ?>

            <?php if ($phone) : ?>
              <div style="margin-top: 14px; text-align: center; font-size: 12px; color: var(--mute, #7b817b);">
                Or call <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>" style="color: var(--forest, #1f4a3a); text-decoration: underline;"><?php echo esc_html($phone); ?></a>
              </div>
            <?php endif; ?>
          </div>
        </aside>

      </div>
    </section>

    <?php if (!empty($gallery) && is_array($gallery)) : ?>
      <section style="padding: 40px 0 120px;">
        <div class="shell">
          <div class="eyebrow reveal" style="margin-bottom: 20px;">The space</div>
          <div class="single-venue__gallery" style="display:grid; grid-template-columns: 2fr 1fr 1fr; gap: 14px;">
            <?php
              $count = 0;
              foreach ($gallery as $image) :
                if ($count >= 4) break;
                $url = is_array($image) ? ($image['sizes']['large'] ?? $image['url'] ?? '') : '';
                $alt = is_array($image) ? ($image['alt'] ?? '') : '';
                if (!$url) continue;
                $is_hero = $count === 0;
            ?>
              <div class="ph reveal<?php echo $is_hero ? ' kb' : ''; ?>" style="height: <?php echo $is_hero ? '520px; grid-row: span 2;' : '253px;'; ?>">
                <img src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr($alt); ?>" style="width:100%; height:100%; object-fit:cover;" />
              </div>
            <?php $count++; endforeach; ?>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <?php
    $more = new WP_Query([
        'post_type'      => 'venue',
        'posts_per_page' => 3,
        'post__not_in'   => [$vid],
        'orderby'        => 'rand',
    ]);
    if ($more->have_posts()) :
    ?>
      <section class="section" style="background: var(--paper, #f8f5e9);">
        <div class="shell">
          <header style="text-align:center; max-width: 720px; margin: 0 auto 56px;">
            <div class="eyebrow reveal">Also at Greensun</div>
            <h2 class="display reveal" style="font-size: clamp(36px, 5vw, 64px); margin-top: 14px;">Other <em>venues</em>.</h2>
          </header>
          <div style="display:grid; grid-template-columns: repeat(<?php echo esc_attr(min(3, $more->post_count)); ?>, 1fr); gap: 28px;">
            <?php while ($more->have_posts()) : $more->the_post();
              $m_id    = get_the_ID();
              $m_cap   = function_exists('get_field') ? get_field('venue_capacity', $m_id) : '';
              $m_thumb = get_the_post_thumbnail_url($m_id, 'large');
            ?>
              <article class="gs-card reveal" style="background:#fff; border-radius: var(--radius-lg, 14px); overflow:hidden; border:1px solid var(--line, #ede9d9);">
                <a href="<?php the_permalink(); ?>" class="ph kb" style="aspect-ratio: 4 / 3; display:block;">
                  <?php if ($m_thumb) : ?><img src="<?php echo esc_url($m_thumb); ?>" alt="" style="width:100%; height:100%; object-fit:cover;" /><?php endif; ?>
                </a>
                <div style="padding: 24px;">
                  <?php if ($m_cap) : ?>
                    <div class="eyebrow" style="margin-bottom: 10px;"><?php echo esc_html($m_cap); ?></div>
                  <?php endif; ?>
                  <h3 class="display" style="font-size: 28px; margin: 0;">
                    <a href="<?php the_permalink(); ?>" style="color:inherit; text-decoration:none;"><?php the_title(); ?></a>
                  </h3>
                </div>
              </article>
            <?php endwhile; wp_reset_postdata(); ?>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <section class="single-venue__inquiry" style="background: var(--forest, #1f4a3a); color: var(--ivory, #f7f6f0); padding: 90px 0;">
      <div class="shell single-venue__inquiry-grid" style="display:grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items:center;">
        <h2 class="display reveal reveal--lg" style="font-size: clamp(36px, 5vw, 60px); color: var(--sun, #e8c46a); max-width: 14ch;">
          Planning <em>something special?</em>
        </h2>
        <div>
          <p class="reveal" style="font-size: 17px; line-height: 1.75; color: rgba(255,255,255,.78); max-width: 480px;">
            Tell us about your event — guest count, dates, the feel you're after. Our team will send back venue options, pricing, and a layout sketch within one business day.
          </p>
          <div class="reveal" style="margin-top: 28px; display:flex; gap: 14px; flex-wrap: wrap;">
            <a href="<?php echo esc_url($cta_url); ?>" class="btn btn--sun btn--lg">
              <span class="ripple"></span>
              <span>Send an inquiry</span>
              <svg width="14" height="10" viewBox="0 0 22 8" fill="none" aria-hidden="true" style="margin-left: 8px;">
                <path d="M0 4 L20 4 M14 0 L20 4 L14 8" stroke="currentColor" stroke-width="1.4" fill="none"/>
              </svg>
            </a>
            <?php if ($brochure) : ?>
              <a href="<?php echo esc_url($brochure); ?>" target="_blank" rel="noopener" class="btn btn--ghost btn--light btn--lg">
                <span class="ripple"></span>
                <span>Download brochure</span>
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </section>

  <?php endwhile; ?>

</main>

<style>
  @media (max-width: 1000px) {
    .single-venue__layout { grid-template-columns: 1fr !important; gap: 48px !important; }
    .single-venue__sidebar { position: static !important; }
    .single-venue__specs { grid-template-columns: 1fr 1fr !important; }
    .single-venue__gallery { grid-template-columns: 1fr !important; }
    .single-venue__gallery .ph { grid-row: span 1 !important; height: 280px !important; }
    .single-venue__inquiry-grid { grid-template-columns: 1fr !important; gap: 32px !important; }
  }
</style>

<?php get_footer(); ?>
