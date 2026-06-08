<?php
/**
 * Explore block — "Under our roof" on-site establishments (alternating
 * feature rows) + a Chino Roces neighborhood grid with walk-distance chips.
 *
 * Mockup-first: ships with the prototype's default content baked in so the
 * page is complete out of the box. Editors can override via the `onsite` /
 * `hood` attributes; in a later phase these become CMS content types.
 * Image URLs are Unsplash placeholders — swap for real photography.
 */

$onsite_eyebrow = $attributes['onsiteEyebrow'] ?? 'Under our roof';
$hood_eyebrow   = $attributes['hoodEyebrow']   ?? 'The neighborhood';
$hood_title     = $attributes['hoodTitle']     ?? 'Chino Roces, <em>on foot.</em>';
$hood_lead      = $attributes['hoodLead']      ?? "Our front desk keeps a running list of the good stuff nearby. Here's where we'd start.";
$cta_title      = $attributes['ctaTitle']      ?? 'Make Green Sun <em>your base in Makati.</em>';

$onsite = !empty($attributes['onsite']) ? $attributes['onsite'] : [
    ['name' => 'Sol Café',           'kind' => 'Coffee & all-day dining',  'hours' => '7:00 — 22:00 daily',      'tone' => 'sun',    'img' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=1200&q=80', 'blurb' => 'Our ground-floor café — single-origin pour-overs, a tight all-day menu, and the unofficial lobby of the neighborhood.'],
    ['name' => 'Verde Rooftop Bar',  'kind' => 'Cocktails & skyline',      'hours' => '17:00 — 01:00, Tue — Sun', 'tone' => 'forest', 'img' => 'https://images.unsplash.com/photo-1572116469696-31de0f17cc34?w=1200&q=80', 'blurb' => 'Five floors up — natural-wine flights, classic cocktails, and the best sunset seat on Chino Roces.'],
    ['name' => 'The Pantry',         'kind' => 'Bakery & provisions',      'hours' => '7:00 — 19:00 daily',      'tone' => 'sage',   'img' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=1200&q=80', 'blurb' => 'Morning loaves, laminated pastries, and a small shelf of local provisions to take home.'],
    ['name' => 'Reset Spa',          'kind' => 'Massage & recovery',       'hours' => '10:00 — 22:00 daily',     'tone' => 'ink',    'img' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=1200&q=80', 'blurb' => 'A quiet floor for post-flight recovery — deep tissue, hot stone, and a steam room that fixes most things.'],
];

$hood = !empty($attributes['hood']) ? $attributes['hood'] : [
    ['name' => 'Chino Roces Art Walk',    'kind' => 'Galleries',      'dist' => '4 min walk',  'tone' => 'sage',   'img' => 'https://images.unsplash.com/photo-1531058020387-3be344556be6?w=1000&q=80', 'blurb' => 'A cluster of contemporary galleries and project spaces — openings most Thursday evenings.'],
    ['name' => 'La Fuente Coffee',        'kind' => 'Specialty café', 'dist' => '6 min walk',  'tone' => 'sun',    'img' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1000&q=80', 'blurb' => 'A neighborhood roaster doing serious filter coffee in a sun-drenched corner unit.'],
    ['name' => 'Makati Saturday Market',  'kind' => 'Market',         'dist' => '9 min walk',  'tone' => 'forest', 'img' => 'https://images.unsplash.com/photo-1488459716781-31db52582fe9?w=1000&q=80', 'blurb' => 'Weekend produce, street food, and crafts — go early, go hungry.'],
    ['name' => 'Hidden Garden Bistro',    'kind' => 'Dinner',         'dist' => '7 min walk',  'tone' => 'ink',    'img' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1000&q=80', 'blurb' => 'Filipino comfort cooking under string lights — book ahead for the back garden.'],
    ['name' => 'Roces Vinyl & Books',     'kind' => 'Record store',   'dist' => '5 min walk',  'tone' => 'sage',   'img' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1000&q=80', 'blurb' => 'Crates of secondhand vinyl and a tight shelf of design books. The owner makes good recommendations.'],
    ['name' => 'The Running Loop',        'kind' => 'Outdoors',       'dist' => 'At the door', 'tone' => 'sun',    'img' => 'https://images.unsplash.com/photo-1476480862126-209bfaa8edc8?w=1000&q=80', 'blurb' => 'A 3.2 km neighborhood loop the front desk maps for early-rising guests.'],
];

/** Tinted-gradient fallback when no image is set (mirrors prototype <Img>). */
$gs_tone = function ($tone) {
    switch ($tone) {
        case 'forest': return 'linear-gradient(135deg, var(--forest), var(--forest-2))';
        case 'sun':    return 'linear-gradient(135deg, var(--sun), var(--sun-2))';
        case 'ink':    return 'linear-gradient(135deg, var(--ink-2), var(--ink))';
        case 'sage':
        default:       return 'linear-gradient(135deg, var(--sage), var(--sage-2))';
    }
};
?>
<div <?php echo get_block_wrapper_attributes(['class' => 'gs-explore']); ?>>

  <section class="section section--tight gs-explore__onsite">
    <div class="shell">
      <div class="eyebrow reveal" style="margin-bottom: 30px;"><?php echo esc_html($onsite_eyebrow); ?></div>
      <div class="gs-explore__rows">
        <?php foreach ($onsite as $i => $p) :
          $flip = ($i % 2) === 1;
          $img  = $p['img'] ?? '';
          $tone = $p['tone'] ?? 'sage';
        ?>
          <article class="gs-explore__row reveal<?php echo $flip ? ' gs-explore__row--flip' : ''; ?>">
            <div class="gs-explore__row-media ph kb" style="<?php echo $img ? '' : 'background:' . esc_attr($gs_tone($tone)) . ';'; ?>">
              <?php if ($img) : ?>
                <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($p['name'] ?? ''); ?>" loading="lazy" decoding="async" />
              <?php endif; ?>
            </div>
            <div class="gs-explore__row-body">
              <div class="mono muted"><?php echo esc_html($p['kind'] ?? ''); ?></div>
              <h3 class="display gs-explore__row-title"><?php echo esc_html($p['name'] ?? ''); ?></h3>
              <p class="gs-explore__row-blurb"><?php echo esc_html($p['blurb'] ?? ''); ?></p>
              <?php if (!empty($p['hours'])) : ?>
                <span class="chip"><span class="dot"></span><?php echo esc_html($p['hours']); ?></span>
              <?php endif; ?>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section gs-explore__hood">
    <div class="shell">
      <div class="gs-explore__hood-head">
        <div>
          <div class="eyebrow reveal"><?php echo esc_html($hood_eyebrow); ?></div>
          <h2 class="display reveal reveal--lg gs-explore__hood-title"><?php echo wp_kses_post($hood_title); ?></h2>
        </div>
        <p class="muted reveal gs-explore__hood-lead"><?php echo esc_html($hood_lead); ?></p>
      </div>
      <div class="gs-explore__grid">
        <?php foreach ($hood as $h) :
          $img  = $h['img'] ?? '';
          $tone = $h['tone'] ?? 'sage';
        ?>
          <article class="gs-explore__card reveal">
            <div class="gs-explore__card-media ph" style="<?php echo $img ? '' : 'background:' . esc_attr($gs_tone($tone)) . ';'; ?>">
              <?php if ($img) : ?>
                <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($h['name'] ?? ''); ?>" loading="lazy" decoding="async" />
              <?php endif; ?>
              <?php if (!empty($h['dist'])) : ?>
                <span class="chip gs-explore__card-dist"><span class="dot"></span><?php echo esc_html($h['dist']); ?></span>
              <?php endif; ?>
            </div>
            <div class="gs-explore__card-body">
              <div class="mono muted"><?php echo esc_html($h['kind'] ?? ''); ?></div>
              <h3 class="display gs-explore__card-title"><?php echo esc_html($h['name'] ?? ''); ?></h3>
              <p class="gs-explore__card-blurb"><?php echo esc_html($h['blurb'] ?? ''); ?></p>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section gs-explore__cta">
    <div class="shell">
      <h2 class="display reveal gs-explore__cta-title"><?php echo wp_kses_post($cta_title); ?></h2>
      <div class="btn-row reveal gs-explore__cta-row">
        <a class="btn btn--sun btn--lg" href="<?php echo esc_url(home_url('/booking')); ?>">
          <span style="position:relative;z-index:1;">Book a room</span>
          <svg class="arrow" width="22" height="8" viewBox="0 0 22 8" fill="none" aria-hidden="true"><path d="M0 4 L20 4 M14 0 L20 4 L14 8" stroke="currentColor" stroke-width="1.4"/></svg>
          <span class="ripple"></span>
        </a>
        <a class="btn btn--ghost btn--lg" href="<?php echo esc_url(home_url('/rooms')); ?>">
          <span style="position:relative;z-index:1;">See the rooms</span>
          <span class="ripple"></span>
        </a>
      </div>
    </div>
  </section>

</div>
