# Greensun Hotel

A classic PHP WordPress theme for the Greensun Hotel & Events Venue (Magallanes / Makati). Hosts a library of dynamic Gutenberg blocks plus three content-managed post types (rooms, venues, events), a multi-step booking flow, and an eZee booking API client. Styled with Tailwind CSS v3 and a hand-built design system ported from a Claude Design prototype.

---

## Requirements

- Node.js 18+
- WordPress 6.6+
- PHP 8.1+
- ACF (bundled; auto-loads if the plugin isn't installed)

---

## Installation

```bash
npm install
npm run build
```

Activate **Greensun Hotel** under *Appearance → Themes*.

On first load the theme registers three CPTs (*Rooms*, *Venues*, *Events*) and an admin page at *Settings → Booking (eZee)* for the mock-mode API client.

---

## Commands

| Command | Description |
| --- | --- |
| `npm run dev` | Watch mode — rebuilds blocks + Tailwind CSS on save |
| `npm run build` | Production build (all blocks + minified `main.min.css` + `critical.min.css`) |
| `npm run build:blocks` | Block bundles only |
| `npm run dev:blocks` | Watch block bundles only |
| `npm run build:css:main` | Compile + minify the main stylesheet |
| `npm run build:css:critical` | Compile + minify above-the-fold styles |
| `npm run create-block <slug>` | Scaffold a new block under `assets/js/blocks/<slug>/` (kebab-case) and build it |

---

## Project layout

```
greensun-hotel/
├── style.css                       # theme stylesheet (header only)
├── functions.php                   # bootstrap + asset enqueues + filters
├── header.php / footer.php         # site chrome
├── index.php                       # blog listing / final WP fallback
├── single.php                      # single blog post
├── page.php                        # default page (auto-detects blocks)
├── search.php                      # search results
├── 404.php                         # not-found landing with leaves animation
├── archive-room.php                # rooms list (alternating rows)
├── single-room.php                 # room detail (hero + sticky book card)
├── archive-venue.php               # venues list (design's "Events" page)
├── single-venue.php                # venue detail + inquiry strip
├── archive-event.php               # dated events list
├── single-event.php                # event detail + dual closing CTA
├── page-booking.php                # 4-step booking wizard
├── inc/
│   ├── cpt-loader.php              # auto-requires every file under post-types/, taxonomies/, fields/, api/, admin/
│   ├── fonts.php                   # Google Fonts (non-blocking)
│   ├── post-types/
│   │   ├── room.php
│   │   ├── venue.php
│   │   └── event.php
│   ├── fields/                     # ACF field groups for each CPT
│   ├── api/
│   │   ├── ezee-client.php         # Greensun_EZee_Client (mock + live modes)
│   │   └── rest-routes.php         # /wp-json/greensun/v1/*
│   ├── admin/
│   │   ├── ezee-settings.php       # Settings → Booking (eZee)
│   │   └── theme-settings.php      # Settings → Greensun Theme
│   └── helpers/
│       ├── icons.php               # inline SVG renderer
│       ├── images.php              # greensun_post_thumbnail_html()
│       └── logo.php                # greensun_logo()
├── assets/
│   ├── css/
│   │   ├── critical.css / .min.css # above-the-fold (header, footer, reveal, focus rings)
│   │   └── main.css / .min.css     # design tokens + components + utilities
│   └── js/
│       ├── critical.js             # header scroll-state, mobile menu, IO reveal, Lenis bridge
│       ├── hero-carousel.js        # home hero crossfade autoplay
│       ├── booking-bar.js          # block submit → /booking-search
│       ├── booking-flow.js         # wizard controller (4 steps + summary)
│       ├── contact-form.js         # POST + success-state swap
│       ├── events-teaser.js        # master/detail picker
│       ├── gallery-grid.js         # filter chips + lightbox
│       ├── reviews.js              # testimonial carousel
│       └── blocks/                 # one folder per block (see below)
├── acf/                            # bundled ACF, auto-loaded if plugin missing
├── acf-json/                       # field-group local JSON sync (in git)
└── .github/workflows/
    ├── deploy.yml                  # rsync deploy to CloudPanel on push to main
    └── README.md                   # deploy setup + required secrets
```

---

## Architecture

### Blocks

Blocks live in `assets/js/blocks/<slug>/` with the convention:

- **`block.json`** — `"render": "file:./render.php"` so all blocks are server-rendered.
- **`index.js`** — registers the block via `@wordpress/blocks`; bundled output goes to `<slug>/build/index.js`.
- **`edit.js`** — editor React component.
- **`render.php`** — frontend markup, queries CPTs, uses `get_block_wrapper_attributes()`.
- **`style.css`** — per-block frontend/editor styles (loaded both places).

`greensun_hotel_register_blocks()` (in `functions.php`) scans the directory on `init` and registers every folder containing a `block.json`. **Adding a block = create the folder; never touch `functions.php`.**

Each block is built independently by `scripts/build-blocks.js` invoking `wp-scripts build` per block. No single bundle.

### Block inventory

| Block | Purpose |
| --- | --- |
| `hero-carousel` | Home hero with crossfade slides + Ken Burns + editorial mark + compass + floating leaves |
| `booking-bar` | Date / guests / room-type form → `/wp-json/greensun/v1/booking-search` |
| `about-teaser` | Two-column intro with stats, dual overlapping images, and year badge |
| `rooms-preview` | Featured rooms (CPT picker), card with chip overlay + hover pill |
| `amenities-grid` | Paper-card grid with hover invert and linedot decorator |
| `stay-smart` | Forest band with sun-gold title + 4-up distance stats + leaf ornament |
| `events-teaser` | Master/detail venue picker — image preview + clickable list |
| `reviews` | Testimonial carousel with sun-gold quote mark + arrows + dots |
| `cta-section` | Full-bleed CTA with centered or split layout, primary + ghost-light buttons |
| `page-hero` | Editorial subpage hero (image + scrim + eyebrow + display title + body) |
| `timeline` | Sticky title column + scrolling list of year-stamped story entries |
| `values-grid` | 3-up cards with sun-2 numerals 01 / 02 / 03 |
| `team-grid` | 4-col portrait + name + mono role |
| `pull-quote` | Editorial quote block with alignment options |
| `gallery-grid` | Filterable spanning grid (4 cols × 220px) with category chips + lightbox |
| `venues-list` | Alternating CPT-driven venue rows with spec card + capacity grid |
| `contact-channels` | Stacked label + lines + stylized map |
| `contact-form` | Self-contained paper-card form → `/wp-json/greensun/v1/contact` |
| `contact-info` | 4-up info grid |

### Custom post types

| CPT | Slug | Archive | Single template |
| --- | --- | --- | --- |
| Rooms | `room` | `/rooms/` (`archive-room.php`) | `single-room.php` |
| Venues | `venue` | `/venues/` (`archive-venue.php`) | `single-venue.php` |
| Events | `event` | `/events/` (`archive-event.php`) | `single-event.php` |

All three are public, REST-enabled, and have ACF field groups synced to `acf-json/` (in git). Rooms power the booking wizard's room picker; venues are the design's "Events" page content; the Event CPT is reserved for dated calendar entries.

### Asset pipeline

`greensun_hotel_enqueue_assets()` enqueues in this order, each cache-busted by `filemtime()`:

1. `critical.min.css` — handle `greensun-hotel-critical` (above-the-fold)
2. `main.min.css` — depends on critical
3. `style.css` — depends on main
4. **Lenis** smooth scroll (deferred)
5. **`critical.js`** (deferred, depends on Lenis)

Heavy block scripts (hero-carousel, reviews, booking-bar, contact-form, gallery-grid, events-teaser, booking-flow) are enqueued **on-demand** via `has_block()` / `is_page_template()` so other pages don't pay for them.

Fonts (Cormorant Garamond / Manrope / JetBrains Mono) are loaded via the `rel="preload" + onload="this.rel='stylesheet'"` non-blocking pattern with a `<noscript>` fallback.

### REST API

Routes under `/wp-json/greensun/v1/`:

| Route | Purpose |
| --- | --- |
| `POST /booking-search` | Availability check via `Greensun_EZee_Client::get_room_availability()` |
| `POST /booking-create` | Reservation submit (mock generates a `GS-######` reference) |
| `GET /booking-status` | Returns the configured/mode state of the eZee client |
| `POST /contact` | Honeypot-guarded form intake → `wp_mail()` with Reply-To |

Permission callback enforces a `wp_rest` nonce and a per-IP rate limit (30/min).

### eZee client

`inc/api/ezee-client.php` ships in **mock mode** by default. It reads credentials from `wp_options` (set on *Settings → Booking (eZee)*) and falls back to Room CPT data when no API is configured, so the booking wizard works end-to-end before the live API is wired.

To switch to live: enter endpoint / hotel code / auth code in the settings page, flip Mode to `live`, then fill in the real eZee request payloads in `get_room_availability()` and `create_booking()` (TODOs are marked in the file).

### Design system

CSS custom properties on `:root` drive the entire site:

- **Brand**: `--forest #2a5a4a`, `--sun #ffd266`, `--ivory #fbf7ec` (plus derived `forest-2`, `moss`, `sun-2`, `paper`, `bone` ramps)
- **Type**: `--display` Cormorant Garamond, `--sans` Manrope, `--mono` JetBrains Mono
- **Motion**: `--ease cubic-bezier(.22,.61,.36,1)`

Component classes used across templates and blocks:

- `.shell` — max-width 1320px container with horizontal padding
- `.section` / `.section--tight` — vertical-rhythm wrappers
- `.eyebrow` — mono uppercase label
- `.display` — Cormorant heading family
- `.btn`, `.btn--sun`, `.btn--ghost`, `.btn--ghost.btn--light`, `.btn--lg` — primary action system
- `.chip`, `.chip--moss` — pill labels with `.dot`
- `.ph` — image placeholder with Ken Burns support
- `.kb > img` — applies the Ken Burns animation
- `.reveal`, `.reveal--lg` — IntersectionObserver-driven enter animations
- `.skip-link`, `.screen-reader-text` — a11y utilities

### Accessibility

- Skip-to-content link as the first focusable element on every page
- Consistent `:focus-visible` outline (forest on light surfaces, sun on dark)
- `aria-current="page"` on the active nav link (via `nav_menu_link_attributes` filter)
- `role="banner"` on header, `role="main"` on every main element with `id="site-main"` for the skip link
- `prefers-reduced-motion` globally disables animations, reveal, KB, leaves, and scroll smoothing
- Every form field has a real `<label>` (no `placeholder`-only labels)

### Performance

- Deferred `critical.js` + Lenis (WP 6.3+ `strategy => 'defer'`)
- Non-blocking Google Fonts via preload + onload swap
- Responsive `srcset` + `sizes` + `width`/`height` on featured images (via `greensun_post_thumbnail_html()`)
- `loading="lazy"` + `decoding="async"` defaults on every WP-rendered `<img>`
- On-demand block JS enqueues (no global bundle)
- Tailwind purges unused utilities at build time

---

## Theme settings

Two admin pages live under *Settings →*:

- **Greensun Theme** — brand tagline, phone, email, address, social URLs, legal HTML for the footer. Read via `greensun_setting($key, $default)`.
- **Booking (eZee)** — endpoint, hotel code, auth code, mode toggle (mock / live), test-connection button.

---

## Deployment

`.github/workflows/deploy.yml` builds the theme on every push to `main` and rsyncs the result to a CloudPanel-hosted WordPress install over SSH.

Setup steps and required GitHub secrets are documented in `.github/workflows/README.md` — at minimum you need `DEPLOY_SSH_PRIVATE_KEY`, `DEPLOY_HOST`, `DEPLOY_USER`, and `DEPLOY_PATH`.

---

## Conventions

- Block names use the `greensun-hotel/` namespace and kebab-case slugs (matching the folder name).
- Server-rendered blocks: keep markup in `render.php`, return `null` from JS `save`. Escape output (`esc_html`, `esc_url`, `wp_kses_post`) and use `get_block_wrapper_attributes()` on the root element.
- Don't hand-register blocks in PHP — rely on the folder scan.
- New CPT / ACF field group / REST route / admin page? Drop a file into `inc/post-types/`, `inc/fields/`, `inc/api/`, or `inc/admin/`. `cpt-loader.php` will auto-require it.
- Heavy block JS / third-party libs: enqueue on demand with `has_block()` rather than globally.

---

## License

GPL-2.0-or-later
