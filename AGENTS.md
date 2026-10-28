# Repository Guidelines

## Project Structure & Module Organization
Unapp is a WordPress block theme requiring WordPress 6.6+ and PHP 7.4+. `theme.json` defines design tokens and global styles; `functions.php` and `inc/` implement theme behavior. Block templates live in `templates/`, reusable parts in `parts/`, and generated PHP patterns in `patterns/`. `styles/` contains palette, typography, and section variations; `assets/` holds CSS, JavaScript, fonts, and images. `.dev/` contains generators and audits. `plugin/unapp-library/` is the companion plugin, `child-theme/` is the customization starter, and `docs/` contains integration guidance.

## Build, Test, and Development Commands
There is no npm toolchain or asset compilation. Install or symlink this directory as `wp-content/themes/unapp` in a local WordPress installation, then activate it.

- `find . -name '*.php' -exec php -l {} \;`: lint all PHP files.
- `python3 -m json.tool theme.json > /dev/null`: validate theme JSON; validate changed `styles/` JSON files similarly.
- `for i in $(seq 1 24); do python3 .dev/batch$i.py; done` followed by `python3 .dev/apply_grounds.py`: regenerate patterns and page-opening variants.
- `python3 .dev/build_styles.py`: regenerate style variations.
- `wp i18n make-pot . languages/unapp.pot`: refresh translations after string changes.
- `sh .dev/build-zip.sh`: package theme and plugin ZIPs in `/tmp/unapp-build`; this directory is cleared first.

## Coding Style & Naming Conventions
Follow existing WordPress conventions: tabs in PHP, JavaScript, CSS, and JSON; spaces inside PHP function calls; `unapp_` prefixes for PHP functions and hooks. Use kebab-case filenames and `unapp/<slug>` pattern identifiers. Translate strings with the `unapp` text domain and escape output appropriately. Edit pattern generators rather than generated markup. Reuse `.dev/pgen.py` components, spacing presets, and palette slugs. Preserve valid serialized block markup.

## Testing Guidelines
Custom Python audits replace a conventional unit-test suite; no coverage threshold is configured. Run `python3 .dev/<audit>.py` for `heading_audit`, `link_audit`, `rhythm_audit`, and `gradient_contrast`. Check changed patterns in the Site Editor for block validation errors and inspect desktop/mobile rendering. For releases, follow `CLAUDE.md`'s Theme Check and runtime verification checklist.

## Commit & Pull Request Guidelines
History uses short, imperative subjects such as “Name both navigation landmarks”; release commits include the version. Keep commits focused. PRs should explain the problem and resulting behavior, list validation performed, link relevant issues, and include screenshots for visual changes. Release changes must synchronize `style.css`, `readme.txt`, and `CHANGELOG.md`.
