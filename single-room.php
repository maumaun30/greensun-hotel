<?php get_header(); ?>

<main id="site-main" class="site-main" role="main">

  <?php while (have_posts()) : the_post();
    $room_id     = get_the_ID();
    $price       = function_exists('get_field') ? get_field('price_per_night', $room_id) : null;
    $currency    = function_exists('get_field') ? (get_field('currency', $room_id) ?: 'USD') : 'USD';
    $size        = function_exists('get_field') ? get_field('room_size', $room_id) : null;
    $guests      = function_exists('get_field') ? get_field('max_guests', $room_id) : null;
    $beds        = function_exists('get_field') ? get_field('bed_configuration', $room_id) : null;
    $tagline     = function_exists('get_field') ? get_field('tagline', $room_id) : null;
    $inclusions  = function_exists('get_field') ? get_field('inclusions', $room_id) : [];
    $gallery     = function_exists('get_field') ? get_field('gallery', $room_id) : [];
    $ezee_id     = function_exists('get_field') ? get_field('ezee_room_type_id', $room_id) : '';
    $thumb       = get_the_post_thumbnail_url($room_id, 'full');
    $phone       = function_exists('greensun_setting') ? greensun_setting('phone', '') : '';

    // Design uses just size · beds in the hero eyebrow (rooms.jsx:104).
    $eyebrow_parts = array_filter([$size, $beds]);
    $eyebrow_text  = implode(' · ', $eyebrow_parts);
  ?>

    <section class="gs-page-hero single-room__hero" style="height: 78vh; min-height: 560px;">
      <div class="gs-page-hero__media kb">
        <?php if ($thumb) : ?>
          <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" />
        <?php endif; ?>
      </div>
      <div class="gs-page-hero__scrim" style="background: linear-gradient(to bottom, rgba(13,42,32,.45), rgba(13,42,32,.85));"></div>
      <div class="shell gs-page-hero__content" style="padding-block: 100px 70px;">
        <?php if ($eyebrow_text) : ?>
          <div class="eyebrow reveal" style="color: var(--sun, #e8c46a);"><?php echo esc_html($eyebrow_text); ?></div>
        <?php endif; ?>
        <h1 class="display reveal reveal--lg" style="font-size: clamp(54px, 8vw, 120px); line-height: 1.05; max-width: 16ch; margin-top: 22px; font-weight: 500;">
          <?php the_title(); ?>
        </h1>
        <?php if ($tagline) : ?>
          <p class="reveal" style="max-width: 600px; margin-top: 22px; font-size: 18px; color: rgba(255,255,255,.78); line-height: 1.7;">
            <?php echo esc_html($tagline); ?>
          </p>
        <?php endif; ?>
      </div>
    </section>

    <section class="section--tight" style="padding-top: 90px;">
      <div class="shell single-room__layout" style="display:grid; grid-template-columns: 1.4fr 1fr; gap: 80px; align-items: start;">

        <div class="single-room__body">
          <?php
            $content        = get_the_content();
            $excerpt_lead   = wp_strip_all_tags(get_the_excerpt());
            $first_sentence = $excerpt_lead ? rtrim(preg_split('/[\.\!\?]/', $excerpt_lead)[0], '.') . '.' : '';
            if ($first_sentence) :
          ?>
            <h2 class="display reveal" style="font-size: 36px; max-width: 22ch; margin: 0; line-height: 1.15;">
              <?php echo esc_html($first_sentence); ?>
            </h2>
          <?php endif; ?>
          <?php if ($content) : ?>
            <div class="reveal" style="margin-top: 24px; color: var(--ink-2, #3d433d); font-size: 16.5px; line-height: 1.85;">
              <?php the_content(); ?>
            </div>
          <?php endif; ?>

          <div class="reveal single-room__specs" style="margin-top: 50px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
            <?php
              $specs = array_filter([
                  $guests ? ['Sleeps', $guests . ' guests'] : null,
                  $size   ? ['Size',   $size] : null,
                  $beds   ? ['Bed',    $beds] : null,
                  ['Bath',          'Hot/cold shower'],
                  ['Entertainment', '43" LED Smart TV'],
                  ['Wi-Fi',         'Complimentary'],
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

          <?php if (!empty($inclusions) && is_array($inclusions)) : ?>
            <div class="reveal" style="margin-top: 56px;">
              <div class="eyebrow">Inclusions</div>
              <ul style="list-style:none; padding:0; margin: 18px 0 0; display:grid; grid-template-columns: repeat(2, 1fr); gap: 12px 24px;">
                <?php foreach ($inclusions as $item) :
                  $label = is_array($item) ? ($item['text'] ?? $item['inclusion'] ?? reset($item)) : $item;
                ?>
                  <li style="display:flex; gap: 12px; align-items: baseline;">
                    <span aria-hidden="true" style="color: var(--moss, #527a55);">·</span>
                    <span style="line-height: 1.6;"><?php echo esc_html($label); ?></span>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>
        </div>

        <aside class="single-room__sidebar reveal" style="position: sticky; top: 100px;">
          <div style="background: var(--paper, #f8f5e9); border:1px solid var(--line, #ede9d9); border-radius: 4px; padding: 32px; box-shadow: 0 24px 40px -28px rgba(0,0,0,.15);">
            <div style="font-family: var(--font-mono, 'JetBrains Mono', monospace); color: var(--mute, #7b817b); font-size: 12px;">From</div>
            <?php if ($price) : ?>
              <div class="display" style="font-size: 56px; color: var(--forest, #1f4a3a); line-height: 1; margin-top: 4px;">
                <?php echo esc_html($currency . ' ' . number_format_i18n((float) $price)); ?>
              </div>
              <div style="font-family: var(--font-mono, 'JetBrains Mono', monospace); color: var(--mute, #7b817b); font-size: 12px; margin-top: 6px;">per night, inclusive</div>
            <?php else : ?>
              <div class="display" style="font-size: 32px; color: var(--ink-2, #3d433d); line-height: 1; margin-top: 4px;">Ask for rate</div>
            <?php endif; ?>

            <div style="height: 1px; background: var(--line, #ede9d9); margin: 28px 0;"></div>

            <ul style="list-style:none; padding:0; margin:0; display:grid; gap: 12px; color: var(--ink-2, #3d433d); font-size: 14.5px;">
              <?php
                $perks = ['Free WiFi & breakfast voucher', '24/7 reception & room service', 'Flexible cancellation 48h prior', 'Direct-rate guarantee'];
                foreach ($perks as $perk) :
              ?>
                <li style="display:flex; gap: 12px; align-items: center;">
                  <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true" style="flex: 0 0 18px;">
                    <circle cx="9" cy="9" r="9" fill="var(--moss, #527a55)" opacity=".15"/>
                    <path d="M5 9 L8 12 L13 6" stroke="var(--moss, #527a55)" stroke-width="1.6" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  <span><?php echo esc_html($perk); ?></span>
                </li>
              <?php endforeach; ?>
            </ul>

            <a href="<?php echo esc_url(add_query_arg('room_type', $ezee_id ?: $room_id, home_url('/booking'))); ?>" class="btn btn--sun btn--lg" style="margin-top: 28px; width:100%; justify-content:center;">
              <span class="ripple"></span>
              <span>Book this room</span>
              <svg width="14" height="10" viewBox="0 0 22 8" fill="none" aria-hidden="true" style="margin-left: 8px;">
                <path d="M0 4 L20 4 M14 0 L20 4 L14 8" stroke="currentColor" stroke-width="1.4" fill="none"/>
              </svg>
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
          <div class="eyebrow reveal" style="margin-bottom: 20px;">The room</div>
          <div class="single-room__gallery" style="display:grid; grid-template-columns: 2fr 1fr 1fr; gap: 14px;">
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
        'post_type'      => 'room',
        'posts_per_page' => 3,
        'post__not_in'   => [$room_id],
        'orderby'        => 'rand',
    ]);
    if ($more->have_posts()) :
    ?>
      <section class="section" style="background: var(--paper, #f8f5e9);">
        <div class="shell">
          <header style="text-align:center; max-width: 720px; margin: 0 auto 56px;">
            <div class="eyebrow reveal">More to stay in</div>
            <h2 class="display reveal" style="font-size: clamp(36px, 5vw, 64px); margin-top: 14px;">Other <em>rooms</em>.</h2>
          </header>
          <div style="display:grid; grid-template-columns: repeat(<?php echo esc_attr(min(3, $more->post_count)); ?>, 1fr); gap: 28px;">
            <?php while ($more->have_posts()) : $more->the_post();
              $m_id    = get_the_ID();
              $m_price = function_exists('get_field') ? get_field('price_per_night', $m_id) : null;
              $m_cur   = function_exists('get_field') ? (get_field('currency', $m_id) ?: 'USD') : 'USD';
              $m_thumb = get_the_post_thumbnail_url($m_id, 'large');
            ?>
              <article class="gs-card reveal" style="background:#fff; border-radius: var(--radius-lg, 14px); overflow:hidden; border:1px solid var(--line, #ede9d9);">
                <a href="<?php the_permalink(); ?>" class="ph kb" style="aspect-ratio: 4 / 3; display:block;">
                  <?php if ($m_thumb) : ?><img src="<?php echo esc_url($m_thumb); ?>" alt="" style="width:100%; height:100%; object-fit:cover;" /><?php endif; ?>
                </a>
                <div style="padding: 24px;">
                  <h3 class="display" style="font-size: 28px; margin: 0;">
                    <a href="<?php the_permalink(); ?>" style="color:inherit; text-decoration:none;"><?php the_title(); ?></a>
                  </h3>
                  <?php if ($m_price) : ?>
                    <div style="margin-top: 12px; font-size: 14px; color: var(--ink-2, #3d433d);">
                      <?php echo esc_html($m_cur . ' ' . number_format_i18n((float) $m_price)); ?> / night
                    </div>
                  <?php endif; ?>
                </div>
              </article>
            <?php endwhile; wp_reset_postdata(); ?>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <section class="single-room__cta" style="position: relative; overflow: hidden;">
      <?php $cta_bg = $thumb; ?>
      <div class="kb" style="position:absolute; inset:0; z-index:0;">
        <?php if ($cta_bg) : ?>
          <img src="<?php echo esc_url($cta_bg); ?>" alt="" style="width:100%; height:100%; object-fit:cover;" loading="lazy" />
        <?php endif; ?>
      </div>
      <div style="position:absolute; inset:0; z-index:0; background: linear-gradient(95deg, rgba(13,42,32,.86), rgba(13,42,32,.4));"></div>
      <div class="shell reveal" style="position:relative; z-index:1; color: var(--ivory, #f7f6f0); padding-block: 140px;">
        <div style="max-width: 720px;">
          <div class="eyebrow" style="color: var(--sun, #e8c46a);">Reserve your stay</div>
          <h2 class="display reveal--lg" style="font-size: clamp(48px, 6.8vw, 92px); margin-top: 22px; color: var(--ivory, #f7f6f0); max-width: 16ch;">
            A room is waiting <em>for you.</em>
          </h2>
          <p style="margin-top: 28px; line-height: 1.7; color: rgba(255,255,255,0.78); max-width: 540px; font-size: 17px;">
            Direct booking, best available rates. Reach our team for tailored stays, long-term bookings, and event packages.
          </p>
          <div class="btn-row" style="margin-top: 40px; display:flex; gap: 14px; flex-wrap: wrap;">
            <a href="<?php echo esc_url(add_query_arg('room_type', $ezee_id ?: $room_id, home_url('/booking'))); ?>" class="btn btn--sun btn--lg">
              <span class="ripple"></span>
              <span>Book this room</span>
              <svg width="14" height="10" viewBox="0 0 22 8" fill="none" aria-hidden="true" style="margin-left: 8px;">
                <path d="M0 4 L20 4 M14 0 L20 4 L14 8" stroke="currentColor" stroke-width="1.4" fill="none"/>
              </svg>
            </a>
            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn--ghost btn--light btn--lg">
              <span class="ripple"></span>
              <span>Talk to our team</span>
            </a>
          </div>
        </div>
      </div>
    </section>

  <?php endwhile; ?>

</main>

<style>
  @media (max-width: 1000px) {
    .single-room__layout { grid-template-columns: 1fr !important; gap: 48px !important; }
    .single-room__sidebar { position: static !important; }
    .single-room__specs { grid-template-columns: 1fr 1fr !important; }
    .single-room__gallery { grid-template-columns: 1fr !important; }
    .single-room__gallery .ph { grid-row: span 1 !important; height: 280px !important; }
  }
</style>

<?php get_footer(); ?>
