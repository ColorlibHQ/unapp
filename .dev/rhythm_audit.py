"""Check the background rhythm of every composition the theme ships.

A page whose sections all sit on the same ground reads as one undifferentiated
column; two full-bleed bands in a row read as one band with a seam.
"""
import re, os, sys

THEME = os.path.join(os.path.dirname(os.path.abspath(__file__)), "..")


def style_of(slug):
    f = os.path.join(THEME, "patterns", slug + ".php")
    if not os.path.exists(f):
        return "?"
    s = open(f).read()
    head = s[:3000]
    if "wp:cover" in head:
        return "COVER"
    m = re.search(r'<!-- wp:group (\{"align":"full".*?)-->', s)
    a = m.group(1) if m else ""
    if '"gradient"' in a:
        return "BAND"
    if '"backgroundColor":"dark"' in a or "is-style-section-dark" in a:
        return "BAND"
    if '"backgroundColor":"primary"' in a or '"backgroundColor":"secondary"' in a:
        return "BAND"
    if "is-style-section-soft" in a:
        return "soft"
    return "plain"


def compositions():
    out = {}
    for f in sorted(os.listdir(os.path.join(THEME, "patterns"))):
        if not f.startswith(("demo-", "page-")):
            continue
        s = open(os.path.join(THEME, "patterns", f)).read()
        refs = re.findall(r'"slug":"unapp/([a-z0-9-]+)"', s)
        if refs:
            out[f[:-4]] = refs
    for name, refs in starter_pages().items():
        # A starter page that is one page starter reads as that page's sections.
        if len(refs) == 1 and refs[0] in out:
            refs = out[refs[0]]
        out[name] = refs
    return out


def starter_pages():
    """{"<starter> / <page key>": [section slugs]} from unapp_get_starter_sites().

    Keyed by starter and page key: several starters have an "About" or a
    "Contact" page, and keying by title silently dropped all but the last.
    """
    src = open(os.path.join(THEME, "inc", "starter-sites.php")).read()
    body = src[src.index("function unapp_get_starter_sites()"):src.index("apply_filters( 'unapp_starter_sites'")]
    out = {}
    starts = list(re.finditer(r"^\t\t'([a-z]+)'\s*=> array\($", body, re.M))
    for i, m in enumerate(starts):
        block = body[m.end():starts[i + 1].start() if i + 1 < len(starts) else len(body)]
        home = re.search(r"'home'\s*=> 'unapp/([a-z0-9-]+)'", block)
        if home:
            out[f"{m.group(1)} / home"] = [home.group(1)]
        for page in re.finditer(r"^\t\t\t\t'([a-z]+)'\s*=> array\((.*?)^\t\t\t\t\),", block, re.M | re.S):
            refs = re.findall(r"'unapp/([a-z0-9-]+)'", page.group(2))
            if refs:
                out[f"{m.group(1)} / {page.group(1)}"] = refs
    return out


def check(name, refs):
    seq = [(r, style_of(r)) for r in refs]
    styles = [st for _, st in seq]
    problems = []
    run = 1
    for i in range(1, len(styles)):
        if styles[i] == styles[i - 1] and styles[i] in ("plain", "soft"):
            run += 1
        else:
            run = 1
        # Three in a row is fine: style.css draws a hairline seam wherever two
        # sections share a ground. Four or more reads as one undifferentiated
        # column whatever the seam does.
        if run >= 4:
            problems.append(f"{run} × {styles[i]} in a row, ending at {seq[i][0]}")
        if styles[i] == "BAND" and styles[i - 1] == "BAND":
            problems.append(f"two bands adjacent: {seq[i-1][0]} + {seq[i][0]}")
    return seq, problems


if __name__ == "__main__":
    bad = 0
    for name, refs in compositions().items():
        seq, problems = check(name, refs)
        if problems:
            bad += 1
            print(f"\n{name}")
            print("   " + " ".join(st for _, st in seq))
            for p in dict.fromkeys(problems):
                print("   !! " + p)
    print(f"\ncompositions with a rhythm problem: {bad}")
    sys.exit(0)
