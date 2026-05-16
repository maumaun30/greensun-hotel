/* ---------- Header scroll-state ---------- */
(function () {
  function init() {
    var header = document.getElementById('site-header');
    if (!header) return;

    // Pages without a dark hero at the top need the header in its solid
    // ivory state from the start, otherwise the white menu text sits on
    // an ivory body and goes invisible.
    var main = document.querySelector('.site-main');
    var firstChild = main ? main.firstElementChild : null;
    var hasHero = !!(firstChild && firstChild.matches(
      '.gs-hero, .gs-hero-carousel, .gs-page-hero, .wp-block-greensun-hotel-hero-carousel'
    ));
    if (!hasHero) document.body.classList.add('no-hero-top');

    var onScroll = function () {
      if (!hasHero) { header.setAttribute('data-scrolled', 'true'); return; }
      header.setAttribute('data-scrolled', window.scrollY > 24 ? 'true' : 'false');
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }
  if (document.readyState !== 'loading') init();
  else document.addEventListener('DOMContentLoaded', init);
})();

/* ---------- Mobile menu toggle ---------- */
document.addEventListener('DOMContentLoaded', function () {
  var toggle = document.getElementById('mobile-menu-toggle');
  var panel  = document.getElementById('site-header-mobile');
  if (!toggle || !panel) return;
  toggle.addEventListener('click', function () {
    var isOpen = toggle.getAttribute('aria-expanded') === 'true';
    toggle.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
    if (isOpen) panel.setAttribute('hidden', '');
    else panel.removeAttribute('hidden');
  });
});

/* ---------- Reveal-on-scroll ---------- */
document.addEventListener('DOMContentLoaded', function () {
  var els = document.querySelectorAll('.reveal');
  if (!els.length) return;
  if (!('IntersectionObserver' in window)) {
    els.forEach(function (el) { el.classList.add('in'); });
    return;
  }
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('in');
        io.unobserve(entry.target);
      }
    });
  }, { rootMargin: '0px 0px -10% 0px', threshold: 0.05 });
  els.forEach(function (el) { io.observe(el); });
});

/* ---------- Lenis smooth scroll ---------- */
(function () {
  if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  function start() {
    if (typeof window.Lenis !== 'function') return;
    var lenis = new window.Lenis({
      duration: 1.1,
      easing: function (t) { return Math.min(1, 1.001 - Math.pow(2, -10 * t)); },
      smoothWheel: true,
      smoothTouch: false,
    });
    function raf(time) { lenis.raf(time); window.requestAnimationFrame(raf); }
    window.requestAnimationFrame(raf);
    window.__lenis = lenis;

    // Anchor links: hand off to Lenis
    document.addEventListener('click', function (e) {
      var link = e.target.closest && e.target.closest('a[href^="#"]');
      if (!link) return;
      var id = link.getAttribute('href');
      if (!id || id === '#' || id.length < 2) return;
      var target = document.querySelector(id);
      if (!target) return;
      e.preventDefault();
      lenis.scrollTo(target, { offset: -80 });
    });
  }
  if (document.readyState === 'complete') start();
  else window.addEventListener('load', start);
})();
