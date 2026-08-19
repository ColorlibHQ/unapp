=== Unapp ===
Contributors: colorlib
Requires at least: 6.6
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 2.2.0
License: GNU General Public License v3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Tags: blog, one-column, rtl-language-support, wide-blocks, block-patterns, block-styles, custom-colors, custom-logo, custom-menu, editor-style, featured-images, full-site-editing, style-variations, template-editing, threaded-comments, translation-ready

Unapp is a lightweight block theme for app and SaaS landing pages, built entirely for the WordPress Site Editor.

== Description ==

Unapp is a modern, block-based WordPress theme for presenting an app, SaaS product or startup. Everything is built with core blocks and configured through theme.json, so the whole site — colors, fonts, spacing, header, footer and every landing-page section — can be edited visually in the Site Editor without touching code.

**What's included**

* **47 section patterns** covering everything a product site needs: five heroes, nine feature layouts, testimonials, logo clouds, ratings, case studies, press, three pricing layouts, FAQ, five calls to action, timeline, values, careers, offices, security, changelog, documentation, contact and more.
* **13 full-page starters** — SaaS landing page, Features, Pricing, Customers, About, Contact, Careers, Help centre, Changelog, Legal, Coming soon and two more — inserted from the pattern modal when you create a page.
* **Ten pattern categories** in the inserter (Heroes, Features, Social proof, Pricing, Calls to action, Content & blog, Company, Utility, Full pages) so a big library stays findable.
* Section styles for any Group, Columns or Column: Card, Soft background, Dark, Gradient, Frosted glass, Outline and Elevated — switch the look of a section with one click.
* Block styles: Checklist, Dashed, Numbered steps and Two columns (List); Device frame, Browser frame and Framed (Image); Testimonial card (Quote); FAQ card (Details); Comparison (Table); Gradient line (Separator); Text link with arrow (Button); Divided (Columns).
* **Mix-and-match styles**: six colour palettes × five typography presets = 30 combinations, plus five curated looks that pair a palette with its typeface. Switch either independently from Appearance → Editor → Styles.
* **Five typography presets** with self-hosted variable fonts: Poppins & Nunito, Inter, Fraunces & Inter, Space Grotesk & Inter, and Manrope.
* **Fourteen templates** including author, category, tag and date archives, plus Page and Post layouts with a sidebar.
* **WordPress 7.0 blocks** used where they belong: a native Accordion FAQ, breadcrumbs on posts and pages, result counts on archives and reading time in post meta — all degrading quietly on WordPress 6.6–6.9.
* Blog, archive, search, single, page, no-title page and 404 templates with comments and pagination.
* Consistent content styling: block and classic (shortcode) content share the same alignment ladder, captions, galleries, tables, code and vertical rhythm.
* Locally hosted Poppins and Nunito fonts (no external requests), fluid typography and a consistent spacing scale.
* No jQuery, no icon fonts, no framework. The only JavaScript is a 2 KB stat counter, loaded solely on pages that use the Stats pattern.

== Installation ==

1. In your WordPress dashboard go to Appearance → Themes → Add New → Upload Theme.
2. Upload the zip file, click Install Now, then Activate.
3. On a site without a static front page Unapp immediately creates a "Home" page (the landing page, template "Page (No Title)") and a "Blog" page and assigns them under Settings → Reading. Both are ordinary pages: edit the front page like any other page, or open Appearance → Editor for the header, footer, colors and templates.
4. If your site already has a static front page it is left untouched; a notice on the Dashboard/Themes screen offers a one-click "Set up the Unapp front page" instead. You can always switch back under Settings → Reading.

**Building the landing page by hand**

Create a page, choose the "Home landing page" pattern from the pattern modal (Patterns → Pages), set the template to "Page (No Title)" and select it under Settings → Reading → A static page. Developers can disable the automatic setup with `add_filter( 'unapp_auto_setup_front_page', '__return_false' );`.

== Accessibility ==

Unapp aims to meet the WordPress accessibility-ready requirements and WCAG 2.1 level AA where a theme can influence the outcome.

* Every colour palette meets AA contrast for body text, buttons and muted text; button labels were chosen for contrast rather than looks.
* A skip link, one main/header/footer landmark per page, and a continuous heading outline with no skipped levels.
* A single, high-contrast focus ring on every interactive element, which switches to the section's own text colour on dark and gradient backgrounds.
* Full keyboard operation: the navigation, its mobile overlay, the accordion, the lightbox and every form control are reachable and operable by keyboard alone.
* Motion respects `prefers-reduced-motion`: the stat counter does not animate and its final values are already in the markup.
* Text can be resized to 200% and reflows to a single column without loss of content or function.
* Images in patterns carry alt text or are marked decorative; no content is conveyed by colour alone.

If you find an accessibility barrier, please report it on the theme's support forum so it can be fixed.

== Frequently Asked Questions ==

= How do I add a contact or newsletter form? =

Unapp does not bundle a form plugin. Install any form plugin with a block (for example WPForms, Kali Forms, Contact Form 7 with its block, or MailChimp for WordPress) and add its block below the Contact cards or in place of the Subscribe button.

= How do I change the colors? =

Appearance → Editor → Styles. Pick one of the built-in variations (Emerald, Sunset, Midnight) or edit the palette. All patterns use palette slugs, so a palette change updates every section.

= Do I need WordPress 7.0? =

No — Unapp runs on WordPress 6.6 and later. A few patterns use blocks that arrived in 7.0 (the Accordion FAQ, breadcrumbs, result counts, reading time). On older versions those patterns are hidden and the blocks render nothing, so the rest of the theme is unaffected.

= Can I use the sections on other pages? =

Yes. Every section is a pattern: open the block inserter, choose the Patterns tab and look under "Unapp" for the whole library, or one of the narrower categories — Heroes, Features, Social proof, Pricing, Calls to action, Content & blog, Company and Utility.

= Where are the placeholder logos and avatars from? =

They are simple SVGs drawn for this theme — invented brand names for the logo cloud and gradient portraits for the team and testimonials. Nothing is licensed from a third party, so replace them with your own at any time.

== Changelog ==

= 2.2.0 - 2026-08-19 =
* Colour and typography style variations you can mix (6 × 5), five typography presets with four new variable fonts, author/category/tag/date and sidebar templates, a sidebar template part, a native Accordion FAQ, breadcrumbs, result counts, reading time, sticky-header support, WooCommerce compatibility styles, post formats, RTL-safe CSS and an accessibility statement.

= 2.1.0 - 2026-08-19 =
* 36 new section patterns, 10 new page starters, 11 new block styles, 3 new section styles and 2 new colour variations. See CHANGELOG.md.

= 2.0.0 - 2026-08-18 =
* Complete rewrite as a block theme (Full Site Editing) — see CHANGELOG.md for details.
* Front page and blog page are created and assigned automatically on activation (or via a one-click notice).

= 1.0 =
* Initial release (classic theme).

== Copyright ==

Unapp WordPress Theme, (C) 2018-2026 Colorlib
Unapp is distributed under the terms of the GNU GPL v3 or later.

This theme bundles the following third-party resources:

Poppins font
Copyright 2020 The Poppins Project Authors (https://github.com/itfoundry/Poppins)
License: SIL Open Font License, Version 1.1 — assets/fonts/poppins/OFL.txt
Source: https://fonts.google.com/specimen/Poppins

Nunito font
Copyright 2014 The Nunito Project Authors (https://github.com/googlefonts/nunito)
License: SIL Open Font License, Version 1.1 — assets/fonts/nunito/OFL.txt
Source: https://fonts.google.com/specimen/Nunito

Inter font
Copyright 2020 The Inter Project Authors (https://github.com/rsms/inter)
License: SIL Open Font License, Version 1.1 — assets/fonts/inter/OFL.txt
Source: https://fonts.google.com/specimen/Inter

Fraunces font
Copyright 2018 The Fraunces Project Authors (https://github.com/undercasetype/Fraunces)
License: SIL Open Font License, Version 1.1 — assets/fonts/fraunces/OFL.txt
Source: https://fonts.google.com/specimen/Fraunces

Space Grotesk font
Copyright 2020 The Space Grotesk Project Authors (https://github.com/floriankarsten/space-grotesk)
License: SIL Open Font License, Version 1.1 — assets/fonts/space-grotesk/OFL.txt
Source: https://fonts.google.com/specimen/Space+Grotesk

Manrope font
Copyright 2018 The Manrope Project Authors (https://github.com/sharanda/manrope)
License: SIL Open Font License, Version 1.1 — assets/fonts/manrope/OFL.txt
Source: https://fonts.google.com/specimen/Manrope

Icons in assets/images/icons/ are based on Feather Icons
Copyright (c) 2013-2023 Cole Bemis
License: MIT — https://github.com/feathericons/feather/blob/main/LICENSE

Avatar placeholders in assets/images/avatars/ were created for this theme by Colorlib and are released under the theme's GPL license.

Product screenshots and photos in assets/images/ were shipped with the original Unapp theme by Colorlib and are redistributed under the theme's GPL license.
