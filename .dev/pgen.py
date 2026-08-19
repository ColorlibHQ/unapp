#!/usr/bin/env python3
"""Emitters for Unapp block-pattern PHP files.

Every helper returns markup that matches what the corresponding core block's
save() produces, so patterns validate in the editor.
"""
import json, os, re

THEME = "/Users/silkalns/Fresh Projects/unapp"
DOM = "unapp"


# --------------------------------------------------------------------------- text
def esc(s):
    return s.replace("\\", "\\\\").replace("'", "\\'")


def t(s, ctx=None):
    """Inline translatable text."""
    if ctx:
        return f"<?php echo esc_html_x( '{esc(s)}', '{esc(ctx)}', '{DOM}' ); ?>"
    return f"<?php esc_html_e( '{esc(s)}', '{DOM}' ); ?>"


def tattr(s):
    return f"<?php esc_attr_e( '{esc(s)}', '{DOM}' ); ?>"


def uri(path):
    return f"<?php echo esc_url( get_theme_file_uri( '{path}' ) ); ?>"


def php(expr):
    return f"<?php echo {expr}; ?>"


# --------------------------------------------------------------------------- attrs
def _attrs(d):
    return json.dumps(d, separators=(",", ":"), ensure_ascii=False)


# The only spacing presets theme.json registers. A value outside this set
# serialises to var(--wp--preset--spacing--16), which is undefined, so the
# declaration is dropped and the element silently falls back to the inherited
# gap — the cause of the ragged spacing in the first cut of the niche patterns.
SPACING_SLUGS = {"20", "30", "40", "50", "60", "70", "80"}


def _space(n):
    n = str(n)
    if n in SPACING_SLUGS:
        return None  # preset
    if n == "0" or n.endswith(("px", "rem", "em", "%", "vw", "vh")):
        return n     # explicit length, deliberate
    raise ValueError(
        f"spacing {n!r} is not a registered preset {sorted(SPACING_SLUGS)} "
        "and not an explicit length; pick a step on the scale"
    )


def sp(n):
    literal = _space(n)
    return literal if literal else f"var:preset|spacing|{n}"


def spc(n):
    literal = _space(n)
    return literal if literal else f"var(--wp--preset--spacing--{n})"


# --------------------------------------------------------------- house style
# Derived from the patterns the theme shipped with; every pattern follows these
# so sections stack in one rhythm regardless of which niche wrote them.
SECTION_PAD = ("70", "70")   # top/bottom of every full-width section
SECTION_GAP = "60"           # intro -> content
TIGHT_GAP = "50"             # content -> footnote inside a section
CARD_RADIUS = "20px"
CARD_PAD = "50"              # all four sides of a card
CARD_GAP = "30"              # between blocks inside a card
ROW_GAP = "40"               # between cards in a grid
SPLIT_GAP = "60"             # between the two halves of a split section
STACK_GAP = "30"             # between stacked text blocks in a column
INTRO_WIDTH = "680px"        # eyebrow + title + lead
READ_WIDTH = "760px"         # FAQ lists, legal text, single-column content
CARD_TITLE_SIZE = "large"    # every card heading is an h3 at this size
AVATAR_GRID = "96px"         # portrait in a card grid
AVATAR_ROW = "56px"          # portrait beside a name in a row
AVATAR_FEATURE = "140px"     # portrait in a team/feature card


def _pad_attr(pad):
    if pad is None:
        return None
    if isinstance(pad, (str, int)):
        pad = {"top": pad, "bottom": pad}
    return {k: sp(v) for k, v in pad.items()}


def _pad_css(pad):
    if pad is None:
        return ""
    if isinstance(pad, (str, int)):
        pad = {"top": pad, "bottom": pad}
    order = ["top", "right", "bottom", "left"]
    return "".join(f"padding-{k}:{spc(pad[k])};" for k in order if k in pad)


# --------------------------------------------------------------------------- blocks
def group(inner, *, align=None, style_variation=None, class_name=None, bg=None, text=None,
          gradient=None, pad=None, gap=None, layout="constrained", content_size=None,
          wide_size=None, radius=None, border_top=None, shadow=None, extra_style="", tag="div",
          justify=None, orientation=None, wrap=None, vertical_align=None, elements=None,
          min_col=None, col_count=None):
    a, classes, css = {}, ["wp-block-group"], ""
    if align:
        a["align"] = align
        classes.append("align" + align)
    cls_extra = " ".join(x for x in [style_variation, class_name] if x)
    if cls_extra:
        a["className"] = cls_extra
        classes.append(cls_extra)
    if tag != "div":
        a["tagName"] = tag
    if gradient:
        a["gradient"] = gradient
        classes += [f"has-{gradient}-gradient-background", "has-background"]
    if bg:
        a["backgroundColor"] = bg
        classes += [f"has-{bg}-background-color", "has-background"]
    if text:
        a["textColor"] = text
        classes += [f"has-{text}-color", "has-text-color"]
    style = {}
    if radius:
        style.setdefault("border", {})["radius"] = radius
        css += f"border-radius:{radius};"
    if border_top:
        # (color, width, style) -> a real block attribute, so save() and the
        # serialized markup agree. Raw CSS here fails block validation.
        col, wid, sty = border_top
        style.setdefault("border", {})["top"] = {"color": col, "width": wid, "style": sty}
        css += f"border-top-color:{col};border-top-style:{sty};border-top-width:{wid};"
    spacing = {}
    if pad is not None:
        spacing["padding"] = _pad_attr(pad)
        css += _pad_css(pad)
    if gap is not None:
        spacing["blockGap"] = sp(gap)
    if spacing:
        style["spacing"] = spacing
    if shadow:
        style["shadow"] = f"var:preset|shadow|{shadow}"
        css += f"box-shadow:var(--wp--preset--shadow--{shadow});"
    if elements:
        style["elements"] = elements
        if "link" in elements:
            classes.append("has-link-color")
    if style:
        a["style"] = style
    lay = {"type": layout}
    if layout == "constrained":
        if content_size:
            lay["contentSize"] = content_size
        if wide_size:
            lay["wideSize"] = wide_size
    if layout == "grid":
        if col_count:
            lay["columnCount"] = col_count
        if min_col:
            lay["minimumColumnWidth"] = min_col
            lay["columnCount"] = None
    if layout == "flex":
        if orientation:
            lay["orientation"] = orientation
        if wrap:
            lay["flexWrap"] = wrap
        if justify:
            lay["justifyContent"] = justify
        if vertical_align:
            lay["verticalAlignment"] = vertical_align
    a["layout"] = lay
    css += extra_style
    style_attr = f' style="{css}"' if css else ""
    return (f'<!-- wp:group {_attrs(a)} -->\n<{tag} class="{" ".join(dict.fromkeys(classes))}"{style_attr}>\n'
            f'{inner}\n</{tag}>\n<!-- /wp:group -->')


def columns(cols, *, align=None, gap=None, vertical_align=None, class_name=None, is_stacked=True):
    a, classes = {}, ["wp-block-columns"]
    if vertical_align:
        a["verticalAlignment"] = vertical_align
        classes.append(f"are-vertically-aligned-{vertical_align}")
    if align:
        a["align"] = align
        classes.append("align" + align)
    if class_name:
        a["className"] = class_name
        classes.append(class_name)
    if not is_stacked:
        a["isStackedOnMobile"] = False
        classes.append("is-not-stacked-on-mobile")
    if gap is not None:
        a["style"] = {"spacing": {"blockGap": {"top": sp(gap), "left": sp(gap)}}}
    return (f'<!-- wp:columns {_attrs(a)} -->\n<div class="{" ".join(classes)}">\n'
            + "\n".join(cols) + '\n</div>\n<!-- /wp:columns -->')


def column(inner, *, width=None, vertical_align=None, gap=None, layout=None, justify=None,
           orientation=None, style_variation=None, pad=None, radius=None, bg=None, shadow=None):
    a, classes, css = {}, ["wp-block-column"], ""
    if vertical_align:
        a["verticalAlignment"] = vertical_align
        classes.append(f"is-vertically-aligned-{vertical_align}")
    if width:
        a["width"] = width
        css += f"flex-basis:{width};"
    if style_variation:
        a["className"] = style_variation
        classes.append(style_variation)
    if bg:
        a["backgroundColor"] = bg
        classes += [f"has-{bg}-background-color", "has-background"]
    style = {}
    spacing = {}
    if gap is not None:
        spacing["blockGap"] = sp(gap)
    if pad is not None:
        spacing["padding"] = _pad_attr(pad)
        css += _pad_css(pad)
    if spacing:
        style["spacing"] = spacing
    if radius:
        style["border"] = {"radius": radius}
        css += f"border-radius:{radius};"
    if shadow:
        style["shadow"] = f"var:preset|shadow|{shadow}"
        css += f"box-shadow:var(--wp--preset--shadow--{shadow});"
    if style:
        a["style"] = style
    if layout:
        lay = {"type": layout}
        if orientation:
            lay["orientation"] = orientation
        if justify:
            lay["justifyContent"] = justify
        a["layout"] = lay
    style_attr = f' style="{css}"' if css else ""
    return (f'<!-- wp:column {_attrs(a)} -->\n<div class="{" ".join(classes)}"{style_attr}>\n'
            f'{inner}\n</div>\n<!-- /wp:column -->')


def heading(text, *, level=2, align=None, size=None, color=None, font=None, weight=None,
            line_height=None, letter=None, margin=None, class_name=None, extra_css=""):
    a, classes, css = {}, ["wp-block-heading"], ""
    if level != 2:
        a["level"] = level
    if align:
        a["textAlign"] = align
        classes.append(f"has-text-align-{align}")
    if class_name:
        a["className"] = class_name
        classes.append(class_name)
    if color:
        a["textColor"] = color
        classes += [f"has-{color}-color", "has-text-color"]
    if font:
        a["fontFamily"] = font
        classes.append(f"has-{font}-font-family")
    if size:
        a["fontSize"] = size
        classes.append(f"has-{size}-font-size")
    style, typo = {}, {}
    if weight:
        typo["fontWeight"] = weight
        css += f"font-weight:{weight};"
    if line_height:
        typo["lineHeight"] = line_height
        css += f"line-height:{line_height};"
    if letter:
        typo["letterSpacing"] = letter
        css += f"letter-spacing:{letter};"
    if typo:
        style["typography"] = typo
    if margin:
        style["spacing"] = {"margin": {k: sp(v) for k, v in margin.items()}}
        css += "".join(f"margin-{k}:{spc(v)};" for k, v in margin.items())
    if style:
        a["style"] = style
    css += extra_css
    style_attr = f' style="{css}"' if css else ""
    attrs = f" {_attrs(a)}" if a else ""
    return (f'<!-- wp:heading{attrs} -->\n<h{level} class="{" ".join(classes)}"{style_attr}>{text}</h{level}>\n'
            f'<!-- /wp:heading -->')


def para(text, *, align=None, size=None, color=None, custom_color=None, font=None, weight=None,
         line_height=None, letter=None, transform=None, class_name=None, margin=None, extra_css=""):
    a, classes, css = {}, [], ""
    if align:
        a["align"] = align
        classes.append(f"has-text-align-{align}")
    if class_name:
        a["className"] = class_name
        classes.append(class_name)
    if color:
        a["textColor"] = color
        classes += [f"has-{color}-color", "has-text-color"]
    if custom_color:
        classes += ["has-text-color"]
        css += f"color:{custom_color};"
    if font:
        a["fontFamily"] = font
        classes.append(f"has-{font}-font-family")
    if size:
        a["fontSize"] = size
        classes.append(f"has-{size}-font-size")
    style, typo = {}, {}
    if custom_color:
        style["color"] = {"text": custom_color}
    if weight:
        typo["fontWeight"] = weight
    if line_height:
        typo["lineHeight"] = line_height
    if letter:
        typo["letterSpacing"] = letter
    if transform:
        typo["textTransform"] = transform
    if typo:
        style["typography"] = typo
        for k, v in (("fontWeight", "font-weight"), ("lineHeight", "line-height"),
                     ("letterSpacing", "letter-spacing"), ("textTransform", "text-transform")):
            if k in typo:
                css += f"{v}:{typo[k]};"
    if margin:
        style.setdefault("spacing", {})["margin"] = {k: sp(v) for k, v in margin.items()}
        css += "".join(f"margin-{k}:{spc(v)};" for k, v in margin.items())
    if style:
        a["style"] = style
    css += extra_css
    style_attr = f' style="{css}"' if css else ""
    attrs = f" {_attrs(a)}" if a else ""
    classes.append("wp-block-paragraph")
    cls = " ".join(c for c in classes if c != "wp-block-paragraph")
    cls_attr = f' class="{cls}"' if cls else ""
    return f'<!-- wp:paragraph{attrs} -->\n<p{cls_attr}{style_attr}>{text}</p>\n<!-- /wp:paragraph -->'


def eyebrow(text, *, align="center", color="primary"):
    return para(text, align=align, size="small", color=color, font="heading",
                weight="600", letter="0.12em", transform="uppercase")


def buttons(items, *, justify=None, margin=None, gap=None):
    """items: list of dicts {text, url, style, bg, color, width}"""
    a = {}
    style = {}
    spacing = {}
    css = ""
    if margin:
        spacing["margin"] = {k: sp(v) for k, v in margin.items()}
        css += "".join(f"margin-{k}:{spc(v)};" for k, v in margin.items())
    if gap is not None:
        spacing["blockGap"] = sp(gap)
    if spacing:
        style["spacing"] = spacing
    if style:
        a["style"] = style
    if justify:
        a["layout"] = {"type": "flex", "justifyContent": justify}
    inner = []
    for it in items:
        ba, bclasses, acls = {}, ["wp-block-button"], ["wp-block-button__link"]
        if it.get("style"):
            # Accept "outline" as shorthand: the class core's save() emits is
            # always is-style-<name>, and a bare name silently renders as the
            # default fill.
            variation = it["style"]
            if not variation.startswith("is-style-"):
                variation = "is-style-" + variation
            ba["className"] = variation
            bclasses.append(variation)
        if it.get("width"):
            ba["width"] = it["width"]
            bclasses += ["has-custom-width", f'wp-block-button__width-{it["width"]}']
        if it.get("color"):
            ba["textColor"] = it["color"]
            acls += [f'has-{it["color"]}-color', "has-text-color"]
        if it.get("bg"):
            ba["backgroundColor"] = it["bg"]
            acls += [f'has-{it["bg"]}-background-color', "has-background"]
        acls.append("wp-element-button")
        battrs = f" {_attrs(ba)}" if ba else ""
        url = it.get("url", "#")
        inner.append(f'<!-- wp:button{battrs} -->\n<div class="{" ".join(bclasses)}">'
                     f'<a class="{" ".join(dict.fromkeys(acls))}" href="{url}">{it["text"]}</a></div>\n'
                     f'<!-- /wp:button -->')
    attrs = f" {_attrs(a)}" if a else ""
    style_attr = f' style="{css}"' if css else ""
    return (f'<!-- wp:buttons{attrs} -->\n<div class="wp-block-buttons"{style_attr}>\n'
            + "\n".join(inner) + '\n</div>\n<!-- /wp:buttons -->')


def image(src, alt, *, width=None, height=None, align=None, radius=None, shadow=None,
          class_name=None, size_slug="full", link=None, aspect=None, scale=None):
    a, fig_classes, img_style = {"sizeSlug": size_slug, "linkDestination": "none"}, ["wp-block-image"], ""
    if align:
        a["align"] = align
        fig_classes.append("align" + align)
    fig_classes.append(f"size-{size_slug}")
    if width:
        a["width"] = width
        img_style += f"width:{width};"
    if height:
        a["height"] = height
        img_style += f"height:{height};"
    if width or height:
        fig_classes.append("is-resized")
    if aspect:
        a["aspectRatio"] = aspect
        img_style += f"aspect-ratio:{aspect};"
    if scale:
        a["scale"] = scale
        img_style += f"object-fit:{scale};"
    style = {}
    if radius:
        style["border"] = {"radius": radius}
        img_style = f"border-radius:{radius};" + img_style
        fig_classes.append("has-custom-border")
    if shadow:
        style["shadow"] = f"var:preset|shadow|{shadow}"
        img_style += f"box-shadow:var(--wp--preset--shadow--{shadow});"
    if class_name:
        a["className"] = class_name
        fig_classes.append(class_name)
    if style:
        a["style"] = style
    st = f' style="{img_style}"' if img_style else ""
    return (f'<!-- wp:image {_attrs(a)} -->\n<figure class="{" ".join(dict.fromkeys(fig_classes))}">'
            f'<img src="{src}" alt="{alt}"{st}/></figure>\n<!-- /wp:image -->')


def icon_badge(icon, *, bg="primary", size=24, pad=14, radius="14px"):
    inner = image(uri(f"assets/images/icons/{icon}.svg"), "", width=f"{size}px", height=f"{size}px")
    return group(inner, bg=bg, radius=radius, layout="flex", wrap="nowrap",
                 extra_style=f"padding-top:{pad}px;padding-right:{pad}px;padding-bottom:{pad}px;padding-left:{pad}px;",
                 pad=None) if False else _icon_group(inner, bg, pad, radius)


def icon_badge_expr(php_expr, *, bg="primary", size=24, pad=14, radius="14px"):
    """Icon badge whose SVG file name comes from a PHP expression."""
    src = f"<?php echo esc_url( get_theme_file_uri( 'assets/images/icons/' . {php_expr} . '.svg' ) ); ?>"
    inner = image(src, "", width=f"{size}px", height=f"{size}px")
    return _icon_group(inner, bg, pad, radius)


def _icon_group(inner, bg, pad, radius):
    a = {"style": {"border": {"radius": radius},
                   "spacing": {"padding": {"top": f"{pad}px", "bottom": f"{pad}px",
                                           "left": f"{pad}px", "right": f"{pad}px"}}},
         "backgroundColor": bg, "layout": {"type": "flex", "flexWrap": "nowrap"}}
    css = (f"border-radius:{radius};padding-top:{pad}px;padding-right:{pad}px;"
           f"padding-bottom:{pad}px;padding-left:{pad}px")
    return (f'<!-- wp:group {_attrs(a)} -->\n<div class="wp-block-group has-{bg}-background-color has-background" '
            f'style="{css}">\n{inner}\n</div>\n<!-- /wp:group -->')


def lst(items, *, style="checklist", ordered=False, class_name=None):
    a = {}
    classes = ["wp-block-list"]
    cls = " ".join(x for x in [f"is-style-{style}" if style else None, class_name] if x)
    if cls:
        a["className"] = cls
        classes.append(cls)
    if ordered:
        a["ordered"] = True
    tag = "ol" if ordered else "ul"
    inner = "\n".join(f'<!-- wp:list-item -->\n<li>{i}</li>\n<!-- /wp:list-item -->' for i in items)
    attrs = f" {_attrs(a)}" if a else ""
    return (f'<!-- wp:list{attrs} -->\n<{tag} class="{" ".join(classes)}">\n{inner}\n</{tag}>\n<!-- /wp:list -->')


def separator(*, style="wide", color="border"):
    a = {"className": f"is-style-{style}"}
    classes = ["wp-block-separator", "has-alpha-channel-opacity"]
    if color:
        a["backgroundColor"] = color
        classes += ["has-text-color", "has-border-color", f"has-{color}-border-color",
                    f"has-{color}-background-color", "has-background"]
    classes.append(f"is-style-{style}")
    order = ["wp-block-separator"]
    if color:
        order += ["has-text-color", "has-border-color", f"has-{color}-border-color"]
    order += ["has-alpha-channel-opacity"]
    if color:
        order += [f"has-{color}-background-color", "has-background"]
    order.append(f"is-style-{style}")
    return f'<!-- wp:separator {_attrs(a)} -->\n<hr class="{" ".join(dict.fromkeys(order))}"/>\n<!-- /wp:separator -->'


def spacer(height="40px"):
    return (f'<!-- wp:spacer {{"height":"{height}"}} -->\n'
            f'<div style="height:{height}" aria-hidden="true" class="wp-block-spacer"></div>\n<!-- /wp:spacer -->')


def details(summary, inner, *, class_name=None):
    a = {"summary": summary}
    classes = ["wp-block-details"]
    if class_name:
        a["className"] = class_name
        classes.append(class_name)
    return (f'<!-- wp:details {_attrs(a)} -->\n<details class="{" ".join(classes)}"><summary>{summary}</summary>\n'
            f'{inner}\n</details>\n<!-- /wp:details -->')


def social(links, *, size="has-normal-icon-size", justify=None, color="muted", value="#6b7280",
           style_class="is-style-logos-only", gap=None):
    a = {"iconColor": color, "iconColorValue": value, "className": style_class}
    classes = ["wp-block-social-links"]
    if size != "has-normal-icon-size":
        a["size"] = size
        classes.append(size)
    classes += ["has-icon-color", style_class]
    if justify:
        a["layout"] = {"type": "flex", "justifyContent": justify}
    if gap is not None:
        a["style"] = {"spacing": {"blockGap": {"left": sp(gap)}}}
    inner = "\n".join(f'<!-- wp:social-link {{"url":"{u}","service":"{s}"}} /-->' for s, u in links)
    return (f'<!-- wp:social-links {_attrs(a)} -->\n<ul class="{" ".join(dict.fromkeys(classes))}">\n{inner}\n</ul>\n'
            f'<!-- /wp:social-links -->')


def pattern_ref(slug):
    return f'<!-- wp:pattern {{"slug":"{slug}"}} /-->'


# --------------------------------------------------------------------------- composites
def intro(*, eyebrow_text=None, title=None, lead=None, align="center", content="680px",
          gap="20", margin_bottom=None, title_size=None, eyebrow_color="primary",
          title_color=None, lead_color="muted"):
    parts = []
    if eyebrow_text:
        parts.append(eyebrow(eyebrow_text, align=align, color=eyebrow_color))
    if title:
        parts.append(heading(title, align=align, size=title_size, color=title_color))
    if lead:
        parts.append(para(lead, align=align, color=lead_color, size="large"))
    css = ""
    a = {"style": {"spacing": {"blockGap": sp(gap)}}, "layout": {"type": "constrained", "contentSize": content}}
    if margin_bottom:
        a["style"]["spacing"]["margin"] = {"bottom": sp(margin_bottom)}
        css = f"margin-bottom:{spc(margin_bottom)};"
    style_attr = f' style="{css}"' if css else ""
    return (f'<!-- wp:group {_attrs(a)} -->\n<div class="wp-block-group"{style_attr}>\n'
            + "\n".join(parts) + '\n</div>\n<!-- /wp:group -->')


def section(inner, *, pad=("70", "70"), gap="60", style_variation=None, bg=None, text=None,
            gradient=None, layout="constrained", content_size=None, wide_size=None, elements=None):
    padding = {"top": pad[0], "bottom": pad[1]}
    return group(inner, align="full", style_variation=style_variation, bg=bg, text=text,
                 gradient=gradient, pad=padding, gap=gap, layout=layout,
                 content_size=content_size, wide_size=wide_size, elements=elements)


HEADER_TPL = """<?php
/**
 * Title: {title}
 * Slug: unapp/{slug}
 * Categories: {cats}
 * Keywords: {keywords}
 * Viewport Width: {viewport}
 * Description: {desc}
 *
 * @package Unapp
 */

?>
"""


def write_pattern(slug, *, title, cats, keywords, desc, body, php_prelude="", viewport=1400,
                  inserter=True, block_types=None, post_types=None):
    head = HEADER_TPL.format(title=title, slug=slug, cats=cats, keywords=keywords,
                             desc=desc, viewport=viewport)
    if block_types:
        head = head.replace(" * Viewport Width:", f" * Block Types: {block_types}\n * Viewport Width:")
    if post_types:
        head = head.replace(" * Viewport Width:", f" * Post Types: {post_types}\n * Viewport Width:")
    if not inserter:
        head = head.replace(" * Categories:", " * Inserter: no\n * Categories:")
    if php_prelude:
        head = head.replace("\n?>\n", "\n" + php_prelude.rstrip() + "\n?>\n")
    path = os.path.join(THEME, "patterns", slug + ".php")
    open(path, "w").write(head + body + "\n")
    return path


# --------------------------------------------------------------- components
# One implementation per repeated shape. Patterns describe what a thing *is*;
# the measurements live here, so a change lands everywhere at once.

def card(inner, *, variation="is-style-card", pad=CARD_PAD, gap=CARD_GAP,
         radius=CARD_RADIUS, vertical=True):
    """A surface with the house padding, radius and internal rhythm."""
    return group(inner, style_variation=variation, radius=radius, gap=gap,
                 layout="flex" if vertical else "constrained",
                 orientation="vertical" if vertical else None,
                 pad={"top": pad, "bottom": pad, "left": pad, "right": pad})


def stack(inner, *, gap=STACK_GAP, justify=None):
    """A vertical run of blocks. Also what makes an icon badge shrink to content."""
    return group(inner, layout="flex", orientation="vertical", gap=gap, justify=justify)


def card_title(text, *, size=CARD_TITLE_SIZE):
    return heading(text, level=3, size=size)


def label(text, *, color="primary"):
    """The small uppercase line above a title inside a card or row."""
    return para(text, color=color, size="small", weight="600", letter="0.06em",
                transform="uppercase")


def icon_card(icon_expr, title, body, *, bg="primary", variation=None, expr=True):
    """Icon badge, h3 and a paragraph — the theme's workhorse feature cell."""
    badge = icon_badge_expr(icon_expr, bg=bg) if expr else icon_badge(icon_expr, bg=bg)
    inner = badge + "\n" + card_title(title) + "\n" + para(body, color="muted")
    return card(inner, variation=variation) if variation else stack(inner, gap=CARD_GAP)


def avatar(src, alt, *, size=AVATAR_GRID):
    return image(src, alt, width=size, height=size, radius="999px")


def grid(inner, *, cols, gap=ROW_GAP):
    """A wide grid that steps down through counts that divide the item count."""
    return group(inner, align="wide", layout="grid", col_count=cols, gap=gap,
                 class_name=f"unapp-grid-{cols}" if cols in (3, 4) else None)


def split(left, right, *, left_width="52%", right_width="48%", align="center",
          gap=SPLIT_GAP):
    """Two columns, vertically centred, at the house gutter."""
    # Both columns carry the stack gap: a column with no blockGap falls back to
    # the default paragraph margin, which is off the spacing scale.
    return columns([
        column(left, width=left_width, vertical_align=align, gap=STACK_GAP),
        column(right, width=right_width, vertical_align=align, gap=STACK_GAP),
    ], align="wide", gap=gap, vertical_align=align)


def faq_list(pairs):
    """Question/answer pairs as Details cards in the reading column."""
    items = [details(q, para(a, color="muted"), class_name="is-style-faq-card")
             for q, a in pairs]
    return group("\n".join(items), layout="constrained", content_size=READ_WIDTH, gap="30")


def section_std(inner, *, variation=None, bg=None, gradient=None, text=None,
                pad=SECTION_PAD, gap=SECTION_GAP, elements=None):
    """Every section: same padding, same intro-to-content gap."""
    return section(inner, pad=pad, gap=gap, style_variation=variation, bg=bg,
                   gradient=gradient, text=text, elements=elements)


GRADIENT_ELEMENTS = {
    "link": {"color": {"text": "var:preset|color|base"}},
    "heading": {"color": {"text": "var:preset|color|base"}},
}


def band(title, body, buttons_list, *, width="720px"):
    """A closing call-to-action on the palette gradient."""
    inner = (heading(title, align="center", size="xx-large", color="base") + "\n" +
             para(body, align="center", custom_color="rgba(255,255,255,0.86)", size="large") + "\n" +
             buttons(buttons_list, justify="center", margin={"top": "20"}))
    return section_std(
        group(inner, layout="constrained", content_size=width, gap=STACK_GAP),
        gradient="primary-to-accent", text="base", gap="0", elements=GRADIENT_ELEMENTS)
