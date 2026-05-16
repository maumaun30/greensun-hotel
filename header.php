<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="site-header" class="site-header" data-scrolled="false">
  <div class="shell site-header__inner">
    <div class="site-header__brand">
      <?php
        if (has_custom_logo()) {
            the_custom_logo();
        } else {
            greensun_logo(36, true);
        }
      ?>
    </div>

    <nav class="site-header__nav" aria-label="<?php esc_attr_e('Primary Menu', 'greensun-hotel'); ?>">
      <?php
        wp_nav_menu([
          'theme_location' => 'primary',
          'container'      => false,
          'menu_class'     => 'site-header__menu',
          'fallback_cb'    => false,
          'depth'          => 1,
        ]);
      ?>
      <a href="<?php echo esc_url(home_url('/booking')); ?>" class="btn btn--ghost site-header__cta">
        <span class="ripple"></span>
        <span>Book now</span>
      </a>
    </nav>

    <button
      id="mobile-menu-toggle"
      class="site-header__toggle"
      aria-label="<?php esc_attr_e('Toggle menu', 'greensun-hotel'); ?>"
      aria-controls="site-header-mobile"
      aria-expanded="false"
      type="button"
    >
      <span></span><span></span><span></span>
    </button>
  </div>

  <div id="site-header-mobile" class="site-header__mobile" hidden>
    <?php
      wp_nav_menu([
        'theme_location' => 'primary',
        'container'      => false,
        'menu_class'     => 'site-header__menu site-header__menu--mobile',
        'fallback_cb'    => false,
        'depth'          => 1,
      ]);
    ?>
    <a href="<?php echo esc_url(home_url('/booking')); ?>" class="btn btn--sun" style="margin-top: 20px;">
      <span class="ripple"></span>
      <span>Book now</span>
    </a>
  </div>
</header>
