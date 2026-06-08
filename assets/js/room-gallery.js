/* Room multi-gallery — category tabs + main viewer + thumbnails + prev/next.
   Inclusion checklist swaps with the active category. Progressive: with no
   JS the first panel is shown via the .is-active classes printed server-side. */
(function () {
  function initGallery(root) {
    var tabs       = Array.prototype.slice.call(root.querySelectorAll('[data-rmg-tab]'));
    var panels     = Array.prototype.slice.call(root.querySelectorAll('[data-rmg-panel]'));
    var inclPanels = Array.prototype.slice.call(root.querySelectorAll('[data-rmg-incl]'));

    function activateCategory(idx) {
      tabs.forEach(function (t) {
        var on = parseInt(t.getAttribute('data-rmg-tab'), 10) === idx;
        t.classList.toggle('is-active', on);
        t.setAttribute('aria-selected', on ? 'true' : 'false');
      });
      panels.forEach(function (p) {
        p.classList.toggle('is-active', parseInt(p.getAttribute('data-rmg-panel'), 10) === idx);
      });
      inclPanels.forEach(function (p) {
        p.classList.toggle('is-active', parseInt(p.getAttribute('data-rmg-incl'), 10) === idx);
      });
    }

    tabs.forEach(function (t) {
      t.addEventListener('click', function () {
        activateCategory(parseInt(t.getAttribute('data-rmg-tab'), 10));
      });
    });

    // Per-panel image switching (thumbnails + prev/next).
    panels.forEach(function (panel) {
      var imgs   = Array.prototype.slice.call(panel.querySelectorAll('[data-rmg-img]'));
      var thumbs = Array.prototype.slice.call(panel.querySelectorAll('[data-rmg-thumb]'));
      if (imgs.length < 2) return;

      function show(i) {
        var n = imgs.length;
        var idx = ((i % n) + n) % n;
        imgs.forEach(function (im, k) { im.classList.toggle('is-active', k === idx); });
        thumbs.forEach(function (th, k) { th.classList.toggle('is-active', k === idx); });
      }
      function current() {
        for (var k = 0; k < imgs.length; k++) { if (imgs[k].classList.contains('is-active')) return k; }
        return 0;
      }

      thumbs.forEach(function (th) {
        th.addEventListener('click', function () { show(parseInt(th.getAttribute('data-rmg-thumb'), 10)); });
      });
      var prev = panel.querySelector('[data-rmg-prev]');
      var next = panel.querySelector('[data-rmg-next]');
      if (prev) prev.addEventListener('click', function () { show(current() - 1); });
      if (next) next.addEventListener('click', function () { show(current() + 1); });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-rmg]').forEach(initGallery);
  });
})();
