"""Generate the ten avatar placeholders.

The originals were the default user glyph — a white head-and-shoulders on a
saturated gradient — which clashed with every palette and read as unfinished
next to real content. These are duotone silhouettes instead: one muted ground,
one darker figure, and a different hair and shoulder shape on each so a row of
four reads as four people rather than one icon recoloured.

Layer order matters: any hair that falls past the jaw is drawn *behind* the
head and shoulders, and anything drawn after the head is clipped to the skull.
Drawing a long style over the top leaves a slab across the chin.
"""
import os

OUT = os.path.join(os.path.dirname(os.path.abspath(__file__)), "..", "assets", "images", "avatars")

# One neutral ground for the whole set: four portraits side by side in a grid
# looked arbitrary when each carried its own tint. The figure tone varies
# instead, across a narrow, low-chroma range that sits under any palette.
GROUND = "#e8e6e1"
FIGURES = ["#7c8699", "#9a8672", "#78998a", "#a8837a", "#828da3",
           "#9d9068", "#8d8399", "#7a9599", "#a29079", "#87908a"]
TONES = [(GROUND, f) for f in FIGURES]

HEAD_CX, HEAD_CY, HEAD_R = 200, 170, 62

# (behind-the-head shape, on-the-skull shape). Both optional.
# The skull shape is always clipped to the head circle, so it can be drawn loosely.
STYLES = [
    # 0 cropped
    ("", '<path d="M120 170a80 80 0 0 1 160 0c0-30-36-46-80-46s-80 16-80 46z"/>'),
    # 1 bun
    ('<circle cx="200" cy="92" r="27"/>',
     '<path d="M120 176a80 80 0 0 1 160 0c0-40-36-60-80-60s-80 20-80 60z"/>'),
    # 2 long, falling behind the shoulders
    ('<path d="M122 180c0-50 35-84 78-84s78 34 78 84c0 42 6 86 14 126H108c8-40 14-84 14-126z"/>',
     '<path d="M120 172a80 80 0 0 1 160 0c0-36-36-54-80-54s-80 18-80 54z"/>'),
    # 3 curls
    ('<circle cx="150" cy="140" r="26"/><circle cx="186" cy="116" r="30"/>'
     '<circle cx="222" cy="118" r="29"/><circle cx="252" cy="144" r="25"/>', ""),
    # 4 side parting
    ("", '<path d="M120 176c0-42 34-72 80-72 34 0 62 18 72 46-22-6-52-2-78 12-22 12-46 16-74 14z"/>'),
    # 5 headscarf: falls to the shoulders, so it goes behind
    ('<path d="M118 330c0-30 6-70 16-104 8-26 12-52 12-76 0-34 24-58 54-58s54 24 54 58c0 24 4 50 12 76 10 34 16 74 16 104z"/>',
     ""),
    # 6 short, with a beard along the jaw
    ("", '<path d="M120 168a80 80 0 0 1 160 0c0-34-36-52-80-52s-80 18-80 52z"/>'
         '<path d="M138 186c0 52 28 86 62 86s62-34 62-86c0 30-28 44-62 44s-62-14-62-44z" fill-opacity="0.5"/>'),
    # 7 bob, tucked behind the jaw
    ('<path d="M124 200c0-58 34-96 76-96s76 38 76 96c0 24 4 44 10 60H114c6-16 10-36 10-60z"/>',
     '<path d="M120 186a80 80 0 0 1 160 0c0-44-36-66-80-66s-80 22-80 66z"/>'),
    # 8 top knot
    ('<path d="M186 88c0-14 28-14 28 0 0 12-6 18-14 18s-14-6-14-18z"/>',
     '<path d="M120 172a80 80 0 0 1 160 0c0-38-36-56-80-56s-80 18-80 56z"/>'),
    # 9 shaved
    ("", '<path d="M120 164a80 80 0 0 1 160 0c0-22-36-34-80-34s-80 12-80 34z" fill-opacity="0.45"/>'),
]

# Shoulder silhouettes, so the bodies differ too.
BODY = [
    "M64 400c0-72 61-124 136-124s136 52 136 124z",
    "M74 400c0-64 56-114 126-114s126 50 126 114z",
    "M56 400c0-80 65-132 144-132s144 52 144 132z",
    "M78 400c0-60 54-108 122-108s122 48 122 108z",
    "M62 400c0-76 63-128 138-128s138 52 138 128z",
]

for i in range(10):
    ground, figure = TONES[i]
    behind, skull = STYLES[i]
    body = BODY[i % len(BODY)]

    svg = (
        '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="400" viewBox="0 0 400 400" '
        'role="img" aria-label="Avatar placeholder">'
        f'<defs><clipPath id="s"><circle cx="{HEAD_CX}" cy="{HEAD_CY}" r="{HEAD_R}"/></clipPath></defs>'
        f'<rect width="400" height="400" fill="{ground}"/>'
        f'<g fill="{figure}">'
        + (behind or "")
        + f'<path d="{body}"/>'
        + f'<circle cx="{HEAD_CX}" cy="{HEAD_CY}" r="{HEAD_R}"/>'
        + (f'<g clip-path="url(#s)">{skull}</g>' if skull else "")
        + "</g></svg>"
    )
    open(os.path.join(OUT, f"avatar-{i + 1}.svg"), "w").write(svg)

total = sum(os.path.getsize(os.path.join(OUT, f"avatar-{i + 1}.svg")) for i in range(10))
print(f"10 avatars written, {total} bytes total")
