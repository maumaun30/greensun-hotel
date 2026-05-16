<?php get_header(); ?>

<main class="site-main">

  <?php while (have_posts()) : the_post();
    $room_id    = get_the_ID();
    $price      = function_exists('get_field') ? get_field('price_per_night', $room_id) : null;
    $currency   = function_exists('get_field') ? (get_field('currency', $room_id) ?: 'USD') : 'USD';
    $size       = function_exists('get_field') ? get_field('room_size', $room_id) : null;
    $guests     = function_exists('get_field') ? get_field('max_guests', $room_id) : null;
    $beds       = function_exists('get_field') ? get_field('bed_configuration', $room_id) : null;
    $tagline    = function_exists('get_field') ? get_field('tagline', $room_id) : null;
    $inclusions = function_exists('get_field') ? get_field('inclusions', $room_id) : [];
    $gallery    = function_exists('get_field') ? get_field('gallery', $room_id) : [];
    $ezee_id    = function_exists('get_field') ? get_field('ezee_room_type_id', $room_id) : '';
    $thumb      = get_the_post_thumbnail_url($room_id, 'full');
  ?>

    <section class="gs-hero" style="position:relative; min-height: 70vh; display:flex; align-items:flex-end; overflow:hidden;">
      <?php if ($thumb) : ?>
        <div class="kb" style="position:absolute; inset:0; z-index:0;">
          <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" style="width:100%; height:100%; object-fit:cover;" />
          <div style="position:absolute; inset:0; background: linear-gradient(180deg, rgba(15,32,24,0) 30%, rgba(15,32,24,0.65) 100%);"></div>
        </div>
      <?php else : ?>
        <div style="position:absolute; inset:0; background: var(--forest, #1f4a3a); z-index:0;"></div>
      <?php endif; ?>
      <div class="shell" style="position:relative; z-index:1; padding-block: 80px; color: var(--ivory, #f7f6f0);">
        <?php if ($tagline) : ?>
          <div class="eyebrow reveal" style="color: var(--sun, #e8c46a);"><?php echo esc_html($tagline); ?></div>
        <?php endif; ?>
        <h1 class="display reveal" style="font-size: clamp(48px, 7vw, 104px); margin-top: 16px; max-width: 18ch;">
          <?php the_title(); ?>
        </h1>
        <?php if ($size || $guests || $beds) : ?>
          <ul class="reveal" style="list-style:none; padding:0; margin: 22px 0 0; display:flex; flex-wrap:wrap; gap: 22px; font-size: 13px; letter-spacing: 0.16em; text-transform: uppercase; opacity: 0.9;">
            <?php if ($size)   : ?><li><?php echo esc_html($size); ?></li><?php endif; ?>
            <?php if ($guests) : ?><li><?php echo esc_html(sprintf(_n('%d guest', '%d guests', (int)$guests, 'greensun-hotel'), (int)$guests)); ?></li><?php endif; ?>
            <?php if ($beds)   : ?><li><?php echo esc_html($beds); ?></li><?php endif; ?>
          </ul>
        <?php endif; ?>
      </div>
    </section>

    <section class="section">
      <div class="shell" style="display:grid; grid-template-columns: 1.4fr 1fr; gap: 80px; align-items:start;">

        <div class="room-detail__body">
          <?php if (get_the_content()) : ?>
            <div class="reveal" style="font-size: 18px; line-height: 1.8; color: var(--ink, #1a1f1a); max-width: 60ch;">
              <?php the_content(); ?>
            </div>
          <?php endif; ?>

          <?php if (!empty($inclusions) && is_array($inclusions)) : ?>
            <div class="reveal" style="margin-top: 56px;">
              <div class="eyebrow">Inclusions</div>
              <ul style="list-style:none; padding:0; margin: 18px 0 0; display:grid; grid-template-columns: repeat(2, 1fr); gap: 12px 24px;">
                <?php foreach ($inclusions as $item) :
                  $label = is_array($item) ? ($item['inclusion'] ?? reset($item)) : $item;
                ?>
                  <li style="display:flex; gap: 12px; align-items: baseline;">
                    <span aria-hidden="true" style="color: var(--moss, #527a55);">·</span>
                    <span style="line-height: 1.6;"><?php echo esc_html($label); ?></span>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <?php if (!empty($gallery) && is_array($gallery)) : ?>
            <div class="reveal" style="margin-top: 64px; display:grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
              <?php foreach ($gallery as $i => $image) :
                $url = is_array($image) ? ($image['sizes']['large'] ?? $image['url'] ?? '') : '';
                $alt = is_array($image) ? ($image['alt'] ?? '') : '';
                if (!$url) continue;
                $is_wide = ($i % 3 === 0);
              ?>
                <div class="ph" style="aspect-ratio: <?php echo $is_wide ? '16 / 10' : '4 / 5'; ?>; <?php echo $is_wide ? 'grid-column: 1 / -1;' : ''; ?>">
                  <img src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr($alt); ?>" style="width:100%; height:100%; object-fit:cover;" />
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

        <aside class="room-detail__sidebar reveal" style="position: sticky; top: 100px;">
          <div style="background:#fff; border:1px solid var(--line, #ede9d9); border-radius: var(--radius-lg, 14px); padding: 32px;">
            <?php if ($price) : ?>
              <div style="display:flex; align-items:baseline; gap: 8px;">
                <span style="font-family: var(--font-display, 'Cormorant Garamond', serif); font-size: 48px; line-height: 1;"><?php echo esc_html($currency . ' ' . number_format_i18n((float) $price)); ?></span>
                <span style="font-size: 12px; letter-spacing: 0.16em; text-transform: uppercase; color: var(--ink-2, #3d433d);">/ night</span>
              </div>
            <?php endif; ?>

            <form class="booking-bar" action="<?php echo esc_url(home_url('/booking')); ?>" method="get" style="display:grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-top: 24px;">
              <input type="hidden" name="room_type" value="<?php echo esc_attr($ezee_id ?: get_post_field('post_name', $room_id)); ?>" />
              <label class="field" style="grid-column: 1 / -1;">
                <span style="display:block; font-size: 11px; letter-spacing: 0.18em; text-transform: uppercase; color: var(--mute, #7b817b); margin-bottom: 6px;">Check-in</span>
                <input type="date" name="checkin" min="<?php echo esc_attr(date('Y-m-d')); ?>" required style="width:100%; border:0; border-bottom:1px solid var(--line); padding: 8px 0; font: inherit; background: transparent;" />
              </label>
              <label class="field" style="grid-column: 1 / -1;">
                <span style="display:block; font-size: 11px; letter-spacing: 0.18em; text-transform: uppercase; color: var(--mute, #7b817b); margin-bottom: 6px;">Check-out</span>
                <input type="date" name="checkout" min="<?php echo esc_attr(date('Y-m-d', strtotime('+1 day'))); ?>" required style="width:100%; border:0; border-bottom:1px solid var(--line); padding: 8px 0; font: inherit; background: transparent;" />
              </label>
              <label class="field" style="grid-column: 1 / -1;">
                <span style="display:block; font-size: 11px; letter-spacing: 0.18em; text-transform: uppercase; color: var(--mute, #7b817b); margin-bottom: 6px;">Guests</span>
                <select name="guests" style="width:100%; border:0; border-bottom:1px solid var(--line); padding: 8px 0; font: inherit; background: transparent;">
                  <?php for ($g = 1; $g <= ($guests ? (int)$guests : 6); $g++) : ?>
                    <option value="<?php echo esc_attr($g); ?>" <?php selected($g, 2); ?>><?php echo esc_html(sprintf(_n('%d guest', '%d guests', $g, 'greensun-hotel'), $g)); ?></option>
                  <?php endfor; ?>
                </select>
              </label>
              <button type="submit" class="btn btn--sun" style="grid-column: 1 / -1; justify-content: center;">
                <span class="ripple"></span>
                <span>Check availability</span>
              </button>
            </form>

            <p style="margin-top: 18px; font-size: 12px; color: var(--mute, #7b817b); text-align:center;">No charge until confirmation.</p>
          </div>
        </aside>

      </div>
    </section>

    <?php
    // Optional: surface other rooms below the detail.
    $more = new WP_Query([
        'post_type'      => 'room',
        'posts_per_page' => 3,
        'post__not_in'   => [$room_id],
        'orderby'        => 'rand',
    ]);
    if ($more->have_posts()) :
    ?>
      <section class="section" style="background: var(--paper, #f4f1e6);">
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

  <?php endwhile; ?>

</main>

<style>
  @media (max-width: 1000px) {
    .room-detail__body + .room-detail__sidebar,
    .single-room main .shell { grid-template-columns: 1fr !important; }
    .room-detail__sidebar { position: static !important; }
  }
</style>

<?php get_footer(); ?>
