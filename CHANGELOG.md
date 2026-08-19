# Changelog

All notable changes to the Unapp theme are documented here.

## 2.3.0 – 2026-08-19

### Added
- **Every starter site now speaks its own language.** The first cut dressed SaaS sections in a new palette: a church home page introduced "Dorothy Murphy, Product Designer" and three international sales offices, and its Contact page closed with a FAQ about 14-day trials and CSV export. Twenty-six further patterns replace every borrowed section, so each starter's home *and* its inner pages are written for that kind of site — church staff, beliefs, events, first-visit answers and directions; gym memberships, member stories and opening hours; adviser process, fees, credentials and the questions a client actually asks; a designer's process, rates, client quote and availability; a publication's about, subscribe and pitch pages.
- **A footer per starter.** The footer is a template part shared by every page, so SaaS wording followed all six starters everywhere. Each starter now swaps in its own — `unapp_set_part_to_pattern()` points the part at a pattern, and clearing it restores the theme file.
- **Starter pages are compositions.** A page definition takes a `patterns` list rather than a single slug, so "Plan your visit" is four sections rather than one, and every inner page is a real page.
- **Starter sites.** Appearance → Starter Sites offers six complete designs — SaaS & app, Portfolio, Church, Blog & magazine, Fitness studio, Finance & advisory. Applying one writes the matching style variation into Global Styles, creates the home page and its supporting pages from patterns, builds a navigation menu, sets Settings → Reading and gives the header the starter's own call-to-action wording. Nothing is deleted; switching starter adds pages and reverts the header when the wording matches the theme default. Extendable through the `unapp_starter_sites` filter.
- **Twenty niche patterns**: portfolio (introduction, work grid, about, services and rates), church (welcome, service times, ministries, giving), fitness (hero, class timetable, coaches), finance (trust-led hero, services, credentials, risk warning) and blog (masthead, category tiles built on the WordPress 7.0 Terms Query block, author introduction).
- **Six starter home pages** as full-page patterns, so the same layouts can be inserted by hand from Patterns → Pages.
- **Four palettes** — Stone, Ember, Navy and Mono — bringing the total to ten, each contrast-checked, plus matching curated looks.
- Ten abstract SVG placeholder images (10 KB in total) for the niches the bundled photography does not suit.

### Changed
- **Avatar placeholders redrawn.** They were the default user glyph — a white head-and-shoulders on a saturated gradient — which clashed with every palette. They are now duotone silhouettes in muted tones, each with a different hair and shoulder shape, so a row of four reads as four people. Ten files, 5 KB in total.
- The five niche pattern categories (`unapp_portfolio`, `unapp_church`, `unapp_fitness`, `unapp_finance`, `unapp_blog`) are registered, so those patterns are grouped and labelled in the inserter instead of landing in an unnamed category.

### Fixed
- **Buttons were illegible in the Midnight palette** — 1.73:1. Buttons take their background from `secondary` and their text from `contrast`, which is light in a dark palette, so light text sat on a light green pill. Midnight now takes its button text from `base`: 9.68:1. All ten palettes were audited; the other nine already passed.
- **The Mono palette's call to action looked disabled.** Its `secondary` was a near-white `#e8e6e1`, so every button rendered as a pale grey pill. Mono now uses a rust `#e07a4f` (6.35:1 with the dark button text), which is also what its swatch always claimed.
- Headings and links vanished on new dark sections: `theme.json` sets headings to `contrast` (near-black), and the niche footers did not override it, so the column headings and site title rendered black on near-black. They now carry the same `elements` override the original footer uses.
- Outline buttons on gradient bands rendered with dark text against the gradient; they now take `base` like the rest of the theme's gradient sections.
- `buttons()` in the pattern generator accepted a bare style name (`outline`) and emitted it verbatim as a class, so the button silently rendered as a default fill. It now normalises to `is-style-outline`.
- The footer's copyright rule was raw CSS with no backing block attribute, which failed block validation. `group()` takes a `border_top` argument that writes both the attribute and the matching CSS.
- Pattern previews of `vh`-height cover heroes were captured against a full-page viewport, which stretched them. The capture now keeps a realistic viewport and captures beyond it.

## 2.2.0 – 2026-08-19

Phase 1 of the competitive roadmap: everything the benchmark identified as table stakes against Twenty Twenty-Five.

### Added
- **Mix-and-match style variations.** Six colour palettes (`styles/colors/`) and five typography presets (`styles/typography/`) are now separate partials, so the Site Editor lists them as independent groups — 30 combinations from 11 small files — alongside five curated looks that pair a palette with a typeface.
- **Five typography presets** with self-hosted variable fonts: Poppins & Nunito, Inter, Fraunces & Inter, Space Grotesk & Inter, Manrope. Presets redefine the `heading` and `body` font-family slugs, so every existing pattern follows automatically.
- **Six templates**: `author`, `category`, `tag`, `date`, `page-with-sidebar` and `single-with-sidebar`, plus a `sidebar` template part and its pattern (search, recent posts, topics, a call-to-action card).
- **WordPress 7.0 blocks**: a native Accordion FAQ pattern, breadcrumbs above post and page titles, a result count on archives and search, and reading time in the post meta row.
- **Sticky header support** — `settings.position.sticky` is enabled, so any header Group can be made sticky from the editor, with a frosted backdrop once it is.
- **WooCommerce compatibility styles**, loaded only when WooCommerce is active: form controls, product cards, prices, sale badges, notices, cart and checkout.
- Post-format support, background-image and dimension controls, and palette-aware shadows built with `color-mix()` so they follow whichever colour variation is active.
- An accessibility statement in `readme.txt`.

### Changed
- All CSS uses logical properties — zero physical `left`/`right` declarations remain, so the theme works in right-to-left languages without a separate stylesheet. Added the `rtl-language-support` tag.
- One consistent focus ring on every interactive element, switching to the section's own text colour on dark and gradient backgrounds instead of relying on the browser default.

### Fixed
- Heading hierarchy skipped a level in five patterns: services, features, team and contact card titles were `h4` directly under the section `h2`, and footer column titles were `h4` after the page's last `h2`. Card titles are now `h3` and footer column titles `h2`, so the front page runs h1 → h2 → h3 with no skips. Screen-reader heading navigation was affected; it is also an accessibility-ready blocker.

## 2.1.0 – 2026-08-19

### Added
- **36 new section patterns**, bringing the library to 47: heroes (split, email capture, photo cover, search), logo cloud, testimonials grid, single testimonial, case study, review scores, press mentions, alternating feature rows, bento grid, numbered steps, integrations grid, two-column checklist, security & compliance, two-plan pricing, pricing comparison table, FAQ accordion, colour-band CTA, app download, newsletter, waitlist, timeline, values, careers, offices, featured blog layout, blog list, author box, related posts, changelog, documentation topics, contact split, legal document, centred header and slim footer.
- **10 new full-page starters**: SaaS landing page, Features, Customers, Contact, Careers, Help centre, Changelog, Legal, Coming soon and a detailed Pricing page.
- **Nine pattern categories** (Heroes, Features, Social proof, Pricing, Calls to action, Content & blog, Company, Utility, Full pages) alongside the catch-all Unapp category.
- **11 new block styles**: List → Dashed, Numbered steps, Two columns; Image → Browser frame, Framed; Quote → Testimonial card; Details → FAQ card; Table → Comparison; Separator → Gradient line; Button → Text link with arrow; Columns → Divided.
- **Three new section styles**: Frosted glass, Outline, Elevated (Group, Columns, Column).
- **Two new colour variations**: Graphite and Violet.
- Assets: 37 more SVG icons (55 total), six more gradient avatars, eight placeholder wordmark logos for logo clouds and press sections, and star-rating graphics.

### Fixed
- **The header never wraps.** Core swaps the menu for the overlay toggle only below 600px, so a real menu plus a call-to-action button stacked onto two or three rows between roughly 600 and 1000 pixels. The swap now happens at 1000px and the header row itself is `nowrap`, so the header is a single 72px line at every width.
- **No orphaned wraps.** Grid sections used auto-fill column widths that left one tile alone on the last row (integrations 5+3, documentation 5+1, logo cloud 5+1, security 3+1). Grids now step through column counts that divide their item count evenly — 4 → 2 → 1 for four and eight items, 3 → 2 → 1 for six, 6 → 3 → 2 for the logo cloud — and a three-image gallery goes to one column instead of 2+1 on small screens. Audited across 50 sections at eight widths from 390 to 1440 pixels: zero orphans.
- Social links in the logos-only style now keep the palette colour they are given; core paints each service its brand colour at the same specificity, so the winner depended on stylesheet order.

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
- Block templates: `index`, `home`, `single`, `page`, `page-no-title`, `archive`, `search`, `404`; template parts `header` and `footer` (backed by PHP patterns for translation).
- Section patterns: hero, services, collaborate (media & text), features around a phone, stats counter, app screens gallery, pricing table, team, latest blog posts, call to action, contact details.
- Page starters: Home landing page, About page, Pricing page.
- `inc/front-page-setup.php`: on activation creates a Home page (expanded Home landing page pattern, Page (No Title) template) and a Blog page and assigns them in Settings → Reading — automatically when no static front page exists, otherwise via a one-click admin notice; idempotent, filterable (`unapp_auto_setup_front_page`).
- Hidden template partials: posts grid, blog heading, search form, 404 content, post meta, post tags, comments.
- Section styles (Group/Columns/Column/Cover): Card, Soft background, Dark, Gradient. Block styles: List → Checklist, Image → Device frame.
- Color variations: Emerald, Sunset, Midnight.
- `assets/js/counter.js`: 2 KB vanilla stat counter with IntersectionObserver, prefers-reduced-motion aware, enqueued only when a paragraph with the `unapp-count` class renders.
- AVIF versions of the product screenshots and photos, Feather-based SVG icons, SVG avatar placeholders.
- `readme.txt` (WordPress.org format), `README.md`, `languages/unapp.pot`, `.gitignore`.

### Changed
- Palette tuned for WCAG AA: primary `#5468d8`, accent `#2a74ca` (≥4.7:1 on white); buttons use dark text on the green secondary (7.5:1 instead of 2.1:1); Emerald/Sunset variations adjusted likewise; white text on gradients raised to 92% opacity.
- Version bumped to 2.0.0; requires WordPress 6.6+ and PHP 7.4+; tested up to WordPress 7.0.
- **Content consistency pass.** Left/right aligned blocks (buttons, images, galleries) now align to the content column instead of the viewport edge, so the alignment ladder is predictable everywhere: content 800px → wide 1200px → full 100%. Query Loop cards share one height per row with "Read more" pinned to the bottom, and query pagination renders as a pill control group with the current page highlighted. Classic (non-block) content — `[gallery]` shortcodes, `[caption]`, floats, tables, `pre`/`code`, definition lists, `hr`, `address`, blockquotes and heading rhythm — now matches the block equivalents, in the editor as well as the front end. Core's "Square" button style works again (it targets the wrapper while the radius lives on the link), and legacy paginated posts (`<!--nextpage-->`) get styled page links.
- Styled the password-protected post form; comment content is constrained (headings scaled down, tables/pre scroll, flex item min-width 0); mobile navigation overlay is left-aligned with proper padding.
- Screenshot regenerated from the new landing page.

## 1.0

- Initial release (classic theme built on the Epsilon Framework).
