(function () {
  const config = window.GreensunBooking || {};

  const fmtDay = (d) => d.toLocaleDateString('en-US', { day: 'numeric', month: 'short' });
  const fmtSub = (d) => d.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric' });
  const fmtIso = (d) => {
    const y = d.getFullYear(), m = String(d.getMonth() + 1).padStart(2, '0'), day = String(d.getDate()).padStart(2, '0');
    return `${y}-${m}-${day}`;
  };
  const parseIso = (s) => {
    const [y, m, d] = s.split('-').map(Number);
    return new Date(y, m - 1, d);
  };
  const startOfDay = (d) => { const x = new Date(d); x.setHours(0, 0, 0, 0); return x; };

  function $(sel, root) { return (root || document).querySelector(sel); }
  function $$(sel, root) { return Array.from((root || document).querySelectorAll(sel)); }

  function buildCalendar(container, state) {
    const view = state.view;
    container.innerHTML = '';
    const head = document.createElement('div');
    head.className = 'bb-cal__head';
    const prev = document.createElement('button');
    prev.type = 'button';
    prev.className = 'bb-cal__nav';
    prev.setAttribute('aria-label', 'Previous month');
    prev.textContent = '‹';
    const title = document.createElement('div');
    title.className = 'bb-cal__title display';
    title.textContent = view.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
    const next = document.createElement('button');
    next.type = 'button';
    next.className = 'bb-cal__nav';
    next.setAttribute('aria-label', 'Next month');
    next.textContent = '›';
    head.appendChild(prev); head.appendChild(title); head.appendChild(next);

    const dow = document.createElement('div');
    dow.className = 'bb-cal__dow';
    ['S', 'M', 'T', 'W', 'T', 'F', 'S'].forEach((d) => {
      const s = document.createElement('span');
      s.textContent = d;
      dow.appendChild(s);
    });

    const grid = document.createElement('div');
    grid.className = 'bb-cal__grid';
    const firstDow = new Date(view.getFullYear(), view.getMonth(), 1).getDay();
    const daysInMonth = new Date(view.getFullYear(), view.getMonth() + 1, 0).getDate();
    const min = state.min ? startOfDay(state.min) : null;
    const sel = state.value ? startOfDay(state.value) : null;
    for (let i = 0; i < 42; i++) {
      const dnum = i - firstDow + 1;
      if (dnum < 1 || dnum > daysInMonth) {
        const blank = document.createElement('div');
        blank.className = 'bb-cal__day bb-cal__day--blank';
        grid.appendChild(blank);
        continue;
      }
      const d = new Date(view.getFullYear(), view.getMonth(), dnum);
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'bb-cal__day';
      btn.textContent = String(dnum);
      if (min && d < min) btn.disabled = true;
      if (sel && d.getTime() === sel.getTime()) btn.setAttribute('aria-selected', 'true');
      btn.addEventListener('click', () => state.onPick(d));
      grid.appendChild(btn);
    }

    container.appendChild(head);
    container.appendChild(dow);
    container.appendChild(grid);

    prev.addEventListener('click', () => {
      state.view = new Date(view.getFullYear(), view.getMonth() - 1, 1);
      buildCalendar(container, state);
    });
    next.addEventListener('click', () => {
      state.view = new Date(view.getFullYear(), view.getMonth() + 1, 1);
      buildCalendar(container, state);
    });
  }

  function bindForm(form) {
    const rooms = JSON.parse(form.dataset.rooms || '[]');
    const inArrival   = form.querySelector('input[name="checkin"]');
    const inDeparture = form.querySelector('input[name="checkout"]');
    const inGuests    = form.querySelector('input[name="guests"]');
    const inRoomType  = form.querySelector('input[name="room_type"]');

    const state = {
      arrival: parseIso(form.dataset.arrival),
      departure: parseIso(form.dataset.departure),
      guests: parseInt(inGuests.value, 10) || 2,
      roomType: '',
      open: null,
    };

    const boxes = {};
    $$('.bb-box', form).forEach((b) => { boxes[b.dataset.field] = b; });

    const closeAll = () => {
      state.open = null;
      Object.values(boxes).forEach((b) => {
        b.dataset.open = 'false';
        b.querySelector('.bb-box__trigger').setAttribute('aria-expanded', 'false');
      });
    };

    const openBox = (field) => {
      if (state.open === field) { closeAll(); return; }
      closeAll();
      state.open = field;
      const b = boxes[field];
      if (!b) return;
      b.dataset.open = 'true';
      b.querySelector('.bb-box__trigger').setAttribute('aria-expanded', 'true');

      if (field === 'arrival' || field === 'departure') {
        const isArr = field === 'arrival';
        const wrap = b.querySelector('.bb-cal');
        const cal = {
          view: new Date((isArr ? state.arrival : state.departure).getFullYear(), (isArr ? state.arrival : state.departure).getMonth(), 1),
          value: isArr ? state.arrival : state.departure,
          min: isArr ? startOfDay(new Date()) : new Date(state.arrival.getTime() + 86400000),
          onPick: (d) => {
            if (isArr) {
              state.arrival = d;
              if (state.arrival >= state.departure) {
                state.departure = new Date(d.getTime() + 2 * 86400000);
              }
              syncBindings();
              openBox('departure');
            } else {
              state.departure = d;
              syncBindings();
              closeAll();
            }
          },
        };
        buildCalendar(wrap, cal);
      }
    };

    // Wire triggers
    Object.entries(boxes).forEach(([field, b]) => {
      b.querySelector('.bb-box__trigger').addEventListener('click', (e) => {
        e.stopPropagation();
        openBox(field);
      });
      b.querySelector('.bb-popover').addEventListener('click', (e) => e.stopPropagation());
    });

    // Outside click + Escape
    document.addEventListener('mousedown', (e) => {
      if (!form.contains(e.target)) closeAll();
    });
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeAll();
    });

    // Stepper
    $$('.bb-step', form).forEach((btn) => {
      btn.addEventListener('click', () => {
        const step = parseInt(btn.dataset.step, 10);
        const next = Math.max(1, Math.min(4, state.guests + step));
        state.guests = next;
        syncBindings();
      });
    });

    // Room picker — build list
    const roomList = form.querySelector('[data-rooms-list]');
    if (roomList) {
      const make = (data) => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'bb-room' + (data.any ? ' bb-room--any' : '');
        btn.dataset.roomId = data.id || '';
        const thumb = data.any
          ? '<span class="bb-room__thumb">★</span>'
          : (data.thumb
              ? `<img class="bb-room__thumb" src="${data.thumb}" alt="">`
              : '<span class="bb-room__thumb"></span>');
        const meta = data.any
          ? 'Show me everything'
          : [data.size, data.beds].filter(Boolean).join(' · ');
        const price = data.any
          ? '<span class="bb-room__price" aria-hidden="true">★</span>'
          : (data.price ? `<span class="bb-room__price">${(data.currency || '₱')}${Number(data.price).toLocaleString()}</span>` : '');
        btn.innerHTML = `${thumb}<span><span class="bb-room__title">${data.name}</span><span class="bb-room__meta">${meta}</span></span>${price}`;
        btn.addEventListener('click', () => {
          state.roomType = data.id || '';
          $$('.bb-room', roomList).forEach((r) => r.removeAttribute('aria-selected'));
          btn.setAttribute('aria-selected', 'true');
          syncBindings();
          closeAll();
        });
        return btn;
      };
      roomList.appendChild(make({ any: true, name: 'Any room type' }));
      rooms.forEach((r) => roomList.appendChild(make(r)));
      $$('.bb-room', roomList)[0].setAttribute('aria-selected', 'true');
    }

    function syncBindings() {
      const nights = Math.max(0, Math.round((startOfDay(state.departure) - startOfDay(state.arrival)) / 86400000));
      const set = (key, value) => {
        const el = form.querySelector(`[data-bind="${key}"]`);
        if (el) el.textContent = value;
      };
      set('arrival-day', fmtDay(state.arrival));
      set('arrival-sub', fmtSub(state.arrival));
      set('departure-day', fmtDay(state.departure));
      set('departure-sub', `${nights} ${nights === 1 ? 'night' : 'nights'}`);
      set('guests-value', String(state.guests));
      set('guests-num', String(state.guests));
      set('guests-sub', state.guests === 1 ? 'Guest' : 'Guests');

      const room = rooms.find((r) => r.id === state.roomType);
      set('room-value', room ? room.name : 'Any room');
      set('room-sub', room
        ? (room.price ? `from ${(room.currency || '₱')}${Number(room.price).toLocaleString()}` : '')
        : `See all ${rooms.length} options`);

      inArrival.value   = fmtIso(state.arrival);
      inDeparture.value = fmtIso(state.departure);
      inGuests.value    = String(state.guests);
      inRoomType.value  = state.roomType;
    }
    syncBindings();

    // Submit — preserve REST-search behavior when configured
    form.addEventListener('submit', (e) => {
      if (!config.restUrl || !config.nonce) return; // allow default GET deep-link
      e.preventDefault();
      const panel = ensureResults(form);
      panel.innerHTML = '<p style="margin-top:18px;color:var(--mute);font-size:12px;letter-spacing:.18em;text-transform:uppercase;">Searching…</p>';

      fetch(config.restUrl + 'booking-search', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': config.nonce },
        body: JSON.stringify({
          checkin: inArrival.value,
          checkout: inDeparture.value,
          guests: state.guests,
          room_type: state.roomType || null,
        }),
      })
        .then((r) => r.json().then((j) => ({ ok: r.ok, json: j })))
        .then((res) => {
          if (!res.ok) {
            panel.innerHTML = `<p style="margin-top:18px;color:#a64;">${res.json.error || 'Search failed.'}</p>`;
            return;
          }
          renderResults(panel, res.json);
        })
        .catch(() => {
          panel.innerHTML = '<p style="margin-top:18px;color:#a64;">Network error. Try again.</p>';
        });
    });
  }

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
    const items = data.rooms.map((r) => {
      const total = (r.currency || 'USD') + ' ' + Math.round(r.total || 0).toLocaleString();
      const rate  = (r.currency || 'USD') + ' ' + Math.round(r.nightly_rate || 0).toLocaleString();
      return (
        '<article class="gs-card" style="display:flex;gap:18px;align-items:center;padding:16px;border:1px solid var(--line);border-radius:14px;background:#fff;margin-top:12px;">' +
          (r.thumbnail ? `<img src="${r.thumbnail}" alt="" style="width:96px;height:72px;object-fit:cover;border-radius:8px;flex:0 0 auto;">` : '') +
          '<div style="flex:1;">' +
            `<div style="font-family:var(--display);font-size:22px;">${r.title}</div>` +
            `<div style="font-size:13px;color:var(--ink-2);">${rate} / night</div>` +
          '</div>' +
          '<div style="text-align:right;">' +
            `<div style="font-family:var(--display);font-size:22px;">${total}</div>` +
            `<a href="${r.permalink}" class="btn btn--ghost" style="margin-top:8px;"><span>View</span></a>` +
          '</div>' +
        '</article>'
      );
    }).join('');
    panel.innerHTML =
      `<div style="margin-top:18px;display:flex;align-items:center;justify-content:space-between;gap:12px;">` +
        `<div style="font-size:12px;letter-spacing:.18em;text-transform:uppercase;color:var(--mute);">` +
          `${data.nights || ''} night${data.nights === 1 ? '' : 's'} · ${data.guests || ''} guest${data.guests === 1 ? '' : 's'}${mockBadge}` +
        `</div>` +
      `</div>${items}`;
  }

  document.addEventListener('DOMContentLoaded', () => {
    $$('.wp-block-greensun-hotel-booking-bar form.booking-bar').forEach(bindForm);
  });
})();
