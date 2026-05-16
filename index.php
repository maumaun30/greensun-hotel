<?php get_header(); ?>

<main class="site-main">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <?php the_content(); ?>
            </article>
        <?php endwhile; ?>
    <?php else : ?>
        <section class="section">
            <div class="shell" style="text-align:center;">
                <p>Sorry, nothing matched your request.</p>
            </div>
        </section>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
