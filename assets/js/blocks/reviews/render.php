<?php
$eyebrow       = $attributes['eyebrow']      ?? '';
$section_title = $attributes['sectionTitle'] ?? '';
$autoplay      = max(3000, (int) ($attributes['autoplayMs'] ?? 7000));
$reviews       = $attributes['reviews']      ?? [];

if (empty($reviews)) return;
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'gs-reviews', 'data-interval' => $autoplay]); ?>>
  <div class="gs-reviews__bg" aria-hidden="true">“</div>

  <div class="shell">
    <div class="gs-reviews__head">
      <div>
        <?php if ($eyebrow) : ?>
          <div class="eyebrow reveal"><?php echo esc_html($eyebrow); ?></div>
        <?php endif; ?>
        <?php if ($section_title) : ?>
          <h2 class="display reveal reveal--lg" style="font-size: clamp(40px, 5.4vw, 72px); margin-top: 18px; max-width: 14ch;">
            <?php echo wp_kses_post($section_title); ?>
          </h2>
        <?php endif; ?>
      </div>
      <div class="gs-reviews__arrows reveal">
        <button type="button" class="gs-reviews__arrow" data-dir="prev" aria-label="Previous review">
          <svg width="20" height="10" viewBox="0 0 22 8" fill="none" style="transform: rotate(180deg);"><path d="M0 4 L20 4 M14 0 L20 4 L14 8" stroke="currentColor" stroke-width="1.4" fill="none"/></svg>
        </button>
        <button type="button" class="gs-reviews__arrow" data-dir="next" aria-label="Next review">
          <svg width="20" height="10" viewBox="0 0 22 8" fill="none"><path d="M0 4 L20 4 M14 0 L20 4 L14 8" stroke="currentColor" stroke-width="1.4" fill="none"/></svg>
        </button>
      </div>
    </div>

    <div class="gs-reviews__stage">
      <?php foreach ($reviews as $idx => $r) :
        $name    = $r['name']    ?? '';
        $caption = $r['caption'] ?? '';
        $text    = $r['text']    ?? '';
        $stars   = max(1, min(5, (int) ($r['stars'] ?? 5)));
      ?>
        <article class="gs-reviews__item<?php echo $idx === 0 ? ' is-active' : ''; ?>" data-index="<?php echo esc_attr($idx); ?>">
          <div class="gs-reviews__stars" aria-label="<?php echo esc_attr($stars . ' out of 5 stars'); ?>">
            <?php for ($i = 0; $i < $stars; $i++) : ?>
              <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M10 1 L12.5 7 L19 7.6 L14 12 L15.6 18.5 L10 15 L4.4 18.5 L6 12 L1 7.6 L7.5 7 Z"/></svg>
            <?php endfor; ?>
          </div>
          <blockquote class="gs-reviews__quote display">“<?php echo esc_html($text); ?>”</blockquote>
          <footer class="gs-reviews__author">
            <span class="gs-reviews__avatar"><?php echo esc_html(mb_substr($name, 0, 1)); ?></span>
            <div>
              <div class="display gs-reviews__name"><?php echo esc_html($name); ?></div>
              <?php if ($caption) : ?>
                <div class="gs-reviews__caption"><?php echo esc_html($caption); ?></div>
              <?php endif; ?>
            </div>
          </footer>
        </article>
      <?php endforeach; ?>
    </div>

    <div class="gs-reviews__dots" role="tablist" aria-label="Choose review">
      <?php foreach ($reviews as $idx => $r) : ?>
        <button type="button" class="gs-reviews__dot<?php echo $idx === 0 ? ' is-active' : ''; ?>" data-index="<?php echo esc_attr($idx); ?>" role="tab" aria-selected="<?php echo $idx === 0 ? 'true' : 'false'; ?>"></button>
      <?php endforeach; ?>
    </div>
  </div>
</section>
