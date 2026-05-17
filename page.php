<?php
/**
 * Default page template.
 *
 * Pages composed entirely of blocks (Hero / About / etc.) already
 * carry their own structure, so we render their content without an
 * extra title block. For "classic" content pages (Privacy, Terms,
 * etc.) we wrap the_content() in the design's light PageHeader so
 * those pages aren't bare.
 */

get_header();

while (have_posts()) : the_post();

  // Detect whether this page's content is composed of blocks. If so,
  // assume the editor has already provided a hero/title and skip the
  // template's own header.
  $has_blocks = function_exists('has_blocks') && has_blocks(get_the_content());
?>

<main id="site-main" class="site-main gs-page" role="main" <?php post_class(); ?>>

  <?php if ($has_blocks) : ?>
    <?php the_content(); ?>
  <?php else : ?>

    <section class="gs-page__header">
      <div class="shell">
        <div class="eyebrow reveal" style="margin-bottom: 22px;">
          <?php echo esc_html(get_bloginfo('name')); ?>
        </div>
        <h1 class="display reveal reveal--lg gs-page__title">
          <?php the_title(); ?>
        </h1>
      </div>
    </section>

    <section style="padding: 40px 0 120px;">
      <div class="shell gs-page__shell">
        <div class="gs-page__content reveal">
          <?php the_content(); ?>
          <?php wp_link_pages(['before' => '<nav class="gs-page__pagination">', 'after' => '</nav>']); ?>
        </div>
      </div>
    </section>

  <?php endif; ?>

</main>

<style>
  .gs-page__header { padding: 160px 0 40px; }
  .gs-page__title {
    font-size: clamp(48px, 7vw, 96px);
    max-width: 16ch;
    margin: 0;
    line-height: 1.05;
    font-weight: 500;
  }
  .gs-page__title em { font-style: italic; }

  .gs-page__shell { max-width: 760px; margin: 0 auto; }
  .gs-page__content { font-size: 17px; line-height: 1.85; color: var(--ink-2, #3d433d); }
  .gs-page__content > * + * { margin-top: 1.2em; }
  .gs-page__content h2 {
    font-family: var(--font-display, 'Cormorant Garamond', serif);
    font-size: clamp(28px, 3.4vw, 40px);
    color: var(--ink, #1a1f1a);
    line-height: 1.15;
    margin-top: 2em;
  }
  .gs-page__content h3 {
    font-family: var(--font-display, 'Cormorant Garamond', serif);
    font-size: clamp(22px, 2.6vw, 30px);
    color: var(--ink, #1a1f1a);
    line-height: 1.2;
    margin-top: 1.6em;
  }
  .gs-page__content a {
    color: var(--forest, #1f4a3a);
    text-decoration: underline;
    text-underline-offset: 3px;
  }
  .gs-page__content a:hover { color: var(--moss, #527a55); }
  .gs-page__content ul, .gs-page__content ol { padding-left: 1.2em; }
  .gs-page__content li + li { margin-top: 0.4em; }
  .gs-page__content blockquote {
    border-left: 2px solid var(--sun, #e8c46a);
    padding: 4px 0 4px 24px;
    margin: 1.4em 0;
    font-family: var(--font-display, 'Cormorant Garamond', serif);
    font-size: 22px;
    font-style: italic;
    color: var(--ink, #1a1f1a);
  }

  @media (max-width: 900px) {
    .gs-page__header { padding: 120px 0 32px; }
  }
</style>

<?php endwhile; ?>

<?php get_footer(); ?>
