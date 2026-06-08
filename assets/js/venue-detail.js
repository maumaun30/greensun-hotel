/* Venue (event-space) detail — gallery lightbox with prev/next + keyboard. */
(function () {
  var grid = document.querySelector('[data-venue-gallery]');
  var box  = document.querySelector('[data-venue-lightbox]');
  if (!grid || !box) return;

  var items   = Array.prototype.slice.call(grid.querySelectorAll('[data-full]'));
  var imgEl   = box.querySelector('[data-lightbox-img]');
  var closeEl = box.querySelector('[data-lightbox-close]');
  var prevEl  = box.querySelector('[data-lightbox-prev]');
  var nextEl  = box.querySelector('[data-lightbox-next]');
  var current = 0;

  function srcAt(i) { return items[i] ? items[i].getAttribute('data-full') : ''; }

  function open(i) {
    current = i;
    if (imgEl) imgEl.src = srcAt(i);
    box.hidden = false;
    box.setAttribute('aria-hidden', 'false');
    document.documentElement.style.overflow = 'hidden';
  }
  function close() {
    box.hidden = true;
    box.setAttribute('aria-hidden', 'true');
    document.documentElement.style.overflow = '';
  }
  function step(dir) {
    var n = items.length;
    current = ((current + dir) % n + n) % n;
    if (imgEl) imgEl.src = srcAt(current);
  }

  items.forEach(function (el, i) {
    el.addEventListener('click', function () { open(i); });
  });
  if (closeEl) closeEl.addEventListener('click', close);
  if (prevEl)  prevEl.addEventListener('click', function () { step(-1); });
  if (nextEl)  nextEl.addEventListener('click', function () { step(1); });
  box.addEventListener('click', function (e) { if (e.target === box) close(); });
  document.addEventListener('keydown', function (e) {
    if (box.hidden) return;
    if (e.key === 'Escape') close();
    else if (e.key === 'ArrowLeft') step(-1);
    else if (e.key === 'ArrowRight') step(1);
  });
})();
