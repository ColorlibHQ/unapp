"""Contrast of text colours on the primary-to-accent gradient, per palette.

Checks both ends of the gradient against the palette's base colour and
against white at the opacities the patterns have used. WCAG AA: 4.5:1 for
body text, 3:1 for large text. Exits 1 if base fails anywhere.
"""
import glob, json, os, sys

THEME = os.path.join(os.path.dirname(os.path.abspath(__file__)), "..")


def rgb(h):
    h = h.lstrip("#")
    return [int(h[i:i + 2], 16) / 255 for i in (0, 2, 4)]


def lum(c):
    c = [x / 12.92 if x <= 0.04045 else ((x + 0.055) / 1.055) ** 2.4 for x in c]
    return 0.2126 * c[0] + 0.7152 * c[1] + 0.0722 * c[2]


def ratio(a, b):
    la, lb = sorted((lum(a), lum(b)), reverse=True)
    return (la + 0.05) / (lb + 0.05)


def over(fg, alpha, bg):
    return [f * alpha + b * (1 - alpha) for f, b in zip(fg, bg)]


worst = 99
print(f"{'palette':10} {'base':>12} {'white':>12} {'white 86%':>12}")
for f in sorted(glob.glob(os.path.join(THEME, "styles", "colors", "*.json"))):
    d = json.load(open(f))
    p = {c["slug"]: rgb(c["color"]) for c in d["settings"]["color"]["palette"]}
    ends = (p["primary"], p["accent"])
    base = min(ratio(p["base"], e) for e in ends)
    white = min(ratio([1, 1, 1], e) for e in ends)
    w86 = min(ratio(over([1, 1, 1], 0.86, e), e) for e in ends)
    worst = min(worst, base)
    print(f"{d['title']:10} {base:12.2f} {white:12.2f} {w86:12.2f}")
print(f"lowest base-on-gradient ratio: {worst:.2f}")
sys.exit(0 if worst >= 4.5 else 1)
