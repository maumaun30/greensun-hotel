(function () {
  const config = window.GreensunBooking || {};

  function $(sel, root) { return (root || document).querySelector(sel); }
  function $$(sel, root) { return Array.from((root || document).querySelectorAll(sel)); }

  function ensureResults(form) {
    let panel = form.parentElement.querySelector('.booking-bar__results');
    if (!panel) {
      panel = document.createElement('div');
      panel.className = 'booking-bar__results';
      panel.setAttribute('aria-live', 'polite');
      form.parentElement.appendChild(panel);
    }
    return panel;
  }

  function renderResults(panel, data) {
    if (!data || !Array.isArray(data.rooms) || data.rooms.length === 0) {
      panel.innerHTML = '<p style="margin-top:18px;color:var(--ink-2);">No rooms available for those dates.</p>';
      return;
    }
    const mockBadge = data.mock ? '<span class="chip" style="background:var(--bone);color:var(--ink-2);margin-left:8px;font-size:11px;">Mock</span>' : '';
    const items = data.rooms.map(function (r) {
      const total = (r.currency || 'USD') + ' ' + Math.round(r.total || 0).toLocaleString();
      const rate  = (r.currency || 'USD') + ' ' + Math.round(r.nightly_rate || 0).toLocaleString();
      return (
        '<article class="gs-card" style="display:flex;gap:18px;align-items:center;padding:16px;border:1px solid var(--line);border-radius:14px;background:#fff;margin-top:12px;">' +
          (r.thumbnail ? '<img src="' + r.thumbnail + '" alt="" style="width:96px;height:72px;object-fit:cover;border-radius:8px;flex:0 0 auto;">' : '') +
          '<div style="flex:1;">' +
            '<div style="font-family:var(--font-display);font-size:22px;">' + r.title + '</div>' +
            '<div style="font-size:13px;color:var(--ink-2);">' + rate + ' / night</div>' +
          '</div>' +
          '<div style="text-align:right;">' +
            '<div style="font-family:var(--font-display);font-size:22px;">' + total + '</div>' +
            '<a href="' + r.permalink + '" class="btn btn--ghost" style="margin-top:8px;"><span>View</span></a>' +
          '</div>' +
        '</article>'
      );
    }).join('');
    panel.innerHTML = (
      '<div style="margin-top:18px;display:flex;align-items:center;justify-content:space-between;gap:12px;">' +
        '<div style="font-size:12px;letter-spacing:.18em;text-transform:uppercase;color:var(--mute);">' +
          (data.nights || '') + ' night' + ((data.nights === 1) ? '' : 's') + ' · ' + (data.guests || '') + ' guest' + ((data.guests === 1) ? '' : 's') +
          mockBadge +
        '</div>' +
      '</div>' + items
    );
  }

  function bindForm(form) {
    form.addEventListener('submit', function (e) {
      // If no API configured at all, allow the default deep-link submit through.
      if (!config.restUrl || !config.nonce) return;
      e.preventDefault();

      const panel = ensureResults(form);
      panel.innerHTML = '<p style="margin-top:18px;color:var(--mute);font-size:12px;letter-spacing:.18em;text-transform:uppercase;">Searching…</p>';

      const fd = new FormData(form);
      const body = {
        checkin:   fd.get('checkin'),
        checkout:  fd.get('checkout'),
        guests:    parseInt(fd.get('guests') || '2', 10),
        room_type: fd.get('room_type') || null,
      };

      fetch(config.restUrl + 'booking-search', {
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
            panel.innerHTML = '<p style="margin-top:18px;color:#a64;">' + (res.json.error || 'Search failed.') + '</p>';
            return;
          }
          renderResults(panel, res.json);
        })
        .catch(function () {
          panel.innerHTML = '<p style="margin-top:18px;color:#a64;">Network error. Try again.</p>';
        });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    $$('.wp-block-greensun-hotel-booking-bar form.booking-bar').forEach(bindForm);
  });
})();
