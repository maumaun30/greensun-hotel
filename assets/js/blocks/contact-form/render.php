<?php
$title         = $attributes['title']        ?? 'Send us a note';
$subtitle      = $attributes['subtitle']     ?? '';
$submit_text   = $attributes['submitText']   ?? 'Send message';
$success_title = $attributes['successTitle'] ?? 'Message sent.';
$success_text  = $attributes['successText']  ?? "We'll be in touch shortly. Thank you.";
$subjects      = $attributes['subjects']     ?? ['Reservation', 'Events inquiry', 'Long stay', 'Press / partnerships', 'Other'];
?>
<form <?php echo get_block_wrapper_attributes(['class' => 'greensun-contact-form gs-contact-form reveal', 'data-success-title' => $success_title, 'data-success-text' => $success_text]); ?>>
  <div class="gs-contact-form__panel">
    <input type="hidden" name="_hp" value="" autocomplete="off" tabindex="-1" aria-hidden="true" style="display:none;" />

    <h3 class="display gs-contact-form__title"><?php echo esc_html($title); ?></h3>
    <?php if ($subtitle) : ?>
      <p class="gs-contact-form__sub"><?php echo esc_html($subtitle); ?></p>
    <?php endif; ?>

    <div class="gs-contact-form__fields">
      <label class="field gs-cf-field">
        <span>Your name</span>
        <input type="text" name="name" required autocomplete="name" />
      </label>
      <label class="field gs-cf-field">
        <span>Email</span>
        <input type="email" name="email" required autocomplete="email" />
      </label>
      <label class="field gs-cf-field">
        <span>Subject</span>
        <select name="subject">
          <?php foreach ($subjects as $opt) : ?>
            <option value="<?php echo esc_attr($opt); ?>"><?php echo esc_html($opt); ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label class="field gs-cf-field gs-cf-field--full">
        <span>Message</span>
        <textarea name="message" rows="5" required></textarea>
      </label>
    </div>

    <button type="submit" class="btn btn--sun btn--lg gs-contact-form__submit">
      <span class="ripple"></span>
      <span class="gs-contact-form__submit-label"><?php echo esc_html($submit_text); ?></span>
      <svg width="14" height="10" viewBox="0 0 22 8" fill="none" aria-hidden="true" style="margin-left: 8px;">
        <path d="M0 4 L20 4 M14 0 L20 4 L14 8" stroke="currentColor" stroke-width="1.4" fill="none"/>
      </svg>
    </button>

    <p class="gs-contact-form__status" role="status" aria-live="polite"></p>
  </div>

  <div class="gs-contact-form__done" hidden>
    <div class="gs-contact-form__check" aria-hidden="true">
      <svg width="40" height="40" viewBox="0 0 48 48" fill="none">
        <path d="M14 24 L21 31 L36 16" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </div>
    <h3 class="display gs-contact-form__done-title"><?php echo esc_html($success_title); ?></h3>
    <p class="gs-contact-form__done-body"><?php echo esc_html($success_text); ?></p>
  </div>
</form>
