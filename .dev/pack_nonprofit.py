"""Build the Nonprofit pack.

A pack is one JSON file holding a starter definition and the block markup of
every pattern it needs, so it can be shipped by the library plugin without
touching the theme. The markup comes from the same generator the theme's own
patterns use, which is what keeps a pack on the house measurements.
"""
import json, os, re, sys
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *

OUT = os.path.join(os.path.dirname(os.path.abspath(__file__)),
                   "..", "plugin", "unapp-library", "packs", "nonprofit.json")

PATTERNS = []


def php_to_markup(body, prelude=""):
    """Run a generated pattern through PHP so the pack stores plain markup.

    Pack patterns are registered with register_block_pattern(), which takes
    finished block markup — there is no PHP evaluation at that point, so the
    loops and translation calls have to be resolved here at build time.
    """
    src = "<?php\n" + prelude + "\n?>\n" + body
    tmp = "/tmp/unapp-pack-part.php"
    open(tmp, "w").write(src)
    import subprocess
    php = subprocess.run(
        ["php", "-r",
         "function _x($s,$c,$d){return $s;} function __($s,$d=null){return $s;}"
         "function esc_html($s){return htmlspecialchars($s,ENT_QUOTES);}"
         "function esc_attr($s){return htmlspecialchars($s,ENT_QUOTES);}"
         "function esc_url($s){return $s;}"
         "function esc_html_e($s,$d=null){echo htmlspecialchars($s,ENT_QUOTES);}"
         "function esc_attr_e($s,$d=null){echo htmlspecialchars($s,ENT_QUOTES);}"
         "function esc_html_x($s,$c,$d=null){return htmlspecialchars($s,ENT_QUOTES);}"
         "function esc_attr_x($s,$c,$d=null){return htmlspecialchars($s,ENT_QUOTES);}"
         "function get_theme_file_uri($p=''){return '{{THEME}}/' . $p;}"
         "include '" + tmp + "';"],
        capture_output=True, text=True)
    if php.returncode != 0:
        raise RuntimeError(php.stderr[:400])
    return php.stdout.strip()


def add(slug, title, body, prelude="", categories=("unapp", "unapp_nonprofit"), inserter=True):
    PATTERNS.append({
        "slug": slug,
        "title": title,
        "categories": list(categories),
        "inserter": inserter,
        "content": php_to_markup(body, prelude),
    })


# ---------------------------------------------------------------- hero
body = section_std(
    split(
        eyebrow(t("Registered charity 1099231", "Nonprofit hero eyebrow"), align="left") + "\n" +
        heading(t("Nobody in this county should have to choose between heating and eating"),
                size="xxx-large", line_height="1.05") + "\n" +
        para(t("We run four food banks, a debt advice line and a warm room every winter. Last year eleven thousand people used one of them."),
             color="muted", size="large") + "\n" +
        buttons([{"text": t("Donate"), "url": "#donate"},
                 {"text": t("Volunteer"), "url": "#volunteer", "style": "outline"}]),
        image(uri("assets/images/abstract/gathering.svg"), tattr("Volunteers"), radius=CARD_RADIUS),
        left_width="55%", right_width="45%"),
    gap="0")
add("nonprofit-hero", "Charity: introduction", body)

# ---------------------------------------------------------------- impact
STATS = [
    ("11,240", "people helped", "Across four food banks and the advice line, in the year to March."),
    ("86p", "in every pound", "Goes directly to the services. The rest is rent, insurance and audit."),
    ("340", "volunteers", "Who between them gave about forty thousand hours."),
    ("19", "years", "Since a church hall, three tables and a rota written on the back of an envelope."),
]
stat = stack(
    para(php("$unapp_np_stat['number']"), size="xxx-large", weight="700", line_height="1") + "\n" +
    para(php("$unapp_np_stat['label']"), weight="600") + "\n" +
    para(php("$unapp_np_stat['note']"), color="muted", size="small"),
    gap="20")
body = section_std(
    intro(eyebrow_text=t("Last year", "Section eyebrow label"),
          title=t("Where the money went"),
          lead=t("The full accounts are on the Charity Commission register, and we would rather you read them than take our word for it.")) + "\n" +
    grid(loop("unapp_np_stats", "unapp_np_stat", stat), cols=4),
    variation="is-style-section-soft")
add("nonprofit-impact", "Charity: impact",
    body, php_rows("unapp_np_stats", ("number", "label", "note"), STATS, "Charity statistic"))

# ---------------------------------------------------------------- programmes
PROGRAMMES = [
    ("package", "Food banks", "Four sites, open six days between them. A referral helps but nobody is turned away."),
    ("life-buoy", "Debt advice", "Free, confidential and FCA-regulated. Most people call about council tax first."),
    ("heart", "The warm room", "November to March, every weekday, with lunch and somebody to talk to."),
    ("users", "Holiday clubs", "Meals and something to do for children on free school meals, every school holiday."),
]
body = section_std(
    intro(eyebrow_text=t("What we run", "Section eyebrow label"),
          title=t("Four things, all year")) + "\n" +
    grid(loop("unapp_np_programmes", "unapp_np_programme",
              icon_card("$unapp_np_programme['icon']",
                        php("$unapp_np_programme['title']"),
                        php("$unapp_np_programme['text']"),
                        variation="is-style-card")), cols=4))
add("nonprofit-programmes", "Charity: programmes",
    body, php_rows("unapp_np_programmes", ("icon", "title", "text"), PROGRAMMES, "Charity programme"))

# ---------------------------------------------------------------- ways to give
WAYS = [
    ("£10 a month", "£10", "Buys a family's worth of tinned goods, every month, without you thinking about it."),
    ("A one-off gift", "Any", "Goes straight into whichever service is emptiest that week."),
    ("Give your time", "Free", "Two hours a week on a rota. The training takes one morning."),
]
prelude = php_rows("unapp_np_ways", ("name", "amount", "text"), WAYS, "Way to give")
way = card(
    label(php("$unapp_np_way['name']")) + "\n" +
    para(php("$unapp_np_way['amount']"), size="xxx-large", weight="700", line_height="1") + "\n" +
    para(php("$unapp_np_way['text']"), color="muted", size="small") + "\n" +
    buttons([{"text": t("Choose this"), "url": "#donate", "width": 100}]))
body = section_std(
    intro(eyebrow_text=t("Ways to help", "Section eyebrow label"),
          title=t("Money or time, both count"),
          lead=t("Gift Aid adds 25p to every pound if you pay UK tax, and costs you nothing.")) + "\n" +
    grid(loop("unapp_np_ways", "unapp_np_way", way), cols=3))
add("nonprofit-give", "Charity: ways to give", body, prelude)

# ---------------------------------------------------------------- trustees
TEAM = [
    ("avatar-6", "Bea Ashford", "Chair of trustees", "Retired district nurse. Founded the first food bank in 2007."),
    ("avatar-3", "Femi Adeyinka", "Treasurer", "Chartered accountant. Publishes the accounts before he has to."),
    ("avatar-9", "Sam Lockhart", "Head of services", "The only full-time member of staff, and the one who does the rota."),
]
person = stack(
    avatar(php("get_theme_file_uri( 'assets/images/avatars/' . $unapp_np_person['image'] . '.svg' )"),
           php("$unapp_np_person['name']")) + "\n" +
    card_title(php("$unapp_np_person['name']")) + "\n" +
    label(php("$unapp_np_person['role']")) + "\n" +
    para(php("$unapp_np_person['note']"), color="muted", size="small"),
    gap=CARD_GAP)
body = section_std(
    intro(eyebrow_text=t("Who runs it", "Section eyebrow label"),
          title=t("Three of us, and three hundred of you"),
          lead=t("The trustees are unpaid. There is one salary in the whole organisation.")) + "\n" +
    grid(loop("unapp_np_team", "unapp_np_person", person), cols=3),
    variation="is-style-section-soft")
add("nonprofit-trustees", "Charity: trustees",
    body, php_rows("unapp_np_team", ("image", "name", "role", "note"), TEAM, "Trustee"))

# ---------------------------------------------------------------- band
body = band(t("Three pounds buys a hot meal and somewhere warm to eat it"),
            t("Every donation is spent in this county, usually within the fortnight."),
            [{"text": t("Donate now"), "url": "#donate", "bg": "base", "color": "contrast"},
             {"text": t("Other ways to help"), "url": "#volunteer", "style": "outline", "color": "base"}])
add("nonprofit-cta", "Charity: donation band", body)

# ---------------------------------------------------------------- the pack
pack = {
    "slug": "nonprofit",
    "title": "Charity",
    "summary": "A charity or community organisation: what it runs, where the money goes, ways to give and who the trustees are.",
    "version": "1.0.0",
    "requires": {"theme": "unapp", "version": "2.5.0"},
    "patterns": PATTERNS,
    "starter": {
        "title": "Charity",
        "summary": "A charity: programmes, published impact figures, ways to give and the trustees.",
        "cta": "Donate",
        "style": "emerald",
        "colors": "colors-2-emerald",
        "type": "typography-1-product",
        "swatches": ["#12805a", "#f7b32b"],
        "home": "unapp-library/nonprofit-home",
        "footer": "",
        "pages": {
            "give": {
                "title": "Ways to give",
                "patterns": ["unapp-library/nonprofit-give", "unapp-library/nonprofit-impact",
                             "unapp-library/nonprofit-cta"],
            },
            "about": {
                "title": "Who we are",
                "patterns": ["unapp-library/nonprofit-trustees", "unapp-library/nonprofit-programmes",
                             "unapp-library/nonprofit-impact"],
            },
            "contact": {
                "title": "Contact",
                "patterns": ["unapp/contact-split", "unapp-library/nonprofit-cta"],
            },
        },
    },
}

# The home page is itself a pattern: a list of references to the others.
home = "\n".join(
    '<!-- wp:pattern {"slug":"unapp-library/%s"} /-->' % p["slug"]
    for p in PATTERNS if p["slug"] != "nonprofit-cta")
home += '\n<!-- wp:pattern {"slug":"unapp-library/nonprofit-cta"} /-->'
PATTERNS.append({
    "slug": "nonprofit-home",
    "title": "Charity: home page",
    "categories": ["unapp", "unapp_page"],
    "inserter": True,
    "content": home,
})

os.makedirs(os.path.dirname(OUT), exist_ok=True)
json.dump(pack, open(OUT, "w"), indent="\t", ensure_ascii=False)
open(OUT, "a").write("\n")
size = os.path.getsize(OUT)
print(f"nonprofit pack written: {len(PATTERNS)} patterns, {size // 1024} KB")
