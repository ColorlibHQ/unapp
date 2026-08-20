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

## The endpoint

`https://updates.colorlib.com` is a Cloudflare Worker backed by a D1 database,
deployed from `~/Projects/colorlib-updates`. It answers:

| Path | Purpose |
| --- | --- |
| `GET /theme/{slug}.json` | The current release of a theme |
| `GET /plugin/{slug}.json` | The current release of a plugin |
| `GET /stats?token=…` | Aggregate install counts, for us |
| `GET /` | A plain-text page saying what is collected |

A live response:

```json
{
	"slug": "unapp",
	"version": "2.5.1",
	"url": "https://colorlib.com/wp/themes/unapp/",
	"package": "https://downloads.colorlib.com/wp/unapp/unapp-2.5.1.zip",
	"tested": "7.0",
	"requires": "6.6",
	"requires_php": "7.4",
	"autoupdate": false
}
```

Only `version` is required — WordPress ignores an update without one. The
`package` URL is what makes the one-click update button work; without it the
user is told an update exists and sent to `url` to fetch it by hand.

The theme caches the response for twelve hours and a failure for one, so a dead
endpoint costs one request an hour rather than one per page load. The Worker
replies `Cache-Control: no-store`, because a response served from an edge cache
would never reach the Worker and the install count would quietly become a count
of cache misses.

### It is a Worker rather than a file in R2

A static JSON file would serve updates perfectly well, and would be simpler.
The reason it is not one is that the update check *is* the install count — the
same request has to be answered and recorded. That only works if something runs
per request.

### Shipping a release

Releases live in a D1 row, not in the Worker source, so publishing never
depends on being able to deploy code:

```bash
# In the theme repository: build the zips (the theme zip must contain exactly
# one root folder named `unapp`, which is what WordPress installs).
.dev/build-zip.sh

# Upload. Filenames carry the version, so nothing is ever overwritten and no
# Cloudflare cache purge is needed.
rclone copyto /tmp/unapp-build/unapp-2.5.1.zip \
  r2pro:colorlib-downloads/wp/unapp/unapp-2.5.1.zip

# In ~/Projects/colorlib-updates: publish the row. This HEAD-checks the package
# first and refuses to publish a release pointing at a missing or non-zip file.
node release.mjs --product theme/unapp --version 2.5.1 \
  --package https://downloads.colorlib.com/wp/unapp/unapp-2.5.1.zip \
  --url https://colorlib.com/wp/themes/unapp/ \
  --tested 7.0 --requires 6.6 --requires-php 7.4
```

Every site sees the new version within twelve hours, on its own schedule.

### Reading the numbers

```bash
cd ~/Projects/colorlib-updates
STATS_TOKEN=$(sed 's/.*=//' .dev.vars) node stats.mjs
```

which prints active installs (distinct sites seen in the last 30 days), total
ever, new in the window, and the spread of versions, WordPress versions, PHP
versions and locales.

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

Because each request carries a stable per-site hash, the endpoint counts
distinct sites rather than requests. One row per site per product, upserted on
each check:

```sql
CREATE TABLE installs (
	site TEXT NOT NULL, product TEXT NOT NULL,
	version TEXT, wp TEXT, php TEXT, locale TEXT, multisite INTEGER,
	first_seen INTEGER NOT NULL, last_seen INTEGER NOT NULL,
	PRIMARY KEY (site, product)
);
```

Active installs are rows whose `last_seen` is inside the window, so the number
falls out of the same table that serves the update — the same shape of number
WordPress.org reports, without the directory. Nothing accumulates per request:
a site that has checked in for three years is still one row.

The Worker stores no IP address and reads none. A ping whose `site` value is
missing or malformed still receives its update, it is simply not counted — the
count must never become a reason to withhold a release.
