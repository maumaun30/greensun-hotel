(function () {
  function init(root) {
    var picks  = root.querySelectorAll('.gs-events__pick');
    var slides = root.querySelectorAll('.gs-events__slide');
    if (!picks.length || !slides.length) return;

    function activate(i) {
      picks.forEach(function (p, idx) { p.classList.toggle('is-active', idx === i); });
      slides.forEach(function (s, idx) { s.classList.toggle('is-active', idx === i); });
      root.setAttribute('data-active', String(i));
    }

    picks.forEach(function (p) {
      p.addEventListener('click', function () {
        var idx = parseInt(p.getAttribute('data-index'), 10);
        if (Number.isFinite(idx)) activate(idx);
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.greensun-events-teaser .gs-events__split').forEach(init);
  });
})();
