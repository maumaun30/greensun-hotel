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
    $gallery   = function_exists('get_field') ? get_field('venue_gallery', $vid) : [];
    $capacity_layouts = function_exists('get_field') ? get_field('venue_capacity_layouts', $vid) : [];
    $thumb     = get_the_post_thumbnail_url($vid, 'full');
    $phone     = function_exists('greensun_setting') ? greensun_setting('phone', '') : '';

    // Deep-link CTAs into the Contact form with this space pre-filled.
    $space_name  = get_the_title();
    $inquire_url = $cta_url ?: add_query_arg(['subject' => 'Events inquiry', 'space' => $space_name], home_url('/contact'));
    $ocular_url  = add_query_arg(['subject' => 'Book an ocular visit', 'space' => $space_name], home_url('/contact'));

    $eyebrow_parts = array_filter([$tagline, $location]);
    $eyebrow_text  = implode(' · ', $eyebrow_parts);

    // Structured capacity-by-layout rows (preferred); fall back to legacy text tiles.
    $cl_rows = [];
    if (!empty($capacity_layouts) && is_array($capacity_layouts)) {
        foreach ($capacity_layouts as $row) {
            $lt  = $row['layout']   ?? '';
            $cap = $row['capacity'] ?? '';
            if ($lt === '' && $cap === '') continue;
            $cl_rows[] = ['layout' => $lt, 'capacity' => $cap];
        }
    }
    $caps = $cl_rows ? [] : array_filter(array_map('trim', explode(',', (string) $layouts)));
    $layout_labels = [
        'theatre' => 'Theatre', 'banquet' => 'Banquet', 'classroom' => 'Classroom',
        'boardroom' => 'Boardroom', 'ushape' => 'U-Shape', 'cocktail' => 'Cocktail / Reception', 'cabaret' => 'Cabaret',
    ];

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

          <?php if (!empty($cl_rows)) : ?>
            <div class="reveal single-venue__captable-wrap" style="margin-top: 56px;">
              <div class="eyebrow" style="margin-bottom: 18px;">Capacity by layout</div>
              <table class="gs-captable">
                <tbody>
                  <?php foreach ($cl_rows as $row) :
                    $glyph = function_exists('greensun_layout_glyph') ? greensun_layout_glyph((string) $row['layout']) : '';
                    $label = $layout_labels[$row['layout']] ?? ucfirst((string) $row['layout']);
                  ?>
                    <tr>
                      <td class="gs-captable__glyph"><?php echo $glyph; // safe: theme-generated SVG ?></td>
                      <th scope="row" class="gs-captable__name"><?php echo esc_html($label); ?></th>
                      <td class="gs-captable__cap"><?php echo $row['capacity'] !== '' ? esc_html(number_format_i18n((int) $row['capacity']) . ' guests') : '—'; ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
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

            <a href="<?php echo esc_url($inquire_url); ?>" class="btn btn--sun btn--lg" style="margin-top: 28px; width:100%; justify-content:center;">
              <span class="ripple"></span>
              <span><?php echo esc_html($cta_text); ?></span>
              <svg width="14" height="10" viewBox="0 0 22 8" fill="none" aria-hidden="true" style="margin-left: 8px;">
                <path d="M0 4 L20 4 M14 0 L20 4 L14 8" stroke="currentColor" stroke-width="1.4" fill="none"/>
              </svg>
            </a>

            <a href="<?php echo esc_url($ocular_url); ?>" class="btn btn--ghost" style="margin-top: 12px; width:100%; justify-content:center;">
              <span class="ripple"></span>
              <span>Book an ocular visit</span>
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
          <div class="single-venue__gallery" style="display:grid; grid-template-columns: 2fr 1fr 1fr; gap: 14px;" data-venue-gallery>
            <?php
              $count = 0;
              foreach ($gallery as $image) :
                if ($count >= 4) break;
                $url  = is_array($image) ? ($image['sizes']['large'] ?? $image['url'] ?? '') : '';
                $full = is_array($image) ? ($image['sizes']['large'] ?? $image['url'] ?? '') : '';
                $alt  = is_array($image) ? ($image['alt'] ?? '') : '';
                if (!$url) continue;
                $is_hero = $count === 0;
            ?>
              <button type="button" class="ph reveal<?php echo $is_hero ? ' kb' : ''; ?> single-venue__gallery-item" style="height: <?php echo $is_hero ? '520px; grid-row: span 2;' : '253px;'; ?>; padding:0; border:0; cursor:zoom-in; display:block;" data-full="<?php echo esc_url($full); ?>" aria-label="<?php echo esc_attr($alt ?: __('View photo', 'greensun-hotel')); ?>">
                <img src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr($alt); ?>" style="width:100%; height:100%; object-fit:cover;" />
              </button>
            <?php $count++; endforeach; ?>
          </div>
        </div>
      </section>

      <div class="gs-lightbox" data-venue-lightbox hidden aria-hidden="true">
        <button type="button" class="gs-lightbox__close" data-lightbox-close aria-label="<?php esc_attr_e('Close', 'greensun-hotel'); ?>">×</button>
        <button type="button" class="gs-lightbox__nav gs-lightbox__nav--prev" data-lightbox-prev aria-label="<?php esc_attr_e('Previous', 'greensun-hotel'); ?>">‹</button>
        <img class="gs-lightbox__img" data-lightbox-img src="" alt="" />
        <button type="button" class="gs-lightbox__nav gs-lightbox__nav--next" data-lightbox-next aria-label="<?php esc_attr_e('Next', 'greensun-hotel'); ?>">›</button>
      </div>
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

    <?php
    // "Next space" link — the following venue in menu/title order, wrapping around.
    $all_venues = get_posts([
        'post_type'      => 'venue',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order title',
        'order'          => 'ASC',
        'fields'         => 'ids',
    ]);
    $next_id = 0;
    if (count($all_venues) > 1) {
        $pos = array_search($vid, $all_venues, true);
        if ($pos !== false) {
            $next_id = $all_venues[($pos + 1) % count($all_venues)];
        }
    }
    if ($next_id) :
      $next_thumb = get_the_post_thumbnail_url($next_id, 'large');
    ?>
      <section class="single-venue__next">
        <a class="single-venue__next-link reveal" href="<?php echo esc_url(get_permalink($next_id)); ?>">
          <span class="single-venue__next-media ph">
            <?php if ($next_thumb) : ?><img src="<?php echo esc_url($next_thumb); ?>" alt="" loading="lazy" /><?php endif; ?>
          </span>
          <span class="single-venue__next-body">
            <span class="eyebrow" style="color: var(--sun, #e8c46a);">Next space</span>
            <span class="display single-venue__next-title"><?php echo esc_html(get_the_title($next_id)); ?></span>
          </span>
          <svg class="single-venue__next-arrow" width="40" height="12" viewBox="0 0 40 12" fill="none" aria-hidden="true">
            <path d="M0 6 H37 M31 1 L37 6 L31 11" stroke="currentColor" stroke-width="1.4"/>
          </svg>
        </a>
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
            <a href="<?php echo esc_url($inquire_url); ?>" class="btn btn--sun btn--lg">
              <span class="ripple"></span>
              <span>Send an inquiry</span>
              <svg width="14" height="10" viewBox="0 0 22 8" fill="none" aria-hidden="true" style="margin-left: 8px;">
                <path d="M0 4 L20 4 M14 0 L20 4 L14 8" stroke="currentColor" stroke-width="1.4" fill="none"/>
              </svg>
            </a>
            <a href="<?php echo esc_url($ocular_url); ?>" class="btn btn--ghost btn--light btn--lg">
              <span class="ripple"></span>
              <span>Book an ocular visit</span>
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
  /* ── Capacity-by-layout table ── */
  .gs-captable { width: 100%; border-collapse: collapse; }
  .gs-captable tr { border-top: 1px solid var(--line, #ede9d9); }
  .gs-captable tr:last-child { border-bottom: 1px solid var(--line, #ede9d9); }
  .gs-captable td, .gs-captable th { padding: 16px 8px; text-align: left; vertical-align: middle; }
  .gs-captable__glyph { width: 44px; color: var(--moss, #527a55); }
  .gs-captable__glyph svg { display: block; }
  .gs-captable__name { font-weight: 500; font-size: 16px; }
  .gs-captable__cap {
    text-align: right;
    font-family: var(--font-mono, 'JetBrains Mono', monospace);
    color: var(--ink-2, #3d433d);
    white-space: nowrap;
  }

  /* ── "Next space" link ── */
  .single-venue__next { border-top: 1px solid var(--line, #ede9d9); }
  .single-venue__next-link {
    display: flex; align-items: center; gap: 28px;
    max-width: var(--max, 1320px); margin: 0 auto; padding: 36px 32px;
    color: var(--ink, #1a1f1a); text-decoration: none;
    transition: background 300ms cubic-bezier(.16,1,.3,1);
  }
  .single-venue__next-link:hover { background: var(--paper, #f8f5e9); }
  .single-venue__next-media { width: 120px; height: 80px; border-radius: 6px; overflow: hidden; flex: 0 0 auto; }
  .single-venue__next-media img { width: 100%; height: 100%; object-fit: cover; }
  .single-venue__next-body { display: flex; flex-direction: column; gap: 8px; flex: 1; }
  .single-venue__next-title { font-size: clamp(28px, 3.5vw, 44px); }
  .single-venue__next-arrow { color: var(--forest, #1f4a3a); flex: 0 0 auto; transition: transform 350ms cubic-bezier(.16,1,.3,1); }
  .single-venue__next-link:hover .single-venue__next-arrow { transform: translateX(8px); }

  /* ── Gallery lightbox ── */
  .gs-lightbox {
    position: fixed; inset: 0; z-index: 1000;
    background: rgba(8, 24, 18, 0.92);
    display: flex; align-items: center; justify-content: center;
    padding: 5vw;
    animation: gsLbFade 250ms ease;
  }
  .gs-lightbox[hidden] { display: none; }
  @keyframes gsLbFade { from { opacity: 0; } to { opacity: 1; } }
  .gs-lightbox__img { max-width: 90vw; max-height: 86vh; object-fit: contain; border-radius: 4px; }
  .gs-lightbox__close {
    position: absolute; top: 22px; right: 28px;
    width: 48px; height: 48px; border: 0; background: none;
    color: var(--ivory, #f7f6f0); font-size: 34px; line-height: 1; cursor: pointer;
  }
  .gs-lightbox__nav {
    position: absolute; top: 50%; transform: translateY(-50%);
    width: 56px; height: 56px; border: 0; border-radius: 50%;
    background: rgba(255,255,255,.12); color: var(--ivory, #f7f6f0);
    font-size: 28px; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background 200ms ease;
  }
  .gs-lightbox__nav:hover { background: rgba(255,255,255,.24); }
  .gs-lightbox__nav--prev { left: 24px; }
  .gs-lightbox__nav--next { right: 24px; }

  @media (max-width: 1000px) {
    .single-venue__layout { grid-template-columns: 1fr !important; gap: 48px !important; }
    .single-venue__sidebar { position: static !important; }
    .single-venue__specs { grid-template-columns: 1fr 1fr !important; }
    .single-venue__gallery { grid-template-columns: 1fr !important; }
    .single-venue__gallery .ph { grid-row: span 1 !important; height: 280px !important; }
    .single-venue__inquiry-grid { grid-template-columns: 1fr !important; gap: 32px !important; }
    .single-venue__next-media { display: none; }
  }
</style>

<?php get_footer(); ?>
