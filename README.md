# Greensun Hotel

A classic PHP WordPress theme for the Greensun Hotel site, hosting dynamic Gutenberg blocks and styled with Tailwind CSS v3.

Built from a custom starter: classic `index.php` / `header.php` / `footer.php` templates, server-rendered React blocks, and a one-command block generator.

---

## Requirements

- Node.js 18+
- WordPress 6.6+
- PHP 8.1+

---

## Installation

```bash
npm install
npm run build
```

Then activate **Greensun Hotel** under Appearance → Themes.

---

## Commands

- `npm run dev` — watch mode (blocks + CSS)
- `npm run build` — production build (blocks + minified CSS)
- `npm run create-block <slug>` — scaffold a new block under `assets/js/blocks/<slug>/` and build it

Block slugs use kebab-case (e.g. `hero-banner`, `feature-grid`).

---

## Architecture

Blocks live in `assets/js/blocks/<slug>/` and are **server-rendered** via `render.php` (JS `save` returns `null`). They are auto-registered by scanning the blocks directory on `init` — no manual registration in `functions.php`.

The `greensun-hotel/carousel` block loads Swiper from CDN on-demand only on pages that use it. Follow this pattern for any other block needing heavyweight third-party assets.

See `CLAUDE.md` for full conventions and the asset-loading pipeline.

---

## License

GPL-2.0-or-later
