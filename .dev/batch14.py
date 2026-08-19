"""Fitness starter: memberships, results, member stories, FAQ and location in a studio voice."""
import os, sys; sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *

F = "unapp, unapp_fitness"

# ---------------------------------------------------------------- what this place is
body = section(
    columns([
        column(
            eyebrow(t("The studio", "Section eyebrow label"), align="left") + "\n" +
            heading(t("A barbell gym that will not make you feel stupid")) + "\n" +
            para(t("Two rooms, sixteen platforms and a coach on the floor at every session. No mirrors down the long wall, no music you have to shout over, and no one filming themselves in the squat rack."),
                 color="muted", size="large") + "\n" +
            lst([t("Every class capped at twelve people"),
                 t("Programmes written for your first year, not your first week"),
                 t("Coaches who lift, and who have all coached beginners"),
                 t("Open gym hours on your membership at no extra cost")]) + "\n" +
            buttons([{"text": t("Book a free session"), "url": "#book"}], margin={"top": "10"}),
            width="54%", vertical_align="center", gap="20"),
        column(image(uri("assets/images/abstract/track.svg"), tattr("The training floor"),
                     radius="20px"), width="46%", vertical_align="center"),
    ], align="wide", gap="60", vertical_align="center"),
    pad=("70", "70"), gap="0")
write_pattern("fitness-intro", title="Fitness: about the studio", cats=F + ", unapp_features",
              keywords="fitness, gym, studio, about, training, intro",
              desc="What the gym is and is not, with a checklist and a photograph of the floor.",
              body=body)

# ---------------------------------------------------------------- memberships
PLANS = [
    ("Off-peak", "39", "a month", "Weekdays before 16:00 and all weekend.",
     ["Every off-peak class", "Open gym whenever we are open", "Programme reviewed each month"], False, "Join off-peak"),
    ("Full", "59", "a month", "Any class, any hour we are open.",
     ["Every class on the timetable", "Open gym whenever we are open", "Programme reviewed each month",
      "Bring a friend once a month"], True, "Join full"),
    ("Coached", "120", "a month", "Small-group coaching, four to a coach.",
     ["Everything in Full", "Two coached sessions a week", "Written programme, updated fortnightly",
      "Video review of your lifts"], False, "Talk to a coach"),
]
prelude = "$unapp_fitness_plans = array(\n"
for name, price, per, note, feats, featured, cta in PLANS:
    feat_php = ", ".join(f"_x( '{f}', 'Membership feature', 'unapp' )" for f in feats)
    prelude += ("\tarray(\n"
                f"\t\t'name'     => _x( '{name}', 'Membership name', 'unapp' ),\n"
                f"\t\t'price'    => _x( '{price}', 'Membership price', 'unapp' ),\n"
                f"\t\t'per'      => _x( '{per}', 'Membership period', 'unapp' ),\n"
                f"\t\t'note'     => _x( '{note}', 'Membership note', 'unapp' ),\n"
                f"\t\t'cta'      => _x( '{cta}', 'Membership button', 'unapp' ),\n"
                f"\t\t'featured' => {'true' if featured else 'false'},\n"
                f"\t\t'features' => array( {feat_php} ),\n"
                "\t),\n")
prelude += ");\n"

price_row = group(
    para(php("'£' . $unapp_fitness_plan['price']"), size="xxx-large", weight="700", line_height="1") + "\n" +
    para(php("$unapp_fitness_plan['per']"), color="muted", size="small"),
    layout="flex", orientation="horizontal", gap="8", vertical_align="bottom")
feat_loop = ('<!-- wp:list {"className":"is-style-checklist"} -->\n<ul class="wp-block-list is-style-checklist">\n'
             '<?php foreach ( $unapp_fitness_plan[\'features\'] as $unapp_fitness_feature ) : ?>\n'
             '<!-- wp:list-item -->\n<li><?php echo esc_html( $unapp_fitness_feature ); ?></li>\n<!-- /wp:list-item -->\n'
             '<?php endforeach; ?>\n</ul>\n<!-- /wp:list -->')
badge = ('<?php if ( $unapp_fitness_plan[\'featured\'] ) : ?>\n'
         + para(t("Most members choose this", "Highlighted membership badge"), color="primary",
                size="small", weight="700", letter="0.06em", transform="uppercase")
         + '\n<?php endif; ?>')
card_inner = (badge + "\n" +
              heading(php("$unapp_fitness_plan['name']"), level=3, size="large") + "\n" +
              para(php("$unapp_fitness_plan['note']"), color="muted", size="small") + "\n" +
              price_row + "\n" + feat_loop + "\n" +
              '<!-- wp:buttons -->\n<div class="wp-block-buttons">\n'
              '<!-- wp:button {"width":100} -->\n'
              '<div class="wp-block-button has-custom-width wp-block-button__width-100">'
              '<a class="wp-block-button__link wp-element-button" href="#join">'
              '<?php echo esc_html( $unapp_fitness_plan[\'cta\'] ); ?></a></div>\n'
              '<!-- /wp:button -->\n</div>\n<!-- /wp:buttons -->')
card = ('<?php if ( $unapp_fitness_plan[\'featured\'] ) : ?>\n'
        + group(card_inner, style_variation="is-style-elevated", radius="18px", layout="flex",
                orientation="vertical", gap="20",
                pad={"top": "50", "bottom": "50", "left": "40", "right": "40"})
        + '\n<?php else : ?>\n'
        + group(card_inner, style_variation="is-style-card", radius="18px", layout="flex",
                orientation="vertical", gap="20",
                pad={"top": "50", "bottom": "50", "left": "40", "right": "40"})
        + '\n<?php endif; ?>')
loop = ("<?php foreach ( $unapp_fitness_plans as $unapp_fitness_plan ) : ?>\n"
        + card + "\n<?php endforeach; ?>")
body = section(
    intro(eyebrow_text=t("Memberships", "Section eyebrow label"),
          title=t("Three ways to train here"),
          lead=t("Monthly rolling, cancel with a month's notice, no joining fee and no twelve-month contract to sign.")) + "\n" +
    group(loop, align="wide", layout="grid", col_count=3, gap="40", class_name="unapp-grid-3") + "\n" +
    para(t("Students, NHS staff and over-65s take 20% off any membership — just ask at the desk."),
         align="center", color="muted", size="small"),
    pad=("60", "70"), gap="40")
write_pattern("fitness-memberships", title="Fitness: memberships", cats=F + ", unapp_pricing, pricing",
              keywords="fitness, gym, membership, pricing, plans, join",
              desc="Three membership tiers with real gym pricing, a highlighted middle option and a concessions line.",
              body=body, php_prelude=prelude)

# ---------------------------------------------------------------- results / numbers
STATS = [
    ("340", "members", "Most of whom had never touched a barbell before they walked in."),
    ("42", "classes a week", "Across two rooms, from 06:00 to 20:30."),
    ("12", "people per class", "The number we cap it at, permanently. It is the whole point."),
    ("7", "coaches", "All of them qualified, all of them still competing or still learning."),
]
prelude = "$unapp_fitness_stats = array(\n"
for num, label, note in STATS:
    prelude += ("\tarray(\n"
                f"\t\t'number' => '{num}',\n"
                f"\t\t'label'  => _x( '{label}', 'Studio statistic label', 'unapp' ),\n"
                f"\t\t'note'   => _x( '{note}', 'Studio statistic note', 'unapp' ),\n"
                "\t),\n")
prelude += ");\n"
card = group(
    para(php("$unapp_fitness_stat['number']"), size="xxx-large", weight="700", line_height="1",
         class_name="unapp-count") + "\n" +
    para(php("$unapp_fitness_stat['label']"), weight="600") + "\n" +
    para(php("$unapp_fitness_stat['note']"), color="muted", size="small"),
    layout="flex", orientation="vertical", gap="8")
loop = ("<?php foreach ( $unapp_fitness_stats as $unapp_fitness_stat ) : ?>\n"
        + card + "\n<?php endforeach; ?>")
body = section(
    intro(eyebrow_text=t("The studio in numbers", "Section eyebrow label"),
          title=t("Small on purpose")) + "\n" +
    group(loop, align="wide", layout="grid", col_count=4, gap="40", class_name="unapp-grid-4"),
    style_variation="is-style-section-soft", pad=("60", "70"), gap="50")
write_pattern("fitness-results", title="Fitness: the studio in numbers", cats=F + ", unapp_company, stats",
              keywords="fitness, gym, stats, numbers, members, counter",
              desc="Four counting numbers about the studio, each with a line explaining why it matters.",
              body=body, php_prelude=prelude)

# ---------------------------------------------------------------- member stories
STORIES = [
    ("avatar-2", "Priya Raman", "Member for 2 years",
     "I joined at forty-one having never lifted anything heavier than a toddler. I deadlift a hundred kilos now. Nobody was ever weird about it."),
    ("avatar-7", "Mark Ellison", "Member for 8 months",
     "I have had three gym memberships and used none of them. This is the first place where a coach noticed I had stopped coming and messaged me."),
    ("avatar-9", "Chloe Boateng", "Member for 4 years",
     "I came back six weeks after having my son. They rewrote the whole programme around what my body could actually do that month."),
]
prelude = "$unapp_fitness_stories = array(\n"
for img_slug, name, meta, quote in STORIES:
    prelude += ("\tarray(\n"
                f"\t\t'image' => '{img_slug}',\n"
                f"\t\t'name'  => _x( '{name}', 'Member name', 'unapp' ),\n"
                f"\t\t'meta'  => _x( '{meta}', 'Member since', 'unapp' ),\n"
                f"\t\t'quote' => _x( '{quote}', 'Member quote', 'unapp' ),\n"
                "\t),\n")
prelude += ");\n"
card = group(
    para(php("$unapp_fitness_story['quote']"), size="large", line_height="1.5") + "\n" +
    group(
        image(php("get_theme_file_uri( 'assets/images/avatars/' . $unapp_fitness_story['image'] . '.svg' )"),
              php("$unapp_fitness_story['name']"), width="48px", height="48px", radius="999px") + "\n" +
        group(para(php("$unapp_fitness_story['name']"), weight="600", size="small") + "\n" +
              para(php("$unapp_fitness_story['meta']"), color="muted", size="small"),
              layout="flex", orientation="vertical", gap="0"),
        layout="flex", orientation="horizontal", gap="14", vertical_align="center"),
    style_variation="is-style-card", radius="18px", layout="flex", orientation="vertical", gap="24",
    pad={"top": "40", "bottom": "40", "left": "40", "right": "40"})
loop = ("<?php foreach ( $unapp_fitness_stories as $unapp_fitness_story ) : ?>\n"
        + card + "\n<?php endforeach; ?>")
body = section(
    intro(eyebrow_text=t("Members", "Section eyebrow label"),
          title=t("People who were nervous on day one")) + "\n" +
    group(loop, align="wide", layout="grid", col_count=3, gap="40", class_name="unapp-grid-3"),
    pad=("60", "70"), gap="50")
write_pattern("fitness-testimonials", title="Fitness: member stories", cats=F + ", unapp_proof, testimonials",
              keywords="fitness, gym, testimonials, members, reviews, stories",
              desc="Three member quotes with portraits and how long each has trained here.",
              body=body, php_prelude=prelude)

# ---------------------------------------------------------------- FAQ
FAQ = [
    ("I have never lifted before. Where do I start?",
     "With the free session. A coach walks you through the four main lifts using an empty bar, and you leave with a plan for your first month. Roughly half the people in any class started exactly this way."),
    ("Do I have to book, or can I turn up?",
     "Book. Classes cap at twelve and the 18:30 slots fill by Sunday night. Booking opens seven days ahead in the app, and cancelling before 12:00 on the day costs you nothing."),
    ("Can I pause my membership?",
     "Yes — up to two months a year for any reason at all, and longer for injury, pregnancy or a spell working away. Email the studio; there is no form and no retention call."),
    ("Is there parking, and are there showers?",
     "Eighteen free spaces at the rear, plus bike racks inside the door. Four showers in each changing room, towels included, and lockers that do not need a pound coin."),
]
items = [details(t(q, "FAQ question"), para(t(a, "FAQ answer"), color="muted"),
                 class_name="is-style-faq-card") for q, a in FAQ]
body = section(
    intro(eyebrow_text=t("Before you join", "Section eyebrow label"),
          title=t("Fair questions")) + "\n" +
    group("\n".join(items), layout="constrained", content_size="760px", gap="16"),
    pad=("60", "70"), gap="50")
write_pattern("fitness-faq", title="Fitness: questions", cats=F + ", unapp_utility, faq",
              keywords="fitness, gym, faq, questions, booking, membership",
              desc="Four questions people ask before joining a gym, answered without a sales pitch.",
              body=body)

# ---------------------------------------------------------------- location and hours
HOURS = [("Monday to Thursday", "06:00 – 21:00"), ("Friday", "06:00 – 20:00"),
         ("Saturday", "08:00 – 14:00"), ("Sunday", "09:00 – 13:00")]
rows = ""
for day, hrs in HOURS:
    rows += (columns([
        column(para(t(day, "Opening day"), weight="600"), width="55%", vertical_align="center"),
        column(para(t(hrs, "Opening hours"), color="muted", align="right"), width="45%", vertical_align="center"),
    ], gap="20", vertical_align="center", is_stacked=False) + "\n" + separator(style="wide", color="border") + "\n")

body = section(
    columns([
        column(
            eyebrow(t("Find the studio", "Section eyebrow label"), align="left") + "\n" +
            heading(t("Arch 12, Bonnington Yard")) + "\n" +
            para(t("Under the railway on Bonnington Road, five minutes from the station. Look for the roller door — the sign is small and the music is not."),
                 color="muted", size="large") + "\n" +
            para(t("hello@archtwelve.example · 0131 555 0142"), color="muted") + "\n" +
            buttons([{"text": t("Get directions"), "url": "#map"},
                     {"text": t("Book a free session"), "url": "#book", "style": "outline"}],
                    margin={"top": "10"}),
            width="52%", vertical_align="top", gap="18"),
        column(
            group(heading(t("Opening hours"), level=3, size="medium") + "\n" + rows.rstrip(),
                  style_variation="is-style-card", radius="18px", gap="14",
                  pad={"top": "40", "bottom": "40", "left": "40", "right": "40"}),
            width="48%", vertical_align="top"),
    ], align="wide", gap="60", vertical_align="top"),
    style_variation="is-style-section-soft", pad=("60", "70"), gap="0")
write_pattern("fitness-location", title="Fitness: location and hours", cats=F + ", unapp_utility, contact",
              keywords="fitness, gym, location, hours, address, directions, opening",
              desc="Where the studio is and when it is open, with the hours in a card.",
              body=body)

# ---------------------------------------------------------------- join band
body = section(
    group(
        heading(t("Your first session is free, and it always will be"), align="center",
                size="xx-large", color="base") + "\n" +
        para(t("An hour with a coach, an empty bar and no obligation to join anything at the end of it."),
             align="center", custom_color="rgba(255,255,255,0.86)", size="large") + "\n" +
        buttons([{"text": t("Book your free session"), "url": "#book", "bg": "base", "color": "contrast"},
                 {"text": t("See the timetable"), "url": "#timetable", "style": "outline", "color": "base"}],
                justify="center", margin={"top": "10"}),
        layout="constrained", content_size="720px", gap="24"),
    gradient="primary-to-accent", text="base", pad=("70", "70"), gap="0")
write_pattern("fitness-cta", title="Fitness: join band", cats=F + ", unapp_cta, call to action",
              keywords="fitness, gym, cta, join, trial, free session",
              desc="A closing band offering the free first session, on the palette gradient.",
              body=body)

print("batch 14 written: 7 fitness patterns")
