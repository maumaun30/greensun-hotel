<?php get_header(); ?>

<main class="site-main">

  <?php while (have_posts()) : the_post();
    $eid       = get_the_ID();
    $start     = function_exists('get_field') ? get_field('event_start', $eid) : '';
    $end       = function_exists('get_field') ? get_field('event_end', $eid) : '';
    $time      = function_exists('get_field') ? get_field('event_time', $eid) : '';
    $location  = function_exists('get_field') ? get_field('event_location', $eid) : '';
    $capacity  = function_exists('get_field') ? get_field('event_capacity', $eid) : null;
    $price     = function_exists('get_field') ? get_field('event_price', $eid) : null;
    $currency  = function_exists('get_field') ? (get_field('event_currency', $eid) ?: 'USD') : 'USD';
    $cta_text  = function_exists('get_field') ? (get_field('event_cta_text', $eid) ?: 'Reserve your seat') : 'Reserve your seat';
    $cta_url   = function_exists('get_field') ? get_field('event_cta_url', $eid) : '';
    if (!$cta_url) $cta_url = '#reserve';
    $gallery   = function_exists('get_field') ? get_field('event_gallery', $eid) : [];
    $thumb     = get_the_post_thumbnail_url($eid, 'full');
    $phone     = function_exists('greensun_setting') ? greensun_setting('phone', '') : '';

    $start_fmt = $start ? date_i18n('M j', strtotime($start)) : '';
    $end_fmt   = $end   ? date_i18n('M j, Y', strtotime($end))   : ($start ? date_i18n('M j, Y', strtotime($start)) : '');
    $date_line = $start_fmt && $end_fmt && $start_fmt !== date_i18n('M j', strtotime($end ?: $start))
        ? $start_fmt . ' – ' . $end_fmt
        : ($start ? date_i18n('F j, Y', strtotime($start)) : '');

    $eyebrow_parts = array_filter([$date_line, $time, $location]);
    $eyebrow_text  = implode(' · ', $eyebrow_parts);

    $excerpt_lead = wp_strip_all_tags(get_the_excerpt());
    $first_sentence = $excerpt_lead ? rtrim(preg_split('/[\.\!\?]/', $excerpt_lead)[0], '.') . '.' : '';
  ?>

    <section class="gs-page-hero single-event__hero" style="min-height: 78vh; min-height: 560px;">
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
      <div class="shell single-event__layout" style="display:grid; grid-template-columns: 1.4fr 1fr; gap: 80px; align-items: start;">

        <div class="single-event__body">
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

          <div class="reveal single-event__specs" style="margin-top: 50px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
            <?php
              $specs = array_filter([
                  $start    ? ['When',     date_i18n('M j, Y', strtotime($start))] : null,
                  $time     ? ['Time',     $time] : null,
                  $location ? ['Where',    $location] : null,
                  $capacity ? ['Capacity', sprintf(_n('%d guest', '%d guests', (int)$capacity, 'greensun-hotel'), (int)$capacity)] : null,
                  ['Dress',  'Smart casual'],
                  ['Format', 'Hosted gathering'],
              ]);
              foreach ($specs as $spec) : list($k, $v) = $spec; ?>
                <div style="border-top: 1px solid var(--line, #ede9d9); padding-top: 14px;">
                  <div style="font-family: var(--font-mono, 'JetBrains Mono', monospace); color: var(--mute, #7b817b); font-size: 12px;">
                    <?php echo esc_html($k); ?>
                  </div>
                  <div style="margin-top: 4px; font-weight: 500;"><?php echo esc_html($v); ?></div>
                </div>
            <?php endforeach; ?>
          </div>
        </div>

        <aside class="single-event__sidebar reveal" style="position: sticky; top: 100px;">
          <div style="background: var(--paper, #f8f5e9); border:1px solid var(--line, #ede9d9); border-radius: 4px; padding: 32px; box-shadow: 0 24px 40px -28px rgba(0,0,0,.15);">
            <div style="font-family: var(--font-mono, 'JetBrains Mono', monospace); color: var(--mute, #7b817b); font-size: 12px;">
              <?php echo $price ? 'From' : 'Entry'; ?>
            </div>
            <?php if ($price) : ?>
              <div class="display" style="font-size: 56px; color: var(--forest, #1f4a3a); line-height: 1; margin-top: 4px;">
                <?php echo esc_html($currency . ' ' . number_format_i18n((float) $price)); ?>
              </div>
              <div style="font-family: var(--font-mono, 'JetBrains Mono', monospace); color: var(--mute, #7b817b); font-size: 12px; margin-top: 6px;">per guest</div>
            <?php else : ?>
              <div class="display" style="font-size: 44px; color: var(--forest, #1f4a3a); line-height: 1; margin-top: 4px;">Complimentary</div>
              <div style="font-family: var(--font-mono, 'JetBrains Mono', monospace); color: var(--mute, #7b817b); font-size: 12px; margin-top: 6px;">for in-house guests</div>
            <?php endif; ?>

            <div style="height: 1px; background: var(--line, #ede9d9); margin: 28px 0;"></div>

            <ul style="list-style:none; padding:0; margin:0; display:grid; gap: 12px; color: var(--ink-2, #3d433d); font-size: 14.5px;">
              <?php
                $perks = array_filter([
                  $time     ? 'Starts ' . $time : 'Welcome from 6 PM',
                  $location ? 'At ' . $location : 'On-property gathering',
                  $capacity ? sprintf('Seats limited to %d', (int)$capacity) : 'Intimate seating',
                  'Curated by Greensun hosts',
                ]);
                foreach ($perks as $perk) :
              ?>
                <li style="display:flex; gap: 12px; align-items: center;">
                  <svg width="16" height="16" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                    <path d="M3 9 L7.5 13.5 L15 5" stroke="var(--moss, #527a55)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  <span><?php echo esc_html($perk); ?></span>
                </li>
              <?php endforeach; ?>
            </ul>

            <a id="reserve" href="<?php echo esc_url($cta_url); ?>" class="btn btn--sun" style="margin-top: 28px; width:100%; justify-content:center;">
              <span class="ripple"></span>
              <span><?php echo esc_html($cta_text); ?></span>
            </a>

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
          <div class="eyebrow reveal" style="margin-bottom: 20px;">The evening</div>
          <div class="single-event__gallery" style="display:grid; grid-template-columns: 2fr 1fr 1fr; gap: 14px;">
            <?php
              $count = 0;
              foreach ($gallery as $image) :
                if ($count >= 4) break;
                $url = is_array($image) ? ($image['sizes']['large'] ?? $image['url'] ?? '') : '';
                $alt = is_array($image) ? ($image['alt'] ?? '') : '';
                if (!$url) continue;
                $is_hero = $count === 0;
            ?>
              <div class="ph reveal<?php echo $is_hero ? ' kb' : ''; ?>" style="height: <?php echo $is_hero ? '520px; grid-row: span 2;' : '253px;'; ?>">
                <img src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr($alt); ?>" style="width:100%; height:100%; object-fit:cover;" />
              </div>
            <?php $count++; endforeach; ?>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <?php
    $more = new WP_Query([
        'post_type'      => 'event',
        'posts_per_page' => 3,
        'post__not_in'   => [$eid],
        'meta_key'       => 'event_start',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'meta_query'     => [
          [
            'key'     => 'event_start',
            'value'   => current_time('Y-m-d'),
            'compare' => '>=',
            'type'    => 'DATE',
          ],
        ],
    ]);
    if ($more->have_posts()) :
    ?>
      <section class="section" style="background: var(--paper, #f8f5e9);">
        <div class="shell">
          <header style="text-align:center; max-width: 720px; margin: 0 auto 56px;">
            <div class="eyebrow reveal">Also on the calendar</div>
            <h2 class="display reveal" style="font-size: clamp(36px, 5vw, 64px); margin-top: 14px;">More <em>evenings</em>.</h2>
          </header>
          <div style="display:grid; grid-template-columns: repeat(<?php echo esc_attr(min(3, $more->post_count)); ?>, 1fr); gap: 28px;">
            <?php while ($more->have_posts()) : $more->the_post();
              $m_id    = get_the_ID();
              $m_start = function_exists('get_field') ? get_field('event_start', $m_id) : '';
              $m_loc   = function_exists('get_field') ? get_field('event_location', $m_id) : '';
              $m_date  = $m_start ? date_i18n('M j, Y', strtotime($m_start)) : '';
              $m_thumb = get_the_post_thumbnail_url($m_id, 'large');
            ?>
              <article class="gs-card reveal" style="background:#fff; border-radius: var(--radius-lg, 14px); overflow:hidden; border:1px solid var(--line, #ede9d9);">
                <a href="<?php the_permalink(); ?>" class="ph kb" style="aspect-ratio: 4 / 3; display:block;">
                  <?php if ($m_thumb) : ?><img src="<?php echo esc_url($m_thumb); ?>" alt="" style="width:100%; height:100%; object-fit:cover;" /><?php endif; ?>
                </a>
                <div style="padding: 24px;">
                  <?php if ($m_date || $m_loc) : ?>
                    <div class="eyebrow" style="margin-bottom: 10px;"><?php echo esc_html(trim($m_date . ($m_loc ? ' · ' . $m_loc : ''))); ?></div>
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

  <?php endwhile; ?>

</main>

<style>
  @media (max-width: 1000px) {
    .single-event__layout { grid-template-columns: 1fr !important; gap: 48px !important; }
    .single-event__sidebar { position: static !important; }
    .single-event__specs { grid-template-columns: 1fr 1fr !important; }
    .single-event__gallery { grid-template-columns: 1fr !important; }
    .single-event__gallery .ph { grid-row: span 1 !important; height: 280px !important; }
  }
</style>

<?php get_footer(); ?>
