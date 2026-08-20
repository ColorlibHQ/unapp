# Updates and install counts

Unapp is not distributed on WordPress.org, so it brings its own update check.
It uses the hook WordPress added for exactly this in 6.1 rather than a bespoke
updater: a theme that declares an `Update URI` header has
`update_themes_{hostname}` filtered during the normal twice-daily update check,
and whatever that filter returns flows into **Dashboard → Updates**, the
**Appearance → Themes** notice and auto-updates. There is no extra cron, no
nag notice and no separate updater screen.

The plugin does the same through `update_plugins_{hostname}`.

## This choice rules out the WordPress.org directory

The `Update URI` header and a WordPress.org listing are mutually exclusive.
Theme Check reports the header as a REQUIRED failure — correctly, because a
theme *in* the directory must not carry it. Ours is distributed outside the
directory, so the header is right for us and that finding is expected.

If Unapp is ever submitted to WordPress.org, remove the header from
`style.css` and `inc/updates.php` stops doing anything, because core will not
fire the hook. Nothing else has to change.

## What the endpoint has to return

A single JSON document at `https://updates.colorlib.com/theme/unapp.json`:

```json
{
  "version": "2.6.0",
  "url": "https://colorlib.com/wp/themes/unapp/",
  "package": "https://downloads.colorlib.com/theme/unapp.zip",
  "tested": "7.1",
  "requires_php": "7.4",
  "autoupdate": false
}
```

Only `version` is required — WordPress ignores an update with no version. A
`package` URL is what makes the one-click update button work; without it the
user is told an update exists and sent to `url` to fetch it.

The response is cached for twelve hours, and a failure is cached for one, so a
dead endpoint costs one request an hour rather than one per page load.

## What is sent, and why

The update request is also the only install count there is. It carries:

| Field | Example | Why |
| --- | --- | --- |
| `theme` | `unapp` | Which product |
| `version` | `2.5.0` | To know what an install is upgrading from |
| `wp` | `7.1` | Which WordPress versions still need supporting |
| `php` | `8.5` | When the PHP floor can safely be raised |
| `locale` | `en_US` | Which translations are worth commissioning |
| `multisite` | `0` | Whether multisite bugs matter |
| `site` | `b0de008e…` | A one-way hash, so a count of installs is a count of sites |

`site` is `hash_hmac( 'sha256', home_url(), wp_salt( 'auth' ) )`, truncated. It
uses the install's own salt, so it cannot be reversed into a URL by whoever
receives it and two sites cannot collide. No site name, no email address and
no personal data is sent.

Two filters control it:

```php
// Send only what an update check strictly needs.
add_filter( 'unapp_update_payload', function () {
    return array( 'theme' => 'unapp', 'version' => wp_get_theme()->get( 'Version' ) );
} );

// Or switch the check off entirely — which also switches off update notices.
add_filter( 'unapp_check_for_updates', '__return_false' );
```

The theme states all of this on its own screen under Appearance → Starter
Sites, so a site owner does not have to read the source to find out what leaves
their server.

## Counting installs

Because each request carries a stable per-site hash, the endpoint can count
distinct sites rather than requests: active installs are distinct `site` values
seen in the last N days, and the version spread falls out of the same log. That
is the same shape of number WordPress.org reports, without the directory.

Keep the log short-lived — a rolling window is enough for the count and avoids
holding data nobody needs.
