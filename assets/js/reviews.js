(function () {
  function init(root) {
    var items = root.querySelectorAll('.gs-reviews__item');
    var dots  = root.querySelectorAll('.gs-reviews__dot');
    var arrows = root.querySelectorAll('.gs-reviews__arrow');
    var count = items.length;
    if (count < 2) return;

    var interval = parseInt(root.getAttribute('data-interval'), 10) || 7000;
    var current = 0;
    var timer = null;
    var hovered = false;

    function go(i) {
      i = ((i % count) + count) % count;
      if (i === current) return;
      items[current].classList.remove('is-active');
      dots[current].classList.remove('is-active');
      dots[current].setAttribute('aria-selected', 'false');
      current = i;
      items[current].classList.add('is-active');
      dots[current].classList.add('is-active');
      dots[current].setAttribute('aria-selected', 'true');
    }
    function tick() { if (!hovered) go(current + 1); }
    function start() { stop(); timer = window.setInterval(tick, interval); }
    function stop() { if (timer) { window.clearInterval(timer); timer = null; } }

    dots.forEach(function (d) {
      d.addEventListener('click', function () {
        go(parseInt(d.getAttribute('data-index'), 10) || 0);
        start();
      });
    });
    arrows.forEach(function (a) {
      a.addEventListener('click', function () {
        go(a.getAttribute('data-dir') === 'next' ? current + 1 : current - 1);
        start();
      });
    });
    root.addEventListener('mouseenter', function () { hovered = true; });
    root.addEventListener('mouseleave', function () { hovered = false; });

    start();
    if ('IntersectionObserver' in window) {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) { if (entry.isIntersecting) start(); else stop(); });
      }, { threshold: 0.2 });
      io.observe(root);
    }
  }
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.gs-reviews').forEach(init);
  });
})();
