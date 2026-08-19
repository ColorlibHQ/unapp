# Unapp

Unapp is a lightweight WordPress **block theme** for app, SaaS and startup landing pages by [Colorlib](https://colorlib.com). Version 2.0 is a ground-up rewrite for the Site Editor: no page-builder framework, no jQuery, no icon fonts — just core blocks, `theme.json` and patterns.

![Unapp screenshot](screenshot.png)

## Highlights

- **Complete landing page on activation** – a real "Home" page (editable page content, *Page (No Title)* template) and a "Blog" page are created and assigned in Settings → Reading; existing front pages are never overridden (a one-click notice is shown instead). Gradient hero with product screenshot, services grid, image & text, features around a phone, animated stats, screenshot gallery (core lightbox), pricing table, team, latest posts, call to action and contact cards.
- **47 section patterns + 13 page starters**, grouped into ten inserter categories (Heroes, Features, Social proof, Pricing, Calls to action, Content & blog, Company, Utility, Full pages).
- **Section styles** for Group/Columns/Column: Card, Soft background, Dark, Gradient, Frosted glass, Outline, Elevated. **Block styles**: List (Checklist, Dashed, Numbered steps, Two columns), Image (Device frame, Browser frame, Framed), Quote (Testimonial card), Details (FAQ card), Table (Comparison), Separator (Gradient line), Button (Text link with arrow), Columns (Divided).
- **Mix-and-match styles**: 6 colour palettes × 5 typography presets = 30 combinations, plus 5 curated looks. Colour and typography are separate partials in `styles/colors/` and `styles/typography/`.
- **Performance**: six self-hosted font families (woff2, latin + latin-ext subsets, ~530 KB total, only the active pair downloads), AVIF images, fluid type, one 2 KB script loaded only where the Stats pattern is used, per-block CSS loaded on demand.
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
├── templates/           index, home, single, page, page-no-title, archive, author, category,
│                       tag, date, search, 404, page-with-sidebar, single-with-sidebar
├── parts/               header, footer, sidebar (each references a PHP pattern for i18n)
├── patterns/            47 section patterns, 13 page starters, hidden template partials
├── styles/              curated looks, colors/ and typography/ partials, section styles
├── assets/css/          per-block styles (checklist, device frame)
├── assets/js/counter.js stat counter (IntersectionObserver, reduced-motion aware)
├── assets/fonts/        Poppins + Nunito (OFL)
├── assets/images/       AVIF screenshots/photos, 55 SVG icons, avatars, logo marks, star ratings
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
