#!/usr/bin/env python3
"""Rebuild Unapp's style variations: colour partials, typography partials and curated full looks."""
import json, os, shutil

THEME = "/Users/silkalns/Fresh Projects/unapp"
S = "https://schemas.wp.org/trunk/theme.json"

LATIN = ("U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, "
         "U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD")
LATIN_EXT = ("U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, "
             "U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, "
             "U+2C60-2C7F, U+A720-A7FF")


def face(family, path, weight, style="normal"):
    subset = LATIN_EXT if "latin-ext" in path else LATIN
    return {"fontFamily": family, "fontStyle": style, "fontWeight": weight, "fontDisplay": "swap",
            "unicodeRange": subset, "src": [f"file:./assets/fonts/{path}"]}


# Font families available to typography presets. Each entry: slug -> (name, stack, faces)
FONTS = {
    "poppins": ("Poppins", "Poppins, ui-sans-serif, system-ui, -apple-system, sans-serif",
                [face("Poppins", f"poppins/poppins-{w}-{s}.woff2", w)
                 for w in ("400", "500", "600", "700") for s in ("latin-ext", "latin")]),
    "nunito": ("Nunito", "Nunito, ui-sans-serif, system-ui, -apple-system, sans-serif",
               [face("Nunito", f"nunito/nunito-300-800-{s}.woff2", "300 800") for s in ("latin-ext", "latin")]),
    "inter": ("Inter", "Inter, ui-sans-serif, system-ui, -apple-system, sans-serif",
              [face("Inter", f"inter/inter-normal-{s}.woff2", "100 900") for s in ("latin-ext", "latin")]),
    "fraunces": ("Fraunces", "Fraunces, ui-serif, Georgia, serif",
                 [face("Fraunces", f"fraunces/fraunces-normal-{s}.woff2", "300 900") for s in ("latin-ext", "latin")]),
    "space-grotesk": ("Space Grotesk", "\"Space Grotesk\", ui-sans-serif, system-ui, sans-serif",
                      [face("Space Grotesk", f"space-grotesk/space-grotesk-normal-{s}.woff2", "300 700")
                       for s in ("latin-ext", "latin")]),
    "manrope": ("Manrope", "Manrope, ui-sans-serif, system-ui, -apple-system, sans-serif",
                [face("Manrope", f"manrope/manrope-normal-{s}.woff2", "200 800") for s in ("latin-ext", "latin")]),
}

SYSTEM = {"slug": "system", "name": "System",
          "fontFamily": "ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, sans-serif"}


def family(slug, override_slug=None):
    name, stack, faces = FONTS[slug]
    return {"slug": override_slug or slug, "name": name, "fontFamily": stack, "fontFace": faces}


# --------------------------------------------------------------------- typography presets
# Each preset redefines the `heading` and `body` slugs, so every pattern follows automatically.
TYPO = [
    ("typography-1-product", "Poppins & Nunito", "poppins", "nunito",
     {"headingWeight": "600", "headingLetter": "-0.01em", "bodyWeight": "400", "bodyLineHeight": "1.7"}),
    ("typography-2-interface", "Inter", "inter", "inter",
     {"headingWeight": "700", "headingLetter": "-0.025em", "bodyWeight": "400", "bodyLineHeight": "1.65"}),
    ("typography-3-editorial", "Fraunces & Inter", "fraunces", "inter",
     {"headingWeight": "600", "headingLetter": "-0.015em", "bodyWeight": "400", "bodyLineHeight": "1.7"}),
    ("typography-4-technical", "Space Grotesk & Inter", "space-grotesk", "inter",
     {"headingWeight": "600", "headingLetter": "-0.02em", "bodyWeight": "400", "bodyLineHeight": "1.65"}),
    ("typography-5-geometric", "Manrope", "manrope", "manrope",
     {"headingWeight": "700", "headingLetter": "-0.02em", "bodyWeight": "400", "bodyLineHeight": "1.7"}),
]


def typography_variation(slug, title, head, body, opts):
    fams = [family(head, "heading"), family(body, "body"), SYSTEM]
    if head == body:
        fams = [family(head, "heading"), family(body, "body"), SYSTEM]
    return {
        "$schema": S, "version": 3, "title": title, "slug": slug,
        "settings": {"typography": {"fontFamilies": fams}},
        "styles": {
            "typography": {"fontFamily": "var:preset|font-family|body",
                           "fontWeight": opts["bodyWeight"], "lineHeight": opts["bodyLineHeight"]},
            "elements": {
                "heading": {"typography": {"fontFamily": "var:preset|font-family|heading",
                                           "fontWeight": opts["headingWeight"],
                                           "letterSpacing": opts["headingLetter"]}},
                "button": {"typography": {"fontFamily": "var:preset|font-family|heading"}},
            },
            "blocks": {
                "core/site-title": {"typography": {"fontFamily": "var:preset|font-family|heading"}},
                "core/navigation": {"typography": {"fontFamily": "var:preset|font-family|heading"}},
                "core/post-title": {"typography": {"fontFamily": "var:preset|font-family|heading"}},
            },
        },
    }


# --------------------------------------------------------------------- colour palettes
NAMES = {"base": "Base", "contrast": "Contrast", "primary": "Primary", "secondary": "Secondary",
         "accent": "Accent", "surface": "Surface", "muted": "Muted", "border": "Border", "dark": "Dark"}


def palette(**c):
    return [{"slug": k, "color": v, "name": NAMES[k]} for k, v in c.items()]


def gradients(p_rgba, a_rgba):
    return [
        {"slug": "primary-to-accent", "name": "Primary to Accent",
         "gradient": "linear-gradient(135deg, var(--wp--preset--color--primary) 0%, var(--wp--preset--color--accent) 100%)"},
        {"slug": "secondary-to-accent", "name": "Secondary to Accent",
         "gradient": "linear-gradient(135deg, var(--wp--preset--color--secondary) 0%, var(--wp--preset--color--accent) 100%)"},
        {"slug": "primary-overlay", "name": "Primary overlay",
         "gradient": f"linear-gradient(135deg, {p_rgba} 0%, {a_rgba} 100%)"},
        {"slug": "dark-overlay", "name": "Dark overlay",
         "gradient": "linear-gradient(180deg, rgba(35,35,35,0.55) 0%, rgba(35,35,35,0.85) 100%)"},
    ]


def shadows(card, strong, glow):
    return {"presets": [
        {"slug": "card", "name": "Card", "shadow": card},
        {"slug": "card-strong", "name": "Card strong", "shadow": strong},
        {"slug": "glow", "name": "Glow", "shadow": glow},
    ]}


PALETTES = [
    ("colors-1-indigo", "Indigo", dict(base="#ffffff", contrast="#232323", primary="#5468d8", secondary="#4aca85",
                                       accent="#2a74ca", surface="#f5f7fc", muted="#6b7280", border="#e4e7f0", dark="#303133"),
     ("rgba(84,104,216,0.9)", "rgba(42,116,202,0.85)"),
     ("0 12px 32px -8px rgba(48,49,51,0.12)", "0 28px 56px -20px rgba(48,49,51,0.30)", "0 24px 64px -24px rgba(84,104,216,0.55)"), None),
    ("colors-2-emerald", "Emerald", dict(base="#ffffff", contrast="#1c2321", primary="#12805a", secondary="#f7b32b",
                                         accent="#157a6f", surface="#f2faf6", muted="#66716d", border="#dfeae4", dark="#1c2321"),
     ("rgba(18,128,90,0.9)", "rgba(21,122,111,0.85)"),
     ("0 12px 32px -8px rgba(28,35,33,0.12)", "0 28px 56px -20px rgba(28,35,33,0.30)", "0 24px 64px -24px rgba(18,128,90,0.5)"), None),
    ("colors-3-sunset", "Sunset", dict(base="#ffffff", contrast="#2b1d1a", primary="#c9412c", secondary="#f9a826",
                                       accent="#b93c65", surface="#fff6f1", muted="#7a6f6b", border="#f1e4dd", dark="#2b1d1a"),
     ("rgba(201,65,44,0.9)", "rgba(185,60,101,0.85)"),
     ("0 12px 32px -8px rgba(43,29,26,0.12)", "0 28px 56px -20px rgba(43,29,26,0.30)", "0 24px 64px -24px rgba(201,65,44,0.5)"), None),
    ("colors-4-graphite", "Graphite", dict(base="#ffffff", contrast="#16181d", primary="#3d4351", secondary="#22c8b4",
                                           accent="#5b6472", surface="#f4f5f7", muted="#5f6672", border="#e2e4e9", dark="#16181d"),
     ("rgba(61,67,81,0.92)", "rgba(91,100,114,0.88)"),
     ("0 12px 32px -8px rgba(22,24,29,0.12)", "0 28px 56px -20px rgba(22,24,29,0.30)", "0 24px 64px -24px rgba(61,67,81,0.45)"), None),
    ("colors-5-violet", "Violet", dict(base="#ffffff", contrast="#20142e", primary="#7c3aed", secondary="#0ea5a5",
                                       accent="#a21caf", surface="#f8f4ff", muted="#6b6280", border="#eae2f6", dark="#20142e"),
     ("rgba(124,58,237,0.9)", "rgba(162,28,175,0.85)"),
     ("0 12px 32px -8px rgba(32,20,46,0.12)", "0 28px 56px -20px rgba(32,20,46,0.30)", "0 24px 64px -24px rgba(124,58,237,0.5)"), None),
    ("colors-6-midnight", "Midnight", dict(base="#12141c", contrast="#f3f4f8", primary="#8b9df0", secondary="#4fd391",
                                           accent="#5aa7f0", surface="#1a1d28", muted="#a3a9b8", border="#2b3040", dark="#0b0d13"),
     ("rgba(139,157,240,0.9)", "rgba(90,167,240,0.85)"),
     ("0 12px 32px -8px rgba(0,0,0,0.45)", "0 28px 56px -20px rgba(0,0,0,0.6)", "0 24px 64px -24px rgba(139,157,240,0.55)"), "dark"),
    ("colors-7-stone", "Stone", dict(base="#ffffff", contrast="#241f1a", primary="#7d5f38", secondary="#5cb39a",
                                     accent="#96683f", surface="#faf6f0", muted="#6a5f56", border="#ece3d8", dark="#241f1a"),
     ("rgba(125,95,56,0.9)", "rgba(150,104,63,0.85)"), None, None),
    ("colors-8-ember", "Ember", dict(base="#ffffff", contrast="#17161a", primary="#c23b26", secondary="#f2b705",
                                     accent="#a8321f", surface="#fdf5f2", muted="#5f5c63", border="#f0e2dd", dark="#17161a"),
     ("rgba(194,59,38,0.9)", "rgba(168,50,31,0.85)"), None, None),
    ("colors-9-navy", "Navy", dict(base="#ffffff", contrast="#0f1a2b", primary="#1e4272", secondary="#d1a33f",
                                   accent="#2b5a8c", surface="#f3f6fa", muted="#5a6473", border="#e0e6ee", dark="#0f1a2b"),
     ("rgba(30,66,114,0.9)", "rgba(43,90,140,0.85)"), None, None),
    ("colors-10-mono", "Mono", dict(base="#ffffff", contrast="#111111", primary="#111111", secondary="#e8e6e1",
                                    accent="#4a4a4a", surface="#f6f5f3", muted="#6b6b6b", border="#e6e4e0", dark="#111111"),
     ("rgba(17,17,17,0.9)", "rgba(74,74,74,0.85)"), None, None),
    ("colors-11-harvest", "Harvest", dict(base="#ffffff", contrast="#221d14", primary="#6b6122", secondary="#e2a33c",
                                          accent="#8a5a1f", surface="#faf7ee", muted="#655e4d", border="#ece5d3", dark="#221d14"),
     ("rgba(107,97,34,0.9)", "rgba(138,90,31,0.85)"), None, None),
    ("colors-12-slate", "Slate", dict(base="#ffffff", contrast="#13181d", primary="#1f4e56", secondary="#57c2b4",
                                      accent="#2f6f79", surface="#f2f7f7", muted="#586268", border="#dfe8e9", dark="#13181d"),
     ("rgba(31,78,86,0.9)", "rgba(47,111,121,0.85)"), None, None),
]

DARK_BUTTON_STYLES = {
    "elements": {"button": {
        "color": {"background": "var:preset|color|secondary", "text": "var:preset|color|contrast"},
        ":hover": {"color": {"background": "var:preset|color|primary", "text": "var:preset|color|base"}},
        ":focus": {"color": {"background": "var:preset|color|primary", "text": "var:preset|color|base"}},
        ":active": {"color": {"background": "var:preset|color|primary", "text": "var:preset|color|base"}},
    }},
    "blocks": {"core/button": {"variations": {"outline": {
        ":hover": {"color": {"background": "var:preset|color|primary", "text": "var:preset|color|base"},
                   "border": {"color": "var:preset|color|primary"}}}}}},
}


def colour_variation(slug, title, pal, grad, shad, mode):
    # Colour partials must contain nothing but colour, or the Site Editor will not
    # list them under Styles > Colors (isVariationWithProperties in edit-site).
    v = {"$schema": S, "version": 3, "title": title, "slug": slug,
         "settings": {"color": {"palette": palette(**pal), "gradients": gradients(*grad)}}}
    if mode == "dark":
        v["styles"] = json.loads(json.dumps(DARK_BUTTON_STYLES))
    return v


# --------------------------------------------------------------------- curated full looks
# palette slug, typography slug, title
FULL = [
    ("indigo", "colors-1-indigo", "typography-1-product", "Indigo"),
    ("emerald", "colors-2-emerald", "typography-5-geometric", "Emerald"),
    ("sunset", "colors-3-sunset", "typography-3-editorial", "Sunset"),
    ("graphite", "colors-4-graphite", "typography-2-interface", "Graphite"),
    ("violet", "colors-5-violet", "typography-1-product", "Violet"),
    ("midnight", "colors-6-midnight", "typography-4-technical", "Midnight"),
    ("stone", "colors-7-stone", "typography-3-editorial", "Stone"),
    ("ember", "colors-8-ember", "typography-5-geometric", "Ember"),
    ("navy", "colors-9-navy", "typography-3-editorial", "Navy"),
    ("mono", "colors-10-mono", "typography-2-interface", "Mono"),
    ("harvest", "colors-11-harvest", "typography-3-editorial", "Harvest"),
    ("slate", "colors-12-slate", "typography-4-technical", "Slate"),
]


def main():
    styles = os.path.join(THEME, "styles")
    # keep block style variations (files carrying blockTypes), drop the old full variations
    keep = {"card.json", "section-soft.json", "section-dark.json", "section-gradient.json",
            "frosted.json", "outline.json", "elevated.json"}
    for f in os.listdir(styles):
        p = os.path.join(styles, f)
        if os.path.isfile(p) and f.endswith(".json") and f not in keep:
            os.remove(p)
            print("removed", f)
    for sub in ("colors", "typography"):
        d = os.path.join(styles, sub)
        shutil.rmtree(d, ignore_errors=True)
        os.makedirs(d)

    def dump(path, obj):
        json.dump(obj, open(path, "w"), indent="\t", ensure_ascii=False)
        open(path, "a").write("\n")

    typo_by_slug = {}
    for slug, title, head, body, opts in TYPO:
        v = typography_variation(slug, title, head, body, opts)
        typo_by_slug[slug] = v
        dump(os.path.join(styles, "typography", slug + ".json"), v)
    print("typography partials:", len(TYPO))

    col_by_slug = {}
    for slug, title, pal, grad, shad, mode in PALETTES:
        v = colour_variation(slug, title, pal, grad, shad, mode)
        col_by_slug[slug] = v
        dump(os.path.join(styles, "colors", slug + ".json"), v)
    print("colour partials:", len(PALETTES))

    for out_slug, col_slug, typo_slug, title in FULL:
        col, typ = col_by_slug[col_slug], typo_by_slug[typo_slug]
        merged = {"$schema": S, "version": 3, "title": title, "slug": out_slug,
                  "settings": {"color": col["settings"]["color"],
                               "typography": typ["settings"]["typography"]},
                  "styles": json.loads(json.dumps(typ["styles"]))}
        if "styles" in col:
            for k, val in col["styles"].items():
                if k in merged["styles"]:
                    merged["styles"][k] = {**merged["styles"][k], **val}
                else:
                    merged["styles"][k] = val
        dump(os.path.join(styles, out_slug + ".json"), merged)
    print("full curated looks:", len(FULL))



if __name__ == "__main__":
    main()
