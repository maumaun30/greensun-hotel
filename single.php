<?php
/**
 * Single post template (standard "post" type).
 *
 * Editorial layout: optional hero image with overlay title, then
 * a centered article column with prose styles.
 */

get_header();

while (have_posts()) : the_post();
  $thumb = get_the_post_thumbnail_url(get_the_ID(), 'full');
  $cats  = get_the_category();
  $cat   = !empty($cats) ? $cats[0]->name : '';
  $author_id = get_the_author_meta('ID');
?>

<main id="site-main" class="site-main gs-post" role="main" <?php post_class(); ?>>

  <?php if ($thumb) : ?>
    <section class="gs-page-hero gs-post__hero" style="height: 70vh; min-height: 480px;">
      <div class="gs-page-hero__media kb">
        <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" />
      </div>
      <div class="gs-page-hero__scrim" style="background: linear-gradient(to bottom, rgba(13,42,32,.4), rgba(13,42,32,.85));"></div>
      <div class="shell gs-page-hero__content" style="padding-block: 100px 70px;">
        <?php if ($cat) : ?>
          <div class="eyebrow reveal" style="color: var(--sun, #e8c46a);"><?php echo esc_html($cat); ?></div>
        <?php endif; ?>
        <h1 class="display reveal reveal--lg" style="font-size: clamp(44px, 6vw, 92px); line-height: 1.05; max-width: 18ch; margin-top: 22px; font-weight: 500;">
          <?php the_title(); ?>
        </h1>
        <div class="reveal gs-post__meta" style="margin-top: 22px;">
          <span><?php echo esc_html(get_the_date('F j, Y')); ?></span>
          <span aria-hidden="true">·</span>
          <span>by <?php echo esc_html(get_the_author_meta('display_name', $author_id)); ?></span>
        </div>
      </div>
    </section>
  <?php else : ?>
    <section class="gs-post__header">
      <div class="shell">
        <?php if ($cat) : ?>
          <div class="eyebrow reveal" style="margin-bottom: 22px;"><?php echo esc_html($cat); ?></div>
        <?php endif; ?>
        <h1 class="display reveal reveal--lg gs-post__title">
          <?php the_title(); ?>
        </h1>
        <div class="reveal gs-post__meta" style="margin-top: 22px; color: var(--mute, #7b817b);">
          <span><?php echo esc_html(get_the_date('F j, Y')); ?></span>
          <span aria-hidden="true">·</span>
          <span>by <?php echo esc_html(get_the_author_meta('display_name', $author_id)); ?></span>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <section style="padding: 90px 0 120px;">
    <div class="shell gs-post__shell">
      <article class="gs-post__article reveal">
        <?php the_content(); ?>
        <?php wp_link_pages(['before' => '<nav class="gs-page__pagination">', 'after' => '</nav>']); ?>
      </article>

      <?php if (get_the_tag_list()) : ?>
        <footer class="gs-post__tags reveal">
          <?php echo get_the_tag_list('<span class="gs-post__tag-label">Tagged</span>', '<span class="gs-post__tag-sep">·</span>'); ?>
        </footer>
      <?php endif; ?>

      <nav class="gs-post__nav reveal" aria-label="<?php esc_attr_e('Post navigation', 'greensun-hotel'); ?>">
        <?php
        $prev = get_previous_post();
        $next = get_next_post();
        ?>
        <?php if ($prev) : ?>
          <a class="gs-post__nav-link" href="<?php echo esc_url(get_permalink($prev)); ?>">
            <span class="gs-post__nav-dir">← Previous</span>
            <span class="display"><?php echo esc_html(get_the_title($prev)); ?></span>
          </a>
        <?php else : ?><span></span><?php endif; ?>
        <?php if ($next) : ?>
          <a class="gs-post__nav-link gs-post__nav-link--next" href="<?php echo esc_url(get_permalink($next)); ?>">
            <span class="gs-post__nav-dir">Next →</span>
            <span class="display"><?php echo esc_html(get_the_title($next)); ?></span>
          </a>
        <?php else : ?><span></span><?php endif; ?>
      </nav>
    </div>
  </section>

</main>

<style>
  .gs-post__header { padding: 160px 0 40px; }
  .gs-post__title {
    font-size: clamp(48px, 7vw, 96px);
    max-width: 18ch;
    margin: 0;
    line-height: 1.05;
    font-weight: 500;
  }
  .gs-post__meta {
    display: inline-flex;
    gap: 12px;
    font-family: var(--font-mono, 'JetBrains Mono', monospace);
    font-size: 12px;
    letter-spacing: 0.08em;
    color: rgba(255, 255, 255, 0.7);
  }
  .gs-post__header .gs-post__meta { color: var(--mute, #7b817b); }

  .gs-post__shell { max-width: 760px; margin: 0 auto; }
  .gs-post__article { font-size: 17px; line-height: 1.85; color: var(--ink-2, #3d433d); }
  .gs-post__article > * + * { margin-top: 1.3em; }
  .gs-post__article h2 {
    font-family: var(--font-display, 'Cormorant Garamond', serif);
    font-size: clamp(28px, 3.4vw, 40px);
    color: var(--ink, #1a1f1a);
    line-height: 1.15;
    margin-top: 2em;
  }
  .gs-post__article h3 {
    font-family: var(--font-display, 'Cormorant Garamond', serif);
    font-size: clamp(22px, 2.6vw, 30px);
    color: var(--ink, #1a1f1a);
    line-height: 1.2;
    margin-top: 1.6em;
  }
  .gs-post__article a {
    color: var(--forest, #1f4a3a);
    text-decoration: underline;
    text-underline-offset: 3px;
  }
  .gs-post__article a:hover { color: var(--moss, #527a55); }
  .gs-post__article ul, .gs-post__article ol { padding-left: 1.2em; }
  .gs-post__article li + li { margin-top: 0.4em; }
  .gs-post__article blockquote {
    border-left: 2px solid var(--sun, #e8c46a);
    padding: 4px 0 4px 24px;
    margin: 1.6em 0;
    font-family: var(--font-display, 'Cormorant Garamond', serif);
    font-size: 24px;
    font-style: italic;
    color: var(--ink, #1a1f1a);
  }
  .gs-post__article img {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
  }

  .gs-post__tags {
    margin-top: 64px;
    padding-top: 28px;
    border-top: 1px solid var(--line, #ede9d9);
    font-family: var(--font-mono, 'JetBrains Mono', monospace);
    font-size: 12px;
    color: var(--mute, #7b817b);
    letter-spacing: 0.06em;
  }
  .gs-post__tag-label {
    margin-right: 10px;
    text-transform: uppercase;
    letter-spacing: 0.14em;
  }
  .gs-post__tags a {
    color: var(--forest, #1f4a3a);
    text-decoration: none;
    margin: 0 4px;
  }
  .gs-post__tag-sep { margin: 0 4px; color: var(--line, #ede9d9); }

  .gs-post__nav {
    margin-top: 60px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
  }
  .gs-post__nav-link {
    display: flex;
    flex-direction: column;
    gap: 8px;
    color: inherit;
    text-decoration: none;
    padding: 22px;
    border: 1px solid var(--line, #ede9d9);
    border-radius: 4px;
    background: var(--paper, #f8f5e9);
    transition: border-color 280ms cubic-bezier(.16,1,.3,1);
  }
  .gs-post__nav-link:hover { border-color: var(--forest, #1f4a3a); }
  .gs-post__nav-link--next { text-align: right; align-items: flex-end; }
  .gs-post__nav-dir {
    font-family: var(--font-mono, 'JetBrains Mono', monospace);
    font-size: 11px;
    letter-spacing: 0.12em;
    color: var(--mute, #7b817b);
    text-transform: uppercase;
  }
  .gs-post__nav-link .display { font-size: 22px; line-height: 1.15; }

  @media (max-width: 900px) {
    .gs-post__header { padding: 120px 0 32px; }
    .gs-post__nav { grid-template-columns: 1fr; }
    .gs-post__nav-link--next { text-align: left; align-items: flex-start; }
  }
</style>

<?php endwhile; ?>

<?php get_footer(); ?>
