<?php
/**
 * Index / blog listing fallback.
 *
 * Used by the Posts page (if any) and as WP's final fallback when no
 * more specific template matches. Renders a 2-column card grid.
 */

get_header();

// Build a sensible header based on context.
$header_eyebrow = 'Journal';
$header_title   = 'Notes from <em>the house.</em>';
if (is_home() && ($posts_page = get_option('page_for_posts'))) {
    $posts_page_obj = get_post($posts_page);
    if ($posts_page_obj) {
        $header_eyebrow = 'Journal';
        $header_title   = esc_html(get_the_title($posts_page_obj));
    }
}
?>

<main id="site-main" class="site-main gs-blog" role="main">

  <section class="gs-blog__header">
    <div class="shell">
      <div class="eyebrow reveal" style="margin-bottom: 22px;">
        <?php echo esc_html($header_eyebrow); ?>
      </div>
      <h1 class="display reveal reveal--lg gs-blog__title">
        <?php echo wp_kses_post($header_title); ?>
      </h1>
    </div>
  </section>

  <section style="padding: 40px 0 120px;">
    <div class="shell">
      <?php if (have_posts()) : ?>
        <div class="gs-blog__grid">
          <?php while (have_posts()) : the_post();
            $cats  = get_the_category();
            $cat   = !empty($cats) ? $cats[0]->name : '';
          ?>
            <article class="gs-blog__card reveal">
              <a href="<?php the_permalink(); ?>" class="ph kb gs-blog__media">
                <?php echo greensun_post_thumbnail_html(get_the_ID(), 'large', '(max-width: 900px) 100vw, 50vw'); ?>
                <span class="gs-blog__scrim" aria-hidden="true"></span>
                <?php if ($cat) : ?>
                  <span class="chip gs-blog__cat"><span class="dot"></span><?php echo esc_html($cat); ?></span>
                <?php endif; ?>
              </a>
              <div class="gs-blog__body">
                <div class="eyebrow gs-blog__date">
                  <?php echo esc_html(get_the_date('M j, Y')); ?>
                </div>
                <h2 class="display gs-blog__name">
                  <a href="<?php the_permalink(); ?>" style="color: inherit; text-decoration: none;"><?php the_title(); ?></a>
                </h2>
                <?php if (get_the_excerpt()) : ?>
                  <p class="gs-blog__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 28)); ?></p>
                <?php endif; ?>
                <a href="<?php the_permalink(); ?>" class="gs-blog__readmore">
                  Read on
                  <svg width="14" height="10" viewBox="0 0 22 8" fill="none" aria-hidden="true">
                    <path d="M0 4 L20 4 M14 0 L20 4 L14 8" stroke="currentColor" stroke-width="1.4" fill="none"/>
                  </svg>
                </a>
              </div>
            </article>
          <?php endwhile; ?>
        </div>

        <div class="reveal" style="margin-top: 64px; display:flex; justify-content:center;">
          <?php the_posts_pagination(['mid_size' => 1, 'prev_text' => '←', 'next_text' => '→']); ?>
        </div>
      <?php else : ?>
        <div style="text-align:center; padding: 80px 0; color: var(--ink-2, #3d433d);">
          <p style="font-size: 17px;">Nothing posted here yet.</p>
        </div>
      <?php endif; ?>
    </div>
  </section>

</main>

<style>
  .gs-blog__header { padding: 160px 0 60px; }
  .gs-blog__title {
    font-size: clamp(48px, 7vw, 96px);
    max-width: 14ch;
    margin: 0;
    line-height: 1.05;
    font-weight: 500;
  }
  .gs-blog__title em { font-style: italic; }

  .gs-blog__grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 32px;
  }
  .gs-blog__card {
    background: #fff;
    border-radius: 4px;
    overflow: hidden;
    border: 1px solid var(--line, #ede9d9);
    display: flex;
    flex-direction: column;
    transition: transform 380ms cubic-bezier(.16,1,.3,1), box-shadow 380ms cubic-bezier(.16,1,.3,1);
  }
  .gs-blog__card:hover {
    transform: translateY(-4px);
    box-shadow: 0 24px 40px -28px rgba(0,0,0,0.2);
  }
  .gs-blog__media {
    position: relative;
    aspect-ratio: 16 / 10;
    overflow: hidden;
    display: block;
    background: var(--bone, #ede9d9);
  }
  .gs-blog__media img {
    width: 100%; height: 100%; object-fit: cover; display: block;
    transition: transform 700ms cubic-bezier(.16,1,.3,1);
  }
  .gs-blog__card:hover .gs-blog__media img { transform: scale(1.04); }
  .gs-blog__scrim {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(13,42,32,.45), transparent 55%);
    pointer-events: none;
  }
  .gs-blog__cat {
    position: absolute;
    top: 16px;
    left: 16px;
    background: rgba(255, 255, 255, 0.9);
  }

  .gs-blog__body {
    padding: 28px;
    display: flex;
    flex-direction: column;
    flex: 1;
  }
  .gs-blog__date { color: var(--moss, #527a55); }
  .gs-blog__name {
    font-size: 32px;
    line-height: 1.1;
    margin: 8px 0 0;
  }
  .gs-blog__excerpt {
    margin-top: 14px;
    color: var(--ink-2, #3d433d);
    line-height: 1.7;
    font-size: 15.5px;
  }
  .gs-blog__readmore {
    margin-top: auto;
    padding-top: 22px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    color: var(--forest, #1f4a3a);
    text-decoration: none;
    font-family: var(--font-mono, 'JetBrains Mono', monospace);
    font-size: 12px;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    transition: gap 280ms cubic-bezier(.16,1,.3,1);
  }
  .gs-blog__readmore:hover { gap: 14px; }

  @media (max-width: 900px) {
    .gs-blog__header { padding: 120px 0 40px; }
    .gs-blog__grid { grid-template-columns: 1fr; }
  }
</style>

<?php get_footer(); ?>
