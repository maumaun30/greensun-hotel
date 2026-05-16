# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

- `npm run dev` â€” watch mode for blocks + CSS (runs `dev:blocks` and `dev:css` concurrently). Use while developing.
- `npm run build` â€” production build: compiles all blocks then minifies `main.css` and `critical.css`.
- `npm run create-block <slug>` â€” scaffolds a new block under `assets/js/blocks/<slug>/` (kebab-case) and immediately runs `wp-scripts build` for it. Generates `block.json`, `index.js`, `edit.js`, `render.php`, `style.css`.
- `npm run build:blocks` / `npm run dev:blocks` â€” only blocks (loops every dir under `assets/js/blocks/` that contains `index.js`).
- `npm run build:css:main` / `build:css:critical` â€” individual Tailwind builds. The critical CSS is enqueued before main and intended to be inlined/loaded first.

Requirements: Node 18+, WordPress 6.6+, PHP 8.1+.

## Architecture

This is a **classic PHP WordPress theme** (not a block theme) that hosts dynamic Gutenberg blocks. There is no `theme.json`-driven templating; rendering goes through `index.php` / `header.php` / `footer.php` and `the_content()`.

### Block pipeline

Blocks live in `assets/js/blocks/<slug>/` with the convention:
- `block.json` â€” uses `"render": "file:./render.php"` so blocks are **server-rendered** (the JS `save` returns `null`).
- `index.js` registers the block via `@wordpress/blocks`; bundled output goes to `<slug>/build/index.js` (referenced as `editorScript` in `block.json`).
- `edit.js` is the editor React component.
- `style.css` is per-block frontend/editor styles.

`functions.php::greensun_hotel_register_blocks()` scans `assets/js/blocks/` on `init` and calls `register_block_type($block_path)` for every folder containing a `block.json` â€” **no manual registration**. Adding a block = create the folder (use `create-block`) and rebuild; do not touch `functions.php`.

Each block is built independently by `scripts/build-blocks.js` invoking `wp-scripts build` per block, with output written into that block's own `build/` directory. There is no single bundle.

### Asset loading

`greensun_hotel_enqueue_assets()` enqueues in this order, each cache-busted by `filemtime`:
1. `assets/css/critical.min.css` (handle `greensun-hotel-critical`)
2. `assets/css/main.min.css` depends on critical
3. `style.css` depends on main

`critical.js` is enqueued in the header (not footer) if present.

The **carousel block** (`greensun-hotel/carousel`) loads Swiper from CDN + `assets/js/greensun-hotel-carousel.js` only when `has_block('greensun-hotel/carousel')` returns true on the current page. Follow this same on-demand pattern for any other block that needs heavyweight third-party assets.

### Tailwind

Tailwind v3 scans `./*.php`, `functions.php`, `./assets/js/**/*.{js,php}`. Block `render.php` files are included in the content globs, so utility classes used in PHP render templates will be picked up. Editor styles come from `main.min.css` via `add_editor_style()`.

### ACF

ACF is bundled in `acf/` and auto-loaded by `functions.php` if the `ACF` class isn't already defined (i.e., the plugin isn't installed). The `acf/settings/path` and `acf/settings/dir` filters point ACF at the theme directory.

## Conventions

- Block names use the `greensun-hotel/` namespace and kebab-case slugs (matching the folder name).
- Server-rendered blocks: keep markup in `render.php`, return `null` from JS `save`. Escape output (`esc_html`, etc.) and use `get_block_wrapper_attributes()` on the root element.
- Don't hand-register blocks in PHP; rely on the folder scan.
