"""Page-opening variants: the same section with its heading as the page's h1.

A starter page has no visible title (page-no-title), so its first section's
heading is the page's h1. The sections that open inner pages also appear
further down other pages — church-story opens About and sits third on Plan
your visit — so the section itself has to stay an h2. Each one listed here
gets a hidden twin, `unapp/<slug>-h1`, whose first heading is level 1 and
looks exactly as the h2 did. Starter definitions and page starters put the
twin first.

Derived from the finished pattern file, so it runs after the grounds are
applied (apply_grounds.py calls it last) and follows every later change to
the section, including hand-written ones such as pricing.php.
"""
import json, os, re, sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import THEME, heading, h1  # noqa: E402

OPENINGS = [
    # page starters (patterns/page-*.php)
    "collaborate", "pricing", "contact-split", "values", "changelog", "waitlist",
    "testimonials", "features-offset",
    # starter pages (inc/starter-sites.php)
    "portfolio-work", "portfolio-about", "portfolio-contact",
    "church-visit", "church-story", "church-giving", "church-contact",
    "blog-about", "blog-contact",
    "fitness-schedule", "fitness-memberships", "fitness-location",
    "restaurant-menu", "restaurant-kitchen", "restaurant-hours",
    "agency-clients", "agency-team", "agency-contact",
    "shop-workshop", "shop-faq", "shop-featured",
    "realestate-listings", "realestate-fees", "realestate-valuation",
    "medical-services", "medical-team", "medical-hours",
    "education-courses", "education-tutors", "education-contact",
    "events-programme", "events-tickets", "events-venue",
    "finance-services", "finance-team", "finance-contact",
]
SUFFIX = "-h1"

HEADING_RE = re.compile(
    r'(?P<indent>[ \t]*)<!-- wp:heading(?: (?P<attrs>\{.*?\}))? -->[ \t]*\n'
    r'[ \t]*<h2 class="(?P<cls>[^"]*)"(?: style="(?P<style>[^"]*)")?>(?P<text>.*?)</h2>[ \t]*\n'
    r'[ \t]*<!-- /wp:heading -->')


def _kwargs(attrs):
    """heading() keyword arguments that reproduce a serialised heading block."""
    attrs = dict(attrs)
    kw = {}
    for key, arg in (("textAlign", "align"), ("className", "class_name"), ("textColor", "color"),
                     ("fontFamily", "font"), ("fontSize", "size")):
        if key in attrs:
            kw[arg] = attrs.pop(key)
    style = attrs.pop("style", {})
    typo = style.pop("typography", {})
    for key, arg in (("fontWeight", "weight"), ("lineHeight", "line_height"), ("letterSpacing", "letter")):
        if key in typo:
            kw[arg] = typo.pop(key)
    margin = style.pop("spacing", {}).pop("margin", None)
    if margin:
        kw["margin"] = {k: v.split("|")[-1] if v.startswith("var:") else v for k, v in margin.items()}
    leftover = {k: v for k, v in {**attrs, **style, **typo}.items() if v}
    if leftover:
        raise ValueError(f"heading attributes the variant cannot carry: {leftover}")
    return kw


def _norm(markup):
    """Markup with class order, style order and trailing semicolons normalised."""
    def cls(m):
        return 'class="' + " ".join(sorted(m.group(1).split())) + '"'

    def sty(m):
        return 'style="' + ";".join(sorted(x for x in m.group(1).split(";") if x)) + '"'
    def attrs(m):
        return "<!-- wp:heading " + json.dumps(json.loads(m.group(1)), sort_keys=True) + " -->"
    markup = re.sub(r"<!-- wp:heading (\{.*?\}) -->", attrs, markup)
    markup = re.sub(r'class="([^"]*)"', cls, markup)
    markup = re.sub(r'style="([^"]*)"', sty, markup)
    return re.sub(r"\s+", " ", markup).strip()


def promote(body):
    """Body with its first h2 heading block rewritten as the page's h1."""
    m = HEADING_RE.search(body)
    if not m:
        raise ValueError("no h2 heading block to promote")
    attrs = json.loads(m.group("attrs")) if m.group("attrs") else {}
    if attrs.get("level", 2) != 2:
        raise ValueError("first heading is not an h2")
    kw = _kwargs(attrs)
    original = body[m.start():m.end()]
    # The rebuilt h2 must be the original block, or the variant would differ
    # from its section in more than the level.
    if _norm(heading(m.group("text"), **kw)) != _norm(original):
        raise ValueError("heading did not round-trip:\n" + original)
    indent = m.group("indent")
    new = "\n".join(indent + line for line in h1(m.group("text"), **kw).split("\n"))
    return body[:m.start()] + new + body[m.end():]


def write_variant(slug):
    path = os.path.join(THEME, "patterns", slug + ".php")
    src = open(path).read()
    head_end = src.index("\n?>\n") + 4
    head, body = src[:head_end], src[head_end:]
    if "<h1" in body:
        raise ValueError(f"{slug} already has an h1")
    title = re.search(r"^ \* Title: (.*)$", head, re.M).group(1)
    head = re.sub(r"^ \* Title: .*$", f" * Title: {title} (opening a page)", head, count=1, flags=re.M)
    head = re.sub(r"^ \* Slug: .*$", f" * Slug: unapp/{slug}{SUFFIX}", head, count=1, flags=re.M)
    head = re.sub(r"^ \* Description: .*$",
                  " * Description: The same section with its heading as the page's h1, for the first section of a page.",
                  head, count=1, flags=re.M)
    head = re.sub(r"^ \* Keywords: .*\n", "", head, count=1, flags=re.M)
    if " * Inserter: no" not in head:
        head = head.replace(" * Categories:", " * Inserter: no\n * Categories:", 1)
    out = os.path.join(THEME, "patterns", slug + SUFFIX + ".php")
    open(out, "w").write(head + promote(body))


def main():
    for slug in OPENINGS:
        write_variant(slug)
    print(f"page-opening variants written: {len(OPENINGS)}")


if __name__ == "__main__":
    main()
