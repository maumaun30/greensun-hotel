(function () {
  function init(root) {
    var chips     = root.querySelectorAll('.gs-gallery__chip');
    var items     = root.querySelectorAll('.gs-gallery__item');
    var emptyEl   = root.querySelector('.gs-gallery__empty');
    var lightbox  = root.querySelector('.gs-gallery__lightbox');
    var lbImg     = lightbox && lightbox.querySelector('.gs-gallery__lb-img');
    var lbCap     = lightbox && lightbox.querySelector('.gs-gallery__lb-cap');
    var lbClose   = lightbox && lightbox.querySelector('.gs-gallery__lb-close');
    var lbPrev    = lightbox && lightbox.querySelector('.gs-gallery__lb-prev');
    var lbNext    = lightbox && lightbox.querySelector('.gs-gallery__lb-next');

    // ── Filter ─────────────────────────────────────────────────
    chips.forEach(function (chip) {
      chip.addEventListener('click', function () {
        var filter = chip.getAttribute('data-filter') || 'all';
        chips.forEach(function (c) {
          var active = c === chip;
          c.classList.toggle('is-active', active);
          c.setAttribute('aria-selected', active ? 'true' : 'false');
        });
        var visibleCount = 0;
        items.forEach(function (el) {
          var cat = el.getAttribute('data-category') || '';
          var match = filter === 'all' || cat === filter;
          el.classList.toggle('is-hidden', !match);
          if (match) visibleCount++;
        });
        if (emptyEl) emptyEl.hidden = visibleCount !== 0;
      });
    });

    // ── Lightbox ──────────────────────────────────────────────
    if (!lightbox) return;
    var openIndex = -1;
    function visibleItems() {
      return Array.prototype.filter.call(items, function (el) { return !el.classList.contains('is-hidden'); });
    }
    function openAt(i) {
      var list = visibleItems();
      if (!list.length) return;
      openIndex = ((i % list.length) + list.length) % list.length;
      var el = list[openIndex];
      lbImg.src         = el.getAttribute('data-full') || '';
      lbImg.alt         = el.getAttribute('data-alt') || '';
      lbCap.textContent = el.getAttribute('data-caption') || '';
      lightbox.hidden   = false;
      document.body.style.overflow = 'hidden';
    }
    function close() {
      lightbox.hidden = true;
      lbImg.src = '';
      document.body.style.overflow = '';
      openIndex = -1;
    }
    items.forEach(function (el, idx) {
      el.addEventListener('click', function () {
        var list = visibleItems();
        var pos = list.indexOf(el);
        openAt(pos >= 0 ? pos : idx);
      });
    });
    lbClose && lbClose.addEventListener('click', close);
    lbPrev  && lbPrev.addEventListener('click',  function () { if (openIndex >= 0) openAt(openIndex - 1); });
    lbNext  && lbNext.addEventListener('click',  function () { if (openIndex >= 0) openAt(openIndex + 1); });
    lightbox.addEventListener('click', function (e) { if (e.target === lightbox) close(); });
    document.addEventListener('keydown', function (e) {
      if (lightbox.hidden) return;
      if (e.key === 'Escape')      close();
      if (e.key === 'ArrowLeft')   openAt(openIndex - 1);
      if (e.key === 'ArrowRight')  openAt(openIndex + 1);
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.greensun-gallery-grid').forEach(init);
  });
})();
