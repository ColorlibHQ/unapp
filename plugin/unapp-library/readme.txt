=== Unapp Starter Library ===
Contributors: colorlib
Tags: block-patterns, starter-sites, full-site-editing
Requires at least: 6.6
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.1
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

== Rewrite with AI ==

**Appearance → Rewrite with AI** rewrites the words on the pages a starter
built, and only the words. Choose ChatGPT, Claude or Gemini — whichever you
already have an account with — paste your own API key, and describe your
business. The model receives the text on the page and your description, and
returns replacements one for one.

The layout is never sent and never changes. Only the text inside headings,
paragraphs, list items and links is extracted; the block markup around it is
not, so a model cannot damage a layout it never sees. Replacements are applied
between tags only, and the result is still valid block markup — verified at
106 blocks with zero invalid after a full-page rewrite.

Your key is stored on your own site and sent only to the provider you chose.
The previous wording stays in each page's revision history.

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

= 1.0.1 =
* A saved AI key now belongs to the provider it was saved with: switching provider clears it, and the model resets to that provider's default.
* A "Remove the saved key" control, a saved notice, and cleanup of the plugin's settings when it is deleted. The key can also be set with an UNAPP_AI_KEY constant.
* AI settings are no longer loaded on every request; Gemini's key is sent in a request header.
* Rewriting needs the same capability as saving the key, skips pages the user cannot edit, and a double click no longer starts a second request.
* Update checks send only the plugin name and version as the User-Agent.
* Remote packs load over HTTPS only, and nothing is loaded when no pack is switched on.

= 1.0.0 =
* First release, with the Charity pack and AI copy rewriting for ChatGPT, Claude and Gemini.
