<?php get_header(); ?>

<main id="site-main" class="site-main archive-venue" role="main">

  <section class="gs-page-hero archive-venue__hero" style="min-height: 60vh; min-height: 460px;">
    <div class="gs-page-hero__media kb" style="background: linear-gradient(160deg, #1f4a3a, #0f2018);"></div>
    <div class="gs-page-hero__scrim" style="background: linear-gradient(to bottom, rgba(13,42,32,.5), rgba(13,42,32,.88));"></div>
    <div class="shell gs-page-hero__content">
      <div class="eyebrow reveal" style="color: var(--sun, #e8c46a);">Event venues</div>
      <h1 class="display reveal reveal--lg" style="font-size: clamp(48px, 7vw, 104px); margin-top: 22px; max-width: 16ch; font-weight: 500;">
        Three venues. <em>One unforgettable evening.</em>
      </h1>
      <p class="reveal" style="max-width: 620px; margin-top: 28px; color: rgba(255,255,255,.82); font-size: 18px; line-height: 1.75;">
        From a 120-pax flagship hall to focus rooms wired for hybrid work — Greensun hosts the meetings, weddings, and quiet board offsites of Makati.
      </p>
    </div>
  </section>

  <section style="padding: 30px 0 120px;">
    <div class="shell archive-venue__stack">
      <?php $idx = 0; if (have_posts()) : while (have_posts()) : the_post();
        $vid       = get_the_ID();
        $flip      = ($idx % 2) === 1;
        $tagline   = function_exists('get_field') ? get_field('venue_tagline', $vid) : '';
        $capacity  = function_exists('get_field') ? get_field('venue_capacity', $vid) : '';
        $area      = function_exists('get_field') ? get_field('venue_area', $vid) : '';
        $location  = function_exists('get_field') ? get_field('venue_location', $vid) : '';
        $layouts   = function_exists('get_field') ? get_field('venue_layouts', $vid) : '';
        $cta_text  = function_exists('get_field') ? (get_field('venue_cta_text', $vid) ?: 'Inquire about ' . get_the_title()) : 'Inquire about ' . get_the_title();
        $cta_url   = function_exists('get_field') ? get_field('venue_cta_url', $vid) : '';
        if (!$cta_url) $cta_url = home_url('/contact');
        $blurb     = wp_strip_all_tags(get_the_excerpt());
        $thumb     = get_the_post_thumbnail_url($vid, 'full');

        $eyebrow_parts = array_filter([$tagline ?: $location, $capacity]);
        $caps = array_filter(array_map('trim', explode(',', (string) $layouts)));
        $caps = array_slice($caps, 0, 3);
      ?>
        <article class="archive-venue__row reveal reveal--lg<?php echo $flip ? ' is-flipped' : ''; ?>">
          <div class="archive-venue__media">
            <a href="<?php the_permalink(); ?>" class="ph kb archive-venue__img" style="display:block; height: 540px;">
              <?php echo greensun_post_thumbnail_html($vid, 'full', '(max-width: 900px) 100vw, 50vw', 'archive-venue__img-el'); ?>
            </a>
            <div class="archive-venue__badge"><?php echo esc_html(str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT)); ?></div>
          </div>
          <div class="archive-venue__body">
            <?php if (!empty($eyebrow_parts)) : ?>
              <div class="eyebrow"><?php echo esc_html(implode(' · ', $eyebrow_parts)); ?></div>
            <?php endif; ?>
            <h2 class="display archive-venue__title">
              <a href="<?php the_permalink(); ?>" style="color: inherit; text-decoration: none;"><?php the_title(); ?></a>
            </h2>
            <?php if ($blurb) : ?>
              <p class="archive-venue__blurb"><?php echo esc_html($blurb); ?></p>
            <?php endif; ?>
            <?php if (!empty($caps)) : ?>
              <dl class="archive-venue__caps" style="grid-template-columns: repeat(<?php echo esc_attr(count($caps)); ?>, 1fr);">
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
      <?php $idx++; endwhile;
      else : ?>
        <p style="text-align:center; color: var(--ink-2, #3d433d);">No venues published yet.</p>
      <?php endif; ?>
    </div>
  </section>

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

</main>

<style>
  .archive-venue__stack { display: grid; gap: 80px; }
  .archive-venue__row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
  }
  .archive-venue__row.is-flipped .archive-venue__media { order: 2; }
  .archive-venue__row.is-flipped .archive-venue__body  { order: 1; }
  .archive-venue__media { position: relative; }
  .archive-venue__media .ph {
    border-radius: 4px;
    overflow: hidden;
    background: var(--bone, #ede9d9);
  }
  .archive-venue__media img,
  .archive-venue__img-el {
    width: 100%; height: 100%; object-fit: cover; display: block;
    transition: transform 800ms cubic-bezier(.16,1,.3,1);
  }
  .archive-venue__media .ph:hover img { transform: scale(1.03); }
  .archive-venue__badge {
    position: absolute;
    top: 20px;
    left: -20px;
    background: var(--sun, #e8c46a);
    color: var(--forest-2, #0f2018);
    padding: 8px 14px;
    font-family: var(--font-display, 'Cormorant Garamond', serif);
    font-size: 32px;
    line-height: 1;
    border-radius: 2px;
  }
  .archive-venue__row.is-flipped .archive-venue__badge { left: auto; right: -20px; }
  .archive-venue__title {
    font-size: clamp(40px, 5vw, 64px);
    margin-top: 14px;
    max-width: 12ch;
    line-height: 1.05;
  }
  .archive-venue__title em { font-style: italic; }
  .archive-venue__blurb {
    margin-top: 22px;
    color: var(--ink-2, #3d433d);
    font-size: 16.5px;
    line-height: 1.8;
    max-width: 520px;
  }
  .archive-venue__caps {
    margin: 24px 0 0;
    display: grid;
    gap: 14px;
    max-width: 460px;
  }
  .archive-venue__caps > div {
    border-top: 1px solid var(--line, #ede9d9);
    padding-top: 12px;
  }
  .archive-venue__caps dt {
    font-family: var(--font-mono, 'JetBrains Mono', monospace);
    color: var(--mute, #7b817b);
    font-size: 12px;
    margin: 0;
  }
  .archive-venue__caps dd {
    margin: 4px 0 0;
    font-size: 24px;
    color: var(--forest, #1f4a3a);
  }

  .archive-venue__inquiry-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
  }

  @media (max-width: 900px) {
    .archive-venue__row,
    .archive-venue__inquiry-grid { grid-template-columns: 1fr !important; gap: 32px !important; }
    .archive-venue__row.is-flipped .archive-venue__media { order: 1; }
    .archive-venue__row.is-flipped .archive-venue__body  { order: 2; }
    .archive-venue__badge { left: 12px !important; right: auto !important; top: 12px; }
    .archive-venue__media .ph { height: 360px !important; }
  }
</style>

<?php get_footer(); ?>
