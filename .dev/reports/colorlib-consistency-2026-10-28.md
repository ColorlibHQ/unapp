# Colorlib article consistency correction

Published 28 October 2026 following review of the free-theme article expansion.

## Changes

- Corrected all 70 new or moved entries across the 29 expanded articles. Each now follows its surrounding article: standard feature cards, “At a glance” paragraphs, plain descriptions, or existing Details/Demo links.
- Replaced the separate green/outlined actions with the existing global Nectar buttons and established “More info / Download” / “Demo” labels. The site’s existing global button design and hosting action are retained.
- Removed bespoke badges, image borders, rounded screenshots, captions and disclosure boxes. Standard WordPress heading, image, paragraph and group blocks keep the content editable. Ownership disclosure and functional limitations remain.
- Centralized repeated feature-card, quick-pick and contents styling from 52 articles in the Colorlib child theme’s shared stylesheet. This includes 23 additional articles whose declarations were identical to the shared rules; their editorial content was retained.
- Added modification-time versioning to the existing shared stylesheet. Future style updates belong in that shared source, rather than CSS embedded inside posts. Added [editorial instructions](../editorial/AGENTS.md) and updated the generator to follow this rule.

## Verification

All 29 expanded articles passed public HTTP, entry count, visible/SEO title, contents destination, ItemList, screenshot decode and section-overflow checks at 1440, 390 and 320 pixels. The verifier compares the new buttons’ computed styles against existing entries on the same page, and checks the versioned shared stylesheet. Representative desktop and phone layouts were inspected.

The additional 23 articles were checked before and after migration at the same three widths: existing component colors, backgrounds, spacing, typography, display and column properties must remain identical, with no migrated rules left inside article content. Every removed CSS declaration has an identical shared declaration.

All writes use source-hash preflight checks and saved-content verification. Private backups of the 29 corrected posts, metadata and shared assets are retained under `/root/colorlib-consistency-20261028/before/`; the additional CSS migration has separate backups under `/root/colorlib-shared-css-20261028/before/`. Email was suppressed, and only affected article cache entries and stylesheet URLs were purged. Theme product releases and updates are unchanged.

See [the article placement manifest](colorlib-roundup-publication-2026-10-28.json), [29-article validation](colorlib-consistency-validation-2026-10-28.json) and [shared-CSS validation](colorlib-shared-css-validation-2026-10-28.json).
