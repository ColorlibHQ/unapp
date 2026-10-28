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
