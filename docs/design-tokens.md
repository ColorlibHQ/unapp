# Design tokens

Every section in the theme is built from one set of measurements. They live in
`theme.json` (as presets) and in `.dev/pgen.py` (as the values the generator
uses), and both agree.

## Spacing

`theme.json` registers seven steps. Nothing in the theme uses a value off this
scale — a gap that references an unregistered preset resolves to nothing and the
element silently falls back to its inherited spacing.

| Slug | Value | Used for |
| --- | --- | --- |
| `20` | `0.5rem` | inside tight rows |
| `30` | `1rem` | between blocks in a card, between stacked text |
| `40` | `1.5rem` | grid gutters |
| `50` | `clamp(1.5rem, 4vw, 2.5rem)` | card padding |
| `60` | `clamp(2rem, 6vw, 4rem)` | section intro to content, split gutters |
| `70` | `clamp(3rem, 8vw, 6rem)` | section padding, top and bottom |
| `80` | `clamp(4rem, 10vw, 8rem)` | cover heroes |

## Radii

Three, for the whole theme:

* `14px` — icon badges
* `20px` — cards, panels and images
* `999px` — pills, buttons and avatars

## Type

* Section titles: the default `h2` size
* Card titles: `h3` at `large`
* Leads: `large`, muted
* Small print and card body: `small`
* Hero headlines: `xxx-large`; gradient band headlines: `xx-large`

## Widths

* Intro column (eyebrow, title, lead): `680px`
* Reading column (FAQ, legal, lists): `760px`
* Wide content (grids, splits): the `wideSize` from `theme.json`

## Portraits

`96px` in a card grid, `56px` beside a name in a row.

## Grounds

Sections alternate their background by role: explanation sits on the page
ground, while proof, prices, answers and contact sit on
`is-style-section-soft`. Where two sections with the same ground meet,
`style.css` draws a hairline seam so they do not read as one column.
