"""Restaurant and agency starter sections, on the house style."""
import os, sys; sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *
from batch13 import php_rows, loop

R = "unapp, unapp_restaurant"
A = "unapp, unapp_agency"

# ================================================================= RESTAURANT
cover_url = uri("assets/images/abstract/gathering.svg")
inner = (eyebrow(t("Kitchen and dining room", "Restaurant hero eyebrow"), align="center", color="base") + "\n" +
         heading(t("Ten tables, one menu, whatever the market had"), align="center", color="base",
                 size="xxx-large") + "\n" +
         para(t("Dinner Wednesday to Saturday · Lunch on Sunday · Bookings open six weeks ahead"),
              align="center", color="base", size="large") + "\n" +
         buttons([{"text": t("Book a table"), "bg": "base", "color": "primary"},
                  {"text": t("See this week's menu"), "style": "outline", "color": "base"}],
                 justify="center", margin={"top": "40"}))
body = f'''<!-- wp:cover {{"url":"{cover_url}","dimRatio":70,"overlayColor":"contrast","isUserOverlayColor":true,"minHeight":66,"minHeightUnit":"vh","align":"full","style":{{"spacing":{{"padding":{{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}},"blockGap":"var:preset|spacing|30"}}}},"layout":{{"type":"constrained","contentSize":"820px"}}}} -->
<div class="wp-block-cover alignfull" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80);min-height:66vh"><span aria-hidden="true" class="wp-block-cover__background has-contrast-background-color has-background-dim-70 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="{cover_url}" data-object-fit="cover"/><div class="wp-block-cover__inner-container">
{inner}
</div></div>
<!-- /wp:cover -->'''
write_pattern("restaurant-hero", title="Restaurant: hero", cats=R + ", banner, featured",
              keywords="restaurant, hero, booking, menu, dining",
              desc="A full-bleed cover with opening nights, a booking button and a link to the menu.",
              body=body)

# ---------------------------------------------------------------- the menu
COURSES = [
    ("To start", [
        ("Sourdough, cultured butter", "5"),
        ("Devilled egg, brown crab", "9"),
        ("Chicory, pear, blue cheese", "11"),
    ]),
    ("Mains", [
        ("Hake, mussels, sea vegetables", "24"),
        ("Short rib, onions, bone marrow", "27"),
        ("Celeriac, hazelnut, sage", "19"),
    ]),
    ("To finish", [
        ("Burnt custard, rhubarb", "8"),
        ("Chocolate, olive oil, salt", "9"),
        ("Three cheeses, oatcakes", "12"),
    ]),
]
prelude = "$unapp_menu = array(\n"
for course, dishes in COURSES:
    items = ", ".join(
        "array( 'dish' => _x( \"%s\", 'Menu dish', 'unapp' ), 'price' => '%s' )" % (d, p)
        for d, p in dishes)
    prelude += ("\tarray(\n"
                f"\t\t'course' => _x( '{course}', 'Menu course', 'unapp' ),\n"
                f"\t\t'dishes' => array( {items} ),\n"
                "\t),\n")
prelude += ");\n"

dish_row = columns([
    column(para(php("$unapp_dish['dish']")), width="80%", vertical_align="top"),
    column(para(php("'£' . $unapp_dish['price']"), color="muted", align="right"),
           width="20%", vertical_align="top"),
], gap="20", vertical_align="top", is_stacked=False)
course_block = stack(
    label(php("$unapp_course['course']")) + "\n" +
    ("<?php foreach ( $unapp_course['dishes'] as $unapp_dish ) : ?>\n" + dish_row + "\n<?php endforeach; ?>"),
    gap="20")
body = section_std(
    intro(eyebrow_text=t("This week", "Section eyebrow label"),
          title=t("The menu changes on Wednesday"),
          lead=t("Whatever the boats and the market gave us. Tell us about allergies when you book and we will work around them.")) + "\n" +
    grid(loop("unapp_menu", "unapp_course", course_block), cols=3) + "\n" +
    para(t("Five courses at the chef's discretion, £48. The whole table, please."),
         align="center", color="muted", size="small"),
    variation="is-style-section-soft")
write_pattern("restaurant-menu", title="Restaurant: menu", cats=R + ", unapp_features, text",
              keywords="restaurant, menu, food, dishes, prices, courses",
              desc="Three courses with dishes and prices, and a tasting-menu line underneath.",
              body=body, php_prelude=prelude)

# ---------------------------------------------------------------- opening hours
HOURS = [("Wed – Fri", "Dinner 18:00 – 22:00"), ("Saturday", "Lunch and dinner, 12:00 – 22:30"),
         ("Sunday", "Lunch 12:00 – 16:00"), ("Mon & Tue", "Closed — the kitchen rests")]
rows = []
for day, hrs in HOURS:
    rows.append(columns([
        column(para(t(day, "Opening day"), weight="600"), width="32%", vertical_align="center"),
        column(para(t(hrs, "Opening hours"), color="muted", align="right"), width="68%", vertical_align="center"),
    ], gap="30", vertical_align="center", is_stacked=False))
hours_card = card(card_title(t("When we are open")) + "\n" +
                  ("\n" + separator(style="wide", color="border") + "\n").join(rows))
body = section_std(
    split(
        eyebrow(t("Find us", "Section eyebrow label"), align="left") + "\n" +
        heading(t("A corner room on Wharf Street")) + "\n" +
        para(t("Eighteen covers downstairs and a counter for six who did not book. The corner table is the good one; ask for it and we will do our best."),
             color="muted", size="large") + "\n" +
        para(t("41 Wharf Street, Bristol BS1 4RW · 0117 555 0192"), color="muted") + "\n" +
        buttons([{"text": t("Book a table"), "url": "#book"},
                 {"text": t("Get directions"), "url": "#map", "style": "outline"}]),
        hours_card, align="top"),
    gap="0")
write_pattern("restaurant-hours", title="Restaurant: hours and address", cats=R + ", unapp_utility, contact",
              keywords="restaurant, hours, opening, address, booking, directions",
              desc="Where the restaurant is and when it serves, with the week in a card.",
              body=body)

# ---------------------------------------------------------------- the kitchen
body = section_std(
    split(
        image(uri("assets/images/abstract/desk.svg"), tattr("The kitchen pass"), radius=CARD_RADIUS),
        eyebrow(t("The kitchen", "Section eyebrow label"), align="left") + "\n" +
        heading(t("We buy small and cook it the same week")) + "\n" +
        para(t("Two boats in Brixham, a farm outside Chew Magna and a cheese room in Bath. If something is not good enough on the day it comes off the menu, which is why the menu is printed on Wednesday morning and not before."),
             color="muted", size="large") + "\n" +
        lst([t("Everything made here, including the bread"),
             t("A short wine list, mostly from small growers"),
             t("Vegetarian menu of the same length, always"),
             t("Service included; no discretionary anything")]),
        left_width="45%", right_width="55%"),
    gap="0")
write_pattern("restaurant-kitchen", title="Restaurant: the kitchen", cats=R + ", unapp_company, about",
              keywords="restaurant, about, kitchen, sourcing, suppliers",
              desc="Where the food comes from and how the kitchen works, beside a photograph.",
              body=body)

# ---------------------------------------------------------------- reviews
REVIEWS = [
    ("The hake was the best thing I ate all year, and I eat out for a living.", "Bristol Food Review"),
    ("Ten tables, no music, no theatre — just extremely good cooking and someone who remembers your name.", "The Wharf Guide"),
    ("Book six weeks out. It is worth the diary management.", "Somerset Life"),
]
prelude = php_rows("unapp_reviews", ("quote", "source"), REVIEWS, "Restaurant review")
review = card(
    para(php("'&#8220;' . $unapp_review['quote'] . '&#8221;'"), size="large", line_height="1.5") + "\n" +
    label(php("$unapp_review['source']")))
body = section_std(
    intro(eyebrow_text=t("Said about us", "Section eyebrow label"), title=t("Kind words")) + "\n" +
    grid(loop("unapp_reviews", "unapp_review", review), cols=3),
    variation="is-style-section-soft")
write_pattern("restaurant-reviews", title="Restaurant: reviews", cats=R + ", unapp_proof, testimonials",
              keywords="restaurant, reviews, press, quotes, praise",
              desc="Three short press quotes with their source.",
              body=body, php_prelude=prelude)

# ---------------------------------------------------------------- booking band
body = band(t("Bookings open six weeks ahead, on the first of the month"),
            t("Tables of two to six online. For anything larger, or the whole room, send us an email and we will sort it out."),
            [{"text": t("Book a table"), "url": "#book", "bg": "base", "color": "contrast"},
             {"text": t("Email the restaurant"), "url": "#email", "style": "outline", "color": "base"}])
write_pattern("restaurant-cta", title="Restaurant: booking band", cats=R + ", unapp_cta, call to action",
              keywords="restaurant, booking, reservation, cta",
              desc="A closing band explaining how bookings work, on the palette gradient.",
              body=body)

# ================================================================= AGENCY
body = section_std(
    split(
        eyebrow(t("Independent since 2011", "Agency hero eyebrow"), align="left") + "\n" +
        heading(t("We make the thing, not the deck about the thing"), size="xxx-large",
                line_height="1.05") + "\n" +
        para(t("A studio of nine in Manchester. Brand, product and the software to run both — for companies that have outgrown the website they built themselves."),
             color="muted", size="large") + "\n" +
        buttons([{"text": t("See the work"), "url": "#work"},
                 {"text": t("Start a project"), "url": "#contact", "style": "outline"}]),
        image(uri("assets/images/abstract/studio-2.svg"), tattr("Studio"), radius=CARD_RADIUS),
        left_width="55%", right_width="45%"),
    gap="0")
write_pattern("agency-hero", title="Agency: introduction", cats=A + ", banner, featured",
              keywords="agency, studio, hero, introduction, creative",
              desc="A studio introduction with two calls to action and an image.",
              body=body)

# ---------------------------------------------------------------- capabilities
CAPS = [
    ("compass", "Strategy", "Positioning, naming and the argument for why anyone should care. Usually two weeks."),
    ("layers", "Brand", "Identity, type, art direction and a system your team can run without us."),
    ("monitor", "Product", "Interface design and front-end build, from the first sketch to the day it ships."),
    ("trending-up", "Growth", "Landing pages, lifecycle email and the measurement to know which worked."),
]
body = section_std(
    intro(eyebrow_text=t("What we do", "Section eyebrow label"),
          title=t("Four things, done properly"),
          lead=t("Most projects use two or three of them. We will tell you which, and talk you out of the rest.")) + "\n" +
    grid(loop("unapp_agency_caps", "unapp_agency_cap",
              icon_card("$unapp_agency_cap['icon']",
                        php("$unapp_agency_cap['title']"),
                        php("$unapp_agency_cap['text']"),
                        variation="is-style-card")), cols=4))
write_pattern("agency-services", title="Agency: capabilities", cats=A + ", unapp_features",
              keywords="agency, services, capabilities, strategy, brand, product",
              desc="Four capability cards with icons, and a line admitting most projects need only two.",
              body=body,
              php_prelude=php_rows("unapp_agency_caps", ("icon", "title", "text"), CAPS, "Agency capability"))

# ---------------------------------------------------------------- selected clients
CLIENTS = [
    ("Northbank", "Brand and product · 2026"),
    ("Halden Rail", "Identity · 2025"),
    ("Pier & Post", "Website and CMS · 2025"),
    ("Verity Health", "Design system · 2024"),
    ("Brightwater", "Campaign · 2024"),
    ("Foldline", "Naming and brand · 2023"),
]
prelude = php_rows("unapp_agency_clients", ("name", "meta"), CLIENTS, "Agency client")
row = (columns([
    column(card_title(php("$unapp_agency_client['name']")), width="55%", vertical_align="center"),
    column(para(php("$unapp_agency_client['meta']"), color="muted", size="small", align="right"),
           width="45%", vertical_align="center"),
], gap="30", vertical_align="center", is_stacked=False) + "\n" + separator(style="wide", color="border"))
body = section_std(
    intro(eyebrow_text=t("Selected clients", "Section eyebrow label"),
          title=t("Who we have done it for")) + "\n" +
    group(loop("unapp_agency_clients", "unapp_agency_client", row),
          layout="constrained", content_size=READ_WIDTH, gap=CARD_GAP),
    variation="is-style-section-soft")
write_pattern("agency-clients", title="Agency: selected clients", cats=A + ", unapp_proof",
              keywords="agency, clients, work, portfolio, list",
              desc="A dated client list with the discipline beside each name.",
              body=body, php_prelude=prelude)

# ---------------------------------------------------------------- engagement
BANDS = [
    ("Sprint", "£8,000", "Two weeks, one question answered — a positioning test, a prototype, a pitch that has to land."),
    ("Project", "from £30,000", "Six to twelve weeks with a named team. Most brand and product work sits here."),
    ("Retained", "£9,000 a month", "Two days a week of the studio, for companies shipping continuously."),
]
prelude = php_rows("unapp_agency_bands", ("name", "price", "text"), BANDS, "Agency engagement")
band_card = card(
    card_title(php("$unapp_agency_band['name']")) + "\n" +
    para(php("$unapp_agency_band['price']"), size="xx-large", weight="700", line_height="1") + "\n" +
    para(php("$unapp_agency_band['text']"), color="muted", size="small"))
body = section_std(
    intro(eyebrow_text=t("How we work together", "Section eyebrow label"),
          title=t("Three ways to buy an agency"),
          lead=t("Published, because you should not have to sit through a call to find out whether we are affordable.")) + "\n" +
    grid(loop("unapp_agency_bands", "unapp_agency_band", band_card), cols=3))
write_pattern("agency-engagements", title="Agency: engagements", cats=A + ", unapp_pricing, pricing",
              keywords="agency, pricing, retainer, project, sprint, rates",
              desc="Three engagement shapes with real prices, from a two-week sprint to a monthly retainer.",
              body=body, php_prelude=prelude)

# ---------------------------------------------------------------- the studio
TEAM = [
    ("avatar-2", "Nadia Feld", "Founder, strategy"),
    ("avatar-5", "Callum Reece", "Design director"),
    ("avatar-9", "Yumi Adeyemi", "Product design"),
    ("avatar-7", "Marek Stolar", "Engineering"),
]
prelude = php_rows("unapp_agency_team", ("image", "name", "role"), TEAM, "Studio member")
person = stack(
    avatar(php("get_theme_file_uri( 'assets/images/avatars/' . $unapp_agency_person['image'] . '.svg' )"),
           php("$unapp_agency_person['name']")) + "\n" +
    card_title(php("$unapp_agency_person['name']")) + "\n" +
    label(php("$unapp_agency_person['role']")),
    gap=CARD_GAP)
body = section_std(
    intro(eyebrow_text=t("The studio", "Section eyebrow label"),
          title=t("Nine people, four of whom you will meet"),
          lead=t("No account layer. The people who design the work are the people in the room.")) + "\n" +
    grid(loop("unapp_agency_team", "unapp_agency_person", person), cols=4))
write_pattern("agency-team", title="Agency: the studio", cats=A + ", unapp_company, team",
              keywords="agency, team, studio, people",
              desc="Four of the studio with portraits and roles, and a note that there is no account layer.",
              body=body, php_prelude=prelude)

# ---------------------------------------------------------------- start a project
brief_card = card(
    para(t("What to include"), weight="600") + "\n" +
    lst([t("What you are making, and who for"),
         t("What has already been tried"),
         t("When it has to be live"),
         t("A budget range, even a wide one")]) + "\n" +
    para(t("We reply to everything within two working days, including the noes."),
         color="muted", size="small"))
body = section_std(
    split(
        eyebrow(t("Start a project", "Section eyebrow label"), align="left") + "\n" +
        heading(t("Tell us what you are trying to do")) + "\n" +
        para(t("We take on about fourteen projects a year and we are usually booked six weeks out. If the timing does not work we will say so in the first reply rather than the third meeting."),
             color="muted", size="large") + "\n" +
        para(t("studio@northgate.example · 0161 555 0134"), color="muted") + "\n" +
        brief_card,
        card(contact_form("Start a project", "studio@northgate.example")),
        align="top"),
    variation="is-style-section-soft", gap="0")
write_pattern("agency-contact", title="Agency: start a project", cats=A + ", unapp_utility, contact",
              keywords="agency, contact, brief, enquiry, start a project",
              desc="An enquiry section with a brief checklist and the contact form.",
              body=body)

print("batch 18 written: 6 restaurant + 6 agency patterns")
