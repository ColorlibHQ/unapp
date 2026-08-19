import os, sys; sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *
FEAT = "unapp, unapp_features, features, columns"

# ------------------------------------------------------------- features zig-zag
def zig(img_file, alt, eyebrow_text, title, text, bullets, reverse=False):
    media = column(image(uri(f"assets/images/{img_file}"), tattr(alt), radius="16px", shadow="card"),
                   width="52%", vertical_align="center")
    copy = column(eyebrow(eyebrow_text, align="left") + "\n" + heading(title, level=3, size="x-large") + "\n" +
                  para(text, color="muted", size="large") + "\n" + lst(bullets),
                  width="48%", vertical_align="center", gap="30")
    cols = [copy, media] if reverse else [media, copy]
    return columns(cols, align="wide", gap="60", vertical_align="center")

body = section(
    intro(eyebrow_text=t("Product tour", "Section eyebrow label"),
          title=t("Everything in one place, finally"),
          lead=t("Three views of the same work — plan it, build it, report on it.")) + "\n" +
    zig("dashboard-1.avif", "Planning board in Unapp", t("Plan", "Feature eyebrow"),
        t("Plan the quarter in an afternoon"),
        t("Drag work into place, set a target date and let Unapp keep the roadmap honest as things move."),
        [t("Roadmap, board and timeline from one backlog"), t("Automatic capacity warnings"), t("Shareable read-only views")]) + "\n" +
    zig("dashboard-2.avif", "Analytics view in Unapp", t("Measure", "Feature eyebrow"),
        t("Reports that answer the real question"),
        t("Velocity, workload and progress are calculated as you work — no spreadsheet exports, no Friday scramble."),
        [t("Live dashboards for every team"), t("Weekly digests by email"), t("Export to CSV or the API")], reverse=True),
    pad=("70", "70"), gap="70")
write_pattern("features-zigzag", title="Features: alternating rows", cats=FEAT,
              keywords="features, alternating, zigzag, media text, product tour",
              desc="Two alternating image-and-text rows with checklists — the classic product tour layout.",
              body=body)

# ------------------------------------------------------------- bento grid
def bento_cell(icon, title, text, *, bg=None, style="is-style-card", width=None, big=False):
    inner = (icon_badge(icon, bg="primary" if not big else "secondary") + "\n" +
             heading(title, level=3, size="large" if not big else "x-large") + "\n" +
             para(text, color="muted"))
    return column(group(inner, style_variation=style, radius="20px", gap="30",
                        layout="flex", orientation="vertical"),
                  width=width)

body = section(
    intro(eyebrow_text=t("Platform", "Section eyebrow label"),
          title=t("A toolkit, not a straitjacket"),
          lead=t("Start with the basics and switch on the rest when your team is ready.")) + "\n" +
    columns([
        bento_cell("layers", t("Unlimited projects"), t("Group work by product, client or squad — nesting included."), width="58%", big=True),
        bento_cell("zap", t("Instant search"), t("Find any task, file or comment as fast as you can type."), width="42%"),
    ], align="wide", gap="40") + "\n" +
    columns([
        bento_cell("git-branch", t("Developer friendly"), t("Branches, commits and pull requests attach themselves to the right task.")),
        bento_cell("pie-chart", t("Live reporting"), t("Dashboards update as work moves. No exports.")),
        bento_cell("lock", t("Enterprise ready"), t("SSO, audit logs and regional data residency.")),
    ], align="wide", gap="40"),
    pad=("70", "70"), gap="40")
write_pattern("features-bento", title="Features: bento grid", cats=FEAT,
              keywords="features, bento, grid, cards, platform",
              desc="An asymmetric grid of feature cards — one wide cell above three equal ones.",
              body=body)

# ------------------------------------------------------------- how it works
prelude = """$unapp_steps = array(
	array(
		'title' => _x( 'Import your work', 'Step title', 'unapp' ),
		'text'  => _x( 'Bring boards, issues and documents across from the tools you already use. Nothing is lost in translation.', 'Step description', 'unapp' ),
	),
	array(
		'title' => _x( 'Invite the team', 'Step title', 'unapp' ),
		'text'  => _x( 'Roles, permissions and notification rules are set once and inherited by every new project.', 'Step description', 'unapp' ),
	),
	array(
		'title' => _x( 'Ship, then report', 'Step title', 'unapp' ),
		'text'  => _x( 'Progress rolls up automatically, so status meetings turn into two-minute reads.', 'Step description', 'unapp' ),
	),
);"""
step_col = column(
    para("<?php echo esc_html( str_pad( (string) ( $unapp_index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?>",
         size="xx-large", font="heading", weight="600", line_height="1", color="primary") + "\n" +
    heading("<?php echo esc_html( $unapp_step['title'] ); ?>", level=3, size="large") + "\n" +
    para("<?php echo esc_html( $unapp_step['text'] ); ?>", color="muted"),
    gap="20")
body = section(
    intro(eyebrow_text=t("How it works", "Section eyebrow label"),
          title=t("Live in three steps"),
          lead=t("Most teams are running their first sprint in Unapp before lunch.")) + "\n" +
    columns([step_col], align="wide", gap="50")
        .replace('<div class="wp-block-columns alignwide">\n',
                 '<div class="wp-block-columns alignwide">\n<?php foreach ( $unapp_steps as $unapp_index => $unapp_step ) : ?>\n')
        .replace('\n</div>\n<!-- /wp:columns -->', '\n<?php endforeach; ?>\n</div>\n<!-- /wp:columns -->'),
    pad=("70", "70"), gap="60")
write_pattern("how-it-works", title="How it works: numbered steps", cats="unapp, unapp_features, featured, text",
              keywords="steps, process, how it works, onboarding, numbered",
              desc="Three numbered steps describing a process, in equal columns.",
              body=body, php_prelude=prelude)

# ------------------------------------------------------------- integrations
prelude = """$unapp_integrations = array(
	array( 'icon' => 'code', 'label' => _x( 'Code hosting', 'Integration label', 'unapp' ) ),
	array( 'icon' => 'figma', 'label' => _x( 'Design files', 'Integration label', 'unapp' ) ),
	array( 'icon' => 'message-circle', 'label' => _x( 'Team chat', 'Integration label', 'unapp' ) ),
	array( 'icon' => 'calendar', 'label' => _x( 'Calendars', 'Integration label', 'unapp' ) ),
	array( 'icon' => 'inbox', 'label' => _x( 'Email', 'Integration label', 'unapp' ) ),
	array( 'icon' => 'database', 'label' => _x( 'Data warehouse', 'Integration label', 'unapp' ) ),
	array( 'icon' => 'credit-card', 'label' => _x( 'Billing', 'Integration label', 'unapp' ) ),
	array( 'icon' => 'terminal', 'label' => _x( 'CLI and API', 'Integration label', 'unapp' ) ),
);"""
tile = group(
    icon_badge_expr("$unapp_integration['icon']", bg="primary") + "\n" +
    para("<?php echo esc_html( $unapp_integration['label'] ); ?>", weight="600", font="heading", size="small"),
    style_variation="is-style-card", radius="16px", gap="30", layout="flex", orientation="vertical",
    justify="center", pad={"top": "40", "bottom": "40", "left": "40", "right": "40"})
body = section(
    intro(eyebrow_text=t("Integrations", "Section eyebrow label"),
          title=t("Plays nicely with your stack"),
          lead=t("Two-way sync with the tools your team already opens every morning.")) + "\n" +
    group('<?php foreach ( $unapp_integrations as $unapp_integration ) : ?>\n' + tile + '\n<?php endforeach; ?>',
          align="wide", layout="grid", gap="40") + "\n" +
    buttons([{"text": t("Browse all integrations"), "style": "is-style-outline"}], justify="center"),
    pad=("70", "70"), gap="60")
write_pattern("integrations", title="Integrations grid", cats=FEAT,
              keywords="integrations, apps, connect, stack, grid, icons",
              desc="A grid of integration tiles with icons, plus a link to a full directory.",
              body=body, php_prelude=prelude)
print("batch 3 written")
