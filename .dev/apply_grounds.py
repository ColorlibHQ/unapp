"""Assign each section pattern its ground, by role.

Pages read as a sequence, not a stack: sections that explain sit on the page
ground, sections that reassure (proof, prices, answers, contact) sit on the
tinted one. Alternation then falls out of any sensible composition, instead of
every page being one undifferentiated white column.

Run after the batch generators; re-runnable and idempotent.
"""
import os, re, sys

THEME = os.path.join(os.path.dirname(os.path.abspath(__file__)), "..")
SOFT = {
    # social proof and credentials
    "logo-cloud", "testimonials", "ratings", "press", "case-study", "stats", "security",
    "finance-credentials", "fitness-results", "portfolio-testimonial",
    # answers
    "faq", "faq-accordion", "docs-topics", "finance-faq", "fitness-faq", "church-faq",
    # what it costs
    "pricing", "pricing-two", "finance-fees", "fitness-memberships",
    # how it works
    "how-it-works", "finance-process",
    # directories and listings
    "integrations", "careers",
    # getting in touch
    "contact", "contact-split", "offices", "church-contact", "fitness-location",
    "blog-contact", "portfolio-contact",
    # company furniture
    "values", "timeline", "related-posts", "author-box",
    # niche schedules and listings
    "church-times", "church-events", "fitness-schedule", "blog-categories",
    "portfolio-about",
}

SECTION_RE = re.compile(r'(<!-- wp:group (\{"align":"full".*?\}) -->\s*\n\s*<div class="([^"]*)")')


def ground_of(slug):
    return "soft" if slug in SOFT else "plain"


def set_ground(path, want):
    s = open(path).read()
    m = SECTION_RE.search(s)
    if not m:
        return None                      # cover, band or hand-authored wrapper
    whole, attrs, classes = m.group(1), m.group(2), m.group(3)
    if '"gradient"' in attrs or '"backgroundColor"' in attrs:
        return None                      # a band keeps its own ground
    has = "is-style-section-soft" in classes
    if (want == "soft") == has:
        return False
    if want == "soft":
        if '"className":"' in attrs:
            new_attrs = attrs.replace('"className":"', '"className":"is-style-section-soft ', 1)
        else:
            new_attrs = attrs.replace('{"align":"full"', '{"align":"full","className":"is-style-section-soft"', 1)
        new_classes = classes.replace("wp-block-group alignfull",
                                      "wp-block-group alignfull is-style-section-soft", 1)
    else:
        new_attrs = attrs.replace('"className":"is-style-section-soft ', '"className":"', 1)
        new_attrs = new_attrs.replace(',"className":"is-style-section-soft"', "", 1)
        new_classes = classes.replace(" is-style-section-soft", "", 1)
    new_whole = whole.replace(attrs, new_attrs).replace(f'class="{classes}"', f'class="{new_classes}"')
    open(path, "w").write(s.replace(whole, new_whole, 1))
    return True


changed = skipped = 0
for f in sorted(os.listdir(os.path.join(THEME, "patterns"))):
    if not f.endswith(".php") or f.startswith(("page-", "demo-", "hidden-", "header", "footer")):
        continue
    slug = f[:-4]
    r = set_ground(os.path.join(THEME, "patterns", f), ground_of(slug))
    if r is None:
        skipped += 1
    elif r:
        changed += 1
print(f"grounds applied: {changed} changed, {skipped} skipped (covers and bands)")
