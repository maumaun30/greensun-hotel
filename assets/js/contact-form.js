(function () {
  const config = window.GreensunContact || {};

  function bindForm(form) {
    form.addEventListener('submit', function (e) {
      if (!config.restUrl || !config.nonce) return; // graceful no-op if not wired
      e.preventDefault();

      const status = form.querySelector('.contact-form__status');
      const submit = form.querySelector('button[type="submit"]');
      const label  = form.querySelector('.contact-form__submit-label');
      const successText = form.getAttribute('data-success') || 'Thanks — message sent.';

      submit.disabled = true;
      if (label) label.textContent = 'Sending…';
      if (status) status.textContent = '';

      const fd = new FormData(form);
      const body = {
        name:    fd.get('name'),
        email:   fd.get('email'),
        phone:   fd.get('phone') || '',
        message: fd.get('message'),
        _hp:     fd.get('_hp') || '',
      };

      fetch(config.restUrl + 'contact', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'X-WP-Nonce': config.nonce,
        },
        body: JSON.stringify(body),
      })
        .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, json: j }; }); })
        .then(function (res) {
          if (!res.ok) {
            status.textContent = res.json.error || 'Could not send. Please try again.';
            status.style.color = '#a64';
            submit.disabled = false;
            if (label) label.textContent = form.getAttribute('data-submit-label') || 'Send';
            return;
          }
          form.querySelectorAll('input, textarea, button').forEach(function (el) { el.disabled = true; });
          if (status) {
            status.textContent = successText;
            status.style.color = 'var(--moss, #527a55)';
          }
        })
        .catch(function () {
          status.textContent = 'Network error. Please try again.';
          status.style.color = '#a64';
          submit.disabled = false;
          if (label) label.textContent = form.getAttribute('data-submit-label') || 'Send';
        });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.wp-block-greensun-hotel-contact-form form.contact-form').forEach(function (form) {
      const label = form.querySelector('.contact-form__submit-label');
      if (label) form.setAttribute('data-submit-label', label.textContent);
      bindForm(form);
    });
  });
})();
