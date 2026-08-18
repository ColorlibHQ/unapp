# Unapp

Unapp is a lightweight WordPress **block theme** for app, SaaS and startup landing pages by [Colorlib](https://colorlib.com). Version 2.0 is a ground-up rewrite for the Site Editor: no page-builder framework, no jQuery, no icon fonts — just core blocks, `theme.json` and patterns.

![Unapp screenshot](screenshot.png)

## Highlights

- **Complete landing page on activation** – a real "Home" page (editable page content, *Page (No Title)* template) and a "Blog" page are created and assigned in Settings → Reading; existing front pages are never overridden (a one-click notice is shown instead). Gradient hero with product screenshot, services grid, image & text, features around a phone, animated stats, screenshot gallery (core lightbox), pricing table, team, latest posts, call to action and contact cards.
- **14 patterns + 3 page starters** (Home, About, Pricing) — every section is reusable on any page.
- **Section styles** for Group blocks: Card, Soft background, Dark, Gradient. **Block styles**: Checklist (List), Device frame (Image).
- **Color variations**: default, Emerald, Sunset, Midnight (dark).
- **Performance**: self-hosted Poppins/Nunito (woff2, latin + latin-ext subsets, ~160 KB total), AVIF images, fluid type, one 2 KB script loaded only where the Stats pattern is used, per-block CSS loaded on demand.
- **Security**: all pattern output escaped, no direct file access, no external requests.

## Requirements

- WordPress 6.6+ (theme.json v3, section styles) — tested up to 7.0
- PHP 7.4+

## Structure

```
unapp/
├── style.css            theme header + a few rules theme.json can't express
├── theme.json           palette, gradients, shadows, spacing, fonts, global styles, templates
├── functions.php        block styles, pattern categories, lazy counter script
├── inc/front-page-setup.php  Home/Blog page creation + Reading settings on activation
├── templates/           index, home, single, page, page-no-title, archive, search, 404
├── parts/               header, footer (each references a PHP pattern for i18n)
├── patterns/            section patterns, page starters and hidden template partials
├── styles/              section styles (blockTypes) and color variations
├── assets/css/          per-block styles (checklist, device frame)
├── assets/js/counter.js stat counter (IntersectionObserver, reduced-motion aware)
├── assets/fonts/        Poppins + Nunito (OFL)
├── assets/images/       AVIF screenshots/photos, SVG icons and avatars
└── languages/unapp.pot  translation template
```

## Customising

- **Colors / fonts / spacing** – Appearance → Editor → Styles, or edit `theme.json`. Add a palette variation by dropping a JSON file into `styles/`.
- **Header / footer** – Appearance → Editor → Patterns → Template Parts, or edit `patterns/header.php` / `patterns/footer.php` (the parts only reference these patterns).
- **New section** – add `patterns/my-section.php` with a `Slug: unapp/my-section` header and the `unapp` category; escape every string with `esc_html_e()` / `esc_attr()` / `esc_url()`.
- **Section look** – select any Group and pick Card / Soft background / Dark / Gradient under Styles. Add more by creating `styles/section-*.json` with `"blockTypes"`.

## Front page behaviour

There is deliberately **no `front-page.html`**. On activation `inc/front-page-setup.php` creates a *Home* page whose content is the fully expanded "Home landing page" pattern (template *Page (No Title)*) plus a *Blog* page, and sets `show_on_front`, `page_on_front`, `page_for_posts`. It only does this automatically when the site has no static front page; otherwise an admin notice offers a one-click setup. The run is recorded in the `unapp_front_page_setup` option and never repeated. Disable with `add_filter( 'unapp_auto_setup_front_page', '__return_false' )`.

## Development

There is no build step. Edit files directly; PHP patterns are lint-able with `php -l`, JSON with any validator.

Regenerate the translation template with WP-CLI:

```bash
wp i18n make-pot . languages/unapp.pot
```

## License

GPL v3 or later. Bundled fonts are OFL 1.1, icons are based on Feather Icons (MIT). See `readme.txt` for full credits.
