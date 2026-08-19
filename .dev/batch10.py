import os, sys; sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *

# ============================================================ FITNESS
F = "unapp, unapp_fitness, banner, featured"
inner = (eyebrow(t("First class free", "Fitness hero eyebrow"), align="center", color="base") + "\n" +
         heading(t("Stronger than last week"), align="center", color="base", size="xxx-large") + "\n" +
         para(t("Small-group strength and conditioning in the old print works. Forty-five minutes, no mirrors, no nonsense."),
              align="center", color="base", size="large") + "\n" +
         buttons([{"text": t("Book a free session"), "bg": "secondary", "color": "contrast"},
                  {"text": t("See the timetable"), "style": "is-style-outline", "color": "base"}],
                 justify="center", gap="30", margin={"top": "40"}))
cover = uri("assets/images/abstract/track.svg")
body = f'''<!-- wp:cover {{"url":"{cover}","dimRatio":60,"overlayColor":"contrast","isUserOverlayColor":true,"minHeight":70,"minHeightUnit":"vh","align":"full","style":{{"spacing":{{"padding":{{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}},"blockGap":"var:preset|spacing|30"}}}},"layout":{{"type":"constrained","contentSize":"760px"}}}} -->
<div class="wp-block-cover alignfull" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80);min-height:70vh"><span aria-hidden="true" class="wp-block-cover__background has-contrast-background-color has-background-dim-60 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="{cover}" data-object-fit="cover"/><div class="wp-block-cover__inner-container">
{inner}
</div></div>
<!-- /wp:cover -->'''
write_pattern("fitness-hero", title="Fitness: hero", cats=F,
              keywords="fitness, gym, hero, class, training, banner",
              desc="A high-contrast cover hero with a free-trial call to action.",
              body=body)

rows = [
    (t("Monday"), t("06:30 · Strength"), t("18:30 · Conditioning")),
    (t("Tuesday"), t("07:00 · Mobility"), t("19:00 · Strength")),
    (t("Wednesday"), t("06:30 · Conditioning"), t("18:30 · Open gym")),
    (t("Thursday"), t("07:00 · Strength"), t("19:00 · Intervals")),
    (t("Friday"), t("06:30 · Strength"), t("17:30 · Team workout")),
    (t("Saturday"), t("08:00 · Long session"), t("10:00 · Beginners")),
]
thead = "<tr><th>" + t("Day") + "</th><th>" + t("Morning") + "</th><th>" + t("Evening") + "</th></tr>"
tbody = "".join(f"<tr><td>{d}</td><td>{m}</td><td>{e}</td></tr>" for d, m, e in rows)
table = ('<!-- wp:table {"className":"is-style-compare"} -->\n'
         f'<figure class="wp-block-table is-style-compare"><table><thead>{thead}</thead><tbody>{tbody}</tbody></table></figure>\n'
         '<!-- /wp:table -->')
body = section(
    intro(eyebrow_text=t("Timetable", "Section eyebrow label"),
          title=t("This week at the studio"),
          lead=t("Twelve people per class. Book through the app up to seven days ahead.")) + "\n" +
    group(table, align="wide", layout="constrained") + "\n" +
    buttons([{"text": t("Book a class")}], justify="center"),
    pad=("70", "70"), gap="60")
write_pattern("fitness-schedule", title="Fitness: class timetable", cats="unapp, unapp_fitness, text",
              keywords="fitness, timetable, schedule, classes, gym",
              desc="A weekly class timetable using the Comparison table style.",
              body=body)

prelude = """$unapp_coaches = array(
	array( 'avatar' => 'avatar-6', 'name' => _x( 'Danny Osei', 'Coach name', 'unapp' ), 'role' => _x( 'Head coach · Strength', 'Coach role', 'unapp' ), 'bio' => _x( 'Fifteen years coaching, two of them with the national team.', 'Coach bio', 'unapp' ) ),
	array( 'avatar' => 'avatar-8', 'name' => _x( 'Sofia Marchetti', 'Coach name', 'unapp' ), 'role' => _x( 'Conditioning', 'Coach role', 'unapp' ), 'bio' => _x( 'Ex-rower. Believes everything is fixable with better breathing.', 'Coach bio', 'unapp' ) ),
	array( 'avatar' => 'avatar-9', 'name' => _x( 'Ruth Okonkwo', 'Coach name', 'unapp' ), 'role' => _x( 'Mobility and rehab', 'Coach role', 'unapp' ), 'bio' => _x( 'Physiotherapist who would rather you never needed a physiotherapist.', 'Coach bio', 'unapp' ) ),
);"""
coach = column(
    image("<?php echo esc_url( get_theme_file_uri( 'assets/images/avatars/' . $unapp_coach['avatar'] . '.svg' ) ); ?>",
          "<?php echo esc_attr( $unapp_coach['name'] ); ?>", radius="20px", aspect="1", scale="cover") + "\n" +
    heading("<?php echo esc_html( $unapp_coach['name'] ); ?>", level=3, size="large") + "\n" +
    para("<?php echo esc_html( $unapp_coach['role'] ); ?>", color="primary", size="small", weight="600", font="heading") + "\n" +
    para("<?php echo esc_html( $unapp_coach['bio'] ); ?>", color="muted", size="small"),
    gap="20")
body = section(
    intro(eyebrow_text=t("Coaches", "Section eyebrow label"),
          title=t("Who you will be training with")) + "\n" +
    columns([coach], align="wide", gap="50")
        .replace('<div class="wp-block-columns alignwide">\n',
                 '<div class="wp-block-columns alignwide">\n<?php foreach ( $unapp_coaches as $unapp_coach ) : ?>\n')
        .replace('\n</div>\n<!-- /wp:columns -->', '\n<?php endforeach; ?>\n</div>\n<!-- /wp:columns -->'),
    style_variation="is-style-section-soft", pad=("70", "70"), gap="60")
write_pattern("fitness-coaches", title="Fitness: coaches", cats="unapp, unapp_fitness, team",
              keywords="fitness, coaches, trainers, team, staff",
              desc="Three coaches with a portrait, discipline and a one-line biography.",
              body=body, php_prelude=prelude)

# ============================================================ FINANCE
FI = "unapp, unapp_finance, featured"
body = section(
    columns([
        column(
            eyebrow(t("Independent since 1998", "Finance hero eyebrow"), align="left") + "\n" +
            heading(t("Advice you could explain to your family"), size="xxx-large", line_height="1.1", align="left") + "\n" +
            para(t("Fee-only financial planning for people who would rather understand the plan than be impressed by it."), color="muted", size="large") + "\n" +
            buttons([{"text": t("Book an introduction")}, {"text": t("How we charge"), "style": "is-style-outline"}],
                    gap="30", margin={"top": "40"}) + "\n" +
            para(t("Regulated by the Financial Conduct Authority · No commission, ever"), color="muted", size="small"),
            width="52%", vertical_align="center", gap="30"),
        column(image(uri("assets/images/abstract/ledger.svg"), tattr("Planning document placeholder"),
                     radius="20px", shadow="card"), width="48%", vertical_align="center"),
    ], align="wide", gap="60", vertical_align="center"),
    pad=("70", "70"), gap="0")
write_pattern("finance-hero", title="Finance: trust-led hero", cats=FI + ", banner",
              keywords="finance, advisor, hero, planning, trust",
              desc="A restrained hero that leads with independence and a regulatory line.",
              body=body)

prelude = """$unapp_offer_list = array(
	array( 'icon' => 'target', 'title' => _x( 'Retirement planning', 'Finance service', 'unapp' ), 'text' => _x( 'What you can spend, when you can stop, and what happens if markets misbehave.', 'Finance service description', 'unapp' ) ),
	array( 'icon' => 'trending-up', 'title' => _x( 'Investment management', 'Finance service', 'unapp' ), 'text' => _x( 'Low-cost, globally diversified portfolios rebalanced on a schedule, not a hunch.', 'Finance service description', 'unapp' ) ),
	array( 'icon' => 'shield', 'title' => _x( 'Protection', 'Finance service', 'unapp' ), 'text' => _x( 'The cover you actually need, and an honest word about the cover you do not.', 'Finance service description', 'unapp' ) ),
	array( 'icon' => 'briefcase', 'title' => _x( 'Business owners', 'Finance service', 'unapp' ), 'text' => _x( 'Extracting profit, funding an exit and keeping the tax bill defensible.', 'Finance service description', 'unapp' ) ),
	array( 'icon' => 'key', 'title' => _x( 'Estate planning', 'Finance service', 'unapp' ), 'text' => _x( 'Wills, trusts and the conversations families keep postponing.', 'Finance service description', 'unapp' ) ),
	array( 'icon' => 'pie-chart', 'title' => _x( 'Cashflow modelling', 'Finance service', 'unapp' ), 'text' => _x( 'One picture of your money for the next thirty years, updated every year.', 'Finance service description', 'unapp' ) ),
);"""
tile = group(
    icon_badge_expr("$unapp_service_item['icon']", bg="primary", pad=12) + "\n" +
    heading("<?php echo esc_html( $unapp_service_item['title'] ); ?>", level=3, size="large") + "\n" +
    para("<?php echo esc_html( $unapp_service_item['text'] ); ?>", color="muted", size="small"),
    style_variation="is-style-card", radius="20px", gap="20", layout="flex", orientation="vertical",
    pad={"top": "50", "bottom": "50", "left": "50", "right": "50"})
body = section(
    intro(eyebrow_text=t("What we do", "Section eyebrow label"),
          title=t("Six ways we help"),
          lead=t("Most clients start with one and end up with a plan that covers all six.")) + "\n" +
    group('<?php foreach ( $unapp_offer_list as $unapp_service_item ) : ?>\n' + tile + '\n<?php endforeach; ?>',
          align="wide", layout="grid", gap="40", col_count=3, class_name="unapp-grid-3"),
    pad=("70", "70"), gap="60")
write_pattern("finance-services", title="Finance: services", cats=FI + ", services",
              keywords="finance, services, advice, planning, grid",
              desc="Six advisory services as icon cards in a three-column grid.",
              body=body, php_prelude=prelude)

metric = lambda v, l: column(
    para(v, align="center", size="xx-large", font="heading", weight="600", line_height="1.1", color="primary") + "\n" +
    para(l, align="center", color="muted", size="small"), gap="20")
body = section(
    columns([
        metric(t("28 years"), t("advising the same families")),
        metric(t("£410m"), t("under advice")),
        metric(t("0%"), t("commission taken")),
        metric(t("4.9/5"), t("client review score")),
    ], align="wide", gap="50"),
    style_variation="is-style-section-soft", pad=("70", "70"), gap="0")
write_pattern("finance-credentials", title="Finance: credentials", cats=FI + ", text",
              keywords="finance, credentials, trust, numbers, stats",
              desc="Four trust numbers in a quiet band — years, assets, commission, rating.",
              body=body)

body = section(
    group(
        para(t("Risk warning"), weight="600", font="heading", size="small",
             transform="uppercase", letter="0.08em", color="muted") + "\n" +
        para(t("The value of investments can fall as well as rise and you may get back less than you invested. Past performance is not a guide to future returns. Tax treatment depends on individual circumstances and may change. This page is a placeholder — replace it with your own regulated wording before publishing."),
             color="muted", size="small"),
        style_variation="is-style-outline", radius="20px", layout="default", gap="20",
        pad={"top": "50", "bottom": "50", "left": "50", "right": "50"}),
    pad=("70", "70"), gap="0", content_size="760px")
write_pattern("finance-disclaimer", title="Finance: risk warning", cats=FI + ", text",
              keywords="finance, disclaimer, risk, compliance, legal",
              desc="A bordered compliance note for regulated firms — replace with your own wording.",
              body=body)
print("batch 10 written")
