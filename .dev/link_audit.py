"""Every link in every starter page arrives somewhere.

For each page a starter creates (its home and its supporting pages), with the
starter's footer added, this resolves every link the patterns write:

  #id                       an element with that id on the same page
  home_url( '/#id' )        an element with that id on the starter's home page
  mailto: tel: https://     a real destination outside the page
  shop, blog, privacy, home URLs built in PHP
  #  or anything else       dead

and flags duplicate ids on one page. The page starters (patterns/page-*.php)
are checked for bare '#' and same-page anchors only: they can be inserted on
any page, so a front-page anchor there cannot be verified. The header's CTA is
set at apply time by PHP and is left out, as are the two JavaScript switches
(dark mode, monthly/yearly) that are links only because a button block is.
Exits 1 on any dead link.
"""
import os, re, sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from rhythm_audit import THEME  # noqa: E402
from heading_audit import source  # noqa: E402

JS_SWITCHES = ("unapp-scheme-toggle", "unapp-period")
OK_PHP = ("wc_get_page_permalink( 'shop' )", "get_post_type_archive_link( 'post' )", "get_feed_link()",
          "get_privacy_policy_url()", "home_url( '/' )", "esc_url( 'mailto:", "esc_url( 'https://")


def ids(markup):
    return re.findall(r'"anchor":"([a-z0-9-]+)"', markup)


def links(markup):
    out = []
    for m in re.finditer(r'<a ((?:<\?php.*?\?>|[^>])*)>', markup):
        attrs = m.group(1)
        if any(c in attrs for c in JS_SWITCHES):
            continue
        h = re.search(r'href="((?:<\?php.*?\?>|[^"])*)"', attrs)
        if h:
            out.append(h.group(1))
    out += re.findall(r'<!-- wp:navigation-link \{.*?"url":"((?:<\?php.*?\?>|[^"])*)"', markup)
    return out


def classify(url):
    m = re.search(r"home_url\( '/#([a-z0-9-]+)' \)", url)
    if m:
        return "home", m.group(1)
    if url.startswith("#") and len(url) > 1:
        return "page", url[1:]
    if url.startswith(("mailto:", "tel:", "https://")) or any(p in url for p in OK_PHP):
        return "ok", url
    return "dead", url


def starters():
    src = open(os.path.join(THEME, "inc", "starter-sites.php")).read()
    body = src[src.index("function unapp_get_starter_sites()"):src.index("apply_filters( 'unapp_starter_sites'")]
    starts = list(re.finditer(r"^\t\t'([a-z]+)'\s*=> array\($", body, re.M))
    for i, m in enumerate(starts):
        block = body[m.end():starts[i + 1].start() if i + 1 < len(starts) else len(body)]
        home = re.search(r"'home'\s*=> 'unapp/([a-z0-9-]+)'", block).group(1)
        footer = re.search(r"'footer'\s*=> 'unapp/([a-z0-9-]+)'", block)
        pages = {"home": [home]}
        for page in re.finditer(r"^\t\t\t\t'([a-z]+)'\s*=> array\((.*?)^\t\t\t\t\),", block, re.M | re.S):
            pages[page.group(1)] = re.findall(r"'unapp/([a-z0-9-]+)'", page.group(2))
        yield m.group(1), pages, footer.group(1) if footer else "footer"


def main():
    dead = checked = 0
    for slug, pages, footer in starters():
        home_ids = set(ids("".join(source(r) for r in pages["home"])))
        for key, refs in pages.items():
            markup = "".join(source(r) for r in refs)
            page_ids = ids(markup)
            dupes = {i for i in page_ids if page_ids.count(i) > 1}
            problems = [f"duplicate id #{i}" for i in sorted(dupes)]
            for url in links(markup + source(footer)):
                checked += 1
                kind, target = classify(url)
                if kind == "home" and target not in home_ids:
                    problems.append(f"home has no #{target}")
                elif kind == "page" and target not in page_ids:
                    problems.append(f"page has no #{target}")
                elif kind == "dead":
                    problems.append(f"dead link {target[:80]}")
            if problems:
                dead += len(problems)
                print(f"{slug} / {key}: " + "; ".join(dict.fromkeys(problems)))
    for f in sorted(os.listdir(os.path.join(THEME, "patterns"))):
        if not f.startswith("page-"):
            continue
        markup = source(f[:-4])
        page_ids = ids(markup)
        for url in links(markup):
            checked += 1
            kind, target = classify(url)
            if (kind == "page" and target not in page_ids) or kind == "dead":
                dead += 1
                print(f"{f[:-4]}: {kind} {target[:80]}")
    print(f"links checked: {checked}, dead: {dead}")
    sys.exit(1 if dead else 0)


if __name__ == "__main__":
    main()
