<?php
/**
 * Greensun brand mark — inline SVG with optional wordmark.
 *
 * @param int  $size  Pixel size of the icon (square).
 * @param bool $label Whether to render the "GreenSun" wordmark next to it.
 */

if (!defined('ABSPATH')) {
    exit;
}

function greensun_logo($size = 36, $label = true) {
    $size = (int) $size;
    ?>
    <a href="<?php echo esc_url(home_url('/')); ?>" class="gs-logo" style="display:inline-flex; align-items:center; gap:12px; color: inherit; text-decoration:none;">
      <svg width="<?php echo esc_attr($size); ?>" height="<?php echo esc_attr($size); ?>" viewBox="0 0 48 48" fill="none" aria-hidden="true">
        <g style="transform-origin:24px 24px;">
          <line x1="24" y1="3" x2="24" y2="9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" transform="rotate(0 24 24)" opacity="0.7"/>
          <line x1="24" y1="3" x2="24" y2="9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" transform="rotate(45 24 24)" opacity="0.7"/>
          <line x1="24" y1="3" x2="24" y2="9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" transform="rotate(90 24 24)" opacity="0.7"/>
          <line x1="24" y1="3" x2="24" y2="9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" transform="rotate(135 24 24)" opacity="0.7"/>
          <line x1="24" y1="5" x2="24" y2="9" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" transform="rotate(22.5 24 24)" opacity="0.45"/>
          <line x1="24" y1="5" x2="24" y2="9" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" transform="rotate(67.5 24 24)" opacity="0.45"/>
          <line x1="24" y1="5" x2="24" y2="9" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" transform="rotate(112.5 24 24)" opacity="0.45"/>
          <line x1="24" y1="5" x2="24" y2="9" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" transform="rotate(157.5 24 24)" opacity="0.45"/>
        </g>
        <circle cx="24" cy="24" r="8" fill="currentColor" opacity="0.18"/>
        <path d="M16 28 C 20 18, 28 14, 34 14 C 34 22, 28 30, 18 31 C 16 30.5, 16 29, 16 28 Z" fill="currentColor"/>
        <path d="M17 30 L 27 19" stroke="var(--ivory, #f7f6f0)" stroke-width="1" stroke-linecap="round" opacity=".7"/>
      </svg>
      <?php if ($label) : ?>
        <span style="font-family: var(--font-display, 'Cormorant Garamond', serif); font-size: 20px; letter-spacing: 0.02em; line-height: 1; font-weight: 500;">
          Green<span style="font-style: italic; opacity: 0.85; margin-left: 4px;">Sun</span>
        </span>
      <?php endif; ?>
    </a>
    <?php
}
