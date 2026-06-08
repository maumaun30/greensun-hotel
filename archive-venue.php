<?php get_header(); ?>

<main id="site-main" class="site-main archive-venue" role="main">

  <?php if (!greensun_render_archive_blocks('venues_archive_page')) : // else: designed fallback below ?>

  <section class="gs-page-hero archive-venue__hero" style="min-height: 60vh; min-height: 460px;">
    <div class="gs-page-hero__media kb" style="background: linear-gradient(160deg, #1f4a3a, #0f2018);"></div>
    <div class="gs-page-hero__scrim" style="background: linear-gradient(to bottom, rgba(13,42,32,.5), rgba(13,42,32,.88));"></div>
    <div class="shell gs-page-hero__content">
      <div class="eyebrow reveal" style="color: var(--sun, #e8c46a);">Event spaces</div>
      <h1 class="display reveal reveal--lg" style="font-size: clamp(48px, 7vw, 104px); margin-top: 22px; max-width: 16ch; font-weight: 500;">
        Many spaces. <em>One unforgettable address.</em>
      </h1>
      <p class="reveal" style="max-width: 620px; margin-top: 28px; color: rgba(255,255,255,.82); font-size: 18px; line-height: 1.75;">
        From a flagship hall to an intimate below-stairs cellar — Greensun hosts the weddings, launches, shoots, and board offsites of Makati. Explore each space below.
      </p>
    </div>
  </section>

  <?php
    // Collect published venues; first = featured, the rest = grid.
    $venue_ids = [];
    if (have_posts()) {
        while (have_posts()) { the_post(); $venue_ids[] = get_the_ID(); }
        wp_reset_postdata();
    }
    $featured_id = $venue_ids ? array_shift($venue_ids) : 0;
  ?>

  <?php if ($featured_id) :
    $f_thumb = get_the_post_thumbnail_url($featured_id, 'full');
    $f_area  = function_exists('get_field') ? get_field('venue_area', $featured_id) : '';
    $f_cap   = function_exists('get_field') ? get_field('venue_capacity', $featured_id) : '';
  ?>
    <section style="padding: 24px 0 40px;">
      <div class="shell">
        <a class="archive-venue__featured reveal reveal--lg" href="<?php echo esc_url(get_permalink($featured_id)); ?>">
          <span class="archive-venue__featured-media ph kb">
            <?php if ($f_thumb) : ?><img src="<?php echo esc_url($f_thumb); ?>" alt="<?php echo esc_attr(get_the_title($featured_id)); ?>" /><?php endif; ?>
          </span>
          <span class="archive-venue__featured-scrim" aria-hidden="true"></span>
          <span class="archive-venue__featured-body">
            <span class="eyebrow" style="color: var(--sun, #e8c46a);">Signature space</span>
            <span class="archive-venue__featured-row">
              <span class="display archive-venue__featured-title"><?php echo esc_html(get_the_title($featured_id)); ?></span>
              <span class="archive-venue__featured-meta">
                <?php if ($f_area) : ?><span class="chip archive-venue__chip"><span class="dot"></span><?php echo esc_html($f_area); ?></span><?php endif; ?>
                <?php if ($f_cap) : ?><span class="chip archive-venue__chip"><span class="dot"></span><?php echo esc_html($f_cap); ?></span><?php endif; ?>
                <span class="btn btn--sun btn--sm"><span style="position:relative; z-index:1;">View space</span><span class="ripple"></span></span>
              </span>
            </span>
          </span>
        </a>
      </div>
    </section>
  <?php endif; ?>

  <?php if (!empty($venue_ids)) : ?>
    <section style="padding: 40px 0 110px;">
      <div class="shell">
        <div class="eyebrow reveal" style="margin-bottom: 32px;">All event spaces</div>
        <div class="archive-venue__grid">
          <?php foreach ($venue_ids as $i => $gid) :
            $g_thumb = get_the_post_thumbnail_url($gid, 'large');
            $g_tag   = function_exists('get_field') ? (get_field('venue_tagline', $gid) ?: get_field('venue_location', $gid)) : '';
          ?>
            <a class="archive-venue__card reveal" href="<?php echo esc_url(get_permalink($gid)); ?>">
              <span class="archive-venue__card-media ph">
                <?php if ($g_thumb) : ?><img src="<?php echo esc_url($g_thumb); ?>" alt="<?php echo esc_attr(get_the_title($gid)); ?>" loading="lazy" /><?php endif; ?>
                <span class="archive-venue__card-num"><?php echo esc_html(str_pad((string) ($i + 2), 2, '0', STR_PAD_LEFT)); ?></span>
              </span>
              <span class="archive-venue__card-body">
                <?php if ($g_tag) : ?><span class="mono muted"><?php echo esc_html($g_tag); ?></span><?php endif; ?>
                <span class="display archive-venue__card-title"><?php echo esc_html(get_the_title($gid)); ?></span>
              </span>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  <?php elseif (!$featured_id) : ?>
    <section style="padding: 60px 0 120px;">
      <div class="shell"><p style="text-align:center; color: var(--ink-2, #3d433d);">No event spaces published yet.</p></div>
    </section>
  <?php endif; ?>

  <section class="archive-venue__inquiry" style="background: var(--forest, #1f4a3a); color: var(--ivory, #f7f6f0); padding: 90px 0;">
    <div class="shell archive-venue__inquiry-grid">
      <h2 class="display reveal reveal--lg" style="font-size: clamp(36px, 5vw, 60px); color: var(--sun, #e8c46a); max-width: 14ch;">
        Planning <em>something special?</em>
      </h2>
      <div>
        <p class="reveal" style="font-size: 17px; line-height: 1.75; color: rgba(255,255,255,.78); max-width: 480px;">
          Tell us about your event — guest count, dates, the feel you're after. Our team will send back venue options, pricing, and a layout sketch within one business day.
        </p>
        <div class="reveal" style="margin-top: 28px;">
          <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn--sun">
            <span class="ripple"></span>
            <span>Send an inquiry</span>
            <svg width="14" height="10" viewBox="0 0 22 8" fill="none" aria-hidden="true" style="margin-left: 8px;">
              <path d="M0 4 L20 4 M14 0 L20 4 L14 8" stroke="currentColor" stroke-width="1.4" fill="none"/>
            </svg>
          </a>
        </div>
      </div>
    </div>
  </section>

  <?php endif; // greensun_render_archive_blocks ?>

</main>

<style>
  /* ── Featured space ── */
  .archive-venue__featured {
    position: relative;
    display: block;
    height: 560px;
    border-radius: 6px;
    overflow: hidden;
    color: var(--ivory, #f7f6f0);
    text-decoration: none;
  }
  .archive-venue__featured-media { position: absolute; inset: 0; }
  .archive-venue__featured-media img { width: 100%; height: 100%; object-fit: cover; }
  .archive-venue__featured-scrim {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(13,42,32,.85), rgba(13,42,32,.1) 60%);
  }
  .archive-venue__featured-body { position: absolute; left: 40px; right: 40px; bottom: 40px; }
  .archive-venue__featured-row {
    display: flex; justify-content: space-between; align-items: flex-end;
    flex-wrap: wrap; gap: 20px; margin-top: 14px;
  }
  .archive-venue__featured-title { font-size: clamp(48px, 6vw, 88px); color: var(--ivory, #f7f6f0); line-height: 1; }
  .archive-venue__featured-meta { display: inline-flex; align-items: center; gap: 16px; flex-wrap: wrap; }
  .archive-venue__chip {
    background: rgba(255,255,255,.16);
    border-color: rgba(255,255,255,.3);
    color: var(--ivory, #f7f6f0);
  }

  /* ── Spaces grid ── */
  .archive-venue__grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 26px; }
  .archive-venue__card { display: block; color: inherit; text-decoration: none; }
  .archive-venue__card-media {
    position: relative; height: 320px; border-radius: 4px; overflow: hidden;
    background: var(--bone, #ede9d9);
  }
  .archive-venue__card-media::after {
    content: ""; position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(13,42,32,.55), transparent 55%);
    pointer-events: none;
  }
  .archive-venue__card-media img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform 700ms cubic-bezier(.16,1,.3,1);
  }
  .archive-venue__card:hover .archive-venue__card-media img { transform: scale(1.04); }
  .archive-venue__card-num {
    position: absolute; top: 16px; left: 16px; z-index: 1;
    background: var(--sun, #e8c46a); color: var(--forest-2, #0f2018);
    padding: 4px 10px; border-radius: 2px;
    font-family: var(--font-display, 'Cormorant Garamond', serif); font-size: 22px; line-height: 1;
  }
  .archive-venue__card-body { padding: 18px 2px 0; display: flex; flex-direction: column; gap: 6px; }
  .archive-venue__card-title { font-size: 28px; }

  .archive-venue__inquiry-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
  }

  @media (max-width: 900px) {
    .archive-venue__grid { grid-template-columns: 1fr 1fr; }
    .archive-venue__featured { height: 440px; }
    .archive-venue__featured-body { left: 24px; right: 24px; bottom: 24px; }
    .archive-venue__inquiry-grid { grid-template-columns: 1fr !important; gap: 32px !important; }
  }
  @media (max-width: 560px) {
    .archive-venue__grid { grid-template-columns: 1fr; }
  }
</style>

<?php get_footer(); ?>
