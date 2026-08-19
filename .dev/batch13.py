"""Church starter sections, built from the shared components in pgen.

Every measurement here comes from the house style (SECTION_PAD, CARD_*, the
spacing presets). Nothing sets a raw number of its own.
"""
import os, sys; sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *

C = "unapp, unapp_church"


def php_rows(name, fields, rows, ctx):
    """Build a translatable PHP array for a repeated row."""
    out = f"${name} = array(\n"
    for row in rows:
        out += "\tarray(\n"
        for key, value in zip(fields, row):
            if key in ("icon", "image"):
                out += f"\t\t'{key}' => '{value}',\n"
            else:
                out += f"\t\t'{key}' => _x( \"{value}\", '{ctx}', 'unapp' ),\n"
        out += "\t),\n"
    return out + ");\n"


def loop(var, item, body):
    return f"<?php foreach ( ${var} as ${item} ) : ?>\n{body}\n<?php endforeach; ?>"


# ---------------------------------------------------------------- what to expect
EXPECT = [
    ("clock", "Ninety minutes", "Songs, a talk and coffee afterwards. You are free to leave whenever you like."),
    ("map-pin", "Parking is free", "Behind the building, entrance on Mill Lane. Ten spaces are kept for visitors."),
    ("users", "Children are welcome", "Creche for under-3s, groups up to Year 6, and nobody minds noise in the service."),
    ("heart", "Wear whatever you own", "You will see suits and you will see trainers. Both are entirely normal."),
]
body = section_std(
    intro(eyebrow_text=t("Your first Sunday", "Section eyebrow label"),
          title=t("What actually happens"),
          lead=t("Nobody will ask you to stand up, introduce yourself or give anything.")) + "\n" +
    grid(loop("unapp_church_expect", "unapp_church_item",
              icon_card("$unapp_church_item['icon']",
                        php("$unapp_church_item['title']"),
                        php("$unapp_church_item['text']"))), cols=4))
write_pattern("church-visit", title="Church: what to expect", cats=C + ", unapp_features",
              keywords="church, visit, first time, expect, welcome, newcomer",
              desc="Four answers a first-time visitor actually wants: how long it lasts, where to park, what happens to the children and what to wear.",
              body=body,
              php_prelude=php_rows("unapp_church_expect", ("icon", "title", "text"), EXPECT,
                                   "Church first-visit card"))

# ---------------------------------------------------------------- staff
STAFF = [
    ("avatar-1", "Tom Iredale", "Lead pastor", "Here since 2014. Preaches most Sundays and answers every email eventually."),
    ("avatar-6", "Grace Okonjo", "Families and children", "Runs the Sunday groups and the Tuesday toddler morning."),
    ("avatar-3", "Danny Ferreira", "Music", "Leads the band and will teach anyone who asks to play."),
    ("avatar-8", "Ruth Nakamura", "Church administrator", "Bookings, the building, and the person who knows where everything is."),
]
person = stack(
    avatar(php("get_theme_file_uri( 'assets/images/avatars/' . $unapp_church_person['image'] . '.svg' )"),
           php("$unapp_church_person['name']")) + "\n" +
    card_title(php("$unapp_church_person['name']")) + "\n" +
    label(php("$unapp_church_person['role']")) + "\n" +
    para(php("$unapp_church_person['note']"), color="muted", size="small"),
    gap=CARD_GAP)
body = section_std(
    intro(eyebrow_text=t("Who you will meet", "Section eyebrow label"),
          title=t("The people here on a Sunday"),
          lead=t("Four of us are paid; most of what happens here is done by people who are not.")) + "\n" +
    grid(loop("unapp_church_staff", "unapp_church_person", person), cols=4))
write_pattern("church-staff", title="Church: staff", cats=C + ", unapp_company, team",
              keywords="church, staff, team, pastor, leaders, people",
              desc="The four people a visitor is likely to meet, with what each of them actually does.",
              body=body,
              php_prelude=php_rows("unapp_church_staff", ("image", "name", "role", "note"), STAFF,
                                   "Church staff"))

# ---------------------------------------------------------------- what we believe
BELIEFS = [
    ("book-open", "The Bible", "We read it together, in order, and we do not skip the parts that are hard to hear."),
    ("heart", "Grace", "Nobody here has earned their place. That is rather the point of the whole thing."),
    ("users", "One another", "Christianity is a team sport. Sunday is where it starts, not where it stops."),
]
body = section_std(
    intro(eyebrow_text=t("What we believe", "Section eyebrow label"),
          title=t("Three things, honestly held"),
          lead=t("There is a longer statement of faith and we will happily send it to you. This is the short version.")) + "\n" +
    grid(loop("unapp_church_beliefs", "unapp_church_belief",
              icon_card("$unapp_church_belief['icon']",
                        php("$unapp_church_belief['title']"),
                        php("$unapp_church_belief['text']"),
                        bg="secondary", variation="is-style-card")), cols=3),
    variation="is-style-section-soft")
write_pattern("church-beliefs", title="Church: what we believe", cats=C + ", unapp_features",
              keywords="church, beliefs, faith, values, about",
              desc="Three belief cards in plain language, for an About page that does not read like a doctrinal statement.",
              body=body,
              php_prelude=php_rows("unapp_church_beliefs", ("icon", "title", "text"), BELIEFS,
                                   "Church belief"))

# ---------------------------------------------------------------- our story
body = section_std(
    split(
        eyebrow(t("Our story", "Section eyebrow label"), align="left") + "\n" +
        heading(t("A hundred and forty years of Tuesdays")) + "\n" +
        para(t("The building went up in 1886 for a congregation of mill workers. The mill closed in 1974; the church did not. We have been a school hall, a night shelter and briefly a bicycle repair shop, and on Sundays we have kept doing the same thing the whole time."),
             color="muted", size="large") + "\n" +
        para(t("There are around two hundred of us now, from about thirty streets, and we would be glad to make it two hundred and one."),
             color="muted") + "\n" +
        buttons([{"text": t("Plan your visit"), "url": "#visit"},
                 {"text": t("Meet the staff"), "url": "#staff", "style": "outline"}]),
        image(uri("assets/images/abstract/sanctuary.svg"), tattr("The church building"), radius=CARD_RADIUS),
        left_width="54%", right_width="46%"),
    gap="0")
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
row = (columns([
    column(label(php("$unapp_church_event['when']")), width="26%", vertical_align="top"),
    column(card_title(php("$unapp_church_event['title']")) + "\n" +
           para(php("$unapp_church_event['text']"), color="muted", size="small"),
           width="74%", vertical_align="top", gap="20"),
], gap=ROW_GAP, vertical_align="top", is_stacked=False) + "\n" + separator(style="wide", color="border"))
body = section_std(
    intro(eyebrow_text=t("Diary", "Section eyebrow label"), title=t("Coming up")) + "\n" +
    group(loop("unapp_church_events", "unapp_church_event", row),
          layout="constrained", content_size=READ_WIDTH, gap=CARD_GAP))
write_pattern("church-events", title="Church: upcoming events", cats=C + ", unapp_content",
              keywords="church, events, diary, calendar, upcoming",
              desc="A dated list of what is on: community lunch, job club, harvest and carols.",
              body=body,
              php_prelude=php_rows("unapp_church_events", ("when", "title", "text"), EVENTS,
                                   "Church event"))

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
body = section_std(
    intro(eyebrow_text=t("Questions", "Section eyebrow label"), title=t("The ones people ask us")) + "\n" +
    faq_list([(t(q, "FAQ question"), t(a, "FAQ answer")) for q, a in FAQ]))
write_pattern("church-faq", title="Church: first-visit questions", cats=C + ", unapp_utility, faq",
              keywords="church, faq, questions, visit, accessibility, children",
              desc="The four questions a newcomer is too polite to ask, answered without church jargon.",
              body=body)

# ---------------------------------------------------------------- contact and directions
details_card = card(
    para(t("Office hours"), weight="600") + "\n" +
    para(t("Tuesday to Friday, 9:30–14:30. Ruth is usually in; if the door is locked, ring the bell."),
         color="muted", size="small") + "\n" +
    separator(style="wide", color="border") + "\n" +
    para(t("Telephone"), weight="600") + "\n" +
    para(t("01246 555 0114"), color="muted", size="small") + "\n" +
    separator(style="wide", color="border") + "\n" +
    para(t("Email"), weight="600") + "\n" +
    para(t("hello@riverside.example"), color="muted", size="small"))
body = section_std(
    split(
        eyebrow(t("Find us", "Section eyebrow label"), align="left") + "\n" +
        heading(t("Mill Lane, and the door is open")) + "\n" +
        para(t("Riverside Church, 12 Mill Lane, Chesterfield S40 1RT. The 43 and 44 buses stop at the end of the road; the car park is behind the building."),
             color="muted", size="large") + "\n" +
        buttons([{"text": t("Get directions"), "url": "#map"}]),
        details_card, align="top"),
    variation="is-style-section-soft", gap="0")
write_pattern("church-contact", title="Church: contact and directions", cats=C + ", unapp_utility, contact",
              keywords="church, contact, directions, address, office hours, parking",
              desc="Address, transport and office hours — the church equivalent of a contact page, without a form.",
              body=body)

# ---------------------------------------------------------------- closing band
body = band(t("There is a service this Sunday at 9:30 and 11:15"),
            t("Come on your own, come late, come and sit at the back. All of that is completely normal here."),
            [{"text": t("Plan your visit"), "url": "#visit", "bg": "base", "color": "contrast"},
             {"text": t("Watch a service online"), "url": "#watch", "style": "outline", "color": "base"}])
write_pattern("church-cta", title="Church: closing invitation", cats=C + ", unapp_cta, call to action",
              keywords="church, cta, invitation, visit, sunday",
              desc="A warm closing band inviting a visit, on the palette gradient.",
              body=body)

print("batch 13 rewritten: 8 church patterns on the house style")
