<?php get_header(); ?>

<main class="site-main">

  <section class="section greensun-rooms-archive-hero" style="padding-block: clamp(80px, 14vw, 160px); text-align:center;">
    <div class="shell">
      <div class="eyebrow reveal">Rooms &amp; Suites</div>
      <h1 class="display reveal" style="font-size: clamp(48px, 7vw, 104px); margin-top: 18px;">
        Find your <em>quiet</em>.
      </h1>
      <p class="reveal" style="margin: 22px auto 0; max-width: 56ch; color: var(--ink-2, #3d433d); line-height: 1.75;">
        Each room is a small theatre of light and stone — chosen for the view it frames and the silence it keeps.
      </p>
    </div>
  </section>

  <?php
  // If the Booking Bar block exists, render it inline so guests can search from the archive.
  $booking_block = WP_Block_Type_Registry::get_instance()->get_registered('greensun-hotel/booking-bar');
  if ($booking_block) {
      echo do_blocks('<!-- wp:greensun-hotel/booking-bar /-->');
  }
  ?>

  <section class="section">
    <div class="shell">
      <?php if (have_posts()) : ?>
        <div class="rooms-archive__grid" style="display:grid; grid-template-columns: repeat(2, 1fr); gap: 32px;">
          <?php while (have_posts()) : the_post();
            $room_id  = get_the_ID();
            $price    = function_exists('get_field') ? get_field('price_per_night', $room_id) : null;
            $currency = function_exists('get_field') ? (get_field('currency', $room_id) ?: 'USD') : 'USD';
            $size     = function_exists('get_field') ? get_field('room_size', $room_id) : null;
            $guests   = function_exists('get_field') ? get_field('max_guests', $room_id) : null;
            $beds     = function_exists('get_field') ? get_field('bed_configuration', $room_id) : null;
            $tagline  = function_exists('get_field') ? get_field('tagline', $room_id) : null;
            $thumb    = get_the_post_thumbnail_url($room_id, 'large');
          ?>
            <article class="gs-card reveal" style="background:#fff; border-radius: var(--radius-lg, 14px); overflow:hidden; border:1px solid var(--line, #ede9d9); display:flex; flex-direction:column;">
              <a href="<?php the_permalink(); ?>" class="ph kb" style="aspect-ratio: 16 / 10; display:block;">
                <?php if ($thumb) : ?>
                  <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" style="width:100%; height:100%; object-fit:cover;" />
                <?php endif; ?>
              </a>
              <div style="padding: 32px; display:flex; flex-direction:column; flex:1;">
                <?php if ($tagline) : ?>
                  <div class="eyebrow" style="color: var(--moss, #527a55);"><?php echo esc_html($tagline); ?></div>
                <?php endif; ?>
                <h2 class="display" style="font-size: 38px; margin: 8px 0 0;">
                  <a href="<?php the_permalink(); ?>" style="color: inherit; text-decoration: none;"><?php the_title(); ?></a>
                </h2>
                <?php if (get_the_excerpt()) : ?>
                  <p style="margin-top: 14px; color: var(--ink-2, #3d433d); line-height: 1.7;"><?php echo esc_html(get_the_excerpt()); ?></p>
                <?php endif; ?>
                <?php if ($size || $guests || $beds) : ?>
                  <ul style="list-style:none; padding:0; margin: 18px 0 0; display:flex; flex-wrap:wrap; gap: 14px; font-size: 13px; color: var(--ink-2, #3d433d);">
                    <?php if ($size)   : ?><li><?php echo esc_html($size); ?></li><?php endif; ?>
                    <?php if ($guests) : ?><li><?php echo esc_html(sprintf(_n('%d guest', '%d guests', (int)$guests, 'greensun-hotel'), (int)$guests)); ?></li><?php endif; ?>
                    <?php if ($beds)   : ?><li><?php echo esc_html($beds); ?></li><?php endif; ?>
                  </ul>
                <?php endif; ?>
                <div style="margin-top:auto; padding-top: 26px; display:flex; align-items:center; justify-content:space-between; gap: 16px;">
                  <?php if ($price) : ?>
                    <div>
                      <span style="font-family: var(--font-display, 'Cormorant Garamond', serif); font-size: 32px;"><?php echo esc_html($currency . ' ' . number_format_i18n((float) $price)); ?></span>
                      <span style="font-size: 12px; letter-spacing: 0.14em; text-transform: uppercase; color: var(--ink-2, #3d433d);"> / night</span>
                    </div>
                  <?php endif; ?>
                  <a href="<?php the_permalink(); ?>" class="btn">
                    <span class="ripple"></span>
                    <span>View room</span>
                  </a>
                </div>
              </div>
            </article>
          <?php endwhile; ?>
        </div>

        <div class="reveal" style="margin-top: 56px; display:flex; justify-content:center;">
          <?php the_posts_pagination([
              'mid_size'  => 1,
              'prev_text' => '←',
              'next_text' => '→',
          ]); ?>
        </div>
      <?php else : ?>
        <p style="text-align:center; color: var(--ink-2, #3d433d);">No rooms have been published yet.</p>
      <?php endif; ?>
    </div>
  </section>

</main>

<style>
  @media (max-width: 900px) {
    .rooms-archive__grid { grid-template-columns: 1fr !important; }
  }
</style>

<?php get_footer(); ?>
