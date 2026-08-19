#!/usr/bin/env python3
"""Emitters for Unapp block-pattern PHP files.

Every helper returns markup that matches what the corresponding core block's
save() produces, so patterns validate in the editor.
"""
import json, os, re

THEME = os.path.join(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
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


def sp(n):
    return f"var:preset|spacing|{n}"


def spc(n):
    return f"var(--wp--preset--spacing--{n})"


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
          wide_size=None, radius=None, shadow=None, extra_style="", tag="div",
          justify=None, orientation=None, wrap=None, vertical_align=None, elements=None):
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
            ba["className"] = it["style"]
            bclasses.append(it["style"])
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
