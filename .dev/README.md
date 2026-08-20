# Generator and audits

The patterns in `patterns/*.php` are generated, not hand-typed. Regenerate the
whole library with:

    for i in $(seq 1 22); do python3 .dev/batch$i.py; done
    python3 .dev/apply_grounds.py

`apply_grounds.py` runs last: it assigns each section its ground by role, which
the batches deliberately do not do.

## The measurement system

`pgen.py` holds every measurement the theme uses, and refuses anything off the
scale. Build sections from the components rather than passing numbers:

| Component | What it is |
| --- | --- |
| `section_std(inner, variation=, bg=, gradient=)` | a full-width section: padding 70, intro-to-content gap 60 |
| `card(inner)` | a surface: padding 50, gap 30, radius 20px |
| `stack(inner, gap=)` | a vertical run; also what makes an icon badge shrink to content |
| `grid(inner, cols=3\|4)` | a wide grid with the step-down utility class |
| `split(left, right)` | two columns at the house gutter, both carrying the stack gap |
| `icon_card(icon, title, body)` | badge, h3 and a paragraph |
| `card_title()` / `label()` | an h3 at `large`; the small uppercase line |
| `faq_list(pairs)` | Details cards in the reading column |
| `band(title, body, buttons)` | a closing call to action on the palette gradient |

Three radii exist in the theme: 14px (icon badges), 20px (cards and images),
999px (pills and avatars). Portraits are 96px in a grid, 56px beside a name.

## Audits

    python3 .dev/rhythm_audit.py            # ground rhythm of every composition
    node cdp-rhythm.mjs jobs.json 1280      # measured gaps vs the spacing scale
    node cdp-wrap.mjs jobs.json 1280,768,390  # orphaned items on the last row

`cdp-rhythm.mjs` resolves the spacing presets by measuring an element: the scale
is written in `rem` and `clamp()`, so parsing the raw custom property gives
nonsense (`parseFloat("0.5rem")` is `0.5`, not `8`).

## Artwork

    python3 .dev/avatars.py     # assets/images/avatars/*.svg
    python3 .dev/abstract.py    # assets/images/abstract/*.svg
