(function () {
  function init(root) {
    var slides = root.querySelectorAll('.gs-hero-carousel__slide');
    var copies = root.querySelectorAll('.gs-hero-carousel__copy-slide');
    var dots   = root.querySelectorAll('.gs-hero-carousel__indicator');
    var count  = slides.length;
    if (count < 2) return;

    var interval = parseInt(root.getAttribute('data-interval'), 10) || 6500;
    var current  = 0;
    var timer    = null;
    var hovered  = false;

    function go(i) {
      i = ((i % count) + count) % count;
      if (i === current) return;
      slides[current].classList.remove('is-active');
      copies[current].classList.remove('is-active');
      dots[current].classList.remove('is-active');
      dots[current].setAttribute('aria-selected', 'false');

      current = i;
      slides[current].classList.add('is-active');
      copies[current].classList.add('is-active');
      dots[current].classList.add('is-active');
      dots[current].setAttribute('aria-selected', 'true');
    }

    function tick() { if (!hovered) go(current + 1); }
    function start() { stop(); timer = window.setInterval(tick, interval); }
    function stop()  { if (timer) { window.clearInterval(timer); timer = null; } }

    dots.forEach(function (dot) {
      dot.addEventListener('click', function () {
        var idx = parseInt(dot.getAttribute('data-index'), 10);
        if (Number.isFinite(idx)) { go(idx); start(); }
      });
    });

    root.addEventListener('mouseenter', function () { hovered = true; });
    root.addEventListener('mouseleave', function () { hovered = false; });

    // Start autoplay immediately on init — IntersectionObserver only
    // pauses the timer when the hero scrolls out of view.
    start();
    if ('IntersectionObserver' in window) {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) start();
          else stop();
        });
      }, { threshold: 0.1 });
      io.observe(root);
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.gs-hero-carousel').forEach(init);
  });
})();
