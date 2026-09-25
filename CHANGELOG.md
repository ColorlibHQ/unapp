# Changelog

## 2.5.10

- Update requests no longer name the site. WordPress's default User-Agent carries the site address; the update check and core's package download to updates.colorlib.com now send only the theme and WordPress versions, so the one-way site identifier is the only thing that tells installs apart.

## 2.5.9 – 2026-09-18

### Fixed
- **2.5.8 fixed one sentence and left the rest of the contradiction standing.** The theme shipped three different names for the same three plans: `pricing` and `pricing-h1` sold Starter, Pro and Business, while `pricing-two` and `pricing-compare` sold Free, Team and Business, and `pricing-toggle` sold Free, Team and Company. Put a comparison table under the pricing table — which is what it is for — and the columns named plans the prices above them did not. `sidebar` also still offered "Fourteen days of the Team plan", the same claim 2.5.8 corrected in the FAQ. Everything now says Starter, Pro and Business.
- **Neither menu had a name a screen reader could use.** Both the header and footer navigation blocks rendered as unnamed `navigation` landmarks, so a site with both announced the same thing twice — and where core fell back to the navigation post's title, one read out as " 2". All 16 header and footer patterns now set `ariaLabel` ("Main menu" / "Footer menu"), translatable with a context.
- **The Starter Sites screen described an update check that a WordPress.org build would not make.** The note was printed whenever the theme was installed, but core only calls `update_themes_{host}` for a theme carrying the matching `Update URI` header. `unapp_updates_self_hosted()` now reads that header back, so removing the one line for a directory upload silences the updater and the note together. No change for the self-hosted build.

## 2.5.8 – 2026-09-18

### Fixed
- **The SaaS starter named a plan that does not exist.** Its FAQ answer offered "a 14-day trial of the Team tier" while the pricing table sells Starter, Pro and Business. Both the FAQ and the accordion FAQ now say Pro. (`faq.php` is generated; `faq-accordion.php` is one of the 27 hand-maintained patterns, so it needed the same edit by hand.)

## 2.5.7 – 2026-09-17

### Fixed
- **The Finance starter contradicted itself.** The home page's "Independent since 1998" and the "28 years" figure sat beside an adviser biography that said she founded the firm in 2009. She now joined in 2009 and has led it since, so the three agree.

## 2.5.6 – 2026-09-17

### Fixed
- **The SaaS starter's Contact page showed setup instructions to visitors.** `contact-split`, which that page opens with, carried a card telling the reader to "add your form plugin's block here". It now uses the same form slot as the niche contact sections: the active form plugin's form, or the email fallback.
- **Forms overflowed phone-width cards.** Found once the demos had real Contact Form 7 forms: fields sized `content-box` ran 34px past the card at 390px, and the form shrank to CF7's `size="40"` width inside the vertical flex card. Fields are `border-box` and the form stretches to the card (0px overflow at 1440, 390 and 360).

## 2.5.5 – 2026-09-17

### Fixed
- **Contact sections could show visitors an administrator-only hint.** A starter expands its patterns into page content, so the form detected at that moment — and, because an administrator applied it, the "Only you can see this" hint — was frozen into every contact page. The pattern now stores a slot (a heading and the email fallback); the active form plugin's form, or the hint for administrators only, is chosen when the page is displayed. Hints saved by earlier versions are hidden from everyone who cannot edit theme options, so updating fixes existing sites.
- **Starters no longer destroy a site's own header, footer or menu.** A customised header or footer was force-deleted, revisions and all, whenever a starter used the theme's own; the newest navigation menu on the site was renamed and emptied. Template parts are now always updated, never deleted, and their current version is saved as a revision first (a part saved once in the Site Editor had none). Starters keep their own menu in `unapp_starter_navigation`, and Global Styles keep a revision of the previous styles.
- **Activation no longer replaces an established blog's front page.** Automatic setup runs only while WordPress's `fresh_site` flag is set; any other site gets the one-click offer, worded for a latest-posts front page. Concurrent requests can no longer both run the setup.
- **The child theme loads the parent stylesheet.** `get_stylesheet_uri()` pointed at the child's file. `UNAPP_VERSION` is the parent's version, and includes use `get_parent_theme_file_path()`.
- **Palettes and fonts listed twice in the Site Editor.** Starters saved preset lists un-keyed, which WordPress files as custom; they are keyed by the theme origin, as the Site Editor saves a variation.
- **Contrast.** Midnight's button labels were 1.73:1 (now 9.68:1), the Conference footer 1.06:1 (all footers are now written in white), and white text on gradient bands failed in six palettes (it now follows the palette's base colour). A generated audit, `.dev/gradient_contrast.py`, checks every text element on every coloured ground in all twelve palettes: 0 failures in 13,416 checks. The Dark and Gradient section styles and the Fitness and Subscribe heroes are fixed too.
- **One h1 per page.** Heroes and the blog masthead are h1; sections that open inner pages have hidden `-h1` copies the starters use. 66 starter pages checked, all with exactly one.
- **Every link arrives somewhere.** Footers and calls to action pointed at anchors no section defined, and the header button was `#` on every starter. Target sections carry anchors, footer links go to the front page's sections, bookings open email or phone, shop links use the WooCommerce shop page, and the header button opens the page each starter names in `cta_page`. 0 dead links across all 13 starters.
- **SaaS copy on niche pages.** Six niche pages used SaaS contact, FAQ and case-study sections; each now has a section written for its business. The blog heading is neutral.
- **Email sign-ups were search forms.** The four patterns that used a Search block for an email address now show an email button.
- **Dark-mode toggle** read `prefers-color-scheme` while the dark tokens follow only the attribute, so an OS-dark visitor saw a light page with the button pressed.
- **The Shop starter** could be applied without WooCommerce, producing an empty storefront; it now needs WooCommerce, and WooCommerce templates and product patterns are hidden until it is active.
- **Layout:** the 20px strip between full-width sections, a stranded card in three-item grids at tablet widths, header overflow on 360–390px phones, the header navigation style leaking into footers, the plain white mobile menu (now palette-coloured with 44px targets), focus rings on coloured sections, unequal card heights and plan buttons, rows in menu and opening-hours cards, the comparison table on phones, and category tiles on small screens.
- **Setup wizard:** Remove now clears the logo, Back keeps what was typed, the done screen reports failures, installed-but-inactive plugins get an Activate link, and install links respect capabilities. The wizard and Starter Sites screen say what a starter replaces instead of "nothing is overwritten".
- The church diary no longer shows dates that go stale.

### Changed
- **Update checks send `Unapp/<version>` as the User-Agent.** The previous User-Agent included the site address, which the one-way site hash exists to avoid. "Check again" on Dashboard → Updates clears the twelve-hour cache, the release's minimum WordPress version is passed to core, and a copy installed under another folder name is matched by its Update URI. The update note sits inside the Starter Sites screen.
- **Performance:** theme images get width and height attributes at render time, so WordPress lazy-loads them and gives the hero `fetchpriority`; the variable fonts are trimmed to the 400–700 weights the theme uses (72 KB smaller); two dashboard screenshots are re-encoded at the size they are shown; `forms.css` loads only on pages that render a form.
- The pattern generator reproduces the shipped patterns byte for byte, and the styles generator the shipped styles. Pattern values are escaped by context (0 PHPCS escaping errors).
- Tested with WordPress 7.1.

## 2.5.4 – 2026-09-17

### Fixed
- The Restaurant and Courses starter descriptions on Appearance → Starter Sites showed a literal `\u2019` instead of an apostrophe ("the week\u2019s menu").
- The font credits in readme.txt named two families; the theme bundles six: Poppins, Nunito, Inter, Fraunces, Space Grotesk and Manrope.

### Changed
- `languages/unapp.pot` is regenerated from the current source. It had fallen behind the 2.5.x releases and still carried the escaped starter descriptions.

## 2.5.3 – 2026-08-20

### Fixed
- **A starter applied through WP-CLI silently kept the default palette.** Core locates the global styles post through the `wp_theme` taxonomy, and creates it with `tax_input` — which `wp_insert_post()` discards when there is no logged-in user with permission to assign terms. Under WP-CLI the term therefore never landed, the next lookup found nothing and created *another* post, and the variation was written to one the site never reads. `unapp_apply_starter_styles()` now attaches the term itself, which is a no-op in the browser and is what makes headless provisioning work at all. Found while building the thirteen public demo sites, every one of which came out in the default indigo.
- Five niche call-to-action patterns declared the category `call to action`; WordPress registers `call-to-action`. They were absent from that group in the inserter.


## 2.5.2 – 2026-08-20

### Changed
- **The companion plugin counts too.** Its update check sent only the plugin slug and version, so a plugin install could never be counted as a site. It now sends the same payload as the theme — WordPress and PHP versions, locale, multisite, and the same one-way site identifier — and honours the same `unapp_check_for_updates` filter, because an opt-out that covers half a product is not an opt-out.
- The update disclosure on Appearance → Starter Sites speaks for both when the plugin is active. It is two whole sentences rather than one with the subject spliced in: the verb has to agree, and a translator cannot make that agree from a fragment.


## 2.5.1 – 2026-08-20

### Fixed
- **The site's tagline is displayed again.** Reported against 1.x in 2019 and still true of the block theme: nothing in `patterns/`, `parts/` or `templates/` rendered the tagline, so a value typed into Settings → General — or into the setup wizard, which asks for one — went nowhere. Every footer already had the slot: a short line under the site title, labelled with a `Footer tagline` translator context in the niche footers, but hard-coded. All fourteen footers now render `core/site-tagline` there when the site has a tagline and keep the written line as a fallback when it does not, so a footer still previews as a finished design on a fresh install.
- The theme description advertised six starter sites and 70+ patterns. There are thirteen and 140+.

### Notes
- Site Tagline is a dynamic block, and the style engine drops `rgba()` from a dynamic block's inline colour while passing `#hex` through. The footer tagline takes its muted tone from a class in `style.css` for that reason.


## 2.5.0 – 2026-08-20

### Added
- **A setup wizard.** Picking a starter was only half of setting a site up. Appearance → Starter Sites is now three steps: choose the starter, set the site title, tagline, logo, palette and typeface, then install anything that starter depends on. The wizard never installs a plugin itself — it links to WordPress's own installer with a proper nonce, so core does the installing and the user sees it happen.
- **Palette and typeface are now independent of the starter.** A starter names both, and the wizard can override either. The composed variation reproduces the curated look byte for byte when neither is changed.

### Fixed
- `unapp_apply_starter_styles()` loaded the curated look file, which bundles a palette and a typeface together, so the `colors` and `type` keys in a starter definition had no effect. They are now composed from the two partials, which is what makes overriding one of them possible.


## 2.4.0 – 2026-08-20

### Added
- **A real contact form in every contact section.** Every starter previously built a Contact page that ended at a card reading "add your form plugin's block here". `inc/forms.php` now finds whichever form plugin is active — ten are recognised — renders its first form, and wraps it in `.unapp-form` so `assets/css/forms.css` can style the fields to the palette without knowing each plugin's class names. With no form plugin installed the sections fall back to an email panel.
- **WooCommerce templates.** `archive-product`, `single-product`, `page-cart`, `page-checkout`, `order-confirmation` and `product-search-results`, all in the theme's section rhythm, with their text in hidden patterns so it stays translatable. The stylesheet grew to cover product grids, the gallery, tabs, cart and checkout panels, badges and breadcrumbs.
- **Three more starter sites** — Restaurant, Agency and Shop — taking the theme to nine, with nineteen new sections and two new palettes (Harvest and Slate, both contrast-checked).
- **Starter pages are locked to their content.** Each top-level section is marked `contentOnly`, so headings, paragraphs, images and buttons stay editable while groups, columns and separators are protected. Measured in the editor: 41 paragraphs, 25 headings, 12 images and 2 buttons editable; 33 groups, 24 columns and 7 separators locked.
- **Documentation** in `docs/` (getting started, design tokens, extending) and a ready-made child theme in `child-theme/`. Neither ships inside the theme zip.

### Fixed
- **The header overflowed the viewport at 390px** by 36 pixels. The row is `nowrap` by contract but its children could not shrink, so a long call-to-action label — or WooCommerce adding account and cart icons — pushed the button off-screen. The groups now shrink, the site title truncates, and the button's padding and gaps tighten below 600px.
- **A three-column shop rendered as two.** WooCommerce sizes its product grid with `auto-fill` and a percentage that assumes its own 1.25em gap; widening the gap to the theme's spacing scale pushed the third column out. The theme now sets the tracks explicitly and steps them 3 → 2 → 1.
- WooCommerce centred the add-to-cart button while the title and price sat left, which read as a mistake.


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
- **One measurement system across every pattern.** The niche sections had been written with per-pattern numbers: fifteen distinct block gaps against the baseline's seven, four card radii, avatars at 48/56/64/110/120/180px, card titles at two sizes. They are now built from named components in the generator — `section_std`, `card`, `grid`, `split`, `icon_card`, `card_title`, `label`, `faq_list`, `band` — so a church page and a SaaS page share one rhythm. The whole library now uses three radii and one spacing scale.
- **Sections alternate grounds by role.** Explanation sits on the page ground; proof, prices, answers and contact sit on the tinted one. A page of six sections no longer renders as one undifferentiated white column — the worst case before this was the finance home, with six identical grounds in a row. Where two same-ground sections do meet, a hairline seam separates them.
- **The ten abstract images redrawn** as line illustrations — a muted ground, a geometric subject and fine white line work, sharing a stroke weight and light source. The previous versions were gradients with two translucent shapes on them, which rendered as smudges in the portfolio work grid.
- **Avatar placeholders redrawn.** They were the default user glyph — a white head-and-shoulders on a saturated gradient — which clashed with every palette. They are now duotone silhouettes in muted tones, each with a different hair and shoulder shape, so a row of four reads as four people. Ten files, 5 KB in total.
- The five niche pattern categories (`unapp_portfolio`, `unapp_church`, `unapp_fitness`, `unapp_finance`, `unapp_blog`) are registered, so those patterns are grouped and labelled in the inserter instead of landing in an unnamed category.

### Fixed
- **Fifty-five block gaps pointed at spacing presets that do not exist.** theme.json registers 20–80 in tens; the niche patterns asked for 6, 8, 10, 12, 14, 16, 18, 22 and 24. `var(--wp--preset--spacing--16)` is undefined, so WordPress dropped the declaration and the element fell back to whatever it inherited — which is why spacing inside cards and rows looked arbitrary. `sp()` now refuses anything that is not a registered preset or an explicit length, and a rendering audit confirms all 101 patterns sit on the scale.
- Columns with no `blockGap` fell back to the default paragraph margin, off the scale; the `split()` component was only setting the gap on its left-hand column, and the shipped footer set none at all.
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
