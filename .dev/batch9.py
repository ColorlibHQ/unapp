import os, sys; sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *

# ============================================================ PORTFOLIO
P = "unapp, unapp_portfolio, portfolio, featured"

body = section(
    columns([
        column(
            eyebrow(t("Designer & art director", "Portfolio eyebrow"), align="left") + "\n" +
            heading(t("Mara Lindqvist"), size="xxx-large", line_height="1.05", align="left") + "\n" +
            para(t("I help small teams look like the companies they are becoming — identity, packaging and the odd website."), color="muted", size="large") + "\n" +
            buttons([{"text": t("See selected work"), "style": "is-style-arrow"}], margin={"top": "30"}),
            width="52%", vertical_align="center", gap="30"),
        column(image(uri("assets/images/abstract/studio-2.svg"), tattr("Portrait placeholder"), radius="20px"),
               width="48%", vertical_align="center"),
    ], align="wide", gap="60", vertical_align="center"),
    pad=("70", "70"), gap="0")
write_pattern("portfolio-hero", title="Portfolio: introduction", cats=P,
              keywords="portfolio, hero, designer, introduction, personal",
              desc="A personal introduction with a portrait — name, discipline and a one-line statement.",
              body=body)

prelude = """$unapp_projects = array(
	array( 'image' => 'studio-1', 'title' => _x( 'Nordwell Coffee', 'Project title', 'unapp' ), 'meta' => _x( 'Identity · Packaging · 2026', 'Project meta', 'unapp' ) ),
	array( 'image' => 'desk', 'title' => _x( 'Fold Studio', 'Project title', 'unapp' ), 'meta' => _x( 'Website · Art direction · 2025', 'Project meta', 'unapp' ) ),
	array( 'image' => 'skyline', 'title' => _x( 'Meridian Housing', 'Project title', 'unapp' ), 'meta' => _x( 'Brand system · 2025', 'Project meta', 'unapp' ) ),
	array( 'image' => 'gathering', 'title' => _x( 'Common Ground', 'Project title', 'unapp' ), 'meta' => _x( 'Campaign · 2024', 'Project meta', 'unapp' ) ),
);"""
project = column(
    image("<?php echo esc_url( get_theme_file_uri( 'assets/images/abstract/' . $unapp_project['image'] . '.svg' ) ); ?>",
          "<?php echo esc_attr( $unapp_project['title'] ); ?>", radius="20px", aspect="4/3", scale="cover") + "\n" +
    heading("<?php echo esc_html( $unapp_project['title'] ); ?>", level=3, size="large") + "\n" +
    para("<?php echo esc_html( $unapp_project['meta'] ); ?>", color="muted", size="small"),
    gap="20")
body = section(
    intro(eyebrow_text=t("Selected work", "Section eyebrow label"), title=t("Recent projects")) + "\n" +
    columns([project], align="wide", gap="40")
        .replace('<div class="wp-block-columns alignwide">\n',
                 '<div class="wp-block-columns alignwide">\n<?php foreach ( array_slice( $unapp_projects, 0, 2 ) as $unapp_project ) : ?>\n')
        .replace('\n</div>\n<!-- /wp:columns -->', '\n<?php endforeach; ?>\n</div>\n<!-- /wp:columns -->') + "\n" +
    columns([project], align="wide", gap="40")
        .replace('<div class="wp-block-columns alignwide">\n',
                 '<div class="wp-block-columns alignwide">\n<?php foreach ( array_slice( $unapp_projects, 2, 2 ) as $unapp_project ) : ?>\n')
        .replace('\n</div>\n<!-- /wp:columns -->', '\n<?php endforeach; ?>\n</div>\n<!-- /wp:columns -->'),
    pad=("70", "70"), gap="60")
write_pattern("portfolio-work", title="Portfolio: work grid", cats=P,
              keywords="portfolio, work, projects, grid, gallery, case studies",
              desc="Four projects in a two-by-two grid with title and credits under each.",
              body=body, php_prelude=prelude)

body = section(
    columns([
        column(image(uri("assets/images/abstract/desk.svg"), tattr("Studio placeholder"), radius="20px"),
               width="45%", vertical_align="center"),
        column(
            eyebrow(t("About", "Section eyebrow label"), align="left") + "\n" +
            heading(t("Fifteen years, three cities, one obsession")) + "\n" +
            para(t("I started in editorial, spent a decade in agencies and now work directly with founders. Most projects run six to ten weeks, start to finish."), color="muted", size="large") + "\n" +
            columns([
                column(lst([t("Brand identity"), t("Packaging"), t("Art direction")], style="dash")),
                column(lst([t("Web design"), t("Typography"), t("Print")], style="dash")),
            ], gap="40"),
            width="55%", vertical_align="center", gap="30"),
    ], align="wide", gap="60", vertical_align="center"),
    style_variation="is-style-section-soft", pad=("70", "70"), gap="0")
write_pattern("portfolio-about", title="Portfolio: about", cats=P,
              keywords="portfolio, about, bio, skills, designer",
              desc="A short biography beside a photograph, with two columns of disciplines.",
              body=body)

prelude = """$unapp_offers = array(
	array( 'title' => _x( 'Identity', 'Service title', 'unapp' ), 'price' => _x( 'from £6,000', 'Service price', 'unapp' ), 'text' => _x( 'Naming, marks, type and a system your team can actually use.', 'Service description', 'unapp' ) ),
	array( 'title' => _x( 'Website', 'Service title', 'unapp' ), 'price' => _x( 'from £9,000', 'Service price', 'unapp' ), 'text' => _x( 'Design and build, from the first sketch to the day it ships.', 'Service description', 'unapp' ) ),
	array( 'title' => _x( 'Art direction', 'Service title', 'unapp' ), 'price' => _x( 'day rate', 'Service price', 'unapp' ), 'text' => _x( 'Photography, campaigns and the taste to say no to the wrong idea.', 'Service description', 'unapp' ) ),
);"""
offer = column(
    heading("<?php echo esc_html( $unapp_offer['title'] ); ?>", level=3, size="large") + "\n" +
    para("<?php echo esc_html( $unapp_offer['price'] ); ?>", color="primary", font="heading", weight="600", size="small") + "\n" +
    para("<?php echo esc_html( $unapp_offer['text'] ); ?>", color="muted"),
    gap="20")
body = section(
    intro(eyebrow_text=t("Services", "Section eyebrow label"), title=t("How we can work together")) + "\n" +
    columns([offer], align="wide", gap="40", class_name="is-style-divided")
        .replace('<div class="wp-block-columns alignwide is-style-divided">\n',
                 '<div class="wp-block-columns alignwide is-style-divided">\n<?php foreach ( $unapp_offers as $unapp_offer ) : ?>\n')
        .replace('\n</div>\n<!-- /wp:columns -->', '\n<?php endforeach; ?>\n</div>\n<!-- /wp:columns -->'),
    pad=("70", "70"), gap="60")
write_pattern("portfolio-services", title="Portfolio: services and rates", cats=P,
              keywords="portfolio, services, rates, pricing, freelance",
              desc="Three services with an indicative price, separated by hairline rules.",
              body=body, php_prelude=prelude)

# ============================================================ CHURCH
C = "unapp, unapp_church, banner, featured"
inner = (eyebrow(t("Everyone is welcome", "Church hero eyebrow"), align="center", color="base") + "\n" +
         heading(t("A church for people who are not sure about church"), align="center", color="base", size="xxx-large") + "\n" +
         para(t("Sundays at 9:30 and 11:15 · Coffee from 9:00 · Kids welcome in every service"), align="center", color="base", size="large") + "\n" +
         buttons([{"text": t("Plan your visit"), "bg": "base", "color": "primary"},
                  {"text": t("Watch online"), "style": "is-style-outline", "color": "base"}],
                 justify="center", gap="30", margin={"top": "40"}))
cover_url = uri("assets/images/abstract/sanctuary.svg")
body = f'''<!-- wp:cover {{"url":"{cover_url}","dimRatio":70,"overlayColor":"contrast","isUserOverlayColor":true,"minHeight":64,"minHeightUnit":"vh","align":"full","style":{{"spacing":{{"padding":{{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}},"blockGap":"var:preset|spacing|30"}}}},"layout":{{"type":"constrained","contentSize":"780px"}}}} -->
<div class="wp-block-cover alignfull" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80);min-height:64vh"><span aria-hidden="true" class="wp-block-cover__background has-contrast-background-color has-background-dim-70 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="{cover_url}" data-object-fit="cover"/><div class="wp-block-cover__inner-container">
{inner}
</div></div>
<!-- /wp:cover -->'''
write_pattern("church-hero", title="Church: welcome", cats=C,
              keywords="church, welcome, hero, service times, visit",
              desc="A welcoming cover with service times and two calls to action.",
              body=body)

prelude = """$unapp_services_times = array(
	array( 'when' => _x( 'Sunday 9:30', 'Service time', 'unapp' ), 'what' => _x( 'Traditional service', 'Service name', 'unapp' ), 'note' => _x( 'Hymns, choir and communion in the main hall.', 'Service note', 'unapp' ) ),
	array( 'when' => _x( 'Sunday 11:15', 'Service time', 'unapp' ), 'what' => _x( 'Contemporary service', 'Service name', 'unapp' ), 'note' => _x( 'Band-led worship with children\\'s groups running alongside.', 'Service note', 'unapp' ) ),
	array( 'when' => _x( 'Wednesday 19:00', 'Service time', 'unapp' ), 'what' => _x( 'Midweek prayer', 'Service name', 'unapp' ), 'note' => _x( 'A quiet hour in the chapel. Come and go as you need.', 'Service note', 'unapp' ) ),
);"""
row = group(
    columns([
        column(para("<?php echo esc_html( $unapp_time['when'] ); ?>", size="large", font="heading",
                    weight="600", color="primary"), width="28%", vertical_align="center"),
        column(heading("<?php echo esc_html( $unapp_time['what'] ); ?>", level=3, size="large") + "\n" +
               para("<?php echo esc_html( $unapp_time['note'] ); ?>", color="muted", size="small"),
               width="72%", vertical_align="center", gap="20"),
    ], gap="40", vertical_align="center") + "\n" + separator(style="wide", color="border"),
    layout="default", gap="30")
body = section(
    intro(eyebrow_text=t("Service times", "Section eyebrow label"),
          title=t("When we gather"),
          lead=t("Come as you are. Every service is signed and the building is step-free.")) + "\n" +
    group('<?php foreach ( $unapp_services_times as $unapp_time ) : ?>\n' + row + '\n<?php endforeach; ?>',
          layout="constrained", content_size="760px", gap="40"),
    style_variation="is-style-section-soft", pad=("70", "70"), gap="60")
write_pattern("church-times", title="Church: service times", cats="unapp, unapp_church, text",
              keywords="church, service times, schedule, worship, sunday",
              desc="Service times with a short description of each gathering.",
              body=body, php_prelude=prelude)

prelude = """$unapp_ministries = array(
	array( 'icon' => 'users', 'title' => _x( 'Children and youth', 'Ministry title', 'unapp' ), 'text' => _x( 'Groups for every age from crèche to sixth form, every Sunday morning.', 'Ministry description', 'unapp' ) ),
	array( 'icon' => 'heart', 'title' => _x( 'Care and support', 'Ministry title', 'unapp' ), 'text' => _x( 'Meals, lifts and a listening ear when life gets heavy.', 'Ministry description', 'unapp' ) ),
	array( 'icon' => 'book-open', 'title' => _x( 'Small groups', 'Ministry title', 'unapp' ), 'text' => _x( 'Twenty groups meeting in homes across the city through the week.', 'Ministry description', 'unapp' ) ),
	array( 'icon' => 'globe', 'title' => _x( 'Community work', 'Ministry title', 'unapp' ), 'text' => _x( 'The food bank, the night shelter and the debt advice centre.', 'Ministry description', 'unapp' ) ),
);"""
tile = group(
    icon_badge_expr("$unapp_ministry['icon']", bg="primary", pad=12) + "\n" +
    heading("<?php echo esc_html( $unapp_ministry['title'] ); ?>", level=3, size="large") + "\n" +
    para("<?php echo esc_html( $unapp_ministry['text'] ); ?>", color="muted", size="small"),
    style_variation="is-style-card", radius="20px", gap="20", layout="flex", orientation="vertical",
    pad={"top": "50", "bottom": "50", "left": "50", "right": "50"})
body = section(
    intro(eyebrow_text=t("Life together", "Section eyebrow label"),
          title=t("More than a Sunday"),
          lead=t("There is a place for you here on the other six days too.")) + "\n" +
    group('<?php foreach ( $unapp_ministries as $unapp_ministry ) : ?>\n' + tile + '\n<?php endforeach; ?>',
          align="wide", layout="grid", gap="40", col_count=4, class_name="unapp-grid-4"),
    pad=("70", "70"), gap="60")
write_pattern("church-ministries", title="Church: ministries", cats=C,
              keywords="church, ministries, groups, community, grid",
              desc="Four ministries or groups as icon cards.",
              body=body, php_prelude=prelude)

body = section(
    columns([
        column(
            eyebrow(t("Giving", "Section eyebrow label"), align="left", color="base") + "\n" +
            heading(t("Every gift stays close to home"), size="xx-large", color="base") + "\n" +
            para(t("Two thirds of what is given funds the food bank, the night shelter and the debt advice centre. The rest keeps the lights on."), color="base", size="large"),
            width="62%", vertical_align="center", gap="20"),
        column(buttons([{"text": t("Give once"), "bg": "base", "color": "primary"},
                        {"text": t("Set up monthly"), "style": "is-style-outline", "color": "base"}],
                       justify="right", gap="30"), width="38%", vertical_align="center"),
    ], align="wide", gap="50", vertical_align="center"),
    style_variation="is-style-section-gradient", pad=("70", "70"), gap="0")
write_pattern("church-giving", title="Church: giving", cats="unapp, unapp_church, call-to-action",
              keywords="church, giving, donate, offering, tithe",
              desc="A giving band that says where the money goes, with two donation buttons.",
              body=body)
print("batch 9 written")
