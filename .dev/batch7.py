import os, sys; sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *

# ------------------------------------------------------------- contact split
body = section(
    columns([
        column(
            eyebrow(t("Contact", "Section eyebrow label"), align="left") + "\n" +
            heading(t("Tell us what you are building"), size="xx-large") + "\n" +
            para(t("Sales questions, security reviews or a bug you cannot shake — this reaches a person, not a queue."), color="muted", size="large") + "\n" +
            group(
                group(icon_badge("mail", bg="primary", pad=12) + "\n" +
                      group(para(t("Email"), weight="600", font="heading", size="small") + "\n" +
                            para('<a href="mailto:hello@example.com">hello@example.com</a>', color="muted", size="small"),
                            layout="default", gap="20"),
                      layout="flex", wrap="nowrap", gap="30", vertical_align="center") + "\n" +
                group(icon_badge("phone", bg="primary", pad=12) + "\n" +
                      group(para(t("Phone"), weight="600", font="heading", size="small") + "\n" +
                            para('<a href="tel:+1235235598">+1 235 2355 98</a>', color="muted", size="small"),
                            layout="default", gap="20"),
                      layout="flex", wrap="nowrap", gap="30", vertical_align="center") + "\n" +
                group(icon_badge("clock", bg="primary", pad=12) + "\n" +
                      group(para(t("Hours"), weight="600", font="heading", size="small") + "\n" +
                            para(t("Mon–Fri, 9:00–18:00 CET"), color="muted", size="small"),
                            layout="default", gap="20"),
                      layout="flex", wrap="nowrap", gap="30", vertical_align="center"),
                layout="default", gap="40"),
            width="45%", gap="30"),
        column(
            group(
                heading(t("Send a message"), level=3, size="large") + "\n" +
                para(t("Add your form plugin's block here — Contact Form 7, WPForms, Kali Forms and Gravity Forms all provide one."), color="muted", size="small") + "\n" +
                buttons([{"text": t("Email us instead"), "style": "is-style-outline", "width": 100}], margin={"top": "30"}),
                style_variation="is-style-card", radius="20px", layout="default", gap="30",
                pad={"top": "50", "bottom": "50", "left": "50", "right": "50"}),
            width="55%"),
    ], align="wide", gap="60"),
    pad=("70", "70"), gap="0")
write_pattern("contact-split", title="Contact: details and form", cats="unapp, unapp_utility, contact",
              keywords="contact, form, details, email, phone, split",
              desc="Contact details on the left and a card for your form plugin's block on the right.",
              body=body)

# ------------------------------------------------------------- feature checklist
body = section(
    columns([
        column(
            eyebrow(t("Why teams switch", "Section eyebrow label"), align="left") + "\n" +
            heading(t("Everything the old tool made hard"), size="xx-large") + "\n" +
            columns([
                column(lst([t("Unlimited projects"), t("Custom fields"), t("Offline mode"), t("Time tracking")])),
                column(lst([t("Guest access"), t("Automations"), t("Public roadmaps"), t("Audit logs")])),
            ], gap="40") + "\n" +
            buttons([{"text": t("See the full feature list"), "style": "is-style-outline"}], margin={"top": "30"}),
            width="55%", vertical_align="center", gap="30"),
        column(image(uri("assets/images/dashboard-3.avif"), tattr("Unapp project board"),
                     radius="20px", shadow="card"), width="45%", vertical_align="center"),
    ], align="wide", gap="60", vertical_align="center"),
    pad=("70", "70"), gap="0")
write_pattern("feature-checklist", title="Features: two-column checklist", cats="unapp, unapp_features, features",
              keywords="features, checklist, list, comparison, switch",
              desc="A double checklist of capabilities beside a product screenshot.",
              body=body)

# ------------------------------------------------------------- legal page body
prelude = """$unapp_sections = array(
	array(
		'title' => _x( 'Who we are', 'Legal section title', 'unapp' ),
		'text'  => _x( 'This policy explains what we collect, why we collect it and what you can ask us to do with it. Replace this placeholder with your own wording before you publish.', 'Legal section body', 'unapp' ),
	),
	array(
		'title' => _x( 'Information we collect', 'Legal section title', 'unapp' ),
		'text'  => _x( 'Account details you give us, usage data from the product itself, and the technical information your browser sends with every request.', 'Legal section body', 'unapp' ),
	),
	array(
		'title' => _x( 'How we use it', 'Legal section title', 'unapp' ),
		'text'  => _x( 'To run the service, to answer your questions and to work out which parts of the product deserve more attention. We do not sell it.', 'Legal section body', 'unapp' ),
	),
	array(
		'title' => _x( 'Your rights', 'Legal section title', 'unapp' ),
		'text'  => _x( 'You can ask for a copy of your data, ask us to correct it, or ask us to delete it. Write to the address at the bottom of this page and we will reply within 30 days.', 'Legal section body', 'unapp' ),
	),
);"""
sec = (heading("<?php echo esc_html( $unapp_section['title'] ); ?>", level=2, size="x-large") + "\n" +
       para("<?php echo esc_html( $unapp_section['text'] ); ?>", color="muted"))
body = section(
    group(
        para(t("Last updated: 12 August 2026"), color="muted", size="small", font="heading",
             letter="0.06em", transform="uppercase") + "\n" +
        heading(t("Privacy policy"), level=1, size="xxx-large") + "\n" +
        para(t("A plain-language summary of how this site handles your information."), color="muted", size="large"),
        layout="default", gap="20") + "\n" +
    separator(style="wide", color="border") + "\n" +
    group('<?php foreach ( $unapp_sections as $unapp_section ) : ?>\n' +
          group(sec, layout="default", gap="20") + '\n<?php endforeach; ?>',
          layout="default", gap="50"),
    pad=("60", "70"), gap="50", content_size="720px")
write_pattern("legal", title="Legal document", cats="unapp, unapp_utility, text",
              keywords="legal, privacy, terms, policy, document",
              desc="A readable legal page: title, last-updated line and numbered sections.",
              body=body, php_prelude=prelude, viewport=900)

# ------------------------------------------------------------- alternative header
body = group(
    group(
        '<!-- wp:site-logo {"width":40} /-->\n<!-- wp:site-title {"level":0,"textAlign":"center"} /-->',
        layout="flex", justify="center", wrap="nowrap", gap="20") + "\n" +
    '<!-- wp:navigation {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"blockGap":"var:preset|spacing|40"}}} /-->',
    align="full", bg="base", pad={"top": "40", "bottom": "40"}, gap="30", layout="constrained")
write_pattern("header-centered", title="Header: centred", cats="unapp, header",
              keywords="header, centered, logo, navigation, template part",
              desc="Centred site title above a centred menu — an alternative header template part.",
              body=body, block_types="core/template-part/header")

# ------------------------------------------------------------- slim footer
body = group(
    group(
        '<!-- wp:site-title {"level":0,"style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}}} /-->\n' +
        '<!-- wp:navigation {"overlayMenu":"never","layout":{"type":"flex"},"style":{"typography":{"textTransform":"none","letterSpacing":"0","fontWeight":"400"}},"fontFamily":"body","fontSize":"small"} -->\n'
        '<!-- wp:navigation-link {"label":"' + t("Privacy", ctx="Footer menu link").replace('"', "'") + '","url":"#"} /-->\n'
        '<!-- wp:navigation-link {"label":"' + t("Terms", ctx="Footer menu link").replace('"', "'") + '","url":"#"} /-->\n'
        '<!-- wp:navigation-link {"label":"' + t("Status", ctx="Footer menu link").replace('"', "'") + '","url":"#"} /-->\n'
        '<!-- /wp:navigation -->',
        layout="flex", wrap="wrap", justify="space-between", gap="40", align="wide") + "\n" +
    group(
        para("<?php printf( esc_html__( '© %1$s %2$s', 'unapp' ), esc_html( date_i18n( 'Y' ) ), esc_html( get_bloginfo( 'name' ) ) ); ?>",
             size="small", custom_color="rgba(255,255,255,0.75)") + "\n" +
        social([("x", "https://x.com"), ("linkedin", "https://linkedin.com"), ("github", "https://github.com")],
               size="has-small-icon-size", color="base", value="#ffffff"),
        layout="flex", wrap="wrap", justify="space-between", gap="30", align="wide"),
    align="full", bg="dark", text="base", pad={"top": "60", "bottom": "50"}, gap="40",
    layout="constrained",
    elements={"link": {"color": {"text": "var:preset|color|base"}, ":hover": {"color": {"text": "var:preset|color|secondary"}}}})
write_pattern("footer-slim", title="Footer: slim", cats="unapp, footer",
              keywords="footer, slim, simple, minimal, template part",
              desc="A one-row footer with the site title, a short menu, copyright and social links.",
              body=body, block_types="core/template-part/footer")
print("batch 7 written")
