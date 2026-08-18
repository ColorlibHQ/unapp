# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

**Unapp 2.x** is a Colorlib **WordPress block theme** (Full Site Editing) for app/SaaS landing pages — text domain `unapp`, upstream `github.com/ColorlibHQ/unapp`. Version 2.0.0 (Aug 2026) is a from-scratch rewrite: the Epsilon Framework, Customizer section builder, Bootstrap 3, jQuery and icon fonts from 1.x are gone. It is *not* a static HTML template — the Colorlib R2 preview/download publishing flow and the HTML-template upgrade phases in the global instructions do not apply here.

Requires WP 6.6+ (theme.json v3, section styles); tested on WP 7.0. No build step, no npm, no SCSS — every file is committed as-is.

## Commands

```bash
# Lint PHP (patterns + functions.php)
find . -name '*.php' -exec php -l {} \; | grep -v "No syntax errors"

# Validate JSON (theme.json + styles/*.json)
for f in theme.json styles/*.json; do python3 -c "import json;json.load(open('$f'))" || echo "BAD $f"; done

# Regenerate translation template (WP-CLI is not installed locally; the extractor used for 2.0.0
# lives in the session scratchpad — `wp i18n make-pot . languages/unapp.pot` is the canonical command)
wp i18n make-pot . languages/unapp.pot

# Run locally: the theme is symlinked into the Local WP site (WordPress 7.0, PHP 8.5, site "local-wp")
ln -sfn "$PWD" "/Users/silkalns/Local Sites/local-wp/app/public/wp-content/themes/unapp"
```

**wp-cli against the Local site** (Local bundles wp-cli.phar but no `wp` on PATH). Use Local's PHP and MySQL socket:

```bash
RUN="$HOME/Library/Application Support/Local/run/X6odgxrlw"
PHP="$HOME/Library/Application Support/Local/lightning-services/php-8.5.3+1/bin/darwin-arm64/bin/php"
WP="/Applications/Local.app/Contents/Resources/extraResources/bin/wp-cli/wp-cli.phar"
cd "/Users/silkalns/Local Sites/local-wp/app/public" && \
MYSQL_HOME="$RUN/conf/mysql" "$PHP" -c "$RUN/conf/php/php.ini" -d mysqli.default_socket="$RUN/mysql/mysqld.sock" "$WP" theme activate unapp
```

Useful checks with it: `wp eval 'echo do_blocks("<!-- wp:pattern {\"slug\":\"unapp/hero\"} /-->");'` renders a pattern server-side; the site has ~100 test posts and the Theme Check + Plugin Check plugins (Theme Check has no CLI — run it from Appearance → Theme Check). Headless Chrome (`/Applications/Google Chrome.app/Contents/MacOS/Google Chrome --headless=new --screenshot=… --window-size=1200,900 URL`) is how `screenshot.png` was produced; note desktop Chrome cannot go narrower than ~500px, so mobile checks need CDP `Emulation.setDeviceMetricsOverride`.

## Architecture

### Where things live

| Concern | File(s) |
| --- | --- |
| Palette, gradients, shadows, spacing scale, fluid font sizes, self-hosted fonts, element/block styles, custom template + part registration | `theme.json` (v3) |
| Section styles (Group/Columns/Column/Cover): `card`, `section-soft`, `section-dark`, `section-gradient` | `styles/*.json` files **with** `blockTypes` |
| Color variations: Emerald, Sunset, Midnight | `styles/*.json` files **without** `blockTypes` |
| Templates | `templates/*.html` — index, home, single, page, page-no-title, archive, search, 404 (deliberately **no** front-page.html) |
| Template parts | `parts/header.html`, `parts/footer.html` — each is a single `<!-- wp:pattern -->` reference to `patterns/header.php` / `patterns/footer.php` so header/footer text is translatable and images use `get_theme_file_uri()` |
| Sections, page starters, hidden template partials | `patterns/*.php` |
| Front page setup on activation (Home + Blog pages, Reading settings, admin notices) | `inc/front-page-setup.php` |
| Block styles + per-block CSS, pattern categories, lazy counter script | `functions.php`, `assets/css/list-checklist.css`, `assets/css/image-device.css`, `assets/js/counter.js` |
| Rules theme.json cannot express: content alignment (`.alignleft/right` pulled back to the content column), Query Loop card equal-heights + pagination pills, classic/shortcode content (galleries, captions, floats, tables, code, rhythm), button `is-style-squared`, `post-nav-links` | `style.css` (also loaded in the editor via `add_editor_style`) |

### Conventions that matter

- **Section wrapper**: every section pattern is `<!-- wp:group {"align":"full","layout":{"type":"constrained"}} -->` with **only top/bottom padding**. `useRootPaddingAwareAlignments` is on, so alignfull constrained groups get left/right padding from root padding automatically (WP adds `has-global-padding`); adding L/R padding double-pads. Section intros use a nested constrained group with `contentSize: 680px`; grids use `align: wide` (1200px).
- **Colours in patterns are palette slugs only** (`textColor:"muted"`, `backgroundColor:"primary"`, `gradient:"primary-to-accent"`) so the color variations restyle everything. The exceptions are white-on-gradient/dark contexts using `rgba(255,255,255,…)` custom colors. Section styles `section-dark`/`section-gradient` redefine `--wp--preset--color--muted` and `--border` via their `css` key so `has-muted-color` text stays readable on dark backgrounds.
- **Icon badges** are a flex Group (background primary/secondary, radius, 12–14px padding) containing a `core/image` pointing at `assets/images/icons/*.svg` (white-stroke Feather icons). Wrap in a vertical-flex column or a flex row so the badge shrinks to content.
- **Repeated items are generated in PHP** (`$unapp_services`, `$unapp_plans`, `$unapp_members`…) with `_x()` in the array and `esc_html()` at output — keep that pattern; do not echo unescaped strings.
- **Block markup must be valid** in the editor. Serialized classes/styles must match what the block's `save()` produces (`has-{slug}-color has-text-color`, `has-{slug}-font-size`, `is-resized`, `has-custom-border`, `alignwide`, …). Cover uses the older *span-before-img* markup on purpose: it validates via deprecation on 7.0 and natively on 6.6. Verify with `wp.blocks.parse(content)` in an editor tab (walk `innerBlocks`, look for `isValid === false`) — the site-editor UI does not reliably surface invalid theme-file blocks, and a paragraph with a `<div>` is *not* flagged, so use heading/button/image probes when sanity-checking the checker.
- **Stats counter**: paragraphs with class `unapp-count`; `functions.php` enqueues `assets/js/counter.js` (defer, footer) from a `render_block_core/paragraph` filter only when such a paragraph renders. Number formats supported: `10,000+`, `1.2M`, `99.9%`, `4.9/5` (prefix/number/suffix regex).
- **Front page**: no HTML template. `inc/front-page-setup.php` hooks `after_switch_theme` (fires on `init` 99) → sets a pending flag → `init` 100 runs `unapp_setup_front_page()`: expands `unapp/page-home` (nested `wp:pattern` refs resolved by `unapp_get_pattern_markup()`), inserts a published "Home" page (template `page-no-title`, kses filters temporarily removed) and a "Blog" page, sets `show_on_front/page_on_front/page_for_posts`, records IDs in option `unapp_front_page_setup` (never re-runs). Automatic only when the site has no static front page; otherwise option `unapp_offer_front_page_setup` shows an admin notice with an admin-post button (nonce + `edit_theme_options`). Filter `unapp_auto_setup_front_page` disables auto-run. `home.html` is the posts page.
- **Hidden patterns** (`Inserter: no`) carry translatable text for templates: `hidden-blog-heading`, `hidden-posts-grid`, `hidden-search`, `hidden-404`, `hidden-post-meta`, `hidden-post-tags`, `hidden-comments`. Page starters (`page-home`, `page-about`, `page-pricing`) are `wp:pattern` lists in category `unapp_page` with `Block Types: core/post-content`.
- Pattern categories registered: `unapp` (sections) and `unapp_page` (page starters); core categories are also used in headers.
- **Alignment model**: post content is `alignfull` + constrained, so core floats `.alignleft/.alignright` direct children to the *container* edge (= viewport). `style.css` pulls them back with `margin-inline-start/end: max(0px, calc((100% - content-size) / 2))`. Verified ladder on `/block-image/`: normal l=300 w=800, alignwide l=100 w=1200, alignfull l=0 w=1400 at a 1400px viewport.
- **Classic content** (freeform HTML, shortcodes) gets no blockGap, so `style.css` supplies the rhythm and styles `[gallery]`/`[caption]`/floats/tables/pre/dl to match block output. Selectors are scoped with `:is(.wp-block-post-content, .wp-block-comment-content, .editor-styles-wrapper)` — in the iframed editor the canvas `<body>` carries `editor-styles-wrapper` and theme CSS is injected unprefixed, so the same rules work in both places. Elements rendered by blocks carry a `wp-block-*` class and are excluded via `:not([class*="wp-block-"])`.
- Core's `is-style-squared` button style targets the *wrapper* while global styles put the radius on the link, so the theme re-applies `border-radius: 0` to the link.
- **Root padding values need units** (`"0px"`, not `"0"`): WP 7.0's navigation overlay uses `clamp(1rem, var(--wp--style--root--padding-top), 20rem)`; a unitless `0` makes the whole padding declaration invalid and the mobile menu loses all padding.
- **theme.json nested `css` strings**: WP splits on `&`, so `& a, & b{…}` breaks (the comma part becomes a bogus root rule). Write one `&` rule per selector.
- **Testing recipe used for 2.0.0**: activation flows via wp-cli (`theme activate` fires the setup within the same CLI run), admin flows/screenshots via headless Chrome + CDP (login with `wp-login.php?redirect_to=…` so the dashboard doesn't consume one-time notices), block validity via `wp.blocks.parse` on the REST content of patterns/templates/parts (probe with heading/button/image — a `<div>` inside a paragraph is *not* flagged), Theme Check via its admin page, colour variations by writing the variation JSON (+`isGlobalStylesUserThemeJSON`) into the `wp_global_styles` post and resetting afterwards, mobile via `Emulation.setDeviceMetricsOverride` (desktop Chrome can't go below ~500px). Test posts with `.mov` cover videos wedge headless capture — skip them.

### Assets

- Fonts: `assets/fonts/{poppins,nunito}/*.woff2` — Google-served latin + latin-ext subsets with `unicodeRange` in theme.json; Poppins static 400–700, Nunito variable 300–800. OFL.txt alongside; credit in readme.txt.
- Images: AVIF (`avifenc --min 20 --max 30 --speed 4`; `crowd.avif` at 34–44 because it sits under an 80% overlay). Sources were the 1.x JPGs; originals are gone from the repo (git history `1d3c10c` has them).
- `screenshot.png` (1200×900) is a headless-Chrome capture of the live front page with the test site's title/menu replaced in the DOM for the shot.

## Release checklist

1. `php -l` all PHP, validate JSON, bump `Version:` in `style.css` and `Stable tag` in `readme.txt`, add a CHANGELOG entry.
2. Activate on the Local site, load `/`, `/blog/`, a single post, a category, `?s=`; check `wp-content/debug.log` (enable `WP_DEBUG_LOG` temporarily — remember to revert wp-config.php).
3. Run Theme Check from wp-admin (only expected notice: single text domain INFO).
4. Regenerate `languages/unapp.pot` and `screenshot.png` if strings or the hero changed.
