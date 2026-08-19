"""Generate the ten abstract placeholder images.

The first cut was a gradient with two translucent shapes on it, which rendered
as a coloured blur — the work grid in the portfolio demo looked like four beige
smudges. These are line drawings instead: a muted duotone ground, one large
geometric subject, and fine white line work over it, all sharing a stroke
weight, a margin and a light source so ten of them read as one commissioned set.

Tones stay desaturated so the images sit under any of the ten palettes.
"""
import os

OUT = os.path.join(os.path.dirname(os.path.abspath(__file__)), "..", "assets", "images", "abstract")
W, H = 1200, 800
LINE = "#ffffff"

# (ground top, ground bottom, subject) — muted, low-chroma, palette-neutral.
TONES = {
    "sanctuary": ("#efe7dc", "#d9c9b4", "#b9a084"),
    "gathering": ("#e8e5de", "#cfd3cb", "#9fa89b"),
    "desk":      ("#eceae5", "#d5d2cb", "#a8a49b"),
    "studio-1":  ("#eae7e4", "#d3ccc6", "#a89c93"),
    "studio-2":  ("#e7e9ec", "#ccd2da", "#9aa3b0"),
    "skyline":   ("#e4e8ec", "#c9d1da", "#94a1b0"),
    "reading":   ("#eeeae2", "#d8d0c2", "#ab9f8c"),
    "ledger":    ("#e6e9ea", "#cdd4d6", "#95a2a6"),
    "motion":    ("#ece7e6", "#d6cac8", "#ab9793"),
    "track":     ("#e7ebe7", "#cbd6cd", "#93a596"),
}


def head(name):
    top, bottom, subject = TONES[name]
    return (
        f'<svg xmlns="http://www.w3.org/2000/svg" width="{W}" height="{H}" viewBox="0 0 {W} {H}" '
        'role="img" aria-label="Abstract placeholder image">'
        '<defs>'
        f'<linearGradient id="g" x1="0" y1="0" x2="0.3" y2="1">'
        f'<stop offset="0" stop-color="{top}"/><stop offset="1" stop-color="{bottom}"/></linearGradient>'
        '<radialGradient id="l" cx="0.72" cy="0.18" r="0.7">'
        '<stop offset="0" stop-color="#ffffff" stop-opacity="0.55"/>'
        '<stop offset="1" stop-color="#ffffff" stop-opacity="0"/></radialGradient>'
        '</defs>'
        f'<rect width="{W}" height="{H}" fill="url(#g)"/>'
    ), subject


def tail():
    return f'<rect width="{W}" height="{H}" fill="url(#l)"/></svg>'


def strokes(paths, width=3, opacity=0.85):
    return (f'<g fill="none" stroke="{LINE}" stroke-opacity="{opacity}" stroke-width="{width}" '
            f'stroke-linecap="round">{paths}</g>')


def build(name):
    start, subject = head(name)
    body = ""

    if name == "sanctuary":
        # three arches, the middle one taller, with a rose window above
        body += f'<g fill="{subject}" fill-opacity="0.5">'
        for x, w, top in ((250, 200, 430), (500, 220, 330), (760, 200, 430)):
            body += f'<path d="M{x} 800 V{top + 100} a{w/2} {w/2} 0 0 1 {w} 0 V800 Z"/>'
        body += "</g>"
        arcs = ""
        for x, w, top in ((250, 200, 430), (500, 220, 330), (760, 200, 430)):
            arcs += f'M{x} 800 V{top + 100} a{w/2} {w/2} 0 0 1 {w} 0 V800 '
        arcs += "M610 250 m-70 0 a70 70 0 1 0 140 0 a70 70 0 1 0 -140 0 "
        arcs += "M610 180 V320 M540 250 H680 "
        body += strokes(f'<path d="{arcs}"/>')

    elif name == "gathering":
        # figures around a long table, seen from above
        body += f'<rect x="330" y="330" width="540" height="150" rx="75" fill="{subject}" fill-opacity="0.45"/>'
        circles = ""
        for i in range(5):
            cx = 380 + i * 110
            circles += f'<circle cx="{cx}" cy="250" r="46"/><circle cx="{cx}" cy="560" r="46"/>'
        body += f'<g fill="{subject}" fill-opacity="0.55">{circles}</g>'
        body += strokes('<path d="M330 405 H870"/>' + circles.replace("<circle", '<circle fill="none"'))

    elif name == "desk":
        # a desk top: paper, tools, a mug
        body += f'<g fill="{subject}" fill-opacity="0.45">'
        body += '<rect x="180" y="180" width="380" height="480" rx="10"/>'
        body += '<rect x="620" y="230" width="300" height="200" rx="12"/>'
        body += '<circle cx="800" cy="580" r="70"/></g>'
        rules = "".join(f'M230 {250 + i * 46} H510 ' for i in range(8))
        body += strokes(f'<path d="M180 180 h380 v480 h-380 Z M620 230 h300 v200 h-300 Z '
                        f'M800 580 m-70 0 a70 70 0 1 0 140 0 a70 70 0 1 0 -140 0 M870 545 h60 a30 30 0 0 1 0 60 h-60"/>'
                        f'<path stroke-opacity="0.5" d="{rules}"/>')

    elif name == "studio-1":
        # an easel with a canvas
        body += f'<rect x="330" y="150" width="440" height="380" rx="8" fill="{subject}" fill-opacity="0.5"/>'
        body += strokes('<path d="M330 150 h440 v380 h-440 Z M550 530 V760 M550 640 L390 760 M550 640 L710 760 '
                        'M400 300 q150 -120 300 0 M400 420 h300"/>')

    elif name == "studio-2":
        # an arched window with a sun and a horizon — a framed view
        body += (f'<path d="M400 680 V400 a200 200 0 0 1 400 0 v280 Z" fill="{subject}" fill-opacity="0.45"/>')
        body += strokes('<path d="M400 680 V400 a200 200 0 0 1 400 0 v280 Z M400 560 H800 M600 200 V680"/>'
                        '<path stroke-opacity="0.6" d="M600 430 m-70 0 a70 70 0 1 0 140 0 a70 70 0 1 0 -140 0"/>'
                        '<path stroke-opacity="0.35" d="M250 680 H950"/>')

    elif name == "skyline":
        # blocks with window grids
        blocks = ((200, 420), (300, 300), (400, 500), (520, 380), (620, 560), (740, 340), (840, 460))
        body += f'<g fill="{subject}" fill-opacity="0.45">'
        for x, h in blocks:
            body += f'<rect x="{x}" y="{800 - h}" width="84" height="{h}" rx="6"/>'
        body += "</g>"
        outline = "".join(f'M{x} 800 V{800 - h} h84 V800 ' for x, h in blocks)
        windows = ""
        for x, h in blocks:
            rows = (h - 60) // 70
            for r in range(rows):
                y = 800 - h + 40 + r * 70
                windows += f'M{x + 20} {y} h44 '
        body += strokes(f'<path d="{outline}"/><path stroke-opacity="0.45" stroke-width="8" d="{windows}"/>')

    elif name == "reading":
        # an open spread with text rules
        body += (f'<g fill="{subject}" fill-opacity="0.45">'
                 '<path d="M600 250 q-160 -60 -320 0 v380 q160 -60 320 0 Z"/>'
                 '<path d="M600 250 q160 -60 320 0 v380 q-160 -60 -320 0 Z"/></g>')
        rules = ""
        for i in range(7):
            y = 300 + i * 48
            rules += f'M320 {y} h230 M650 {y} h230 '
        body += strokes('<path d="M600 250 q-160 -60 -320 0 v380 q160 -60 320 0 Z '
                        'M600 250 q160 -60 320 0 v380 q-160 -60 -320 0 Z M600 250 V630"/>'
                        f'<path stroke-opacity="0.45" d="{rules}"/>')

    elif name == "ledger":
        # a table of figures with a rising line over it
        body += f'<rect x="200" y="200" width="800" height="440" rx="12" fill="{subject}" fill-opacity="0.4"/>'
        rules = "".join(f'M200 {270 + i * 70} H1000 ' for i in range(6))
        cols = "".join(f'M{440 + i * 190} 200 V640 ' for i in range(3))
        body += strokes(f'<path d="M200 200 h800 v440 h-800 Z"/>'
                        f'<path stroke-opacity="0.4" d="{rules}{cols}"/>'
                        '<path stroke-width="6" d="M260 560 L440 470 L620 500 L800 360 L940 300"/>'
                        '<path stroke-width="6" d="M880 300 h60 v60"/>')

    elif name == "motion":
        # concentric arcs, like a stride
        arcs = "".join(f'M{300 + i * 40} 640 a{260 - i * 40} {260 - i * 40} 0 0 1 {(260 - i * 40) * 2} 0 '
                       for i in range(5))
        body += f'<path d="M300 640 a260 260 0 0 1 520 0 Z" fill="{subject}" fill-opacity="0.4"/>'
        body += strokes(f'<path d="{arcs}"/><path stroke-opacity="0.5" d="M240 700 H960 M300 760 H900"/>')

    elif name == "track":
        # an oval track with lanes, seen from above
        body += (f'<rect x="220" y="220" width="760" height="360" rx="180" '
                 f'fill="{subject}" fill-opacity="0.45"/>')
        lanes = ""
        for i in range(4):
            inset = 34 + i * 34
            lanes += (f'M{220 + inset} {220 + inset} h{760 - inset * 2 - 0} '
                      f'a{180 - inset} {180 - inset} 0 0 1 0 {360 - inset * 2} '
                      f'h-{760 - inset * 2} a{180 - inset} {180 - inset} 0 0 1 0 -{360 - inset * 2} Z ')
        body += strokes('<path d="M400 220 h400 a180 180 0 0 1 0 360 h-400 a180 180 0 0 1 0 -360 Z"/>'
                        f'<path stroke-opacity="0.45" stroke-width="2" d="{lanes}"/>'
                        '<path stroke-opacity="0.7" stroke-width="5" d="M600 220 V320"/>')

    return start + body + tail()


total = 0
for name in TONES:
    svg = build(name)
    path = os.path.join(OUT, name + ".svg")
    open(path, "w").write(svg)
    total += len(svg)
print(f"10 abstract images written, {total} bytes total")
