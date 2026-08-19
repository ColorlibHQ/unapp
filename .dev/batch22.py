"""Education and events starter sections."""
import os, sys; sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *
from batch13 import php_rows, loop

ED = "unapp, unapp_education"
EV = "unapp, unapp_events"

# ================================================================= EDUCATION
body = section_std(
    split(
        eyebrow(t("Evening and weekend courses", "School hero eyebrow"), align="left") + "\n" +
        heading(t("Learn a trade properly, from someone who does it")) + "\n" +
        para(t("Short courses in woodwork, letterpress, ceramics and bookbinding, taught in a Victorian school hall by people who make their living at it."),
             color="muted", size="large") + "\n" +
        buttons([{"text": t("See the courses"), "url": "#courses"},
                 {"text": t("How booking works"), "url": "#faq", "style": "outline"}]),
        image(uri("assets/images/abstract/studio-1.svg"), tattr("The workshop"), radius=CARD_RADIUS),
        left_width="55%", right_width="45%"),
    gap="0")
write_pattern("education-hero", title="Courses: introduction", cats=ED + ", banner, featured",
              keywords="education, courses, school, learning, workshop, hero",
              desc="A course provider's introduction with two calls to action.",
              body=body)

COURSES = [
    ("Woodwork: the first joint", "6 evenings", "£320", "Hand tools only. You leave with a stool you made and did not glue in a panic."),
    ("Letterpress", "2 days", "£210", "Setting metal type, mixing ink and printing a run of fifty cards to take home."),
    ("Wheel-thrown ceramics", "8 evenings", "£420", "Throwing, trimming and glazing. Everything you make gets fired."),
    ("Bookbinding", "1 day", "£140", "Two sewn bindings in a day, in the good cloth rather than the practice stuff."),
]
prelude = "$unapp_courses = array(\n"
for name, length, price, text in COURSES:
    prelude += ("\tarray(\n"
                f"\t\t'name'   => _x( \"{name}\", 'Course name', 'unapp' ),\n"
                f"\t\t'length' => _x( '{length}', 'Course length', 'unapp' ),\n"
                f"\t\t'price'  => _x( '{price}', 'Course price', 'unapp' ),\n"
                f"\t\t'text'   => _x( \"{text}\", 'Course description', 'unapp' ),\n"
                "\t),\n")
prelude += ");\n"
row = (columns([
    column(card_title(php("$unapp_course['name']")) + "\n" +
           para(php("$unapp_course['text']"), color="muted", size="small"),
           width="62%", vertical_align="top", gap="20"),
    column(label(php("$unapp_course['length']")) + "\n" +
           para(php("$unapp_course['price']"), size="large", weight="700"),
           width="38%", vertical_align="top", gap="20"),
], gap=ROW_GAP, vertical_align="top", is_stacked=False) + "\n" + separator(style="wide", color="border"))
body = section_std(
    intro(eyebrow_text=t("This term", "Section eyebrow label"),
          title=t("Four courses, and a waiting list for two of them"),
          lead=t("Everything is capped at eight people, because that is how many benches there are.")) + "\n" +
    group(loop("unapp_courses", "unapp_course", row),
          layout="constrained", content_size=READ_WIDTH, gap=CARD_GAP))
write_pattern("education-courses", title="Courses: the timetable", cats=ED + ", unapp_content",
              keywords="education, courses, classes, timetable, prices, list",
              desc="Four courses with length, price and what you leave with.",
              body=body, php_prelude=prelude)

TUTORS = [
    ("avatar-3", "Rob Feeny", "Woodwork", "Furniture maker for twenty-two years. Teaches the way he was taught, minus the shouting."),
    ("avatar-6", "Ivy Sandoval", "Letterpress and bookbinding", "Runs a two-person press in the same building. Owns more type than sense."),
    ("avatar-9", "Kwame Boakye", "Ceramics", "Production potter. Fires the kiln on Wednesdays, which is why glazing is on Tuesday."),
]
person = stack(
    avatar(php("get_theme_file_uri( 'assets/images/avatars/' . $unapp_tutor['image'] . '.svg' )"),
           php("$unapp_tutor['name']")) + "\n" +
    card_title(php("$unapp_tutor['name']")) + "\n" +
    label(php("$unapp_tutor['role']")) + "\n" +
    para(php("$unapp_tutor['note']"), color="muted", size="small"),
    gap=CARD_GAP)
body = section_std(
    intro(eyebrow_text=t("Who teaches", "Section eyebrow label"),
          title=t("People who do it for a living")) + "\n" +
    grid(loop("unapp_tutors", "unapp_tutor", person), cols=3),
    variation="is-style-section-soft")
write_pattern("education-tutors", title="Courses: tutors", cats=ED + ", unapp_company, team",
              keywords="education, tutors, teachers, staff, instructors",
              desc="Three tutors with what they make when they are not teaching.",
              body=body,
              php_prelude=php_rows("unapp_tutors", ("image", "name", "role", "note"), TUTORS, "Tutor"))

FAQ = [
    ("Do I need to bring anything?",
     "Only clothes you do not mind ruining. Tools, timber, clay, ink and paper are all included, and there is an apron on the back of every door."),
    ("I have never done this before.",
     "Good — most people have not. Every course starts from nothing and the first evening is deliberately slow. If you have done some before, say so when you book and we will give you harder work."),
    ("What if I miss a week?",
     "Tell us and we will run through what you missed at the start of the next one. Miss more than two of six and we will move you to the following term rather than have you fall behind."),
    ("Can I buy someone a place?",
     "Yes — gift vouchers do not expire, and they are for a value rather than a specific course, so the recipient picks what and when."),
]
body = section_std(
    intro(eyebrow_text=t("Before you book", "Section eyebrow label"), title=t("The usual questions")) + "\n" +
    faq_list([(t(q, "FAQ question"), t(a, "FAQ answer")) for q, a in FAQ]))
write_pattern("education-faq", title="Courses: questions", cats=ED + ", unapp_utility, faq",
              keywords="education, faq, booking, courses, beginners",
              desc="What to bring, starting from nothing, missed weeks and gift vouchers.",
              body=body)

body = band(t("The autumn term opens for booking on 1 September"),
            t("Courses fill in about a fortnight. The mailing list gets a day's head start, which is the only perk it has."),
            [{"text": t("See the courses"), "url": "#courses", "bg": "base", "color": "contrast"},
             {"text": t("Join the list"), "url": "#subscribe", "style": "outline", "color": "base"}])
write_pattern("education-cta", title="Courses: booking band", cats=ED + ", unapp_cta, call to action",
              keywords="education, cta, booking, term, newsletter",
              desc="A closing band about when booking opens, on the palette gradient.",
              body=body)

# ================================================================= EVENTS
cover_url = uri("assets/images/abstract/gathering.svg")
inner = (eyebrow(t("Bristol · 14–15 May 2027", "Conference hero eyebrow"), align="center", color="base") + "\n" +
         heading(t("Two days about building things that outlast the team that built them"),
                 align="center", color="base", size="xxx-large") + "\n" +
         para(t("Sixteen talks, no sponsor keynotes, four hundred people and a bar that opens at five."),
              align="center", color="base", size="large") + "\n" +
         buttons([{"text": t("Buy a ticket"), "bg": "base", "color": "primary"},
                  {"text": t("See the programme"), "style": "is-style-outline", "color": "base"}],
                 justify="center", margin={"top": "40"}))
body = f'''<!-- wp:cover {{"url":"{cover_url}","dimRatio":70,"overlayColor":"contrast","isUserOverlayColor":true,"minHeight":68,"minHeightUnit":"vh","align":"full","style":{{"spacing":{{"padding":{{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}},"blockGap":"var:preset|spacing|30"}}}},"layout":{{"type":"constrained","contentSize":"860px"}}}} -->
<div class="wp-block-cover alignfull" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80);min-height:68vh"><span aria-hidden="true" class="wp-block-cover__background has-contrast-background-color has-background-dim-70 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="{cover_url}" data-object-fit="cover"/><div class="wp-block-cover__inner-container">
{inner}
</div></div>
<!-- /wp:cover -->'''
write_pattern("events-hero", title="Event: hero", cats=EV + ", banner, featured",
              keywords="events, conference, hero, tickets, programme",
              desc="A full-bleed conference cover with the dates, the shape of the event and a ticket button.",
              body=body)

PROGRAMME = [
    ("09:30", "Doors and coffee", "The good coffee, from the cart outside, until it runs out at eleven."),
    ("10:00", "Opening: the ten-year codebase", "What survives, what gets rewritten, and how to tell the difference early."),
    ("11:30", "Three short talks", "Twenty minutes each, on testing, on hiring, and on saying no to a roadmap."),
    ("14:00", "Workshops", "Four rooms, sign up on the day, capped at thirty each."),
    ("17:00", "The bar", "Which is, historically, where the useful conversations happen."),
]
prelude = php_rows("unapp_programme", ("when", "title", "text"), PROGRAMME, "Programme item")
row = (columns([
    column(label(php("$unapp_slot['when']")), width="18%", vertical_align="top"),
    column(card_title(php("$unapp_slot['title']")) + "\n" +
           para(php("$unapp_slot['text']"), color="muted", size="small"),
           width="82%", vertical_align="top", gap="20"),
], gap=ROW_GAP, vertical_align="top", is_stacked=False) + "\n" + separator(style="wide", color="border"))
body = section_std(
    intro(eyebrow_text=t("Day one", "Section eyebrow label"),
          title=t("The programme"),
          lead=t("Day two is the same shape with different people. The full grid goes out a month before.")) + "\n" +
    group(loop("unapp_programme", "unapp_slot", row),
          layout="constrained", content_size=READ_WIDTH, gap=CARD_GAP),
    variation="is-style-section-soft")
write_pattern("events-programme", title="Event: programme", cats=EV + ", unapp_content",
              keywords="events, conference, programme, schedule, agenda, timetable",
              desc="A timed programme for one day, with a note about the other.",
              body=body, php_prelude=prelude)

SPEAKERS = [
    ("avatar-2", "Tess Oduya", "Principal engineer, Halden"),
    ("avatar-7", "Ben Marchetti", "CTO, Pier & Post"),
    ("avatar-10", "Amara Nwosu", "Head of platform, Verity"),
    ("avatar-5", "Joachim Reiss", "Author, The Long Rewrite"),
]
person = stack(
    avatar(php("get_theme_file_uri( 'assets/images/avatars/' . $unapp_speaker['image'] . '.svg' )"),
           php("$unapp_speaker['name']")) + "\n" +
    card_title(php("$unapp_speaker['name']")) + "\n" +
    label(php("$unapp_speaker['role']")),
    gap=CARD_GAP)
body = section_std(
    intro(eyebrow_text=t("Speaking", "Section eyebrow label"),
          title=t("Four of the sixteen"),
          lead=t("The rest are announced in February, once they have all said yes in writing.")) + "\n" +
    grid(loop("unapp_speakers", "unapp_speaker", person), cols=4))
write_pattern("events-speakers", title="Event: speakers", cats=EV + ", unapp_company, team",
              keywords="events, conference, speakers, line-up, talks",
              desc="Four announced speakers with their day jobs.",
              body=body,
              php_prelude=php_rows("unapp_speakers", ("image", "name", "role"), SPEAKERS, "Speaker"))

TICKETS = [
    ("Early", "£180", "Until 31 January, or until three hundred have gone.", ["Both days", "Lunch and coffee", "The bar"], False),
    ("Standard", "£240", "From February, and on the door if any are left.", ["Both days", "Lunch and coffee", "The bar", "Talk recordings"], True),
    ("Supported", "£0", "Thirty places, no questions asked beyond a sentence about why.", ["Both days", "Lunch and coffee", "Travel help if needed"], False),
]
prelude = "$unapp_tickets = array(\n"
for name, price, note, feats, featured in TICKETS:
    fl = ", ".join(f"_x( '{x}', 'Ticket feature', 'unapp' )" for x in feats)
    prelude += ("\tarray(\n"
                f"\t\t'name'     => _x( '{name}', 'Ticket name', 'unapp' ),\n"
                f"\t\t'price'    => _x( '{price}', 'Ticket price', 'unapp' ),\n"
                f"\t\t'note'     => _x( \"{note}\", 'Ticket note', 'unapp' ),\n"
                f"\t\t'featured' => {'true' if featured else 'false'},\n"
                f"\t\t'features' => array( {fl} ),\n"
                "\t),\n")
prelude += ");\n"
feature_list = ('<!-- wp:list {"className":"is-style-checklist"} -->\n<ul class="wp-block-list is-style-checklist">\n'
                "<?php foreach ( $unapp_ticket['features'] as $unapp_ticket_feature ) : ?>\n"
                '<!-- wp:list-item -->\n<li><?php echo esc_html( $unapp_ticket_feature ); ?></li>\n<!-- /wp:list-item -->\n'
                '<?php endforeach; ?>\n</ul>\n<!-- /wp:list -->')
inner = (card_title(php("$unapp_ticket['name']")) + "\n" +
         para(php("$unapp_ticket['price']"), size="xxx-large", weight="700", line_height="1") + "\n" +
         para(php("$unapp_ticket['note']"), color="muted", size="small") + "\n" +
         feature_list + "\n" +
         buttons([{"text": t("Buy"), "url": "#tickets", "width": 100}]))
ticket = ("<?php if ( $unapp_ticket['featured'] ) : ?>\n" + card(inner, variation="is-style-elevated")
          + "\n<?php else : ?>\n" + card(inner) + "\n<?php endif; ?>")
body = section_std(
    intro(eyebrow_text=t("Tickets", "Section eyebrow label"),
          title=t("Three prices, one of them nothing"),
          lead=t("Supported tickets come out of the standard ones. That is the whole funding model.")) + "\n" +
    grid(loop("unapp_tickets", "unapp_ticket", ticket), cols=3))
write_pattern("events-tickets", title="Event: tickets", cats=EV + ", unapp_pricing, pricing",
              keywords="events, tickets, pricing, conference, early bird",
              desc="Three ticket tiers including free supported places, with what each includes.",
              body=body, php_prelude=prelude)

body = section_std(
    split(
        eyebrow(t("Getting there", "Section eyebrow label"), align="left") + "\n" +
        heading(t("The Assembly Rooms, Bristol")) + "\n" +
        para(t("Ten minutes from Temple Meads on foot. Step-free throughout, a quiet room off the main hall, and live captions on every talk."),
             color="muted", size="large") + "\n" +
        para(t("hello@thelongrewrite.example"), color="muted") + "\n" +
        buttons([{"text": t("Accessibility and travel"), "url": "#access"}]),
        card(para(t("Dates"), weight="600") + "\n" +
             para(t("14–15 May 2027, 09:30 to 18:00 both days"), color="muted", size="small") + "\n" +
             separator(style="wide", color="border") + "\n" +
             para(t("Venue"), weight="600") + "\n" +
             para(t("The Assembly Rooms, Prince Street, Bristol BS1 4QD"), color="muted", size="small") + "\n" +
             separator(style="wide", color="border") + "\n" +
             para(t("Access"), weight="600") + "\n" +
             para(t("Step-free, hearing loop, live captions, quiet room"), color="muted", size="small")),
        align="top"),
    gap="0")
write_pattern("events-venue", title="Event: venue and access", cats=EV + ", unapp_utility, contact",
              keywords="events, venue, travel, access, accessibility, conference",
              desc="Where the event is, when, and what access provision it makes.",
              body=body)

print("batch 22 written: 5 education + 5 event patterns")
