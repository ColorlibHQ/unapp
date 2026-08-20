# Extending Unapp

## Use a child theme

A ready-made child theme ships alongside the theme in `child-theme/`. Zip that
folder, upload it under **Appearance → Themes → Add New**, and activate it.
Anything you add to its `style.css` or `theme.json` overrides the parent.

To change only colours or spacing, a child `theme.json` is usually enough:

```json
{
  "$schema": "https://schemas.wp.org/wp/6.6/theme.json",
  "version": 3,
  "settings": {
    "color": {
      "palette": [
        { "slug": "primary", "color": "#0f766e", "name": "Primary" }
      ]
    }
  }
}
```

## Add a starter site

Starter sites are filterable. Register your own with the same shape as the
built-in ones:

```php
add_filter( 'unapp_starter_sites', function ( $sites ) {
    $sites['bakery'] = array(
        'title'    => 'Bakery',
        'summary'  => 'Opening hours, the day\'s bread, and where to find us.',
        'cta'      => 'Order a loaf',
        'style'    => 'ember',
        'colors'   => 'colors-8-ember',
        'type'     => 'typography-5-geometric',
        'swatches' => array( '#c23b26', '#f2b705' ),
        'home'     => 'unapp/demo-bakery',
        'footer'   => 'unapp/footer-bakery',
        'pages'    => array(
            'menu' => array(
                'title'    => 'Menu',
                'patterns' => array( 'unapp/bakery-menu', 'unapp/bakery-hours' ),
            ),
        ),
    );
    return $sites;
} );
```

A page takes a `patterns` list, so each supporting page is a composition of
sections rather than a single pattern.

## Add patterns

Patterns are PHP files in `patterns/`. They are generated rather than
hand-typed — see `.dev/README.md` — but a hand-written file works the same way
provided its markup matches what the block's `save()` produces.

Register a new pattern category in a child theme with
`register_block_pattern_category()`, then reference it in the pattern header.

## Change the contact form

The form is chosen by `unapp_detect_form()`. To force a specific one, or to
render something the theme does not know about:

```php
add_filter( 'unapp_detected_form', function ( $form ) {
    return array(
        'key'    => 'custom',
        'label'  => 'My form',
        'markup' => '[my_form id="12"]',
    );
} );
```

Return `null` to force the email fallback.

## Turn off the front-page setup

```php
add_filter( 'unapp_auto_setup_front_page', '__return_false' );
```

## Ship a starter without a theme update

The **Unapp Starter Library** plugin (in `plugin/unapp-library/`) loads starter
sites from JSON packs. A pack holds the starter definition and the block markup
of every pattern it needs, so a new kind of site is a file rather than a
release.

Packs are read from the plugin's own `packs/` directory, or fetched from an
endpoint you nominate:

```php
add_filter( 'unapp_library_endpoint', function () {
    return 'https://example.com/unapp/packs.json';
} );
```

Build a pack from the generator the theme's own patterns use — see
`.dev/pack_nonprofit.py`, which resolves the PHP in a generated pattern down to
plain block markup and writes the JSON. Patterns register under the
`unapp-library/` namespace, and `{{THEME}}` in an image URL resolves to the
active theme's URL at runtime.

## Rewriting copy with AI

The library plugin can rewrite the words a starter produced, using ChatGPT,
Claude or Gemini and the site's own API key. It deliberately does not generate
layouts: the layouts are already measured and verified, so the model is pointed
at the part it is good at.

Only the text inside headings, paragraphs, list items and links is sent —
`unapp_ai_extract_text()` pulls the text nodes and nothing else — and
replacements go back between tags, so the block markup cannot be damaged by the
reply. A reply that does not line up with the page one-for-one is rejected
whole rather than applied partially.

To add a provider, filter the list and give it an endpoint and a response
shape; see `unapp_ai_providers()` and `unapp_ai_response_text()` in
`plugin/unapp-library/ai.php`.
