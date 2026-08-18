=== Unapp ===
Contributors: colorlib
Requires at least: 6.6
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 2.0.0
License: GNU General Public License v3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Tags: blog, one-column, wide-blocks, block-patterns, block-styles, custom-colors, custom-logo, custom-menu, editor-style, featured-images, full-site-editing, style-variations, template-editing, threaded-comments, translation-ready

Unapp is a lightweight block theme for app and SaaS landing pages, built entirely for the WordPress Site Editor.

== Description ==

Unapp is a modern, block-based WordPress theme for presenting an app, SaaS product or startup. Everything is built with core blocks and configured through theme.json, so the whole site — colors, fonts, spacing, header, footer and every landing-page section — can be edited visually in the Site Editor without touching code.

**What's included**

* A complete landing page out of the box: gradient hero with product screenshot, services grid, image & text feature, features around a phone, animated stats, screenshot gallery with lightbox, pricing table, team, latest posts, call to action and contact cards.
* 14 insertable patterns plus three full-page starters (Home, About, Pricing) under Patterns → Pages.
* Section styles for any Group: Card, Soft background, Dark and Gradient — switch the look of a section with one click.
* Block styles: Checklist (List) and Device frame (Image).
* Four color variations: default (blue/green), Emerald, Sunset and Midnight (dark).
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

== Frequently Asked Questions ==

= How do I add a contact or newsletter form? =

Unapp does not bundle a form plugin. Install any form plugin with a block (for example WPForms, Kali Forms, Contact Form 7 with its block, or MailChimp for WordPress) and add its block below the Contact cards or in place of the Subscribe button.

= How do I change the colors? =

Appearance → Editor → Styles. Pick one of the built-in variations (Emerald, Sunset, Midnight) or edit the palette. All patterns use palette slugs, so a palette change updates every section.

= Can I use the sections on other pages? =

Yes. Every section is a pattern: open the block inserter, choose the Patterns tab and look under "Unapp" or "Pages".

== Changelog ==

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

Icons in assets/images/icons/ are based on Feather Icons
Copyright (c) 2013-2023 Cole Bemis
License: MIT — https://github.com/feathericons/feather/blob/main/LICENSE

Avatar placeholders in assets/images/avatars/ were created for this theme by Colorlib and are released under the theme's GPL license.

Product screenshots and photos in assets/images/ were shipped with the original Unapp theme by Colorlib and are redistributed under the theme's GPL license.
