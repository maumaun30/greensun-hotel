<footer class="site-footer" style="background: var(--forest-2); color: var(--ivory);">
    <div class="shell" style="padding: 64px 32px 40px;">
        <div class="row between" style="flex-wrap: wrap; gap: 32px; align-items: flex-start;">
            <div class="col gap-12" style="max-width: 320px;">
                <div class="display" style="font-size: 28px;"><?php bloginfo('name'); ?></div>
                <p class="mono" style="opacity: 0.7; line-height: 1.7;">
                    <?php echo esc_html(get_bloginfo('description')); ?>
                </p>
            </div>
            <?php if (has_nav_menu('primary')) : ?>
                <nav aria-label="<?php esc_attr_e('Footer Menu', 'greensun-hotel'); ?>">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => 'primary-menu',
                        'fallback_cb'    => false,
                        'depth'          => 1,
                    ]);
                    ?>
                </nav>
            <?php endif; ?>
        </div>
        <div class="mono" style="margin-top: 48px; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.1); opacity: 0.5; font-size: 11px;">
            &copy; <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('All rights reserved.', 'greensun-hotel'); ?>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
