# Colorlib article expansion — 28 October 2026

These operator scripts prepare and verify an editorial batch on the Colorlib `/wp/` WordPress site. They are excluded from theme distribution and do not change Unapp runtime behavior.

## Preparation

1. Export the published product/article inventory with `wp eval-file export_inventory.php`, saving stdout as `/tmp/colorlib-editorial-inventory.json`. Export article SEO fields with `export_meta.php` to `/tmp/colorlib-editorial-meta.json`. Use the correct multisite `--url`.
2. Review current product pages and actual block-edition demos. Capture screenshots with Playwright, inspect loaded images, import screenshots through WordPress media APIs, and provide `/tmp/colorlib-editorial-demos.json` and `/tmp/colorlib-editorial-media.json`. Consult the dated publication manifest for the reviewed demo URLs. Product descriptions are in `colorlib-roundup-copy.json`.
3. Run `python3 .dev/editorial/expand_colorlib_roundups.py`. It writes full proposed patches and a compact plan to `/tmp/colorlib-editorial-{patches,plan}.json`.
4. Review rendered drafts at desktop and phone widths. Check theme counts, existing benchmark tables, requirements, ownership disclosure, contents links, numbering and JSON-LD.

## Publication and verification

`publish_roundups.php` is a **one-time, guarded production mutation**, executed using WP-CLI. It verifies source hashes and an explicit post allowlist, backs up every original post and its metadata before writing, suppresses email, and verifies saved content. It refuses to overwrite its existing private backup. Do not rerun the dated batch against already updated articles; prepare a separately reviewed batch instead.

After publication, purge the exact article URLs from edge cache. If a stale origin response remains, inspect and remove only its verified Nginx cache key before purging the edge again. HEAD and GET can have separate cache entries.

Run `node .dev/editorial/check_roundups.cjs` with Playwright installed. Optional environment variables: `PLAYWRIGHT_MODULE`, `CHROME_PATH`, `EDITORIAL_PATCHES`. The script checks public responses, leading entries, titles, contents destinations, ItemList totals, screenshot decoding and layouts at 1440, 390 and 320 pixels. It writes `/tmp/colorlib-editorial-live-checks.json` and representative screenshots.

Keep full original article bodies and private backups outside Git. Save the reviewed compact plan and verification results in `.dev/reports/`.

## Presentation correction

The initial expansion used custom scoped presentation; the 28 October follow-up removes it. `article_components.py` now renders native WordPress groups, headings, images and paragraphs using the surrounding article's established format. `normalize_roundup_styles.py` prepares the guarded correction from a fresh `/tmp/colorlib-editorial-current.json` export, the original plan/media inputs and `/tmp/colorlib-v3ct-before.css`.

`colorlib-article-components.css` is the canonical shared component source deployed into the Colorlib `v3ct/style.css` stylesheet. Do not add article-specific CSS overrides. `colorlib-article-assets.php` records the hook appended to the child theme's `functions.php` to version its existing stylesheet using file modification time. Existing global Nectar button rules are retained.

`publish_consistency.php` checks the current 29 article hashes, exact target stylesheet/functions hashes, and private backup availability before writing. Its dated backup is `/root/colorlib-consistency-20261028/before/`. Like the original publisher, it is a one-time operator script: prepare a new batch rather than rerunning it. `EDITORIAL_CONSISTENCY=1 node .dev/editorial/check_roundups.cjs` checks the corrected layout, shared asset version and matching existing/new computed button styles, in addition to the ordinary public-page checks. Results go to `/tmp/colorlib-consistency-live-checks.json`.

`centralize_component_css.py` prepares the additional 23 CSS-only removals. It asserts that every removed declaration has an identical rule in the shared stylesheet, including the existing `.cltoc__more` text. `publish_shared_css.php` backs up those posts and the stylesheet separately under `/root/colorlib-shared-css-20261028/before/`, with source-hash guards. Use `node .dev/editorial/check_shared_css.cjs before` before publishing and the same command without `before` afterward; it compares computed component styles at 1440, 390 and 320 pixels. It also verifies that article content no longer embeds the migrated rules. These migration scripts describe the dated rollout and intentionally refuse to overwrite existing backups.
