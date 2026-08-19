import os, sys; sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *

C = "unapp, unapp_hero, banner, featured"

# ---------------------------------------------------------------- hero-split
body = section(
    columns([
        column(
            eyebrow(t("New in 2.0","Hero eyebrow"), align="left") + "\n" +
            heading(t("Ship your product, not your project plan"), size="xxx-large", line_height="1.1", align="left") + "\n" +
            para(t("Unapp keeps roadmaps, files and conversations in one place, so the work moves forward while you sleep."), color="muted", size="large") + "\n" +
            buttons([{"text": t("Start free trial")}, {"text": t("Book a demo"), "style": "is-style-outline"}], gap="30", margin={"top": "40"}) + "\n" +
            para(t("No credit card required · 14-day trial"), color="muted", size="small"),
            width="46%", vertical_align="center", gap="30"),
        column(
            image(uri("assets/images/dashboard-1.avif"), tattr("Unapp dashboard with project analytics"),
                  radius="20px", shadow="card-strong"),
            width="54%", vertical_align="center"),
    ], align="wide", gap="60", vertical_align="center"),
    pad=("70", "70"), gap="60")
write_pattern("hero-split", title="Hero: split with screenshot", cats=C,
              keywords="hero, banner, split, screenshot, landing",
              desc="Two-column hero: copy and buttons on the left, a product screenshot on the right.",
              body=body)

# ---------------------------------------------------------------- hero-email
body = section(
    intro(eyebrow_text=t("Early access","Hero eyebrow"),
          title=t("The workspace your team will actually use"),
          lead=t("Join 10,000 teams planning, shipping and reporting in one calm place."),
          content="760px", margin_bottom=None) + "\n" +
    group(
        '<!-- wp:search {"label":"' + t("Email address", ctx="Hero email capture label").replace('"', "'") +
        '","showLabel":false,"placeholder":"' + tattr("you@company.com") +
        '","width":100,"widthUnit":"%","buttonText":"' + t("Get early access", ctx="Hero email capture button").replace('"', "'") +
        '","buttonPosition":"button-inside"} /-->',
        layout="constrained", content_size="460px") + "\n" +
    para(t("Free while in beta. Unsubscribe any time."), align="center", color="muted", size="small") + "\n" +
    image(uri("assets/images/dashboard-2.avif"), tattr("Unapp analytics dashboard"),
          align="wide", radius="20px", shadow="card-strong"),
    pad=("70", "70"), gap="40", content_size="760px", wide_size="1100px")
write_pattern("hero-email", title="Hero: email capture", cats=C,
              keywords="hero, email, signup, waitlist, capture, form",
              desc="Centred hero with an inline email capture field, a reassurance line and a product screenshot.",
              body=body)

# ---------------------------------------------------------------- hero-cover
inner_cover = (heading(t("Built for teams who ship every week"), align="center", color="base", size="xxx-large") + "\n" +
               para(t("From first sketch to release notes, Unapp keeps everyone pointed at the same goal."),
                    align="center", color="base", size="large") + "\n" +
               buttons([{"text": t("Get Premium"), "bg": "base", "color": "primary"},
                        {"text": t("Watch the tour"), "style": "is-style-outline", "color": "base"}],
                       justify="center", gap="30", margin={"top": "40"}))
cover_url = uri("assets/images/crowd.avif")
body = f'''<!-- wp:cover {{"url":"{cover_url}","dimRatio":80,"overlayColor":"contrast","isUserOverlayColor":true,"minHeight":72,"minHeightUnit":"vh","align":"full","style":{{"spacing":{{"padding":{{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}},"blockGap":"var:preset|spacing|30"}}}},"layout":{{"type":"constrained","contentSize":"780px"}}}} -->
<div class="wp-block-cover alignfull" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80);min-height:72vh"><span aria-hidden="true" class="wp-block-cover__background has-contrast-background-color has-background-dim-80 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="{cover_url}" data-object-fit="cover"/><div class="wp-block-cover__inner-container">
{inner_cover}
</div></div>
<!-- /wp:cover -->'''
write_pattern("hero-cover", title="Hero: photo background", cats=C,
              keywords="hero, cover, photo, image, banner, overlay",
              desc="Full-height Cover hero with a tinted photograph behind the headline and buttons.",
              body=body)

# ---------------------------------------------------------------- hero-minimal
body = section(
    intro(title=t("Documentation, changelog and everything in between"),
          lead=t("Everything you need to run Unapp day to day — guides, API reference and release notes."),
          content="720px") + "\n" +
    group(
        '<!-- wp:search {"label":"' + t("Search", ctx="Search form label").replace('"', "'") +
        '","showLabel":false,"placeholder":"' + tattr("Search the docs…") +
        '","width":100,"widthUnit":"%","buttonText":"' + t("Search", ctx="Search button text").replace('"', "'") +
        '","buttonUseIcon":true} /-->',
        layout="constrained", content_size="520px"),
    pad=("70", "60"), gap="40")
write_pattern("hero-minimal", title="Hero: heading and search", cats=C,
              keywords="hero, heading, search, docs, simple, minimal",
              desc="Compact type-only hero with a search field — good for documentation, help centres and blog homes.",
              body=body)

# ---------------------------------------------------------------- logo cloud
LOGOS = [("northwind","Northwind"),("vertex","Vertex"),("lumen","Lumen"),
         ("cobalt","Cobalt"),("harbor","Harbor"),("meridian","Meridian")]
prelude = """$unapp_logos = array(
	'northwind' => _x( 'Northwind', 'Placeholder customer name', 'unapp' ),
	'vertex'    => _x( 'Vertex', 'Placeholder customer name', 'unapp' ),
	'lumen'     => _x( 'Lumen', 'Placeholder customer name', 'unapp' ),
	'cobalt'    => _x( 'Cobalt', 'Placeholder customer name', 'unapp' ),
	'harbor'    => _x( 'Harbor', 'Placeholder customer name', 'unapp' ),
	'meridian'  => _x( 'Meridian', 'Placeholder customer name', 'unapp' ),
);"""
logo_loop = ('<?php foreach ( $unapp_logos as $unapp_slug => $unapp_name ) : ?>\n'
             + image("<?php echo esc_url( get_theme_file_uri( 'assets/images/logos/' . $unapp_slug . '.svg' ) ); ?>",
                     "<?php echo esc_attr( $unapp_name ); ?>", height="26px")
             + '\n<?php endforeach; ?>')
body = section(
    para(t("Trusted by product teams at"), align="center", color="muted", size="small",
         font="heading", weight="600", letter="0.12em", transform="uppercase") + "\n" +
    group(logo_loop, align="wide", layout="flex", justify="center", wrap="wrap", gap="60",
          class_name="unapp-logo-cloud"),
    pad=("60", "60"), gap="40")
write_pattern("logo-cloud", title="Logo cloud", cats="unapp, unapp_proof, featured, gallery",
              keywords="logos, customers, clients, trusted by, social proof",
              desc="A row of customer logos under a small label. Replace the placeholder marks with your own.",
              body=body, php_prelude=prelude)
print("batch 1 written")
