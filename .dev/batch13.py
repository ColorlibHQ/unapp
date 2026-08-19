"""Church starter: the sections that were borrowing SaaS copy, written in a church voice."""
import os, sys; sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *

C = "unapp, unapp_church"

# ---------------------------------------------------------------- what to expect
EXPECT = [
    ("clock", "Ninety minutes", "Songs, a talk and coffee afterwards. You are free to leave whenever you like."),
    ("map-pin", "Parking is free", "Behind the building, entrance on Mill Lane. Ten spaces are reserved for visitors."),
    ("users", "Children are welcome", "Creche for under-3s, groups up to Year 6, and nobody minds noise in the service."),
    ("heart", "Wear whatever you own", "You will see suits and you will see trainers. Both are fine."),
]
prelude = "$unapp_church_expect = array(\n"
for icon, title, body in EXPECT:
    prelude += ("\tarray(\n"
                f"\t\t'icon'  => '{icon}',\n"
                f"\t\t'title' => _x( '{title}', 'Church first-visit card title', 'unapp' ),\n"
                f"\t\t'text'  => _x( \"{body}\", 'Church first-visit card text', 'unapp' ),\n"
                "\t),\n")
prelude += ");\n"

card = group(
    icon_badge_expr("$unapp_church_item['icon']", bg="primary", pad=12) + "\n" +
    heading(php("$unapp_church_item['title']"), level=3, size="medium") + "\n" +
    para(php("$unapp_church_item['text']"), color="muted"),
    layout="flex", orientation="vertical", gap="16")
loop = ("<?php foreach ( $unapp_church_expect as $unapp_church_item ) : ?>\n"
        + card + "\n<?php endforeach; ?>")
body = section(
    intro(eyebrow_text=t("Your first Sunday", "Section eyebrow label"),
          title=t("What actually happens"),
          lead=t("Nobody will ask you to stand up, introduce yourself or give anything.")) + "\n" +
    group(loop, align="wide", layout="grid", col_count=4, min_col=None, gap="40",
          class_name="unapp-grid-4"),
    pad=("60", "70"), gap="50")
write_pattern("church-visit", title="Church: what to expect", cats=C + ", unapp_features",
              keywords="church, visit, first time, expect, welcome, newcomer",
              desc="Four answers a first-time visitor actually wants: how long it lasts, where to park, what happens to the children and what to wear.",
              body=body, php_prelude=prelude)

# ---------------------------------------------------------------- staff
STAFF = [
    ("avatar-1", "Tom Iredale", "Lead pastor", "Here since 2014. Preaches most Sundays and answers every email eventually."),
    ("avatar-6", "Grace Okonjo", "Families and children", "Runs the Sunday groups and the Tuesday toddler morning."),
    ("avatar-3", "Danny Ferreira", "Music", "Leads the band and will teach anyone who asks to play."),
    ("avatar-8", "Ruth Nakamura", "Church administrator", "Bookings, the building, and the person who knows where everything is."),
]
prelude = "$unapp_church_staff = array(\n"
for img_slug, name, role, note in STAFF:
    prelude += ("\tarray(\n"
                f"\t\t'image' => '{img_slug}',\n"
                f"\t\t'name'  => _x( '{name}', 'Church staff name', 'unapp' ),\n"
                f"\t\t'role'  => _x( '{role}', 'Church staff role', 'unapp' ),\n"
                f"\t\t'note'  => _x( '{note}', 'Church staff note', 'unapp' ),\n"
                "\t),\n")
prelude += ");\n"

card = group(
    image(php("get_theme_file_uri( 'assets/images/avatars/' . $unapp_church_person['image'] . '.svg' )"),
          php("$unapp_church_person['name']"), width="120px", height="120px", radius="999px") + "\n" +
    heading(php("$unapp_church_person['name']"), level=3, size="medium") + "\n" +
    para(php("$unapp_church_person['role']"), color="primary", size="small",
         weight="600", letter="0.04em", transform="uppercase") + "\n" +
    para(php("$unapp_church_person['note']"), color="muted", size="small"),
    layout="flex", orientation="vertical", gap="12")
loop = ("<?php foreach ( $unapp_church_staff as $unapp_church_person ) : ?>\n"
        + card + "\n<?php endforeach; ?>")
body = section(
    intro(eyebrow_text=t("Who you will meet", "Section eyebrow label"),
          title=t("The people here on a Sunday"),
          lead=t("Four of us are paid; most of what happens here is done by people who are not.")) + "\n" +
    group(loop, align="wide", layout="grid", col_count=4, gap="40", class_name="unapp-grid-4"),
    pad=("60", "70"), gap="50")
write_pattern("church-staff", title="Church: staff", cats=C + ", unapp_company, team",
              keywords="church, staff, team, pastor, leaders, people",
              desc="The four people a visitor is likely to meet, with what each of them actually does.",
              body=body, php_prelude=prelude)

# ---------------------------------------------------------------- what we believe
BELIEFS = [
    ("book-open", "The Bible", "We read it together, in order, and we do not skip the parts that are hard to hear."),
    ("heart", "Grace", "Nobody here has earned their place. That is rather the point of the whole thing."),
    ("users", "One another", "Christianity is a team sport. Sunday is where it starts, not where it stops."),
]
prelude = "$unapp_church_beliefs = array(\n"
for icon, title, text in BELIEFS:
    prelude += ("\tarray(\n"
                f"\t\t'icon'  => '{icon}',\n"
                f"\t\t'title' => _x( '{title}', 'Church belief title', 'unapp' ),\n"
                f"\t\t'text'  => _x( '{text}', 'Church belief text', 'unapp' ),\n"
                "\t),\n")
prelude += ");\n"

card = group(
    icon_badge_expr("$unapp_church_belief['icon']", bg="secondary", pad=12) + "\n" +
    heading(php("$unapp_church_belief['title']"), level=3, size="medium") + "\n" +
    para(php("$unapp_church_belief['text']"), color="muted"),
    style_variation="is-style-card", radius="18px", layout="flex", orientation="vertical", gap="16",
    pad={"top": "40", "bottom": "40", "left": "40", "right": "40"})
loop = ("<?php foreach ( $unapp_church_beliefs as $unapp_church_belief ) : ?>\n"
        + card + "\n<?php endforeach; ?>")
body = section(
    intro(eyebrow_text=t("What we believe", "Section eyebrow label"),
          title=t("Three things, honestly held"),
          lead=t("There is a longer statement of faith and we will happily send it to you. This is the short version.")) + "\n" +
    group(loop, align="wide", layout="grid", col_count=3, gap="40", class_name="unapp-grid-3"),
    style_variation="is-style-section-soft", pad=("60", "70"), gap="50")
write_pattern("church-beliefs", title="Church: what we believe", cats=C + ", unapp_features",
              keywords="church, beliefs, faith, values, about",
              desc="Three belief cards in plain language, for an About page that does not read like a doctrinal statement.",
              body=body, php_prelude=prelude)

# ---------------------------------------------------------------- our story
body = section(
    columns([
        column(image(uri("assets/images/abstract/sanctuary.svg"), tattr("The church building"),
                     radius="20px"), width="46%", vertical_align="center"),
        column(
            eyebrow(t("Our story", "Section eyebrow label"), align="left") + "\n" +
            heading(t("A hundred and forty years of Tuesdays")) + "\n" +
            para(t("The building went up in 1886 for a congregation of mill workers. The mill closed in 1974; the church did not. We have been a school hall, a night shelter and briefly a bicycle repair shop, and on Sundays we have kept doing the same thing the whole time."),
                 color="muted", size="large") + "\n" +
            para(t("There are around two hundred of us now, from about thirty streets, and we would be glad to make it two hundred and one."),
                 color="muted") + "\n" +
            buttons([{"text": t("Plan your visit"), "url": "#visit"},
                     {"text": t("Meet the staff"), "url": "#staff", "style": "outline"}], margin={"top": "20"}),
            width="54%", vertical_align="center", gap="20"),
    ], align="wide", gap="60", vertical_align="center"),
    pad=("70", "70"), gap="0")
write_pattern("church-story", title="Church: our story", cats=C + ", unapp_company, about",
              keywords="church, story, history, about, building",
              desc="A short history of the church beside a photograph — the opening of an About page.",
              body=body)

# ---------------------------------------------------------------- upcoming events
EVENTS = [
    ("Sat 14 Sep", "Community lunch", "Everyone eats, nobody pays. Twelve o'clock in the hall."),
    ("Thu 26 Sep", "Job club", "CVs, applications and interview practice with people who hire for a living."),
    ("Sun 6 Oct", "Harvest service", "Bring tinned food if you can; the whole lot goes to the foodbank on Mill Lane."),
    ("Sun 22 Dec", "Carols by candlelight", "The one service a year that fills the balcony. Come early."),
]
prelude = "$unapp_church_events = array(\n"
for when, title, text in EVENTS:
    prelude += ("\tarray(\n"
                f"\t\t'when'  => _x( '{when}', 'Church event date', 'unapp' ),\n"
                f"\t\t'title' => _x( '{title}', 'Church event title', 'unapp' ),\n"
                f"\t\t'text'  => _x( \"{text}\", 'Church event description', 'unapp' ),\n"
                "\t),\n")
prelude += ");\n"

row = (columns([
    column(para(php("$unapp_church_event['when']"), color="primary", weight="600"),
           width="24%", vertical_align="top"),
    column(heading(php("$unapp_church_event['title']"), level=3, size="medium") + "\n" +
           para(php("$unapp_church_event['text']"), color="muted", size="small"),
           width="76%", vertical_align="top", gap="6"),
], gap="30", vertical_align="top", is_stacked=False) + "\n" + separator(style="wide", color="border"))
loop = ("<?php foreach ( $unapp_church_events as $unapp_church_event ) : ?>\n"
        + row + "\n<?php endforeach; ?>")
body = section(
    intro(eyebrow_text=t("Diary", "Section eyebrow label"), title=t("Coming up")) + "\n" +
    group(loop, layout="constrained", content_size="800px", gap="30"),
    pad=("60", "70"), gap="50")
write_pattern("church-events", title="Church: upcoming events", cats=C + ", unapp_content",
              keywords="church, events, diary, calendar, upcoming",
              desc="A dated list of what is on: community lunch, job club, harvest and carols.",
              body=body, php_prelude=prelude)

# ---------------------------------------------------------------- first-visit FAQ
FAQ = [
    ("I am not religious. Is that a problem?",
     "Not in the least. A good number of people here would say the same thing, some of them for years. You are welcome to come, listen and make your own mind up in your own time."),
    ("Will I be asked for money?",
     "No. The offering is taken during the service and visitors are asked, genuinely, not to give to it. Giving is for people who have decided this is their church."),
    ("Is the building accessible?",
     "Step-free from the Mill Lane entrance, a hearing loop throughout, and an accessible toilet by the hall. Large-print orders of service are on the table as you come in."),
    ("What happens to my children?",
     "They stay with you for the first twenty minutes, then go out to groups by school year. Every leader is DBS-checked. Under-3s can go straight to the creche."),
]
items = []
for q, a in FAQ:
    items.append(details(t(q, "FAQ question"), para(t(a, "FAQ answer"), color="muted"),
                         class_name="is-style-faq-card"))
body = section(
    intro(eyebrow_text=t("Questions", "Section eyebrow label"),
          title=t("The ones people ask us")) + "\n" +
    group("\n".join(items), layout="constrained", content_size="760px", gap="16"),
    pad=("60", "70"), gap="50")
write_pattern("church-faq", title="Church: first-visit questions", cats=C + ", unapp_utility, faq",
              keywords="church, faq, questions, visit, accessibility, children",
              desc="The four questions a newcomer is too polite to ask, answered without church jargon.",
              body=body)

# ---------------------------------------------------------------- contact and directions
body = section(
    columns([
        column(
            eyebrow(t("Find us", "Section eyebrow label"), align="left") + "\n" +
            heading(t("Mill Lane, and the door is open")) + "\n" +
            para(t("Riverside Church, 12 Mill Lane, Chesterfield S40 1RT. The 43 and 44 buses stop at the end of the road; the car park is behind the building."),
                 color="muted", size="large") + "\n" +
            buttons([{"text": t("Get directions"), "url": "#map"}], margin={"top": "10"}),
            width="52%", vertical_align="top", gap="20"),
        column(
            group(
                para(t("Office hours"), weight="600") + "\n" +
                para(t("Tuesday to Friday, 9:30–14:30. Ruth is usually in; if the door is locked, ring the bell."),
                     color="muted", size="small") + "\n" +
                separator(style="wide", color="border") + "\n" +
                para(t("Telephone"), weight="600") + "\n" +
                para(t("01246 555 0114"), color="muted", size="small") + "\n" +
                separator(style="wide", color="border") + "\n" +
                para(t("Email"), weight="600") + "\n" +
                para(t("hello@riverside.example"), color="muted", size="small"),
                style_variation="is-style-card", radius="18px", gap="12",
                pad={"top": "40", "bottom": "40", "left": "40", "right": "40"}),
            width="48%", vertical_align="top"),
    ], align="wide", gap="60", vertical_align="top"),
    style_variation="is-style-section-soft", pad=("60", "70"), gap="0")
write_pattern("church-contact", title="Church: contact and directions", cats=C + ", unapp_utility, contact",
              keywords="church, contact, directions, address, office hours, parking",
              desc="Address, transport and office hours — the church equivalent of a contact page, without a form.",
              body=body)

# ---------------------------------------------------------------- closing band
body = section(
    group(
        heading(t("There is a service this Sunday at 9:30 and 11:15"), align="center",
                size="xx-large", color="base") + "\n" +
        para(t("Come on your own, come late, come and sit at the back. All of that is completely normal here."),
             align="center", custom_color="rgba(255,255,255,0.86)", size="large") + "\n" +
        buttons([{"text": t("Plan your visit"), "url": "#visit", "bg": "base", "color": "contrast"},
                 {"text": t("Watch a service online"), "url": "#watch", "style": "outline", "color": "base"}],
                justify="center", margin={"top": "10"}),
        layout="constrained", content_size="720px", gap="24"),
    gradient="primary-to-accent", text="base", pad=("70", "70"), gap="0")
write_pattern("church-cta", title="Church: closing invitation", cats=C + ", unapp_cta, call to action",
              keywords="church, cta, invitation, visit, sunday",
              desc="A warm closing band inviting a visit, on the palette gradient.",
              body=body)

print("batch 13 written: 8 church patterns")
