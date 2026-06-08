module.exports = {
  content: [
    "./templates/**/*.html",
    "./parts/**/*.html",
    "./assets/js/**/*.js",
    "./assets/js/**/*.php",
    "./inc/**/*.php",
    "./functions.php",
    "./*.php"
  ],
  theme: {
    extend: {
      colors: {
        forest:   "#5a7755",
        "forest-2":"#3f5a3b",
        moss:     "#6e8f67",
        sage:     "#b8c7b1",
        "sage-2": "#dde5d6",
        sun:      "#e8c46a",
        "sun-2":  "#cda64a",
        ember:    "#b9612a",
        ivory:    "#fbf7ec",
        bone:     "#e9e5da",
        paper:    "#fffff4",
        ink:      "#403e3f",
        "ink-2":  "#615e5f",
        mute:     "#918d89",
        line:     "#e6e1d6"
      },
      fontFamily: {
        display: ['"Roca Two"', '"Cormorant Garamond"', 'Georgia', 'serif'],
        sans:    ['"Codec Pro"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        mono:    ['"Codec Pro"', 'ui-sans-serif', 'system-ui', 'sans-serif']
      },
      maxWidth: {
        shell: '1320px'
      },
      spacing: {
        'section':  '120px',
        'section-tight': '80px'
      },
      borderRadius: {
        DEFAULT: '4px',
        lg: '14px'
      },
      letterSpacing: {
        eyebrow: '0.22em',
        btn: '0.18em'
      },
      transitionTimingFunction: {
        smooth: 'cubic-bezier(.22,.61,.36,1)',
        out:    'cubic-bezier(.16,1,.3,1)'
      }
    }
  },
  plugins: []
};
