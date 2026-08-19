import os, sys; sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *

# ------------------------------------------------------------- pricing: two plans
def plan(name, price, period, tagline, features, button, featured=False):
    inner = (heading(name, level=3, size="large") + "\n" +
             para(tagline, color=None if featured else "muted", size="small") + "\n" +
             group(para(price, size="xxx-large", font="heading", weight="600", line_height="1") + "\n" +
                   para(period, color=None if featured else "muted", size="small"),
                   layout="flex", wrap="nowrap", gap="20", vertical_align="bottom") + "\n" +
             lst(features) + "\n" +
             buttons([{"text": button, "width": 100,
                       **({"bg": "base", "color": "primary"} if featured else {"style": "is-style-outline"})}],
                     margin={"top": "40"}))
    return column(group(inner, style_variation="is-style-section-gradient" if featured else "is-style-card",
                        radius="20px", gap="30", layout="default",
                        pad={"top": "60", "bottom": "60", "left": "50", "right": "50"} if featured else None,
                        shadow="glow" if featured else None))

body = section(
    intro(eyebrow_text=t("Pricing", "Section eyebrow label"),
          title=t("Two plans. No surprises."),
          lead=t("Start free forever. Upgrade the day your team outgrows it.")) + "\n" +
    columns([
        plan(t("Free"), t("$0"), t("forever"), t("For solo makers and side projects"),
             [t("3 projects"), t("1 GB storage"), t("Community support")], t("Create an account")),
        plan(t("Team"), t("$12"), t("per user / month"), t("For teams shipping every week"),
             [t("Unlimited projects"), t("100 GB storage"), t("Priority support"), t("Advanced reporting"), t("SSO and audit logs")],
             t("Start free trial"), featured=True),
    ], align="wide", gap="40") + "\n" +
    para(t("Prices exclude VAT. Annual billing saves 20%."), align="center", color="muted", size="small"),
    style_variation="is-style-section-soft", pad=("70", "70"), gap="50", content_size="900px", wide_size="900px")
write_pattern("pricing-two", title="Pricing: two plans", cats="unapp, unapp_pricing, featured, columns",
              keywords="pricing, plans, two, free, pro, simple",
              desc="A two-plan pricing block for products with one paid tier.",
              body=body)

# ------------------------------------------------------------- pricing comparison
check = '<img src="' + uri("assets/images/icons/check-circle.svg") + '" alt="' + tattr("Included") + '" width="18" height="18" style="vertical-align:middle" />'
rows = [
    (t("Projects"), t("3"), t("Unlimited"), t("Unlimited")),
    (t("Storage"), t("1 GB"), t("100 GB"), t("1 TB")),
    (t("Team members"), t("2"), t("Up to 50"), t("Unlimited")),
    (t("Reporting"), t("Basic"), t("Advanced"), t("Advanced + API")),
    (t("Single sign-on"), t("—"), t("Yes"), t("Yes")),
    (t("Support"), t("Community"), t("Priority"), t("Dedicated manager")),
]
thead = "<tr><th>" + t("Feature") + "</th><th>" + t("Free") + "</th><th>" + t("Team") + "</th><th>" + t("Business") + "</th></tr>"
tbody = "".join(f"<tr><td>{r[0]}</td><td>{r[1]}</td><td>{r[2]}</td><td>{r[3]}</td></tr>" for r in rows)
table = ('<!-- wp:table {"className":"is-style-compare"} -->\n'
         f'<figure class="wp-block-table is-style-compare"><table><thead>{thead}</thead><tbody>{tbody}</tbody></table></figure>\n'
         '<!-- /wp:table -->')
body = section(
    intro(eyebrow_text=t("Compare", "Section eyebrow label"),
          title=t("Everything, side by side"),
          lead=t("The full breakdown of what each plan includes.")) + "\n" +
    group(table, align="wide", layout="constrained") + "\n" +
    buttons([{"text": t("Start free trial")}, {"text": t("Talk to sales"), "style": "is-style-outline"}],
            justify="center", gap="30"),
    pad=("70", "70"), gap="50")
write_pattern("pricing-compare", title="Pricing: comparison table", cats="unapp, unapp_pricing, featured",
              keywords="pricing, compare, table, plans, features",
              desc="A feature comparison table across three plans, with calls to action beneath.",
              body=body)

# ------------------------------------------------------------- FAQ
prelude = """$unapp_faqs = array(
	array(
		'q' => _x( 'Can I try Unapp before paying?', 'FAQ question', 'unapp' ),
		'a' => _x( 'Yes. Every plan starts with a 14-day trial of the Team tier — no card, no sales call. When the trial ends you drop to the free plan rather than losing your data.', 'FAQ answer', 'unapp' ),
	),
	array(
		'q' => _x( 'What happens to my data if I cancel?', 'FAQ question', 'unapp' ),
		'a' => _x( 'You can export everything as CSV or through the API at any time. We keep a backup for 30 days after cancellation, then delete it for good.', 'FAQ answer', 'unapp' ),
	),
	array(
		'q' => _x( 'Do you offer discounts for non-profits?', 'FAQ question', 'unapp' ),
		'a' => _x( 'We do — registered non-profits and educational institutions get 50% off any paid plan. Email us from your organisation address and we will set it up.', 'FAQ answer', 'unapp' ),
	),
	array(
		'q' => _x( 'Where is my data stored?', 'FAQ question', 'unapp' ),
		'a' => _x( 'In the region you choose when the workspace is created: the EU, the United States or Australia. Data never leaves that region.', 'FAQ answer', 'unapp' ),
	),
	array(
		'q' => _x( 'Can I move my team from another tool?', 'FAQ question', 'unapp' ),
		'a' => _x( 'Importers are built in for the usual suspects, and our team will run the migration with you on Business plans.', 'FAQ answer', 'unapp' ),
	),
);"""
faq_item = ('<?php foreach ( $unapp_faqs as $unapp_faq ) : ?>\n'
            '<!-- wp:details {"className":"is-style-faq-card"} -->\n'
            '<details class="wp-block-details is-style-faq-card"><summary><?php echo esc_html( $unapp_faq[\'q\'] ); ?></summary>\n'
            + para("<?php echo esc_html( $unapp_faq['a'] ); ?>", color="muted") +
            '\n</details>\n<!-- /wp:details -->\n'
            '<?php endforeach; ?>')
body = section(
    intro(eyebrow_text=t("FAQ", "Section eyebrow label"),
          title=t("Questions we get a lot"),
          lead=t("Still stuck? The support team answers in under an hour on weekdays.")) + "\n" +
    group(faq_item, layout="constrained", content_size="760px", gap="30"),
    pad=("70", "70"), gap="50")
write_pattern("faq", title="FAQ accordion", cats="unapp, unapp_utility, text, featured",
              keywords="faq, questions, accordion, details, support, help",
              desc="Expandable question-and-answer list built on the core Details block.",
              body=body, php_prelude=prelude)

# ------------------------------------------------------------- CTA band
body = section(
    columns([
        column(heading(t("Ready to see it with your own projects?"), size="x-large", color="base") + "\n" +
               para(t("Import a board and watch it come to life. It takes about five minutes."), color="base"),
               width="62%", vertical_align="center", gap="20"),
        column(buttons([{"text": t("Start free trial"), "bg": "base", "color": "primary"}],
                       justify="right"), width="38%", vertical_align="center"),
    ], align="wide", gap="50", vertical_align="center"),
    style_variation="is-style-section-gradient", pad=("60", "60"), gap="0")
write_pattern("cta-band", title="Call to action: colour band", cats="unapp, unapp_cta, call-to-action, banner",
              keywords="cta, banner, band, gradient, conversion",
              desc="A compact full-width gradient band with a headline and one button.",
              body=body)

# ------------------------------------------------------------- CTA split
body = section(
    columns([
        column(image(uri("assets/images/app-mobile-2.avif"), tattr("Unapp mobile app"),
                     class_name="is-style-device", width="240px", align="center"),
               width="38%", vertical_align="center"),
        column(eyebrow(t("Mobile", "Section eyebrow label"), align="left") + "\n" +
               heading(t("Take the roadmap with you"), size="x-large") + "\n" +
               para(t("Review work, approve requests and reply to comments from the train. Everything syncs the moment you are back online."), color="muted", size="large") + "\n" +
               buttons([{"text": t("App Store")}, {"text": t("Google Play"), "style": "is-style-outline"}], gap="30"),
               width="62%", vertical_align="center", gap="30"),
    ], align="wide", gap="60", vertical_align="center"),
    style_variation="is-style-section-soft", pad=("70", "70"), gap="0")
write_pattern("cta-app", title="Call to action: mobile app", cats="unapp, unapp_cta, call-to-action, media",
              keywords="cta, app, download, mobile, ios, android",
              desc="App download call to action with a phone screenshot and two store buttons.",
              body=body)

# ------------------------------------------------------------- newsletter inline
body = section(
    columns([
        column(heading(t("The Friday changelog"), level=3, size="large") + "\n" +
               para(t("One short email a week: what shipped, what broke, what we learned. No marketing."), color="muted"),
               width="52%", vertical_align="center", gap="20"),
        column('<!-- wp:search {"label":"' + t("Email address", ctx="Newsletter field label").replace('"', "'") +
               '","showLabel":false,"placeholder":"' + tattr("you@company.com") +
               '","width":100,"widthUnit":"%","buttonText":"' + t("Subscribe", ctx="Newsletter button").replace('"', "'") +
               '","buttonPosition":"button-inside"} /-->\n' +
               para(t("Roughly 900 readers. Unsubscribe in one click."), color="muted", size="small"),
               width="48%", vertical_align="center", gap="20"),
    ], align="wide", gap="50", vertical_align="center"),
    pad=("60", "60"), gap="0")
write_pattern("newsletter", title="Newsletter sign-up", cats="unapp, unapp_cta, call-to-action, text",
              keywords="newsletter, subscribe, email, signup, inline",
              desc="Inline newsletter row: pitch on the left, email field on the right. Swap the field for your mail plugin's block.",
              body=body)

# ------------------------------------------------------------- waitlist / coming soon
body = section(
    intro(eyebrow_text=t("Coming soon", "Section eyebrow label"),
          title=t("Something new is nearly ready"),
          lead=t("We are putting the finishing touches to the next version of Unapp. Leave your address and you will be first through the door."),
          content="620px", eyebrow_color="base", title_color="base", lead_color="base") + "\n" +
    group('<!-- wp:search {"label":"' + t("Email address", ctx="Waitlist field label").replace('"', "'") +
          '","showLabel":false,"placeholder":"' + tattr("you@company.com") +
          '","width":100,"widthUnit":"%","buttonText":"' + t("Join the waitlist", ctx="Waitlist button").replace('"', "'") +
          '","buttonPosition":"button-inside"} /-->',
          layout="constrained", content_size="440px") + "\n" +
    social([("x", "https://x.com"), ("linkedin", "https://linkedin.com"), ("github", "https://github.com")],
           justify="center", color="base", value="#ffffff"),
    style_variation="is-style-section-gradient", pad=("80", "80"), gap="40", content_size="620px")
write_pattern("waitlist", title="Waitlist / coming soon", cats="unapp, unapp_cta, call-to-action, banner",
              keywords="waitlist, coming soon, launch, early access, signup",
              desc="Full-bleed gradient panel with a waitlist field and social links — the whole page for a pre-launch site.",
              body=body)
print("batch 4 written")
