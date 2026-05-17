(function () {
  var config = window.GreensunContact || {};

  function bindForm(form) {
    var panel  = form.querySelector('.gs-contact-form__panel');
    var done   = form.querySelector('.gs-contact-form__done');
    var status = form.querySelector('.gs-contact-form__status');
    var submit = form.querySelector('button[type="submit"]');
    var label  = form.querySelector('.gs-contact-form__submit-label');
    var initialLabel = label ? label.textContent : 'Send message';

    form.addEventListener('submit', function (e) {
      if (!config.restUrl || !config.nonce) return; // graceful no-op if not wired
      e.preventDefault();

      submit.disabled = true;
      if (label)  label.textContent = 'Sending…';
      if (status) status.textContent = '';
      form.classList.remove('is-error');

      var fd = new FormData(form);
      var body = {
        name:    fd.get('name')    || '',
        email:   fd.get('email')   || '',
        subject: fd.get('subject') || '',
        message: fd.get('message') || '',
        _hp:     fd.get('_hp')     || '',
      };

      fetch(config.restUrl + 'contact', {
        method:      'POST',
        credentials: 'same-origin',
        headers:     { 'Content-Type': 'application/json', 'X-WP-Nonce': config.nonce },
        body:        JSON.stringify(body),
      })
        .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, json: j }; }); })
        .then(function (res) {
          if (!res.ok) {
            form.classList.add('is-error');
            if (status) status.textContent = (res.json && res.json.error) || 'Could not send. Please try again.';
            submit.disabled = false;
            if (label) label.textContent = initialLabel;
            return;
          }
          // Swap the panel for the success card. Personalise body if name was supplied.
          if (panel && done) {
            var bodyEl = done.querySelector('.gs-contact-form__done-body');
            if (bodyEl && body.name) {
              bodyEl.textContent = bodyEl.textContent.replace(/\.\s*$/, '') + ', ' + body.name + '.';
            }
            panel.hidden = true;
            done.hidden  = false;
          }
        })
        .catch(function () {
          form.classList.add('is-error');
          if (status) status.textContent = 'Network error. Please try again.';
          submit.disabled = false;
          if (label) label.textContent = initialLabel;
        });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form.greensun-contact-form').forEach(bindForm);
  });
})();
