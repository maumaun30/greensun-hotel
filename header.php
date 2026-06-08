<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#site-main" style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden;"><?php esc_html_e('Skip to content', 'greensun-hotel'); ?></a>

<?php
  $gs_division  = greensun_current_division();
  $gs_nav_items = greensun_division_nav_items($gs_division);
  $gs_cta       = greensun_division_cta($gs_division);
?>

<header id="site-header" class="site-header" data-scrolled="false" data-division="<?php echo esc_attr($gs_division); ?>" role="banner">
  <div class="shell site-header__inner">
    <div class="site-header__lead">
      <div class="site-header__brand">
        <?php
          if (has_custom_logo()) {
              the_custom_logo();
          } else {
              greensun_logo(36, true);
          }
        ?>
      </div>

      <?php // ── HOTEL | EVENTS division switcher ─────────────────── ?>
      <div class="site-header__division" data-active="<?php echo esc_attr($gs_division); ?>" role="tablist" aria-label="<?php esc_attr_e('Choose a division', 'greensun-hotel'); ?>">
        <span class="site-header__division-pill" aria-hidden="true"></span>
        <a class="site-header__division-tab" href="<?php echo esc_url(home_url('/')); ?>" role="tab" data-division="hotel" aria-selected="<?php echo $gs_division === 'hotel' ? 'true' : 'false'; ?>"><?php esc_html_e('Hotel', 'greensun-hotel'); ?></a>
        <a class="site-header__division-tab" href="<?php echo esc_url(get_post_type_archive_link('venue') ?: home_url('/venues')); ?>" role="tab" data-division="events" aria-selected="<?php echo $gs_division === 'events' ? 'true' : 'false'; ?>"><?php esc_html_e('Events', 'greensun-hotel'); ?></a>
      </div>
    </div>

    <nav class="site-header__nav" aria-label="<?php esc_attr_e('Primary Menu', 'greensun-hotel'); ?>">
      <ul class="site-header__menu">
        <?php foreach ($gs_nav_items as $gs_item) : ?>
          <li class="<?php echo greensun_nav_item_is_active($gs_item) ? 'current-menu-item' : ''; ?>">
            <a href="<?php echo esc_url($gs_item['url']); ?>"<?php echo greensun_nav_item_is_active($gs_item) ? ' aria-current="page"' : ''; ?>><?php echo esc_html($gs_item['label']); ?></a>
          </li>
        <?php endforeach; ?>
      </ul>
      <a href="<?php echo esc_url($gs_cta['url']); ?>" class="btn btn--ghost site-header__cta">
        <span class="ripple"></span>
        <span><?php echo esc_html($gs_cta['label']); ?></span>
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
    <div class="site-header__division site-header__division--mobile" data-active="<?php echo esc_attr($gs_division); ?>" role="tablist">
      <span class="site-header__division-pill" aria-hidden="true"></span>
      <a class="site-header__division-tab" href="<?php echo esc_url(home_url('/')); ?>" role="tab" data-division="hotel" aria-selected="<?php echo $gs_division === 'hotel' ? 'true' : 'false'; ?>"><?php esc_html_e('Hotel', 'greensun-hotel'); ?></a>
      <a class="site-header__division-tab" href="<?php echo esc_url(get_post_type_archive_link('venue') ?: home_url('/venues')); ?>" role="tab" data-division="events" aria-selected="<?php echo $gs_division === 'events' ? 'true' : 'false'; ?>"><?php esc_html_e('Events', 'greensun-hotel'); ?></a>
    </div>
    <ul class="site-header__menu site-header__menu--mobile">
      <?php foreach ($gs_nav_items as $gs_item) : ?>
        <li class="<?php echo greensun_nav_item_is_active($gs_item) ? 'current-menu-item' : ''; ?>">
          <a href="<?php echo esc_url($gs_item['url']); ?>"<?php echo greensun_nav_item_is_active($gs_item) ? ' aria-current="page"' : ''; ?>><?php echo esc_html($gs_item['label']); ?></a>
        </li>
      <?php endforeach; ?>
    </ul>
    <a href="<?php echo esc_url($gs_cta['url']); ?>" class="btn btn--sun" style="margin-top: 20px;">
      <span class="ripple"></span>
      <span><?php echo esc_html($gs_cta['label']); ?></span>
    </a>
  </div>
</header>
