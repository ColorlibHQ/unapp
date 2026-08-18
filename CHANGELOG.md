# Changelog

All notable changes to the Unapp theme are documented here.

## 2.0.0 – 2026-08-18

Complete rewrite as a WordPress block theme (Full Site Editing). Nothing from 1.x is carried over except the visual identity (Poppins/Nunito, blue-green palette, gradient hero) and the bundled screenshots.

### Removed
- Epsilon Framework and Epsilon Theme Dashboard (git submodules), the Customizer section builder, repeatable sections and demo importer.
- All classic PHP templates (`header.php`, `footer.php`, `index.php`, `single.php`, …), the nav walker, widgets, breadcrumbs, `unapp_functions.php`.
- Bootstrap 3, jQuery and every jQuery plugin (Owl Carousel, Magnific Popup, Waypoints, Stellar, YTPlayer, countTo, easing), Modernizr, Respond, animate.css.
- Font Awesome and Icomoon icon fonts, Google Fonts remote loading, `custom_js.js`, `map.js`, `flexslider.css`.
- Recommended-plugin notices, tracking/upsell hooks, `unapp-portfolio` companion plugin checks.

### Added
- `theme.json` v3: palette (base, contrast, primary, secondary, accent, surface, muted, border, dark), gradients, shadow presets, spacing scale, fluid font sizes, self-hosted Poppins + Nunito font faces, global element and block styles, `Page (No Title)` template.
- Block templates: `index`, `home`, `front-page`, `single`, `page`, `page-no-title`, `archive`, `search`, `404`; template parts `header` and `footer` (backed by PHP patterns for translation).
- Section patterns: hero, services, collaborate (media & text), features around a phone, stats counter, app screens gallery, pricing table, team, latest blog posts, call to action, contact details.
- Page starters: Home landing page, About page, Pricing page.
- Hidden template partials: posts grid, blog heading, search form, 404 content, post meta, post tags, comments.
- Section styles (Group/Columns/Column/Cover): Card, Soft background, Dark, Gradient. Block styles: List → Checklist, Image → Device frame.
- Color variations: Emerald, Sunset, Midnight.
- `assets/js/counter.js`: 2 KB vanilla stat counter with IntersectionObserver, prefers-reduced-motion aware, enqueued only when a paragraph with the `unapp-count` class renders.
- AVIF versions of the product screenshots and photos, Feather-based SVG icons, SVG avatar placeholders.
- `readme.txt` (WordPress.org format), `README.md`, `languages/unapp.pot`, `.gitignore`.

### Changed
- Version bumped to 2.0.0; requires WordPress 6.6+ and PHP 7.4+; tested up to WordPress 7.0.
- Screenshot regenerated from the new landing page.

## 1.0

- Initial release (classic theme built on the Epsilon Framework).
