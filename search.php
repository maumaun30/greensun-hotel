<?php
/**
 * Search results.
 */

get_header();

global $wp_query;
$query  = trim(get_search_query());
$total  = (int) $wp_query->found_posts;
?>

<main class="site-main gs-search">

  <section class="gs-search__header">
    <div class="shell">
      <div class="eyebrow reveal" style="margin-bottom: 22px;">Search</div>
      <h1 class="display reveal reveal--lg gs-search__title">
        <?php if ($query) : ?>
          Results for <em>“<?php echo esc_html($query); ?>”</em>
        <?php else : ?>
          What are you <em>looking for?</em>
        <?php endif; ?>
      </h1>
      <p class="reveal gs-search__meta">
        <?php
        if ($query) {
          echo esc_html(sprintf(
            _n('%d result found.', '%d results found.', $total, 'greensun-hotel'),
            $total
          ));
        } else {
          esc_html_e('Search for rooms, venues, events, or pages.', 'greensun-hotel');
        }
        ?>
      </p>

      <form role="search" method="get" class="reveal gs-search__form" action="<?php echo esc_url(home_url('/')); ?>">
        <label for="gs-search-input" class="screen-reader-text">Search</label>
        <input type="search" id="gs-search-input" name="s" value="<?php echo esc_attr($query); ?>" placeholder="Search the site…" autocomplete="off" />
        <button type="submit" class="btn btn--sun">
          <span class="ripple"></span>
          <span>Search</span>
        </button>
      </form>
    </div>
  </section>

  <section style="padding: 40px 0 120px;">
    <div class="shell">
      <?php if (have_posts()) : ?>
        <ul class="gs-search__list">
          <?php while (have_posts()) : the_post();
            $type_obj = get_post_type_object(get_post_type());
            $type_lbl = $type_obj ? $type_obj->labels->singular_name : 'Post';
            $thumb    = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
          ?>
            <li class="gs-search__item reveal">
              <a href="<?php the_permalink(); ?>" class="gs-search__link">
                <?php if ($thumb) : ?>
                  <div class="ph gs-search__thumb">
                    <img src="<?php echo esc_url($thumb); ?>" alt="" loading="lazy" />
                  </div>
                <?php endif; ?>
                <div class="gs-search__copy">
                  <div class="eyebrow gs-search__type"><?php echo esc_html($type_lbl); ?></div>
                  <h2 class="display gs-search__name"><?php the_title(); ?></h2>
                  <?php if (get_the_excerpt()) : ?>
                    <p class="gs-search__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 28)); ?></p>
                  <?php endif; ?>
                </div>
                <span class="gs-search__arrow" aria-hidden="true">
                  <svg width="20" height="14" viewBox="0 0 22 8" fill="none"><path d="M0 4 L20 4 M14 0 L20 4 L14 8" stroke="currentColor" stroke-width="1.4" fill="none"/></svg>
                </span>
              </a>
            </li>
          <?php endwhile; ?>
        </ul>

        <div class="reveal" style="margin-top: 56px; display:flex; justify-content:center;">
          <?php the_posts_pagination(['mid_size' => 1, 'prev_text' => '←', 'next_text' => '→']); ?>
        </div>
      <?php else : ?>
        <div style="text-align:center; padding: 80px 0; color: var(--ink-2, #3d433d);">
          <p style="font-size: 17px;">No results <?php echo $query ? 'for "' . esc_html($query) . '"' : ''; ?>. Try another phrase.</p>
        </div>
      <?php endif; ?>
    </div>
  </section>

</main>

<style>
  .gs-search__header { padding: 160px 0 40px; }
  .gs-search__title {
    font-size: clamp(40px, 5.6vw, 80px);
    max-width: 18ch;
    margin: 0;
    line-height: 1.05;
    font-weight: 500;
  }
  .gs-search__title em { font-style: italic; }
  .gs-search__meta {
    margin-top: 22px;
    color: var(--mute, #7b817b);
    font-family: var(--font-mono, 'JetBrains Mono', monospace);
    font-size: 13px;
    letter-spacing: 0.06em;
  }

  .gs-search__form {
    margin-top: 32px;
    display: flex;
    gap: 12px;
    align-items: stretch;
    max-width: 540px;
    border-bottom: 1px solid var(--line, #ede9d9);
    padding-bottom: 4px;
  }
  .gs-search__form input[type="search"] {
    flex: 1;
    border: 0;
    background: transparent;
    font: inherit;
    font-size: 17px;
    color: var(--ink, #1a1f1a);
    padding: 10px 0;
    outline: none;
  }

  .gs-search__list {
    list-style: none;
    margin: 0;
    padding: 0;
    display: grid;
    gap: 18px;
  }
  .gs-search__link {
    display: grid;
    grid-template-columns: 140px 1fr auto;
    gap: 24px;
    align-items: center;
    padding: 18px;
    border: 1px solid var(--line, #ede9d9);
    border-radius: 4px;
    background: #fff;
    text-decoration: none;
    color: inherit;
    transition: border-color 280ms cubic-bezier(.16,1,.3,1), transform 280ms cubic-bezier(.16,1,.3,1);
  }
  .gs-search__link:hover { border-color: var(--forest, #1f4a3a); transform: translateY(-2px); }
  .gs-search__thumb {
    aspect-ratio: 4 / 3;
    border-radius: 2px;
    overflow: hidden;
    background: var(--bone, #ede9d9);
  }
  .gs-search__thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
  .gs-search__type { color: var(--moss, #527a55); }
  .gs-search__name {
    font-size: 26px;
    margin: 6px 0 0;
    line-height: 1.15;
  }
  .gs-search__excerpt {
    margin-top: 8px;
    color: var(--ink-2, #3d433d);
    font-size: 14.5px;
    line-height: 1.6;
  }
  .gs-search__arrow {
    color: var(--forest, #1f4a3a);
    transition: transform 280ms cubic-bezier(.16,1,.3,1);
  }
  .gs-search__link:hover .gs-search__arrow { transform: translateX(4px); }

  @media (max-width: 720px) {
    .gs-search__header { padding: 120px 0 32px; }
    .gs-search__link { grid-template-columns: 1fr; gap: 16px; }
    .gs-search__arrow { display: none; }
  }
</style>

<?php get_footer(); ?>
