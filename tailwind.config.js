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
        forest:   "#1f4a3a",
        "forest-2":"#173a2d",
        moss:     "#4a6f55",
        sage:     "#bcc9b6",
        "sage-2": "#dbe3d5",
        sun:      "#d8b25a",
        "sun-2":  "#caa14a",
        ember:    "#b9612a",
        ivory:    "#f7f6f0",
        bone:     "#ede9d9",
        paper:    "#faf8f1",
        ink:      "#1f231f",
        "ink-2":  "#3d433d",
        mute:     "#7b817b",
        line:     "#d8dcd5"
      },
      fontFamily: {
        display: ['"Cormorant Garamond"', '"Cormorant"', '"Times New Roman"', 'serif'],
        sans:    ['Manrope', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        mono:    ['"JetBrains Mono"', '"DM Mono"', 'ui-monospace', 'monospace']
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
