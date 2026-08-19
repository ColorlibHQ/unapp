"""Fitness starter sections, on the house style."""
import os, sys; sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *
from batch13 import php_rows, loop

F = "unapp, unapp_fitness"

# ---------------------------------------------------------------- about the studio
body = section_std(
    split(
        eyebrow(t("The studio", "Section eyebrow label"), align="left") + "\n" +
        heading(t("A barbell gym that will not make you feel stupid")) + "\n" +
        para(t("Two rooms, sixteen platforms and a coach on the floor at every session. No mirrors down the long wall, no music you have to shout over, and nobody filming themselves in the squat rack."),
             color="muted", size="large") + "\n" +
        lst([t("Every class capped at twelve people"),
             t("Programmes written for your first year, not your first week"),
             t("Coaches who lift, and who have all coached beginners"),
             t("Open gym hours included in every membership")]) + "\n" +
        buttons([{"text": t("Book a free session"), "url": "#book"}]),
        image(uri("assets/images/abstract/track.svg"), tattr("The training floor"), radius=CARD_RADIUS),
        left_width="54%", right_width="46%"),
    gap="0")
write_pattern("fitness-intro", title="Fitness: about the studio", cats=F + ", unapp_features",
              keywords="fitness, gym, studio, about, training, intro",
              desc="What the gym is and is not, with a checklist and a photograph of the floor.",
              body=body)

# ---------------------------------------------------------------- memberships
PLANS = [
    ("Off-peak", "39", "Weekdays before 16:00 and all weekend.",
     ["Every off-peak class", "Open gym whenever we are open", "Programme reviewed each month"], False, "Join off-peak"),
    ("Full", "59", "Any class, any hour we are open.",
     ["Every class on the timetable", "Open gym whenever we are open", "Programme reviewed each month",
      "Bring a friend once a month"], True, "Join full"),
    ("Coached", "120", "Small-group coaching, four to a coach.",
     ["Everything in Full", "Two coached sessions a week", "Written programme, updated fortnightly",
      "Video review of your lifts"], False, "Talk to a coach"),
]
prelude = "$unapp_fitness_plans = array(\n"
for name, price, note, feats, featured, cta in PLANS:
    feat_php = ", ".join(f"_x( '{x}', 'Membership feature', 'unapp' )" for x in feats)
    prelude += ("\tarray(\n"
                f"\t\t'name'     => _x( '{name}', 'Membership name', 'unapp' ),\n"
                f"\t\t'price'    => _x( '{price}', 'Membership price', 'unapp' ),\n"
                f"\t\t'note'     => _x( '{note}', 'Membership note', 'unapp' ),\n"
                f"\t\t'cta'      => _x( '{cta}', 'Membership button', 'unapp' ),\n"
                f"\t\t'featured' => {'true' if featured else 'false'},\n"
                f"\t\t'features' => array( {feat_php} ),\n"
                "\t),\n")
prelude += ");\n"

price = group(
    para(php("'£' . $unapp_fitness_plan['price']"), size="xxx-large", weight="700", line_height="1") + "\n" +
    para(t("a month", "Membership period"), color="muted", size="small"),
    layout="flex", orientation="horizontal", gap="20", vertical_align="bottom")
feature_list = ('<!-- wp:list {"className":"is-style-checklist"} -->\n<ul class="wp-block-list is-style-checklist">\n'
                "<?php foreach ( $unapp_fitness_plan['features'] as $unapp_fitness_feature ) : ?>\n"
                '<!-- wp:list-item -->\n<li><?php echo esc_html( $unapp_fitness_feature ); ?></li>\n<!-- /wp:list-item -->\n'
                '<?php endforeach; ?>\n</ul>\n<!-- /wp:list -->')
plan_button = ('<!-- wp:buttons -->\n<div class="wp-block-buttons">\n<!-- wp:button {"width":100} -->\n'
               '<div class="wp-block-button has-custom-width wp-block-button__width-100">'
               '<a class="wp-block-button__link wp-element-button" href="#join">'
               "<?php echo esc_html( $unapp_fitness_plan['cta'] ); ?></a></div>\n"
               '<!-- /wp:button -->\n</div>\n<!-- /wp:buttons -->')
badge = ("<?php if ( $unapp_fitness_plan['featured'] ) : ?>\n"
         + label(t("Most members choose this", "Highlighted membership badge"))
         + "\n<?php endif; ?>")
plan_inner = (badge + "\n" + card_title(php("$unapp_fitness_plan['name']")) + "\n" +
              para(php("$unapp_fitness_plan['note']"), color="muted", size="small") + "\n" +
              price + "\n" + feature_list + "\n" + plan_button)
plan = ("<?php if ( $unapp_fitness_plan['featured'] ) : ?>\n"
        + card(plan_inner, variation="is-style-elevated")
        + "\n<?php else : ?>\n" + card(plan_inner) + "\n<?php endif; ?>")
body = section_std(
    intro(eyebrow_text=t("Memberships", "Section eyebrow label"),
          title=t("Three ways to train here"),
          lead=t("Monthly rolling, cancel with a month's notice, no joining fee and no twelve-month contract to sign.")) + "\n" +
    grid(loop("unapp_fitness_plans", "unapp_fitness_plan", plan), cols=3) + "\n" +
    para(t("Students, NHS staff and over-65s take 20% off any membership — just ask at the desk."),
         align="center", color="muted", size="small"))
write_pattern("fitness-memberships", title="Fitness: memberships", cats=F + ", unapp_pricing, pricing",
              keywords="fitness, gym, membership, pricing, plans, join",
              desc="Three membership tiers at real gym prices, the popular one badged, and a concessions line.",
              body=body, php_prelude=prelude)

# ---------------------------------------------------------------- the studio in numbers
STATS = [
    ("340", "members", "Most of whom had never touched a barbell before they walked in."),
    ("42", "classes a week", "Across two rooms, from 06:00 to 20:30."),
    ("12", "people per class", "The number we cap it at, permanently. It is the whole point."),
    ("7", "coaches", "All of them qualified, all of them still competing or still learning."),
]
stat = stack(
    para(php("$unapp_fitness_stat['number']"), size="xxx-large", weight="700", line_height="1",
         class_name="unapp-count") + "\n" +
    para(php("$unapp_fitness_stat['label']"), weight="600") + "\n" +
    para(php("$unapp_fitness_stat['note']"), color="muted", size="small"),
    gap="20")
body = section_std(
    intro(eyebrow_text=t("The studio in numbers", "Section eyebrow label"), title=t("Small on purpose")) + "\n" +
    grid(loop("unapp_fitness_stats", "unapp_fitness_stat", stat), cols=4),
    variation="is-style-section-soft")
write_pattern("fitness-results", title="Fitness: the studio in numbers", cats=F + ", unapp_company, stats",
              keywords="fitness, gym, stats, numbers, members, counter",
              desc="Four counting numbers about the studio, each with a line on why it matters.",
              body=body,
              php_prelude=php_rows("unapp_fitness_stats", ("number", "label", "note"), STATS,
                                   "Studio statistic"))

# ---------------------------------------------------------------- member stories
STORIES = [
    ("avatar-2", "Priya Raman", "Member for two years",
     "I joined at forty-one having never lifted anything heavier than a toddler. I deadlift a hundred kilos now. Nobody was ever weird about it."),
    ("avatar-7", "Mark Ellison", "Member for eight months",
     "I have had three gym memberships and used none of them. This is the first place where a coach noticed I had stopped coming and messaged me."),
    ("avatar-9", "Chloe Boateng", "Member for four years",
     "I came back six weeks after having my son. They rewrote the whole programme around what my body could actually do that month."),
]
attribution = group(
    avatar(php("get_theme_file_uri( 'assets/images/avatars/' . $unapp_fitness_story['image'] . '.svg' )"),
           php("$unapp_fitness_story['name']"), size=AVATAR_ROW) + "\n" +
    stack(para(php("$unapp_fitness_story['name']"), weight="600") + "\n" +
          para(php("$unapp_fitness_story['meta']"), color="muted", size="small"), gap="0"),
    layout="flex", orientation="horizontal", gap="30", vertical_align="center")
story = card(para(php("$unapp_fitness_story['quote']"), size="large", line_height="1.5") + "\n" + attribution)
body = section_std(
    intro(eyebrow_text=t("Members", "Section eyebrow label"),
          title=t("People who were nervous on day one")) + "\n" +
    grid(loop("unapp_fitness_stories", "unapp_fitness_story", story), cols=3))
write_pattern("fitness-testimonials", title="Fitness: member stories", cats=F + ", unapp_proof, testimonials",
              keywords="fitness, gym, testimonials, members, reviews, stories",
              desc="Three member quotes with portraits and how long each has trained here.",
              body=body,
              php_prelude=php_rows("unapp_fitness_stories", ("image", "name", "meta", "quote"), STORIES,
                                   "Member story"))

# ---------------------------------------------------------------- questions
FAQ = [
    ("I have never lifted before. Where do I start?",
     "With the free session. A coach walks you through the four main lifts using an empty bar, and you leave with a plan for your first month. Roughly half the people in any class started exactly this way."),
    ("Do I have to book, or can I turn up?",
     "Book. Classes cap at twelve and the 18:30 slots fill by Sunday night. Booking opens seven days ahead in the app, and cancelling before midday costs you nothing."),
    ("Can I pause my membership?",
     "Yes — up to two months a year for any reason at all, and longer for injury, pregnancy or a spell working away. Email the studio; there is no form and no retention call."),
    ("Is there parking, and are there showers?",
     "Eighteen free spaces at the rear, plus bike racks inside the door. Four showers in each changing room, towels included, and lockers that do not need a pound coin."),
]
body = section_std(
    intro(eyebrow_text=t("Before you join", "Section eyebrow label"), title=t("Fair questions")) + "\n" +
    faq_list([(t(q, "FAQ question"), t(a, "FAQ answer")) for q, a in FAQ]))
write_pattern("fitness-faq", title="Fitness: questions", cats=F + ", unapp_utility, faq",
              keywords="fitness, gym, faq, questions, booking, membership",
              desc="Four questions people ask before joining a gym, answered without a sales pitch.",
              body=body)

# ---------------------------------------------------------------- location and hours
HOURS = [("Monday to Thursday", "06:00 – 21:00"), ("Friday", "06:00 – 20:00"),
         ("Saturday", "08:00 – 14:00"), ("Sunday", "09:00 – 13:00")]
rows = []
for day, hrs in HOURS:
    rows.append(columns([
        column(para(t(day, "Opening day"), weight="600"), width="55%", vertical_align="center"),
        column(para(t(hrs, "Opening hours"), color="muted", align="right"), width="45%", vertical_align="center"),
    ], gap="30", vertical_align="center", is_stacked=False))
hours_card = card(card_title(t("Opening hours")) + "\n" +
                  ("\n" + separator(style="wide", color="border") + "\n").join(rows))
body = section_std(
    split(
        eyebrow(t("Find the studio", "Section eyebrow label"), align="left") + "\n" +
        heading(t("Arch 12, Bonnington Yard")) + "\n" +
        para(t("Under the railway on Bonnington Road, five minutes from the station. Look for the roller door — the sign is small and the music is not."),
             color="muted", size="large") + "\n" +
        para(t("hello@archtwelve.example · 0131 555 0142"), color="muted") + "\n" +
        buttons([{"text": t("Get directions"), "url": "#map"},
                 {"text": t("Book a free session"), "url": "#book", "style": "outline"}]),
        hours_card, align="top"),
    variation="is-style-section-soft", gap="0")
write_pattern("fitness-location", title="Fitness: location and hours", cats=F + ", unapp_utility, contact",
              keywords="fitness, gym, location, hours, address, directions, opening",
              desc="Where the studio is and when it is open, with the hours in a card.",
              body=body)

# ---------------------------------------------------------------- join band
body = band(t("Your first session is free, and it always will be"),
            t("An hour with a coach, an empty bar and no obligation to join anything at the end of it."),
            [{"text": t("Book your free session"), "url": "#book", "bg": "base", "color": "contrast"},
             {"text": t("See the timetable"), "url": "#timetable", "style": "outline", "color": "base"}])
write_pattern("fitness-cta", title="Fitness: join band", cats=F + ", unapp_cta, call to action",
              keywords="fitness, gym, cta, join, trial, free session",
              desc="A closing band offering the free first session, on the palette gradient.",
              body=body)

print("batch 14 rewritten: 7 fitness patterns on the house style")
