<?php
$tagline = greensun_setting('brand_tagline', get_bloginfo('description'));
$address = greensun_setting('address');
$phone   = greensun_setting('phone');
$email   = greensun_setting('email', get_bloginfo('admin_email'));
$socials = array_filter([
    'Facebook'    => greensun_setting('social_facebook'),
    'Instagram'   => greensun_setting('social_instagram'),
    'Tripadvisor' => greensun_setting('social_tripadvisor'),
]);
$legal_html = greensun_setting('footer_legal_html', '<a href="#">Privacy</a><a href="#">Terms</a><a href="#">FAQ</a>');
?>
<footer class="site-footer">
  <div class="shell site-footer__inner">
    <div class="site-footer__grid">
      <div class="site-footer__brand">
        <div style="color: var(--sun, #e8c46a);">
          <?php greensun_logo(36, true); ?>
        </div>
        <?php if ($tagline) : ?>
          <p class="site-footer__tagline"><?php echo wp_kses_post($tagline); ?></p>
        <?php endif; ?>
        <?php if (!empty($socials)) : ?>
          <ul class="site-footer__socials">
            <?php foreach ($socials as $name => $url) : ?>
              <li>
                <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr($name); ?>">
                  <?php echo esc_html(substr($name, 0, 1)); ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>

      <div class="site-footer__col">
        <h4 class="site-footer__head">Stay</h4>
        <?php if (has_nav_menu('footer-stay')) : ?>
          <?php wp_nav_menu(['theme_location' => 'footer-stay', 'container' => false, 'menu_class' => 'site-footer__links', 'depth' => 1, 'fallback_cb' => false]); ?>
        <?php else : ?>
          <ul class="site-footer__links">
            <li><a href="<?php echo esc_url(home_url('/rooms')); ?>">Rooms &amp; suites</a></li>
            <li><a href="<?php echo esc_url(home_url('/booking')); ?>">Book a room</a></li>
            <li><a href="<?php echo esc_url(home_url('/contact')); ?>">Group bookings</a></li>
          </ul>
        <?php endif; ?>
      </div>

      <div class="site-footer__col">
        <h4 class="site-footer__head">Hotel</h4>
        <?php if (has_nav_menu('footer-hotel')) : ?>
          <?php wp_nav_menu(['theme_location' => 'footer-hotel', 'container' => false, 'menu_class' => 'site-footer__links', 'depth' => 1, 'fallback_cb' => false]); ?>
        <?php else : ?>
          <ul class="site-footer__links">
            <li><a href="<?php echo esc_url(home_url('/about')); ?>">About</a></li>
            <li><a href="<?php echo esc_url(home_url('/events')); ?>">Events</a></li>
            <li><a href="<?php echo esc_url(home_url('/gallery')); ?>">Gallery</a></li>
            <li><a href="<?php echo esc_url(home_url('/contact')); ?>">Contact</a></li>
          </ul>
        <?php endif; ?>
      </div>

      <div class="site-footer__col">
        <h4 class="site-footer__head">Reach us</h4>
        <ul class="site-footer__contact">
          <?php if ($phone) : ?>
            <li><a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a></li>
          <?php endif; ?>
          <?php if ($email) : ?>
            <li><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></li>
          <?php endif; ?>
          <?php if ($address) : ?>
            <li class="site-footer__address"><?php echo nl2br(esc_html($address)); ?></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>

    <div class="site-footer__bottom">
      <div>&copy; <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('All rights reserved.', 'greensun-hotel'); ?></div>
      <div class="site-footer__legal"><?php echo wp_kses_post($legal_html); ?></div>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
