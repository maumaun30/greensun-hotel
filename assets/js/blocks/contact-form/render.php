<?php
$eyebrow      = $attributes['eyebrow']      ?? '';
$title        = $attributes['sectionTitle'] ?? '';
$subtitle     = $attributes['subtitle']     ?? '';
$submit_text  = $attributes['submitText']   ?? 'Send';
$success_text = $attributes['successText']  ?? "Thanks — we'll be in touch within a day.";
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'section greensun-contact-form']); ?>>
  <div class="shell">
    <div class="contact-form__grid" style="display:grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items:start;">

      <div>
        <?php if ($eyebrow) : ?>
          <div class="eyebrow reveal"><?php echo esc_html($eyebrow); ?></div>
        <?php endif; ?>
        <?php if ($title) : ?>
          <h2 class="display reveal" style="font-size: clamp(36px, 5vw, 72px); margin-top: 14px; max-width: 14ch;">
            <?php echo wp_kses_post($title); ?>
          </h2>
        <?php endif; ?>
        <?php if ($subtitle) : ?>
          <p class="reveal" style="margin-top: 22px; color: var(--ink-2, #3d433d); line-height: 1.75; max-width: 44ch;">
            <?php echo wp_kses_post($subtitle); ?>
          </p>
        <?php endif; ?>
      </div>

      <form class="contact-form reveal" data-success="<?php echo esc_attr($success_text); ?>" style="padding: 36px; background:#fff; border:1px solid var(--line, #ede9d9); border-radius: var(--radius-lg, 14px);">
        <input type="hidden" name="_hp" value="" autocomplete="off" tabindex="-1" aria-hidden="true" style="display:none;" />
        <label class="field" style="display:block; margin-bottom: 18px;">
          <span style="display:block; font-size: 11px; letter-spacing: 0.18em; text-transform: uppercase; color: var(--mute, #7b817b); margin-bottom: 8px;">Name</span>
          <input type="text" name="name" required style="width:100%; border:0; border-bottom:1px solid var(--line); padding: 10px 0; font: inherit; background: transparent;" />
        </label>
        <label class="field" style="display:block; margin-bottom: 18px;">
          <span style="display:block; font-size: 11px; letter-spacing: 0.18em; text-transform: uppercase; color: var(--mute, #7b817b); margin-bottom: 8px;">Email</span>
          <input type="email" name="email" required style="width:100%; border:0; border-bottom:1px solid var(--line); padding: 10px 0; font: inherit; background: transparent;" />
        </label>
        <label class="field" style="display:block; margin-bottom: 18px;">
          <span style="display:block; font-size: 11px; letter-spacing: 0.18em; text-transform: uppercase; color: var(--mute, #7b817b); margin-bottom: 8px;">Phone (optional)</span>
          <input type="tel" name="phone" style="width:100%; border:0; border-bottom:1px solid var(--line); padding: 10px 0; font: inherit; background: transparent;" />
        </label>
        <label class="field" style="display:block; margin-bottom: 28px;">
          <span style="display:block; font-size: 11px; letter-spacing: 0.18em; text-transform: uppercase; color: var(--mute, #7b817b); margin-bottom: 8px;">Message</span>
          <textarea name="message" rows="5" required style="width:100%; border:0; border-bottom:1px solid var(--line); padding: 10px 0; font: inherit; background: transparent; resize: vertical;"></textarea>
        </label>
        <button type="submit" class="btn btn--sun" style="width:100%; justify-content:center;">
          <span class="ripple"></span>
          <span class="contact-form__submit-label"><?php echo esc_html($submit_text); ?></span>
        </button>
        <p class="contact-form__status" role="status" aria-live="polite" style="margin-top: 18px; font-size: 13px; color: var(--ink-2, #3d433d); min-height: 1.5em;"></p>
      </form>

    </div>
  </div>
</section>
