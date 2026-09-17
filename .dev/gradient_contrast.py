"""Contrast of every pattern's text on the grounds it sets, in all 12 palettes.

Two parts.

1. The palette table: the primary-to-accent gradient against the palette's
   base colour and against white, per palette (what the closing bands use).

2. The pattern sweep. Every block in patterns/*.php is walked with the colour
   context CSS would give it: the ground (page base, a background or gradient
   preset, a section style, or a Cover's overlay over its image) and the text,
   heading, link and button colours inherited from ancestors, section styles,
   the palette partial and theme.json. Each piece of text is then measured in
   every palette against every colour its ground can show:

     solid ground          the colour
     gradient              both ends
     translucent ground    blended over the parent's ground
     Cover                 the overlay at dimRatio over the image's lightest
                           and darkest fill (an SVG's fills and gradient
                           stops; black and white for a photograph)

   Thresholds are WCAG AA: 4.5:1, 3:1 for large text (24px, or 18.66px at
   700) and for icons. Font sizes use the fluid minimum. A palette token such
   as base is the classic trap here: it is white in eleven palettes and
   near-black in Midnight, so base on the dark ground reads 1.06:1 there.

Page-opening copies (*-h1.php) are skipped: their colours are their
section's. Exits 1 if any text fails.
"""
import glob, json, os, re, sys

THEME = os.path.join(os.path.dirname(os.path.abspath(__file__)), "..")
IMAGES = os.path.join(THEME, "assets", "images")


# ------------------------------------------------------------------ colour maths
def rgba(value):
    """'#hex' | 'rgba(r,g,b,a)' -> [r, g, b, a] in 0..1."""
    value = value.strip()
    if value.startswith("#"):
        h = value[1:]
        if len(h) == 3:
            h = "".join(c * 2 for c in h)
        return [int(h[i:i + 2], 16) / 255 for i in (0, 2, 4)] + [1.0]
    m = re.match(r"rgba?\(([^)]*)\)", value)
    parts = [float(x) for x in m.group(1).replace("/", ",").split(",") if x.strip()]
    return [parts[0] / 255, parts[1] / 255, parts[2] / 255, parts[3] if len(parts) > 3 else 1.0]


def lum(c):
    c = [x / 12.92 if x <= 0.04045 else ((x + 0.055) / 1.055) ** 2.4 for x in c[:3]]
    return 0.2126 * c[0] + 0.7152 * c[1] + 0.0722 * c[2]


def ratio(a, b):
    la, lb = sorted((lum(a), lum(b)), reverse=True)
    return (la + 0.05) / (lb + 0.05)


def over(fg, bg, alpha=None):
    a = fg[3] if alpha is None else alpha
    return [fg[i] * a + bg[i] * (1 - a) for i in range(3)] + [1.0]


# ------------------------------------------------------------------ theme data
def load(path):
    return json.load(open(path))


THEME_JSON = load(os.path.join(THEME, "theme.json"))


def palettes():
    out = []
    for f in sorted(glob.glob(os.path.join(THEME, "styles", "colors", "*.json"))):
        d = load(f)
        colors = {c["slug"]: c["color"] for c in d["settings"]["color"]["palette"]}
        grads = {g["slug"]: g["gradient"] for g in d["settings"]["color"].get("gradients", [])}
        out.append({"title": d["title"], "colors": colors, "gradients": grads, "styles": d.get("styles", {})})
    return out


SECTION_STYLES = {}
for f in glob.glob(os.path.join(THEME, "styles", "*.json")):
    d = load(f)
    if "blockTypes" in d:
        SECTION_STYLES["is-style-" + d["slug"]] = d.get("styles", {})

FONT_PX = {"small": 14, "medium": 16, "large": 18, "x-large": 24, "xx-large": 30, "xxx-large": 38}
HEADING_PX = {1: 38, 2: 30, 3: 24, 4: 18, 5: 16, 6: 14}


def image_extremes(url):
    """The lightest and darkest colours an image can put behind text."""
    m = re.search(r"assets/images/([a-z0-9/_.-]+)'", url)
    if not m or not m.group(1).endswith(".svg"):
        return [rgba("#ffffff"), rgba("#000000")]
    svg = open(os.path.join(IMAGES, m.group(1))).read()
    # Area colours only: the fine white line work is a hairline, not a ground.
    found = [rgba(c) for c in re.findall(r'(?:fill|stop-color)="(#[0-9a-fA-F]{3,6})"', svg)]
    found.sort(key=lum)
    return [found[-1], found[0]] if found else [rgba("#ffffff"), rgba("#000000")]


# ------------------------------------------------------------------ block tree
PHP_RE = re.compile(r"<\?php.*?\?>", re.S)
TOKEN_RE = re.compile(r"<!-- (/?)wp:([a-z0-9/-]+)(?: (\{.*?\}))? (/?)-->", re.S)


def readable(php):
    s = re.search(r"'((?:[^'\\]|\\.)+)'", php.group(0))
    return s.group(1) if s else "…"


def parse(src):
    """A list of {name, attrs, html, children} from serialised block markup."""
    body = src[src.index("\n?>\n") + 4:] if "\n?>\n" in src else src
    body = PHP_RE.sub(lambda m: readable(m).replace('"', "'"), body)
    root = {"name": "root", "attrs": {}, "children": [], "start": 0}
    stack = [root]
    for m in TOKEN_RE.finditer(body):
        closing, name, attrs, selfclose = m.group(1), m.group(2), m.group(3), m.group(4)
        name = name if "/" in name else "core/" + name
        if closing:
            node = stack.pop()
            node["html"] = body[node["start"]:m.start()]
            continue
        try:
            a = json.loads(attrs) if attrs else {}
        except json.JSONDecodeError:
            a = {}
        node = {"name": name, "attrs": a, "children": [], "start": m.end(), "html": ""}
        stack[-1]["children"].append(node)
        if not selfclose:
            stack.append(node)
    return root["children"]


def text_of(html):
    t = re.sub(r"<!--.*?-->", " ", html, flags=re.S)
    t = re.sub(r"<[^>]+>", " ", t)
    return " ".join(t.split())[:48]


# ------------------------------------------------------------------ colour context
def token(value):
    """A theme.json colour reference -> ('slug', name) or ('lit', value)."""
    if value is None:
        return None
    if value.startswith("var:preset|color|"):
        return ("slug", value.split("|")[-1])
    if value.startswith("var(--wp--preset--color--"):
        return ("slug", value[len("var(--wp--preset--color--"):-1])
    return ("lit", value)


def resolve(spec, pal, ctx):
    kind, v = spec
    if kind == "slug":
        if v == "muted" and ctx.get("muted"):
            return rgba(ctx["muted"])
        return rgba(pal["colors"][v])
    return rgba(v)


def gradient_stops(value, pal):
    if value.startswith("var:preset|gradient|"):
        value = pal["gradients"].get(value.split("|")[-1], "")
    stops = re.findall(r"var\(--wp--preset--color--([a-z0-9-]+)\)|(rgba?\([^)]*\)|#[0-9a-fA-F]{3,6})", value)
    return [rgba(pal["colors"][s]) if s else rgba(lit) for s, lit in stops]


def apply_style(ctx, style):
    """Fold a section style's or a block's element colours into the context."""
    color = style.get("color", {})
    if color.get("text"):
        ctx["text"] = token(color["text"])
    els = style.get("elements", {})
    if els.get("heading", {}).get("color", {}).get("text"):
        ctx["heading"] = token(els["heading"]["color"]["text"])
    if els.get("link", {}).get("color", {}).get("text"):
        ctx["link"] = token(els["link"]["color"]["text"])
        ctx["link_set"] = True
    btn = els.get("button", {}).get("color", {})
    if btn.get("background"):
        ctx["button_bg"] = token(btn["background"])
    if btn.get("text"):
        ctx["button_text"] = token(btn["text"])
    m = re.search(r"--wp--preset--color--muted:\s*([^;]+);", style.get("css", ""))
    if m:
        ctx["muted"] = m.group(1).strip()


def ground_of(node, ctx, pal):
    """Colours this block can show behind its content, or None to inherit."""
    a = node["attrs"]
    parent = ctx["ground"]
    style_color = a.get("style", {}).get("color", {})
    classes = a.get("className", "").split()
    if node["name"] == "core/cover":
        dim = a.get("dimRatio", 50 if a.get("url") else 100) / 100
        if a.get("overlayColor"):
            overlay = rgba(pal["colors"][a["overlayColor"]])
        elif a.get("customOverlayColor"):
            overlay = rgba(a["customOverlayColor"])
        else:
            overlay = rgba("#000000")
        images = image_extremes(a["url"]) if a.get("url") else parent
        return [over(overlay, img, dim) for img in images]
    for c in classes:
        st = SECTION_STYLES.get(c, {}).get("color", {})
        if st.get("gradient"):
            return gradient_stops(st["gradient"], pal)
        if st.get("background"):
            bg = resolve(token(st["background"]), pal, ctx)
            return [over(bg, g) for g in parent]
    if a.get("gradient"):
        return [over(s, g) for s in gradient_stops("var:preset|gradient|" + a["gradient"], pal) for g in parent]
    if style_color.get("gradient"):
        return [over(s, g) for s in gradient_stops(style_color["gradient"], pal) for g in parent]
    if a.get("backgroundColor"):
        return [rgba(pal["colors"][a["backgroundColor"]])]
    if style_color.get("background"):
        bg = rgba(style_color["background"])
        return [over(bg, g) for g in parent]
    return None


def own_text(a):
    if a.get("textColor"):
        return ("slug", a["textColor"])
    t = a.get("style", {}).get("color", {}).get("text")
    return token(t) if t else None


def size_of(a, default_px):
    slug = a.get("fontSize")
    if slug in FONT_PX:
        return FONT_PX[slug]
    fs = a.get("style", {}).get("typography", {}).get("fontSize")
    if fs:
        # A literal, or the smallest length in a clamp()
        m = re.search(r"([\d.]+)(rem|px|em)", fs)
        if m:
            return float(m.group(1)) * (16 if m.group(2) in ("rem", "em") else 1)
    return default_px


def weight_of(a, default):
    return int(a.get("style", {}).get("typography", {}).get("fontWeight", default))


# ------------------------------------------------------------------ the sweep
def checks(nodes, ctx, pal, out, path=""):
    for node in nodes:
        a, name = node["attrs"], node["name"]
        child = dict(ctx)
        g = ground_of(node, ctx, pal)
        if g:
            child["ground"] = g
        for c in a.get("className", "").split():
            if c in SECTION_STYLES:
                apply_style(child, SECTION_STYLES[c])
        if name == "core/cover" and not own_text(a):
            child["text"] = ("lit", "#ffffff")
        if own_text(a) and name in ("core/group", "core/column", "core/columns", "core/cover"):
            child["text"] = own_text(a)
        apply_style(child, {"elements": a.get("style", {}).get("elements", {})})
        label = text_of(node["html"])
        where = f"{name.split('/')[-1]} “{label}”"

        def need(px, weight, icon=False):
            return 3.0 if icon or px >= 24 or (px >= 18.66 and weight >= 700) else 4.5

        if name in ("core/paragraph", "core/list", "core/details", "core/table", "core/term-count"):
            spec = own_text(a) or ctx["text"]
            px, w = size_of(a, ctx["px"]), weight_of(a, 400)
            out.append((where, resolve(spec, pal, child), child["ground"], need(px, w)))
            if "<a " in node["html"] and name == "core/paragraph":
                out.append((where + " (link)", resolve(child["link"], pal, child), child["ground"], need(px, w)))
        elif name == "core/heading":
            spec = own_text(a) or child["heading"]
            level = a.get("level", 2)
            px, w = size_of(a, HEADING_PX.get(level, 16)), weight_of(a, 600)
            out.append((where, resolve(spec, pal, child), child["ground"], need(px, w)))
        elif name == "core/button":
            classes = a.get("className", "")
            outline = "is-style-outline" in classes
            if a.get("backgroundColor"):
                bg = [rgba(pal["colors"][a["backgroundColor"]])]
            elif a.get("style", {}).get("color", {}).get("background"):
                bg = [over(rgba(a["style"]["color"]["background"]), x) for x in child["ground"]]
            elif outline:
                bg = child["ground"]
            else:
                bg = [over(resolve(child["button_bg"], pal, child), x) for x in child["ground"]]
            spec = own_text(a) or (child["outline_text"] if outline else child["button_text"])
            out.append((where, resolve(spec, pal, child), bg, 4.5))
        elif name == "core/navigation-link":
            out.append((f"navigation-link “{a.get('label', '')}”", resolve(child["link"], pal, child),
                        child["ground"], 4.5))
        elif name == "core/site-title":
            own = a.get("style", {}).get("elements", {}).get("link", {}).get("color", {}).get("text")
            spec = token(own) if own else (child["link"] if ctx.get("link_set") else ("slug", "contrast"))
            out.append((where, resolve(spec, pal, child), child["ground"], 3.0))
        elif name == "core/site-tagline":
            spec = ("lit", "rgba(255,255,255,0.75)") if "unapp-footer-note" in a.get("className", "") else child["text"]
            out.append((where, resolve(spec, pal, child), child["ground"], 4.5))
        elif name == "core/latest-posts":
            out.append(("latest-posts (titles)", resolve(child["link"], pal, child), child["ground"], 4.5))
            date = resolve(child["text"], pal, child)
            out.append(("latest-posts (dates)", date[:3] + [date[3] * 0.7], child["ground"], 4.5))
        elif name == "core/social-links":
            if a.get("iconColor"):
                spec = ("slug", a["iconColor"])
            elif a.get("iconColorValue"):
                spec = ("lit", a["iconColorValue"])
            else:
                spec = None
            if spec:
                out.append(("social-links (icons)", resolve(spec, pal, child), child["ground"], 3.0))
        if node["children"]:
            child["px"] = size_of(a, ctx["px"]) if name in ("core/group", "core/column") else ctx["px"]
            checks(node["children"], child, pal, out, path)


def root_context(pal):
    styles = THEME_JSON["styles"]
    ctx = {
        "ground": [rgba(pal["colors"]["base"])],
        "text": token(styles["color"]["text"]),
        "heading": token(styles["elements"]["heading"]["color"]["text"]),
        "link": token(styles["elements"]["link"]["color"]["text"]),
        "button_bg": token(styles["elements"]["button"]["color"]["background"]),
        "button_text": token(styles["elements"]["button"]["color"]["text"]),
        "outline_text": token(styles["blocks"]["core/button"]["variations"]["outline"]["color"]["text"]),
        "muted": None,
        "px": 16,
    }
    partial = pal["styles"]
    btn = partial.get("elements", {}).get("button", {}).get("color", {})
    if btn.get("background"):
        ctx["button_bg"] = token(btn["background"])
    if btn.get("text"):
        ctx["button_text"] = token(btn["text"])
    outline = partial.get("blocks", {}).get("core/button", {}).get("variations", {}).get("outline", {})
    if outline.get("color", {}).get("text"):
        ctx["outline_text"] = token(outline["color"]["text"])
    return ctx


def gradient_table(pals):
    worst = 99
    print(f"{'palette':10} {'base':>12} {'white':>12} {'white 86%':>12}")
    for p in pals:
        c = {k: rgba(v) for k, v in p["colors"].items()}
        ends = (c["primary"], c["accent"])
        base = min(ratio(c["base"], e) for e in ends)
        white = min(ratio(rgba("#ffffff"), e) for e in ends)
        w86 = min(ratio(over(rgba("#ffffff"), e, 0.86), e) for e in ends)
        worst = min(worst, base)
        print(f"{p['title']:10} {base:12.2f} {white:12.2f} {w86:12.2f}")
    print(f"lowest base-on-gradient ratio: {worst:.2f}")
    return worst


def sweep(pals, only=None):
    failures = {}
    measured = 0
    files = sorted(glob.glob(os.path.join(THEME, "patterns", "*.php")))
    for f in files:
        slug = os.path.basename(f)[:-4]
        if slug.endswith("-h1") or (only and slug not in only):
            continue
        tree = parse(open(f).read())
        for pal in pals:
            out = []
            checks(tree, root_context(pal), pal, out)
            for where, fg, grounds, need in out:
                measured += 1
                worst = min(ratio(over(fg, g), g) for g in grounds)
                if worst < need:
                    key = (slug, where)
                    failures.setdefault(key, []).append((pal["title"], worst, need))
    for (slug, where), fails in sorted(failures.items()):
        detail = ", ".join(f"{t} {r:.2f}" for t, r, _ in sorted(fails, key=lambda x: x[1]))
        print(f"{slug}: {where} needs {fails[0][2]}:1 — {detail}")
    print(f"text checks (pattern × palette): {measured}, failing: {len(failures)} texts in "
          f"{len({s for s, _ in failures})} patterns")
    return failures


if __name__ == "__main__":
    pals = palettes()
    worst = gradient_table(pals)
    print()
    fails = sweep(pals, set(sys.argv[1:]) or None)
    sys.exit(0 if worst >= 4.5 and not fails else 1)
