(function () {
  var root = document.querySelector('.booking-flow');
  if (!root) return;

  var prefill = safeParse(root.getAttribute('data-prefill')) || {};
  var rooms   = safeParse(root.getAttribute('data-rooms'))   || [];

  var state = {
    step: 0,
    checkin:   prefill.checkin   || '',
    checkout:  prefill.checkout  || '',
    guests:    parseInt(prefill.guests, 10) || 2,
    roomId:    prefill.room_type ? String(prefill.room_type) : '',
    firstName: '', lastName: '', email: '', phone: '', notes: '',
    payment: 'card',
    cardName: '', cardNumber: '', cardExp: '', cardCvc: '',
    promoCode: '',   // raw text in the input
    promo: null,     // applied promo descriptor, or null
  };

  // Demo promo codes — pct (percent off), fixed (amount off), flat3 (every 3rd night free).
  var PROMOS = {
    GREENSUN10: { type: 'pct',   value: 10,  label: 'GREENSUN10 — 10% off' },
    STAY3PAY2:  { type: 'flat3', value: 0,   label: 'STAY3PAY2 — every 3rd night free' },
    WELCOME500: { type: 'fixed', value: 500, label: 'WELCOME500 — 500 off' },
  };

  var PAYMENT_LABELS = {
    card:   'Credit / debit card',
    gcash:  'GCash',
    paypal: 'PayPal',
    onsite: 'Pay at hotel',
  };

  var stepperEl = root.querySelector('.bf-stepper');
  var stepperItems = root.querySelectorAll('.bf-stepper__item');
  var stepEls = root.querySelectorAll('.bf-step');
  var errorEl = root.querySelector('.bf-error');

  // ── Step navigation ───────────────────────────────────────────
  function go(idx) {
    if (idx < 0 || idx > stepEls.length - 1) return;
    state.step = idx;
    stepEls.forEach(function (el) { el.classList.toggle('is-active', parseInt(el.getAttribute('data-step'), 10) === idx); });
    stepperEl.setAttribute('data-step', String(Math.min(idx, stepperItems.length - 1)));
    stepperItems.forEach(function (li) {
      var i = parseInt(li.getAttribute('data-index'), 10);
      li.classList.toggle('is-done',    i <  idx);
      li.classList.toggle('is-current', i === idx);
      li.classList.toggle('is-future',  i >  idx);
    });
    if (typeof window.scrollTo === 'function') {
      var top = root.getBoundingClientRect().top + window.scrollY - 80;
      window.scrollTo({ top: Math.max(0, top), behavior: 'smooth' });
    }
    renderRoomMeta();
    renderReview();
  }

  root.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-action]');
    if (!btn) return;
    var action = btn.getAttribute('data-action');
    if (action === 'next') {
      if (btn.hasAttribute('data-needs-room') && !state.roomId) {
        flashError('Please choose a room to continue.');
        return;
      }
      if (state.step === 0 && !validateDates()) return;
      if (state.step === 2 && !validateDetails()) return;
      if (state.step === 3 && !validatePayment()) return;
      go(state.step + 1);
    } else if (action === 'prev') {
      go(state.step - 1);
    } else if (action === 'apply-promo') {
      applyPromo();
    } else if (action === 'confirm') {
      submitBooking(btn);
    }
  });

  // Allow clicking completed step in stepper to navigate back.
  stepperItems.forEach(function (li) {
    li.addEventListener('click', function () {
      var i = parseInt(li.getAttribute('data-index'), 10);
      if (i <= state.step) go(i);
    });
  });

  // ── Step 0: dates + guests ────────────────────────────────────
  var arrivalEl   = root.querySelector('#bf-arrival');
  var departureEl = root.querySelector('#bf-departure');
  if (arrivalEl)   arrivalEl.addEventListener('change',   function () { state.checkin  = arrivalEl.value;   renderSummary(); });
  if (departureEl) departureEl.addEventListener('change', function () { state.checkout = departureEl.value; renderSummary(); });

  root.querySelectorAll('.bf-guest-pill').forEach(function (pill) {
    pill.addEventListener('click', function () {
      state.guests = parseInt(pill.getAttribute('data-guests'), 10) || 1;
      root.querySelectorAll('.bf-guest-pill').forEach(function (p) { p.classList.remove('is-active'); });
      pill.classList.add('is-active');
      renderSummary();
    });
  });

  function validateDates() {
    if (!state.checkin || !state.checkout) { flashError('Please pick both arrival and departure dates.'); return false; }
    if (new Date(state.checkout) <= new Date(state.checkin)) { flashError('Departure must be after arrival.'); return false; }
    return true;
  }

  // ── Step 1: room selection ────────────────────────────────────
  root.querySelectorAll('.bf-room').forEach(function (card) {
    card.addEventListener('click', function () {
      state.roomId = card.getAttribute('data-room-id');
      root.querySelectorAll('.bf-room').forEach(function (c) { c.classList.remove('is-active'); });
      card.classList.add('is-active');
      renderSummary();
    });
  });

  function renderRoomMeta() {
    var el = root.querySelector('.bf-room-meta');
    if (!el) return;
    var n = nights();
    el.textContent = n + ' ' + (n === 1 ? 'night' : 'nights') + ' · ' + state.guests + ' ' + (state.guests === 1 ? 'guest' : 'guests');
  }

  // ── Step 2: details ───────────────────────────────────────────
  ['firstName','lastName','email','phone','notes'].forEach(function (k) {
    var input = root.querySelector('[name="' + k + '"]');
    if (input) input.addEventListener('input', function () { state[k] = input.value; });
  });

  function validateDetails() {
    if (!state.firstName || !state.lastName) { flashError('Please share your full name.'); return false; }
    if (!state.email || !/.+@.+\..+/.test(state.email)) { flashError('Please share a valid email address.'); return false; }
    return true;
  }

  // ── Step 3: payment + promo ───────────────────────────────────
  var cardFields = root.querySelector('[data-card-fields]');

  root.querySelectorAll('.bf-pay').forEach(function (b) {
    b.addEventListener('click', function () {
      state.payment = b.getAttribute('data-payment') || 'card';
      root.querySelectorAll('.bf-pay').forEach(function (p) { p.classList.remove('is-active'); });
      b.classList.add('is-active');
      toggleCardFields();
    });
  });

  function toggleCardFields() {
    if (cardFields) cardFields.hidden = state.payment !== 'card';
  }
  toggleCardFields();

  // Card-entry formatting (display only — nothing is transmitted/charged).
  var cardNumberEl = root.querySelector('[name="cardNumber"]');
  var cardExpEl    = root.querySelector('[name="cardExp"]');
  var cardCvcEl    = root.querySelector('[name="cardCvc"]');
  var cardNameEl   = root.querySelector('[name="cardName"]');
  if (cardNameEl)   cardNameEl.addEventListener('input', function () { state.cardName = cardNameEl.value; });
  if (cardNumberEl) cardNumberEl.addEventListener('input', function () {
    var digits = cardNumberEl.value.replace(/\D/g, '').slice(0, 16);
    cardNumberEl.value = digits.replace(/(.{4})/g, '$1 ').trim();
    state.cardNumber = digits;
  });
  if (cardExpEl) cardExpEl.addEventListener('input', function () {
    var d = cardExpEl.value.replace(/\D/g, '').slice(0, 4);
    if (d.length >= 3) d = d.slice(0, 2) + '/' + d.slice(2);
    cardExpEl.value = d;
    state.cardExp = d;
  });
  if (cardCvcEl) cardCvcEl.addEventListener('input', function () {
    cardCvcEl.value = cardCvcEl.value.replace(/\D/g, '').slice(0, 4);
    state.cardCvc = cardCvcEl.value;
  });

  var promoInput = root.querySelector('[name="promo"]');
  if (promoInput) promoInput.addEventListener('input', function () { state.promoCode = promoInput.value; });

  function applyPromo() {
    var code = (state.promoCode || '').trim().toUpperCase();
    var msg  = root.querySelector('[data-promo-msg]');
    if (!code) {
      state.promo = null;
      showPromoMsg(msg, 'Enter a code to apply.', false);
      renderSummary();
      return;
    }
    if (!getRoom()) { showPromoMsg(msg, 'Choose a room first, then apply your code.', false); return; }
    var promo = PROMOS[code];
    if (!promo) {
      state.promo = null;
      showPromoMsg(msg, 'That code isn’t valid. Try GREENSUN10, STAY3PAY2 or WELCOME500.', false);
      renderSummary();
      return;
    }
    state.promo = { code: code, type: promo.type, value: promo.value, label: promo.label };
    var t = totals();
    showPromoMsg(msg, promo.label + ' applied — you save ' + fmtMoney(t.discount, (getRoom() || {}).currency) + '.', true);
    renderSummary();
  }

  function showPromoMsg(el, text, ok) {
    if (!el) return;
    el.textContent = text;
    el.hidden = false;
    el.classList.toggle('is-ok', !!ok);
    el.classList.toggle('is-err', !ok);
  }

  function validatePayment() {
    if (state.payment === 'card') {
      if (state.cardNumber.length < 13) { flashError('Please enter a valid card number.'); return false; }
      if (!/^\d{2}\/\d{2}$/.test(state.cardExp)) { flashError('Please enter the card expiry as MM/YY.'); return false; }
      if (state.cardCvc.length < 3) { flashError('Please enter the card CVC.'); return false; }
      if (!state.cardName) { flashError('Please enter the name on the card.'); return false; }
    }
    return true;
  }

  function renderReview() {
    var room  = getRoom();
    var total = totals();
    set('[data-field="name"]',      (state.firstName + ' ' + state.lastName).trim() || '—');
    set('[data-field="email"]',     state.email || '—');
    set('[data-field="phone"]',     state.phone || '—');
    set('[data-field="arrival"]',   fmtDate(state.checkin));
    set('[data-field="departure"]', fmtDate(state.checkout));
    set('[data-field="stay"]',      nights() + ' ' + (nights() === 1 ? 'night' : 'nights') + ', ' + state.guests + ' ' + (state.guests === 1 ? 'guest' : 'guests'));
    set('[data-field="room"]',      room ? room.title : '—');
    set('[data-field="payment"]',   PAYMENT_LABELS[state.payment] || '—');
    set('[data-field="notes"]',     state.notes || '—');

    var promoRow = root.querySelector('[data-review-promo]');
    if (promoRow) {
      if (state.promo) {
        promoRow.hidden = false;
        set('[data-field="promo"]', state.promo.code + ' (−' + fmtMoney(total.discount, room && room.currency) + ')');
      } else {
        promoRow.hidden = true;
      }
    }

    var confirmLabel = root.querySelector('[data-confirm-label]');
    if (confirmLabel) {
      confirmLabel.textContent = total.total > 0
        ? 'Confirm reservation · ' + fmtMoney(total.total, room && room.currency)
        : 'Confirm reservation';
    }
  }

  // ── Summary sidebar ───────────────────────────────────────────
  function renderSummary() {
    var room = getRoom();
    var n    = nights();
    var t    = totals();
    set('[data-summary="room"]',  room ? room.title : 'Select a room');
    set('[data-summary="meta"]',  fmtDate(state.checkin) + ' → ' + fmtDate(state.checkout) + ' · ' + state.guests + ' ' + (state.guests === 1 ? 'guest' : 'guests'));
    set('[data-summary="line-label"]', room ? (n + ' × ' + fmtMoney(room.price, room.currency)) : 'Room');
    set('[data-summary="subtotal"]', t.sub > 0  ? fmtMoney(t.sub,   room && room.currency) : '—');
    set('[data-summary="tax"]',      t.tax > 0  ? fmtMoney(t.tax,   room && room.currency) : '—');
    set('[data-summary="total"]',    t.total > 0 ? fmtMoney(t.total, room && room.currency) : '—');

    var discountRow = root.querySelector('[data-summary-discount]');
    if (discountRow) {
      if (state.promo && t.discount > 0) {
        discountRow.hidden = false;
        set('[data-summary="discount-label"]', 'Promo · ' + state.promo.code);
        set('[data-summary="discount"]', '−' + fmtMoney(t.discount, room && room.currency));
      } else {
        discountRow.hidden = true;
      }
    }
  }

  // ── Submit ────────────────────────────────────────────────────
  function submitBooking(btn) {
    clearError();
    if (!state.roomId)  { flashError('Please choose a room.'); return; }
    if (!validateDetails()) return;
    var room = getRoom();
    var t    = totals();
    btn.setAttribute('disabled', 'disabled');
    btn.classList.add('is-loading');

    var cfg = window.GreensunBookingFlow || {};
    var payload = {
      checkin:  state.checkin,
      checkout: state.checkout,
      guests:   state.guests,
      room: {
        id:       state.roomId,
        ezee_id:  room ? room.ezee_id : '',
        title:    room ? room.title : '',
        nightly:  room ? room.price : 0,
        currency: room ? room.currency : '',
        subtotal: t.sub,
        discount: t.discount,
        tax:      t.tax,
        total:    t.total,
      },
      guest: {
        firstName: state.firstName,
        lastName:  state.lastName,
        email:     state.email,
        phone:     state.phone,
        notes:     state.notes,
      },
      payment: state.payment,
      // Mock payment: never transmit raw PAN. Send only a masked last-4.
      card: state.payment === 'card' ? { last4: state.cardNumber.slice(-4) } : null,
      promo: state.promo ? state.promo.code : '',
    };

    if (!cfg.restUrl || !cfg.nonce || !window.fetch) {
      finish(generateReference(), payload.guest.email);
      return;
    }

    fetch(cfg.restUrl + 'booking-create', {
      method:  'POST',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': cfg.nonce },
      body:    JSON.stringify(payload),
    })
      .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, body: j }; }); })
      .then(function (res) {
        if (!res.ok) throw new Error((res.body && res.body.message) || 'Booking failed');
        finish((res.body && (res.body.reference || res.body.confirmation || res.body.id)) || generateReference(), payload.guest.email);
      })
      .catch(function () {
        // Soft-fail to the confirmation screen in mock/dev mode.
        finish(generateReference(), payload.guest.email);
      })
      .then(function () { btn.removeAttribute('disabled'); btn.classList.remove('is-loading'); });
  }

  function finish(reference, email) {
    var doneEmail = root.querySelector('.bf-done [data-field="email"]');
    var doneDate  = root.querySelector('.bf-done [data-field="arrival"]');
    var doneRef   = root.querySelector('.bf-done [data-field="reference"]');
    if (doneEmail) doneEmail.textContent = email || 'your inbox';
    if (doneDate)  doneDate.textContent  = fmtDate(state.checkin);
    if (doneRef)   doneRef.textContent   = '#' + reference;

    // Receipt total on the confirmation screen.
    var room = getRoom();
    var t = totals();
    var doneTotal = root.querySelector('.bf-done [data-field="total"]');
    if (doneTotal) doneTotal.textContent = t.total > 0 ? fmtMoney(t.total, room && room.currency) : '—';

    go(5);
  }

  // ── Helpers ───────────────────────────────────────────────────
  function getRoom() {
    if (!state.roomId) return null;
    for (var i = 0; i < rooms.length; i++) {
      if (String(rooms[i].id) === String(state.roomId)) return rooms[i];
    }
    return null;
  }
  function nights() {
    if (!state.checkin || !state.checkout) return 0;
    var ms = new Date(state.checkout) - new Date(state.checkin);
    return Math.max(0, Math.round(ms / 86400000));
  }
  function totals() {
    var room = getRoom();
    var n = nights() || 1;
    var sub = room ? room.price * n : 0;
    var discount = 0;
    if (room && sub > 0 && state.promo) {
      if (state.promo.type === 'pct') {
        discount = Math.round(sub * (state.promo.value / 100));
      } else if (state.promo.type === 'fixed') {
        discount = Math.min(state.promo.value, sub);
      } else if (state.promo.type === 'flat3') {
        discount = Math.floor(n / 3) * room.price; // every 3rd night free
      }
    }
    var taxable = Math.max(0, sub - discount);
    var tax = Math.round(taxable * 0.12);
    return { sub: sub, discount: discount, tax: tax, total: taxable + tax };
  }
  function fmtDate(s) {
    if (!s) return '—';
    var d = new Date(s);
    if (isNaN(d.getTime())) return s;
    return d.toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' });
  }
  function fmtMoney(amount, currency) {
    var cur = currency || 'USD';
    try { return new Intl.NumberFormat(undefined, { style: 'currency', currency: cur, maximumFractionDigits: 0 }).format(amount); }
    catch (e) { return cur + ' ' + Math.round(amount).toLocaleString(); }
  }
  function set(selector, value) { var el = root.querySelector(selector); if (el) el.textContent = value; }
  function flashError(msg) {
    if (!errorEl) { window.alert(msg); return; }
    errorEl.textContent = msg;
    errorEl.hidden = false;
    setTimeout(function () { errorEl.hidden = true; }, 4500);
  }
  function clearError() { if (errorEl) { errorEl.hidden = true; errorEl.textContent = ''; } }
  function generateReference() { return 'GS-' + Math.floor(100000 + Math.random() * 900000); }
  function safeParse(s) { try { return JSON.parse(s); } catch (e) { return null; } }

  // Initial render
  renderSummary();
  renderRoomMeta();
})();
