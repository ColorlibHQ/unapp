# Getting started

## Install and activate

Upload the theme zip under **Appearance → Themes → Add New → Upload Theme**, then
activate it. On activation Unapp creates a **Home** page and a **Blog** page,
assigns them under **Settings → Reading**, and leaves you on a finished landing
page rather than an empty blog roll. If your site already has a static front
page, nothing is overwritten — a notice offers the setup instead.

Requires WordPress 6.6 or later. Everything works on 6.6; WordPress 7.0 adds
the Accordion, Breadcrumbs, Query Total and Terms Query blocks, which the theme
uses where available and quietly omits where not.

## Pick a starter site

**Appearance → Starter Sites** offers six complete designs:

| Starter | Look | Pages it creates |
| --- | --- | --- |
| SaaS & app | Indigo · Poppins & Nunito | Home, Features, Pricing, About, Contact |
| Portfolio | Mono · Inter | Home, Work, About, Contact |
| Church | Stone · Fraunces & Inter | Home, Plan your visit, About us, Give, Contact |
| Blog & magazine | Sunset · Fraunces & Inter | Home, About, Contact |
| Fitness studio | Ember · Manrope | Home, Timetable, Memberships, Contact |
| Finance & advisory | Navy · Fraunces & Inter | Home, Services, About, Contact |

Applying a starter writes its palette and typeface into Global Styles, builds
the pages, creates a menu, sets the front page, changes the header button
wording and swaps in that starter's footer.

**Nothing is ever deleted.** Applying a second starter adds new pages beside the
old ones and repoints the front page; your previous pages stay in **Pages**.

## Editing a starter page

Starter pages open in *content-only* mode: headings, paragraphs, images and
buttons are editable, while the groups and columns that hold the layout
together are locked. This stops a layout coming apart by accident. To edit the
structure of a section, select it and choose **Unlock** from the block toolbar.

## Contact forms

Unapp does not process form submissions — themes must not, and WordPress.org
rejects themes that do. Instead it renders whichever form plugin you already
have. Install any of WPForms, Contact Form 7, Gravity Forms, Fluent Forms,
Forminator, Ninja Forms, Kali Forms, Everest Forms, HappyForms or Jetpack,
create one form, and it appears on every contact section automatically, styled
to your palette.

With no form plugin installed, the contact sections show an email panel instead.

## Changing the look

**Appearance → Editor → Styles** holds three separate choices:

* **Colors** — ten palettes, each contrast-checked for body text and buttons.
* **Typography** — five typeface pairings. Every pattern follows automatically.
* **Browse styles** — ten curated looks that pair a palette with a typeface.

Colours and typography are independent, so the ten palettes and five type
presets give fifty combinations.

## Dark mode

The **Dark mode toggle** pattern adds a button that lets a visitor read the
site light or dark and remembers the choice. It keeps your palette rather than
replacing it: the neutrals swap to a dark set and your own primary, secondary
and accent are lightened just enough to stay legible, so a green site stays
green.

Nothing loads on pages without the toggle — no stylesheet, no script.

## Performance

Measured on a local WordPress 7.0 install with WooCommerce, Jetpack and a form
plugin active, cold cache, at 1280px:

| Page | Requests | Transferred | First contentful paint |
| --- | --- | --- | --- |
| Front page | 43 | 235 KB | 224 ms |
| Blog | 37 | 501 KB | 152 ms |

Most of the script weight is the active plugins rather than the theme; the
theme itself ships no framework, no jQuery and no icon font, and loads its
per-block stylesheets only on pages that use those blocks.
