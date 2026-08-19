import sys; sys.path.insert(0,'.')
from pgen import *
COMPANY = "unapp, unapp_company, about, text"

# ------------------------------------------------------------- timeline
prelude = """$unapp_milestones = array(
	array( 'year' => '2021', 'title' => _x( 'Two people and a whiteboard', 'Milestone title', 'unapp' ), 'text' => _x( 'Unapp started as an internal tool for our own studio. It fitted on one screen and did three things well.', 'Milestone description', 'unapp' ) ),
	array( 'year' => '2023', 'title' => _x( 'Opened to everyone', 'Milestone title', 'unapp' ), 'text' => _x( 'The public beta filled in a week. We spent the year saying no to features and yes to speed.', 'Milestone description', 'unapp' ) ),
	array( 'year' => '2025', 'title' => _x( '10,000 teams', 'Milestone title', 'unapp' ), 'text' => _x( 'Teams in 40 countries now plan their week in Unapp, from two-person studios to listed companies.', 'Milestone description', 'unapp' ) ),
	array( 'year' => '2026', 'title' => _x( 'Built for the long run', 'Milestone title', 'unapp' ), 'text' => _x( 'Profitable, independent and still shipping every Friday.', 'Milestone description', 'unapp' ) ),
);"""
row = group(
    columns([
        column(para("<?php echo esc_html( $unapp_milestone['year'] ); ?>", size="x-large", font="heading",
                    weight="600", color="primary"), width="18%"),
        column(heading("<?php echo esc_html( $unapp_milestone['title'] ); ?>", level=3, size="large") + "\n" +
               para("<?php echo esc_html( $unapp_milestone['text'] ); ?>", color="muted"),
               width="82%", gap="20"),
    ], gap="40") + "\n" + separator(style="wide", color="border"),
    layout="default", gap="40", class_name="unapp-timeline-row")
body = section(
    intro(eyebrow_text=t("Our story", "Section eyebrow label"),
          title=t("Five years of shipping on Fridays"),
          lead=t("A short history of how Unapp got here.")) + "\n" +
    group('<?php foreach ( $unapp_milestones as $unapp_milestone ) : ?>\n' + row + '\n<?php endforeach; ?>',
          layout="constrained", content_size="820px", gap="50"),
    pad=("70", "70"), gap="60")
write_pattern("timeline", title="Timeline / milestones", cats=COMPANY,
              keywords="timeline, history, milestones, about, story, years",
              desc="A dated list of milestones with a rule between each entry.",
              body=body, php_prelude=prelude)

# ------------------------------------------------------------- values
prelude = """$unapp_values = array(
	array( 'icon' => 'compass', 'title' => _x( 'Opinionated, not rigid', 'Value title', 'unapp' ), 'text' => _x( 'We ship a strong default and let you change it. Blank screens help nobody.', 'Value description', 'unapp' ) ),
	array( 'icon' => 'heart', 'title' => _x( 'Support is everyone\\'s job', 'Value title', 'unapp' ), 'text' => _x( 'Every engineer spends a day a month answering tickets. It shows in the product.', 'Value description', 'unapp' ) ),
	array( 'icon' => 'eye', 'title' => _x( 'Work in the open', 'Value title', 'unapp' ), 'text' => _x( 'Public roadmap, public changelog, public post-mortems when we get it wrong.', 'Value description', 'unapp' ) ),
);"""
val_col = column(
    icon_badge_expr("$unapp_value['icon']", bg="secondary") + "\n" +
    heading("<?php echo esc_html( $unapp_value['title'] ); ?>", level=3, size="large") + "\n" +
    para("<?php echo esc_html( $unapp_value['text'] ); ?>", color="muted"),
    gap="30", layout="flex", orientation="vertical")
body = section(
    intro(eyebrow_text=t("What we believe", "Section eyebrow label"), title=t("Three rules we hold to")) + "\n" +
    columns([val_col], align="wide", gap="50")
        .replace('<div class="wp-block-columns alignwide">\n',
                 '<div class="wp-block-columns alignwide">\n<?php foreach ( $unapp_values as $unapp_value ) : ?>\n')
        .replace('\n</div>\n<!-- /wp:columns -->', '\n<?php endforeach; ?>\n</div>\n<!-- /wp:columns -->'),
    style_variation="is-style-section-soft", pad=("70", "70"), gap="60")
write_pattern("values", title="Company values", cats=COMPANY,
              keywords="values, principles, about, culture, icons",
              desc="Three value cards with icons — for an About or Careers page.",
              body=body, php_prelude=prelude)

# ------------------------------------------------------------- careers
prelude = """$unapp_roles = array(
	array( 'title' => _x( 'Senior Product Engineer', 'Job title', 'unapp' ), 'team' => _x( 'Engineering', 'Job team', 'unapp' ), 'place' => _x( 'Remote · Europe', 'Job location', 'unapp' ) ),
	array( 'title' => _x( 'Product Designer', 'Job title', 'unapp' ), 'team' => _x( 'Design', 'Job team', 'unapp' ), 'place' => _x( 'Berlin or remote', 'Job location', 'unapp' ) ),
	array( 'title' => _x( 'Customer Engineer', 'Job title', 'unapp' ), 'team' => _x( 'Support', 'Job team', 'unapp' ), 'place' => _x( 'Remote · Americas', 'Job location', 'unapp' ) ),
	array( 'title' => _x( 'Technical Writer', 'Job title', 'unapp' ), 'team' => _x( 'Documentation', 'Job team', 'unapp' ), 'place' => _x( 'Part-time · Remote', 'Job location', 'unapp' ) ),
);"""
role_row = group(
    columns([
        column(heading("<?php echo esc_html( $unapp_role['title'] ); ?>", level=3, size="large") + "\n" +
               para("<?php echo esc_html( $unapp_role['team'] . ' · ' . $unapp_role['place'] ); ?>",
                    color="muted", size="small"), width="70%", vertical_align="center", gap="20"),
        column(buttons([{"text": t("View role"), "style": "is-style-outline"}], justify="right"),
               width="30%", vertical_align="center"),
    ], gap="40", vertical_align="center"),
    style_variation="is-style-card", radius="16px", layout="default",
    pad={"top": "40", "bottom": "40", "left": "50", "right": "50"})
body = section(
    intro(eyebrow_text=t("Careers", "Section eyebrow label"),
          title=t("Open roles"),
          lead=t("We hire slowly, pay at the top of the band and work asynchronously.")) + "\n" +
    group('<?php foreach ( $unapp_roles as $unapp_role ) : ?>\n' + role_row + '\n<?php endforeach; ?>',
          align="wide", layout="constrained", gap="30") + "\n" +
    para(t("Nothing that fits? Send us a note anyway — we keep good people in mind."),
         align="center", color="muted"),
    pad=("70", "70"), gap="50")
write_pattern("careers", title="Open positions", cats=COMPANY,
              keywords="careers, jobs, hiring, roles, positions, list",
              desc="A list of open roles with team, location and a link on each row.",
              body=body, php_prelude=prelude)

# ------------------------------------------------------------- offices
prelude = """$unapp_offices = array(
	array( 'city' => _x( 'Berlin', 'Office city', 'unapp' ), 'address' => _x( 'Prenzlauer Allee 12<br>10405 Berlin, Germany', 'Office address', 'unapp' ) ),
	array( 'city' => _x( 'New York', 'Office city', 'unapp' ), 'address' => _x( '198 West 21th Street, Suite 721<br>New York, NY 10016', 'Office address', 'unapp' ) ),
	array( 'city' => _x( 'Melbourne', 'Office city', 'unapp' ), 'address' => _x( '45 Collins Street<br>Melbourne VIC 3000, Australia', 'Office address', 'unapp' ) ),
);"""
office_col = column(
    icon_badge("map-pin", bg="primary") + "\n" +
    heading("<?php echo esc_html( $unapp_office['city'] ); ?>", level=3, size="large") + "\n" +
    para("<?php echo wp_kses( $unapp_office['address'], array( 'br' => array() ) ); ?>", color="muted"),
    gap="30", layout="flex", orientation="vertical")
body = section(
    intro(eyebrow_text=t("Offices", "Section eyebrow label"),
          title=t("Where you will find us"),
          lead=t("Remote-first, with three places to drop in for coffee.")) + "\n" +
    columns([office_col], align="wide", gap="50")
        .replace('<div class="wp-block-columns alignwide">\n',
                 '<div class="wp-block-columns alignwide">\n<?php foreach ( $unapp_offices as $unapp_office ) : ?>\n')
        .replace('\n</div>\n<!-- /wp:columns -->', '\n<?php endforeach; ?>\n</div>\n<!-- /wp:columns -->'),
    pad=("70", "70"), gap="60")
write_pattern("offices", title="Office locations", cats=COMPANY,
              keywords="offices, locations, addresses, contact, cities",
              desc="Three office locations with pin icons and addresses.",
              body=body, php_prelude=prelude)

# ------------------------------------------------------------- press
prelude = """$unapp_press = array(
	array( 'logo' => 'foundry', 'quote' => _x( '“The most opinionated project tool since the original Basecamp — and that is a compliment.”', 'Press quote', 'unapp' ) ),
	array( 'logo' => 'kite', 'quote' => _x( '“Unapp has quietly become the default for small product teams who hate ceremony.”', 'Press quote', 'unapp' ) ),
	array( 'logo' => 'meridian', 'quote' => _x( '“Reporting that a chief executive can read without a translator.”', 'Press quote', 'unapp' ) ),
);"""
press_col = column(
    image("<?php echo esc_url( get_theme_file_uri( 'assets/images/logos/' . $unapp_item['logo'] . '.svg' ) ); ?>",
          "<?php echo esc_attr( $unapp_item['logo'] ); ?>", height="24px") + "\n" +
    para("<?php echo esc_html( $unapp_item['quote'] ); ?>", size="large", line_height="1.5"),
    gap="30")
body = section(
    intro(eyebrow_text=t("Press", "Section eyebrow label"), title=t("What people are writing")) + "\n" +
    columns([press_col], align="wide", gap="50")
        .replace('<div class="wp-block-columns alignwide">\n',
                 '<div class="wp-block-columns alignwide">\n<?php foreach ( $unapp_press as $unapp_item ) : ?>\n')
        .replace('\n</div>\n<!-- /wp:columns -->', '\n<?php endforeach; ?>\n</div>\n<!-- /wp:columns -->'),
    style_variation="is-style-section-soft", pad=("70", "70"), gap="60")
write_pattern("press", title="Press mentions", cats="unapp, unapp_proof, about",
              keywords="press, media, mentions, quotes, coverage",
              desc="Publication logos with a pull quote from each.",
              body=body, php_prelude=prelude)

# ------------------------------------------------------------- security / trust
prelude = """$unapp_trust = array(
	array( 'icon' => 'shield', 'title' => _x( 'SOC 2 Type II', 'Trust item title', 'unapp' ), 'text' => _x( 'Audited annually. Report available under NDA.', 'Trust item description', 'unapp' ) ),
	array( 'icon' => 'lock', 'title' => _x( 'Encrypted throughout', 'Trust item title', 'unapp' ), 'text' => _x( 'TLS 1.3 in transit, AES-256 at rest, keys rotated quarterly.', 'Trust item description', 'unapp' ) ),
	array( 'icon' => 'globe', 'title' => _x( 'Data residency', 'Trust item title', 'unapp' ), 'text' => _x( 'Choose the EU, the US or Australia. Data never leaves the region.', 'Trust item description', 'unapp' ) ),
	array( 'icon' => 'user-check', 'title' => _x( 'SSO and SCIM', 'Trust item title', 'unapp' ), 'text' => _x( 'Okta, Entra ID and Google Workspace, with automated provisioning.', 'Trust item description', 'unapp' ) ),
);"""
trust_tile = group(
    icon_badge_expr("$unapp_trust_item['icon']", bg="primary", pad=12) + "\n" +
    heading("<?php echo esc_html( $unapp_trust_item['title'] ); ?>", level=3, size="medium") + "\n" +
    para("<?php echo esc_html( $unapp_trust_item['text'] ); ?>", color="muted", size="small"),
    style_variation="is-style-card", radius="16px", gap="20", layout="flex", orientation="vertical",
    pad={"top": "40", "bottom": "40", "left": "40", "right": "40"})
body = section(
    intro(eyebrow_text=t("Security", "Section eyebrow label"),
          title=t("Boring where it counts"),
          lead=t("The controls your security review will ask about, ready before you do.")) + "\n" +
    group('<?php foreach ( $unapp_trust as $unapp_trust_item ) : ?>\n' + trust_tile + '\n<?php endforeach; ?>',
          align="wide", layout="grid", gap="40", col_count=4, class_name="unapp-grid-4") + "\n" +
    buttons([{"text": t("Read the security overview"), "style": "is-style-outline"}], justify="center"),
    pad=("70", "70"), gap="50")
write_pattern("security", title="Security and compliance", cats="unapp, unapp_features, featured",
              keywords="security, compliance, trust, soc2, encryption, sso",
              desc="A grid of trust and compliance points — useful on pricing and enterprise pages.",
              body=body, php_prelude=prelude)
print("batch 5 written")
