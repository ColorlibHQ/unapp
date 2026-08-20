=== Unapp Starter Library ===
Contributors: colorlib
Tags: block-patterns, starter-sites, full-site-editing
Requires at least: 6.6
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Additional starter sites for the Unapp theme, shipped as self-contained packs.

== Description ==

The Unapp theme ships thirteen starter sites. This plugin adds more, and does
it without a theme update: each pack is one JSON file holding a starter
definition and the block markup of every pattern it needs.

Switch a pack on under **Appearance → Starter Library** and its starter appears
in **Appearance → Starter Sites** alongside the theme's own. Switch it off and
it disappears again — pages you have already built are never touched.

Packs can be bundled with the plugin or fetched from a library endpoint. Point
the plugin at your own endpoint with the `unapp_library_endpoint` filter:

`add_filter( 'unapp_library_endpoint', function () { return 'https://example.com/packs.json'; } );`

The endpoint returns a JSON array of packs in the same shape as the bundled
ones. Responses are cached for a day; a broken or unreachable endpoint simply
means no remote packs, and the bundled ones keep working.

The plugin does nothing at all unless the Unapp theme is active.

== Writing a pack ==

    {
      "slug": "nonprofit",
      "title": "Charity",
      "summary": "One line for the library screen.",
      "patterns": [
        { "slug": "nonprofit-hero", "title": "Charity: introduction",
          "categories": ["unapp"], "content": "<!-- wp:group ... -->" }
      ],
      "starter": {
        "title": "Charity", "cta": "Donate",
        "colors": "colors-2-emerald", "type": "typography-1-product",
        "home": "unapp-library/nonprofit-home",
        "pages": { "give": { "title": "Ways to give", "patterns": ["unapp-library/nonprofit-give"] } }
      }
    }

Patterns are registered under the `unapp-library/` namespace. Use `{{THEME}}`
in an image URL to point at the active theme's own assets rather than shipping
copies.

== Changelog ==

= 1.0.0 =
* First release, with the Charity pack.
