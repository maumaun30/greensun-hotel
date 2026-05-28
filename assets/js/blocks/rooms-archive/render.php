<?php
$eyebrow  = $attributes['eyebrow'] ?? '';
$title    = $attributes['title']   ?? '';
$lead     = $attributes['lead']    ?? '';
$per_page = max(1, min(24, (int) ($attributes['perPage'] ?? 12)));

$paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
$rooms = new WP_Query([
    'post_type'      => 'room',
    'post_status'    => 'publish',
    'posts_per_page' => $per_page,
    'orderby'        => 'menu_order date',
    'order'          => 'ASC',
    'paged'          => $paged,
]);
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'rooms-archive-block']); ?>>

  <div class="rooms-archive__header">
    <div class="shell">
      <?php if ($eyebrow) : ?>
        <div class="eyebrow reveal" style="margin-bottom: 22px;"><?php echo esc_html($eyebrow); ?></div>
      <?php endif; ?>
      <?php if ($title) : ?>
        <h1 class="display reveal reveal--lg rooms-archive__title"><?php echo wp_kses_post($title); ?></h1>
      <?php endif; ?>
      <?php if ($lead) : ?>
        <p class="reveal rooms-archive__lead"><?php echo wp_kses_post($lead); ?></p>
      <?php endif; ?>
    </div>
  </div>

  <div class="shell" style="padding: 40px 0 120px;">
    <?php if ($rooms->have_posts()) : ?>
      <ul class="rooms-archive__stack">
        <?php $room_idx = ($paged - 1) * $per_page; while ($rooms->have_posts()) : $rooms->the_post();
          $room_id  = get_the_ID();
          $price    = function_exists('get_field') ? get_field('price_per_night', $room_id) : null;
          $currency = function_exists('get_field') ? (get_field('currency', $room_id) ?: 'USD') : 'USD';
          $size     = function_exists('get_field') ? get_field('room_size', $room_id) : null;
          $guests   = function_exists('get_field') ? (int) get_field('max_guests', $room_id) : 0;
          $beds     = function_exists('get_field') ? get_field('bed_configuration', $room_id) : null;
          $tagline  = function_exists('get_field') ? get_field('tagline', $room_id) : null;
          $flip     = ($room_idx % 2) === 1;

          $inclusions = function_exists('get_field') ? get_field('inclusions', $room_id) : [];
          $chips      = [];
          if (!empty($inclusions) && is_array($inclusions)) {
            foreach ($inclusions as $inc) {
              $label = is_array($inc) ? ($inc['text'] ?? $inc['inclusion'] ?? reset($inc)) : $inc;
              if ($label) $chips[] = $label;
              if (count($chips) >= 6) break;
            }
          }
          if (empty($chips)) $chips = ['WiFi', 'AC', '43" TV', 'Hot shower', 'Toiletries'];

          $has_window = false;
          foreach ($chips as $c) { if (stripos($c, 'window') !== false) { $has_window = true; break; } }
        ?>
          <li>
            <article class="rooms-archive__row reveal reveal--lg<?php echo $flip ? ' is-flipped' : ''; ?>">
              <div class="rooms-archive__media">
                <a href="<?php the_permalink(); ?>" class="ph kb rooms-archive__img" style="display:block; height: 520px; border-radius: 4px; overflow:hidden;">
                  <?php echo greensun_post_thumbnail_html($room_id, 'large', '(max-width: 900px) 100vw, 60vw', 'rooms-archive__img-el'); ?>
                </a>
                <div class="rooms-archive__spec-card">
                  <?php if ($size) : ?>
                    <div class="rooms-archive__spec">
                      <div class="display"><?php echo esc_html(preg_replace('/[^0-9.]/', '', (string) $size) ?: $size); ?></div>
                      <div class="rooms-archive__spec-unit">sqm</div>
                    </div>
                  <?php endif; ?>
                  <?php if ($guests) : ?>
                    <div class="rooms-archive__spec">
                      <div class="display"><?php echo esc_html((string) $guests); ?></div>
                      <div class="rooms-archive__spec-unit"><?php echo esc_html($guests === 1 ? 'guest' : 'guests'); ?></div>
                    </div>
                  <?php endif; ?>
                  <div class="rooms-archive__spec">
                    <div class="display"><?php echo $has_window ? 'yes' : 'no'; ?></div>
                    <div class="rooms-archive__spec-unit">window</div>
                  </div>
                </div>
              </div>

              <div class="rooms-archive__body">
                <div class="rooms-archive__num">
                  <?php echo esc_html(sprintf('%02d', $room_idx + 1)); ?><?php echo $beds ? ' — ' . esc_html($beds) : ''; ?>
                </div>
                <h2 class="display rooms-archive__name">
                  <a href="<?php the_permalink(); ?>" style="color: inherit; text-decoration: none;"><?php the_title(); ?></a>
                  <?php if ($tagline) : ?>
                    <br><em><?php echo esc_html($tagline); ?></em>
                  <?php endif; ?>
                </h2>
                <?php if (get_the_excerpt()) : ?>
                  <p class="rooms-archive__blurb"><?php echo esc_html(get_the_excerpt()); ?></p>
                <?php endif; ?>

                <?php if (!empty($chips)) : ?>
                  <div class="rooms-archive__chips">
                    <?php foreach ($chips as $c) : ?>
                      <span class="chip"><span class="dot"></span><?php echo esc_html($c); ?></span>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>

                <div class="rooms-archive__cta-row">
                  <?php if ($price) : ?>
                    <div>
                      <div class="rooms-archive__from">from</div>
                      <div class="display rooms-archive__price">
                        <?php echo esc_html($currency . ' ' . number_format_i18n((float) $price)); ?>
                        <span class="rooms-archive__per">/ NIGHT</span>
                      </div>
                    </div>
                  <?php endif; ?>
                  <div class="rooms-archive__buttons">
                    <a href="<?php the_permalink(); ?>" class="btn btn--ghost">
                      <span class="ripple"></span>
                      <span>View details</span>
                    </a>
                    <a href="<?php echo esc_url(add_query_arg('room_type', $room_id, home_url('/booking'))); ?>" class="btn btn--sun">
                      <span class="ripple"></span>
                      <span>Book this room</span>
                      <svg width="14" height="10" viewBox="0 0 22 8" fill="none" aria-hidden="true" style="margin-left: 8px;">
                        <path d="M0 4 L20 4 M14 0 L20 4 L14 8" stroke="currentColor" stroke-width="1.4" fill="none"/>
                      </svg>
                    </a>
                  </div>
                </div>
              </div>
            </article>
          </li>
        <?php $room_idx++; endwhile; ?>
      </ul>

      <?php if ($rooms->max_num_pages > 1) : ?>
        <div class="reveal" style="margin-top: 80px; display:flex; justify-content:center;">
          <?php echo paginate_links([
            'total'     => $rooms->max_num_pages,
            'current'   => $paged,
            'mid_size'  => 1,
            'prev_text' => '←',
            'next_text' => '→',
          ]); ?>
        </div>
      <?php endif; ?>
    <?php else : ?>
      <p style="text-align:center; color: var(--ink-2, #3d433d);">No rooms have been published yet.</p>
    <?php endif; wp_reset_postdata(); ?>
  </div>

</section>
