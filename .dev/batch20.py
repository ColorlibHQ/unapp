"""Composition variety.

The library was consistent but narrow: almost every section was a centred
intro over an even grid. These are the shapes that were missing — asymmetric
splits, full-bleed bands, offset and overlapping elements, a sticky side-by-side
scroll, a marquee, tabs, a period toggle and editorial furniture.
"""
import os, sys; sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *
from batch13 import php_rows, loop

U = "unapp, unapp_features"

# ---------------------------------------------------------------- asymmetric feature
body = section_std(
    columns([
        column(
            eyebrow(t("Why teams move", "Section eyebrow label"), align="left") + "\n" +
            heading(t("One place to plan, decide and ship")) + "\n" +
            para(t("Everything about a project lives in one thread: the brief, the argument about the brief, the file that settled it and the date it shipped."),
                 color="muted", size="large"),
            width="38%", vertical_align="top", gap=STACK_GAP),
        column(
            columns([
                column(card(icon_badge("target", bg="primary") + "\n" +
                            card_title(t("Plans that survive contact")) + "\n" +
                            para(t("Move a date and everything downstream moves with it, including the people who need telling."),
                                 color="muted", size="small")),
                       gap=CARD_GAP),
                column(card(icon_badge("layers", bg="secondary") + "\n" +
                            card_title(t("Files where the decision was made")) + "\n" +
                            para(t("Attached to the thread that produced them, not buried in a drive nobody opens."),
                                 color="muted", size="small")),
                       gap=CARD_GAP),
            ], gap=ROW_GAP) + "\n" +
            card(icon_badge("activity", bg="accent") + "\n" +
                 card_title(t("A record you can hand to anyone")) + "\n" +
                 para(t("Every project writes its own history as it goes, so the handover is already written when someone leaves."),
                      color="muted", size="small")),
            width="62%", vertical_align="top", gap=ROW_GAP),
    ], align="wide", gap=SPLIT_GAP, vertical_align="top"))
write_pattern("features-offset", title="Features: asymmetric", cats=U,
              keywords="features, asymmetric, offset, cards, layout",
              desc="A narrow column of copy beside an offset stack of three cards — a change of shape from the even grid.",
              body=body)

# ---------------------------------------------------------------- full-bleed statement
body = section_std(
    group(
        eyebrow(t("The short version", "Section eyebrow label"), align="center", color="base") + "\n" +
        heading(t("Software should make the work smaller, not the week longer"),
                align="center", size="xxx-large", color="base", line_height="1.1") + "\n" +
        para(t("Everything else on this page is detail underneath that sentence."),
             align="center", custom_color="rgba(255,255,255,0.8)", size="large"),
        layout="constrained", content_size="900px", gap=STACK_GAP),
    bg="dark", text="base", pad=("80", "80"), gap="0",
    elements={"heading": {"color": {"text": "var:preset|color|base"}}})
write_pattern("statement", title="Statement", cats="unapp, unapp_content, text",
              keywords="statement, quote, manifesto, full width, dark",
              desc="One sentence, full bleed on the dark ground. A pause between two busy sections.",
              body=body)

# ---------------------------------------------------------------- sticky scroll
STEPS = [
    ("Capture", "Anything anyone says in passing goes in the same inbox, so it stops living in someone's head."),
    ("Decide", "Turn the noise into a short list with owners and dates. The rest is archived, not deleted."),
    ("Ship", "The plan updates itself as the work moves, and the people who need telling are told."),
]
prelude = php_rows("unapp_sticky_steps", ("title", "text"), STEPS, "Sticky step")
prelude += "$unapp_sticky_number = 0;\n"
step = card(
    label(php("str_pad( (string) ++$unapp_sticky_number, 2, '0', STR_PAD_LEFT )")) + "\n" +
    card_title(php("$unapp_sticky_step['title']")) + "\n" +
    para(php("$unapp_sticky_step['text']"), color="muted"))
body = section_std(
    columns([
        column(
            group(
                eyebrow(t("How it works", "Section eyebrow label"), align="left") + "\n" +
                heading(t("Three moves, and the second one is the hard one")) + "\n" +
                para(t("Scroll — the explanation keeps up on its own."), color="muted", size="large"),
                class_name="unapp-sticky", gap=STACK_GAP),
            width="44%", vertical_align="top"),
        column(loop("unapp_sticky_steps", "unapp_sticky_step", step),
               width="56%", vertical_align="top", gap=ROW_GAP),
    ], align="wide", gap=SPLIT_GAP, vertical_align="top"))
write_pattern("features-sticky", title="Features: sticky explanation", cats=U + ", steps",
              keywords="features, sticky, scroll, steps, side by side",
              desc="The explanation sticks while the steps scroll past it. Falls back to a plain two-column layout where sticky is unsupported.",
              body=body, php_prelude=prelude)

# ---------------------------------------------------------------- logo marquee
LOGOS = ['cobalt', 'foundry', 'harbor', 'kite', 'lumen', 'meridian', 'northwind', 'vertex']
marquee_items = "".join(
    '<!-- wp:image {"width":"128px","sizeSlug":"full","linkDestination":"none"} -->\n'
    f'<figure class="wp-block-image size-full is-resized"><img src="{uri("assets/images/logos/" + n + ".svg")}" '
    f'alt="" style="width:128px"/></figure>\n<!-- /wp:image -->\n' for n in LOGOS + LOGOS)
track = group(marquee_items, class_name="unapp-marquee__track", layout="flex", wrap="nowrap", gap="60")
body = section_std(
    para(t("Trusted by product teams at"), align="center", color="muted", size="small",
         weight="600", letter="0.08em", transform="uppercase") + "\n" +
    group(track, align="full", class_name="unapp-marquee", layout="constrained"),
    pad=("60", "60"), gap="40")
write_pattern("logo-marquee", title="Logo marquee", cats="unapp, unapp_proof, logos",
              keywords="logos, marquee, scrolling, customers, proof, ticker",
              desc="Customer logos scrolling slowly across the full width. Stops for anyone who prefers reduced motion.",
              body=body)

# ---------------------------------------------------------------- pricing period toggle
PLANS = [
    ("Free", "0", "0", "For one person keeping track of one thing.",
     ["Three projects", "Two weeks of history", "Community support"], False),
    ("Team", "12", "10", "For a team that has outgrown a group chat.",
     ["Unlimited projects", "Full history", "Roles and permissions", "Priority support"], True),
    ("Company", "24", "20", "For several teams that have to agree with each other.",
     ["Everything in Team", "Single sign-on", "Audit log", "A named contact"], False),
]
prelude = "$unapp_toggle_plans = array(\n"
for name, m, y, note, feats, featured in PLANS:
    fl = ", ".join(f"_x( '{x}', 'Plan feature', 'unapp' )" for x in feats)
    prelude += ("\tarray(\n"
                f"\t\t'name'     => _x( '{name}', 'Plan name', 'unapp' ),\n"
                f"\t\t'monthly'  => '{m}',\n"
                f"\t\t'yearly'   => '{y}',\n"
                f"\t\t'note'     => _x( '{note}', 'Plan note', 'unapp' ),\n"
                f"\t\t'featured' => {'true' if featured else 'false'},\n"
                f"\t\t'features' => array( {fl} ),\n"
                "\t),\n")
prelude += ");\n"
price = group(
    para('<span class="unapp-price__monthly">' + php("'$' . $unapp_toggle_plan['monthly']") + '</span>'
         '<span class="unapp-price__yearly">' + php("'$' . $unapp_toggle_plan['yearly']") + '</span>',
         size="xxx-large", weight="700", line_height="1", class_name="unapp-price") + "\n" +
    para(t("per person, per month", "Plan period"), color="muted", size="small"),
    layout="flex", orientation="horizontal", gap="20", vertical_align="bottom")
feature_list = ('<!-- wp:list {"className":"is-style-checklist"} -->\n<ul class="wp-block-list is-style-checklist">\n'
                "<?php foreach ( $unapp_toggle_plan['features'] as $unapp_toggle_feature ) : ?>\n"
                '<!-- wp:list-item -->\n<li><?php echo esc_html( $unapp_toggle_feature ); ?></li>\n<!-- /wp:list-item -->\n'
                '<?php endforeach; ?>\n</ul>\n<!-- /wp:list -->')
plan_inner = (card_title(php("$unapp_toggle_plan['name']")) + "\n" +
              para(php("$unapp_toggle_plan['note']"), color="muted", size="small") + "\n" +
              price + "\n" + feature_list + "\n" +
              buttons([{"text": t("Start free"), "url": "#start", "width": 100}]))
plan = ("<?php if ( $unapp_toggle_plan['featured'] ) : ?>\n"
        + card(plan_inner, variation="is-style-elevated")
        + "\n<?php else : ?>\n" + card(plan_inner) + "\n<?php endif; ?>")
switch = group(
    '<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->\n<div class="wp-block-buttons">\n'
    '<!-- wp:button {"className":"unapp-period is-style-outline","fontSize":"small"} -->\n'
    '<div class="wp-block-button has-custom-font-size unapp-period is-style-outline has-small-font-size">'
    '<a class="wp-block-button__link has-small-font-size has-custom-font-size wp-element-button" href="#">'
    '<span class="unapp-period__to-yearly">' + t("Show yearly prices") + '</span>'
    '<span class="unapp-period__to-monthly">' + t("Show monthly prices") + '</span>'
    '</a></div>\n<!-- /wp:button -->\n</div>\n<!-- /wp:buttons -->',
    layout="constrained")
body = section_std(
    intro(eyebrow_text=t("Pricing", "Section eyebrow label"),
          title=t("Two sizes of team, and one of you"),
          lead=t("Yearly is two months cheaper. No card to look around.")) + "\n" +
    switch + "\n" +
    grid(loop("unapp_toggle_plans", "unapp_toggle_plan", plan), cols=3),
    variation="is-style-section-soft")
write_pattern("pricing-toggle", title="Pricing: monthly and yearly", cats="unapp, unapp_pricing, pricing",
              keywords="pricing, toggle, monthly, yearly, plans, switch",
              desc="Three plans with a switch between monthly and yearly prices. Falls back to monthly with no JavaScript.",
              body=body, php_prelude=prelude)

# ---------------------------------------------------------------- editorial pull quote
body = section_std(
    group(
        para(t("&#8220;We stopped having the same meeting every Monday. That is the whole review.&#8221;"),
             align="center", size="xxx-large", line_height="1.25", font="heading") + "\n" +
        para(t("Elena Marsh · Head of Delivery, Northbank"), align="center", color="muted", size="small"),
        layout="constrained", content_size="820px", gap=STACK_GAP))
write_pattern("pull-quote", title="Pull quote", cats="unapp, unapp_proof, text",
              keywords="quote, pull quote, editorial, testimonial",
              desc="A single oversized quote set in the display face, for breaking up a long page.",
              body=body)

print("batch 20 written: 6 composition patterns")
