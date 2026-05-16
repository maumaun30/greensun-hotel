<?php
$section_title    = $attributes['sectionTitle'] ?? '';
$section_subtitle = $attributes['sectionSubtitle'] ?? '';
$steps            = $attributes['steps'] ?? [];
$total            = count( $steps );
?>

<section <?php echo get_block_wrapper_attributes(['class' => 'greensun-hotel-steps']); ?>>
  <div class="greensun-hotel-steps__container">

    <?php if ( $section_title || $section_subtitle ) : ?>
      <div class="greensun-hotel-steps__heading">
        <?php if ( $section_title ) : ?>
          <h2 class="greensun-hotel-steps__title">
            <?php echo wp_kses_post( $section_title ); ?>
          </h2>
        <?php endif; ?>
        <?php if ( $section_subtitle ) : ?>
          <p class="greensun-hotel-steps__subtitle">
            <?php echo wp_kses_post( $section_subtitle ); ?>
          </p>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <div class="greensun-hotel-steps__grid">
      <?php foreach ( $steps as $index => $step ) :
        $number      = esc_html( $step['number'] ?? '' );
        $title       = esc_html( $step['title'] ?? '' );
        $description = esc_html( $step['description'] ?? '' );
        $is_last     = $index === $total - 1;
      ?>
        <div class="greensun-hotel-steps__card">

          <?php if ( ! $is_last ) : ?>
            <div class="greensun-hotel-steps__connector" aria-hidden="true"></div>
          <?php endif; ?>

          <div class="greensun-hotel-steps__number"><?php echo $number; ?></div>

          <h3 class="greensun-hotel-steps__card-title"><?php echo $title; ?></h3>

          <p class="greensun-hotel-steps__card-desc"><?php echo $description; ?></p>

          <div class="greensun-hotel-steps__glow" aria-hidden="true"></div>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>