<?php
$title         = $attributes['title']        ?? 'Send us a note';
$subtitle      = $attributes['subtitle']     ?? '';
$submit_text   = $attributes['submitText']   ?? 'Send message';
$success_title = $attributes['successTitle'] ?? 'Message sent.';
$success_text  = $attributes['successText']  ?? "We'll be in touch shortly. Thank you.";
$subjects      = $attributes['subjects']     ?? [
    'Make a reservation',
    'Book an ocular visit',
    'Events inquiry',
    'Partnership inquiry',
    'Long stay / corporate rate',
    'Press / media',
    'Careers',
    'Something else',
];

// Deep-link prefill from event pages: /contact?subject=…&space=…
$prefill_subject = isset($_GET['subject']) ? sanitize_text_field(wp_unslash($_GET['subject'])) : '';
$prefill_space   = isset($_GET['space'])   ? sanitize_text_field(wp_unslash($_GET['space']))   : '';
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
        <span>Phone</span>
        <input type="tel" name="phone" autocomplete="tel" />
      </label>
      <label class="field gs-cf-field">
        <span>I'd like to&hellip;</span>
        <select name="subject">
          <?php foreach ($subjects as $opt) : ?>
            <option value="<?php echo esc_attr($opt); ?>"<?php selected($prefill_subject, $opt); ?>><?php echo esc_html($opt); ?></option>
          <?php endforeach; ?>
        </select>
      </label>

      <?php if ($prefill_space) : ?>
        <input type="hidden" name="space" value="<?php echo esc_attr($prefill_space); ?>" />
        <div class="gs-cf-field gs-cf-field--full gs-contact-form__space-note">
          <span class="chip chip--moss"><span class="dot"></span><?php echo esc_html($prefill_space); ?></span>
        </div>
      <?php endif; ?>

      <label class="field gs-cf-field gs-cf-field--full">
        <span>Message</span>
        <textarea name="message" rows="5" required></textarea>
      </label>

      <label class="gs-cf-field gs-cf-field--full gs-contact-form__optin">
        <input type="checkbox" name="marketing" value="1" />
        <span class="gs-contact-form__optin-box" aria-hidden="true">
          <svg width="11" height="9" viewBox="0 0 11 9" fill="none"><path d="M1 4.5 L4 7.5 L10 1" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </span>
        <span class="gs-contact-form__optin-text">Keep me posted on offers, events and announcements from Green Sun.</span>
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
