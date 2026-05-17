<?php
/**
 * 404 not found.
 */

get_header();
?>

<main id="site-main" class="site-main gs-404" role="main">
  <section class="gs-404__hero" style="position: relative; overflow: hidden;">
    <div class="kb" style="position:absolute; inset:0; z-index:0; background: linear-gradient(160deg, var(--forest, #1f4a3a), var(--forest-2, #0f2018));"></div>
    <svg class="gs-404__leaves" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
      <g fill="oklch(0.72 0.11 130)">
        <path d="M 80 600 C 100 540, 180 520, 240 540 C 220 600, 140 640, 80 600 Z" opacity=".5" style="transform-origin:160px 580px; animation: gs-leaf1 9s ease-in-out infinite;"/>
        <path d="M 1480 200 C 1500 140, 1580 120, 1640 140 C 1620 200, 1540 240, 1480 200 Z" opacity=".4" style="transform-origin:1560px 180px; animation: gs-leaf2 11s ease-in-out infinite;"/>
        <path d="M 1200 740 C 1220 680, 1300 660, 1360 680 C 1340 740, 1260 780, 1200 740 Z" opacity=".3" style="transform-origin:1280px 720px; animation: gs-leaf1 13s ease-in-out infinite;"/>
      </g>
    </svg>
    <div class="shell gs-404__inner">
      <div class="display gs-404__code">404</div>
      <div class="eyebrow reveal" style="color: var(--sun, #e8c46a); margin-top: 24px;">Page not found</div>
      <h1 class="display reveal reveal--lg gs-404__title">
        This page is taking <em>a quiet day.</em>
      </h1>
      <p class="reveal gs-404__body">
        The link you followed may be old, or we may have moved the page. Head back to the home page, browse our rooms, or send us a note and we'll point you the right way.
      </p>
      <div class="reveal gs-404__actions">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn--sun btn--lg">
          <span class="ripple"></span>
          <span>Back to home</span>
          <svg width="14" height="10" viewBox="0 0 22 8" fill="none" aria-hidden="true" style="margin-left: 8px;">
            <path d="M0 4 L20 4 M14 0 L20 4 L14 8" stroke="currentColor" stroke-width="1.4" fill="none"/>
          </svg>
        </a>
        <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn--ghost btn--light btn--lg">
          <span class="ripple"></span>
          <span>Contact us</span>
        </a>
      </div>

      <form role="search" method="get" class="reveal gs-404__form" action="<?php echo esc_url(home_url('/')); ?>">
        <label for="gs-404-search" class="screen-reader-text">Search</label>
        <input type="search" id="gs-404-search" name="s" placeholder="Or search the site…" autocomplete="off" />
        <button type="submit" aria-label="Search">
          <svg width="16" height="16" viewBox="0 0 20 20" fill="none">
            <circle cx="9" cy="9" r="6" stroke="currentColor" stroke-width="1.4"/>
            <path d="M13.5 13.5 L17 17" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
          </svg>
        </button>
      </form>
    </div>
  </section>
</main>

<style>
  .gs-404 .gs-404__hero {
    min-height: 100vh;
    color: var(--ivory, #f7f6f0);
    display: flex;
    align-items: center;
  }
  .gs-404 .gs-404__leaves {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    pointer-events: none;
    opacity: 0.5;
  }
  .gs-404 .gs-404__inner {
    position: relative;
    z-index: 2;
    padding-block: 160px;
    max-width: 720px;
  }
  .gs-404 .gs-404__code {
    font-size: clamp(120px, 18vw, 240px);
    line-height: 1;
    color: var(--sun, #e8c46a);
    opacity: 0.25;
    margin-bottom: -40px;
  }
  .gs-404 .gs-404__title {
    font-size: clamp(48px, 7vw, 92px);
    max-width: 16ch;
    line-height: 1.05;
    margin: 22px 0 0;
    font-weight: 500;
  }
  .gs-404 .gs-404__title em { font-style: italic; }
  .gs-404 .gs-404__body {
    margin-top: 28px;
    color: rgba(255, 255, 255, 0.78);
    line-height: 1.75;
    font-size: 17px;
    max-width: 560px;
  }
  .gs-404 .gs-404__actions {
    margin-top: 40px;
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
  }

  .gs-404 .gs-404__form {
    margin-top: 36px;
    display: flex;
    gap: 12px;
    align-items: center;
    max-width: 420px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.3);
    padding-bottom: 4px;
  }
  .gs-404 .gs-404__form input[type="search"] {
    flex: 1;
    border: 0;
    background: transparent;
    font: inherit;
    font-size: 15px;
    color: var(--ivory, #f7f6f0);
    padding: 8px 0;
    outline: none;
  }
  .gs-404 .gs-404__form input::placeholder { color: rgba(255, 255, 255, 0.55); }
  .gs-404 .gs-404__form button {
    background: transparent;
    border: 0;
    color: rgba(255, 255, 255, 0.7);
    cursor: pointer;
    padding: 6px;
    transition: color 200ms ease;
  }
  .gs-404 .gs-404__form button:hover { color: var(--sun, #e8c46a); }

  @media (max-width: 720px) {
    .gs-404 .gs-404__inner { padding-block: 120px; }
  }
</style>

<?php get_footer(); ?>
