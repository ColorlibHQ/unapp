"""Portfolio and blog starters: the remaining slots that were borrowing SaaS copy."""
import os, sys; sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *

P = "unapp, unapp_portfolio"
B = "unapp, unapp_blog"

# ================================================================= PORTFOLIO
# ---------------------------------------------------------------- process
STEPS = [
    ("Week one", "Reading", "Everything you have already written down, plus conversations with the people who will have to live with the result."),
    ("Weeks two to four", "Drawing", "Two directions, shown early and shown rough. We kill one together before either gets expensive."),
    ("Weeks five to eight", "Building", "Type, colour, layout and every state of every component, in a file your developers can actually open."),
]
prelude = "$unapp_portfolio_steps = array(\n"
for when, title, text in STEPS:
    prelude += ("\tarray(\n"
                f"\t\t'when'  => _x( '{when}', 'Project stage timing', 'unapp' ),\n"
                f"\t\t'title' => _x( '{title}', 'Project stage title', 'unapp' ),\n"
                f"\t\t'text'  => _x( '{text}', 'Project stage text', 'unapp' ),\n"
                "\t),\n")
prelude += ");\n"
card = group(
    para(php("$unapp_portfolio_step['when']"), color="primary", size="small", weight="600",
         letter="0.06em", transform="uppercase") + "\n" +
    heading(php("$unapp_portfolio_step['title']"), level=3, size="large") + "\n" +
    para(php("$unapp_portfolio_step['text']"), color="muted"),
    layout="flex", orientation="vertical", gap="12")
loop = ("<?php foreach ( $unapp_portfolio_steps as $unapp_portfolio_step ) : ?>\n"
        + card + "\n<?php endforeach; ?>")
body = section(
    intro(eyebrow_text=t("How a project runs", "Section eyebrow label"),
          title=t("Eight weeks, three stages, no surprises"),
          lead=t("Every project I take on runs the same way, and you see work in the first fortnight.")) + "\n" +
    group(loop, align="wide", layout="grid", col_count=3, gap="50", class_name="unapp-grid-3"),
    pad=("60", "70"), gap="50")
write_pattern("portfolio-process", title="Portfolio: how a project runs", cats=P + ", unapp_features, steps",
              keywords="portfolio, process, project, stages, timeline, freelance",
              desc="Three stages of a design engagement with the week numbers attached, so a client knows what they are buying.",
              body=body, php_prelude=prelude)

# ---------------------------------------------------------------- client quote
body = section(
    group(
        para(t("&#8220;She showed us two directions in a fortnight and then argued us out of the one we liked. She was right. The thing we shipped is the thing people can actually use.&#8221;"),
             align="center", size="xx-large", line_height="1.35") + "\n" +
        image(uri("assets/images/avatars/avatar-3.svg"), tattr("Client portrait placeholder"),
              width="64px", height="64px", radius="999px", align="center") + "\n" +
        para(t("Ollie Trent"), align="center", weight="600") + "\n" +
        para(t("Founder, Nordwell Coffee"), align="center", color="muted", size="small"),
        layout="constrained", content_size="760px", gap="20"),
    style_variation="is-style-section-soft", pad=("70", "70"), gap="0")
write_pattern("portfolio-testimonial", title="Portfolio: client quote", cats=P + ", unapp_proof, testimonials",
              keywords="portfolio, testimonial, client, quote, review",
              desc="A single client quote, centred, with a portrait and attribution.",
              body=body)

# ---------------------------------------------------------------- availability / contact
body = section(
    columns([
        column(
            eyebrow(t("Working together", "Section eyebrow label"), align="left") + "\n" +
            heading(t("Booking from February")) + "\n" +
            para(t("I take on four or five projects a year, usually branding and product design for teams between five and fifty people. If the dates do not work I will say so straight away rather than keep you waiting."),
                 color="muted", size="large") + "\n" +
            para(t("mara@lindqvist.example"), size="large", weight="600") + "\n" +
            social([("x", "https://x.com"), ("instagram", "https://instagram.com"),
                    ("linkedin", "https://linkedin.com")], size="has-small-icon-size", justify="left"),
            width="55%", vertical_align="top", gap="18"),
        column(
            group(
                para(t("What to send"), weight="600") + "\n" +
                lst([t("Roughly what you are making, in a paragraph"),
                     t("Who it is for, and who you are competing with"),
                     t("When you need it live"),
                     t("A budget range, even a wide one")]) + "\n" +
                para(t("You will hear back within two working days, always from me."),
                     color="muted", size="small"),
                style_variation="is-style-card", radius="18px", gap="16",
                pad={"top": "40", "bottom": "40", "left": "40", "right": "40"}),
            width="45%", vertical_align="top"),
    ], align="wide", gap="60", vertical_align="top"),
    pad=("60", "70"), gap="0")
write_pattern("portfolio-contact", title="Portfolio: availability and enquiries", cats=P + ", unapp_utility, contact",
              keywords="portfolio, contact, availability, enquiry, freelance, hire",
              desc="Current availability, an email address and a short brief of what to include in a first message.",
              body=body)

# ================================================================= BLOG
# ---------------------------------------------------------------- about the publication
body = section(
    columns([
        column(image(uri("assets/images/abstract/reading.svg"), tattr("Reading"), radius="20px"),
               width="44%", vertical_align="center"),
        column(
            eyebrow(t("About", "Section eyebrow label"), align="left") + "\n" +
            heading(t("Why this exists")) + "\n" +
            para(t("The Slow Build started in 2019 as an argument with a former colleague about whether software has to be made the way it usually is. He has since conceded, partially."),
                 color="muted", size="large") + "\n" +
            para(t("One essay most Fridays, about a thousand words, on the parts of building software that do not fit in a conference talk: estimation, rewrites, hiring, and the quiet cost of moving fast. No sponsorship, no affiliate links, no course at the end of it."),
                 color="muted") + "\n" +
            buttons([{"text": t("Read the archive"), "url": "#archive"},
                     {"text": t("Subscribe"), "url": "#subscribe", "style": "outline"}],
                    margin={"top": "10"}),
            width="56%", vertical_align="center", gap="20"),
    ], align="wide", gap="60", vertical_align="center"),
    pad=("70", "70"), gap="0")
write_pattern("blog-about", title="Blog: about the publication", cats=B + ", unapp_company, about",
              keywords="blog, about, publication, magazine, editorial",
              desc="What the publication is, how often it appears and how it is paid for.",
              body=body)

# ---------------------------------------------------------------- subscribe
body = section(
    group(
        heading(t("One essay, most Fridays"), align="center", size="xx-large", color="base") + "\n" +
        para(t("Four thousand people read it over coffee. No tracking pixels, no sequence, one click to leave."),
             align="center", custom_color="rgba(255,255,255,0.86)", size="large") + "\n" +
        '<!-- wp:search {"label":"","showLabel":false,"placeholder":"' + tattr("you@example.com") +
        '","buttonText":"' + tattr("Subscribe") + '","align":"center","buttonPosition":"button-inside",'
        '"style":{"border":{"radius":"999px"}}} /-->' + "\n" +
        para(t("Or follow the RSS feed, if you are that sort of person. Many of us are."),
             align="center", custom_color="rgba(255,255,255,0.7)", size="small"),
        layout="constrained", content_size="620px", gap="22"),
    gradient="primary-to-accent", text="base", pad=("70", "70"), gap="0")
write_pattern("blog-subscribe", title="Blog: subscribe band", cats=B + ", unapp_cta, newsletter",
              keywords="blog, subscribe, newsletter, email, rss",
              desc="A newsletter band in an editorial voice, with an email field and a nod to RSS.",
              body=body)

# ---------------------------------------------------------------- write for us / contact
body = section(
    columns([
        column(
            eyebrow(t("Get in touch", "Section eyebrow label"), align="left") + "\n" +
            heading(t("Pitches, corrections and arguments")) + "\n" +
            para(t("All three are welcome, in that order of enthusiasm. Corrections get published as corrections, with a note at the top of the piece — not quietly edited in overnight."),
                 color="muted", size="large") + "\n" +
            para(t("editor@theslowbuild.example"), size="large", weight="600"),
            width="55%", vertical_align="top", gap="18"),
        column(
            group(
                para(t("Pitching an essay"), weight="600") + "\n" +
                para(t("Two or three paragraphs on the argument you want to make, plus one thing you have written before. We pay £250 on publication and we do not commission on spec."),
                     color="muted", size="small") + "\n" +
                separator(style="wide", color="border") + "\n" +
                para(t("Response time"), weight="600") + "\n" +
                para(t("Ten working days. If you have not heard by then, chase — it means the email got lost, not that the answer is no."),
                     color="muted", size="small"),
                style_variation="is-style-card", radius="18px", gap="14",
                pad={"top": "40", "bottom": "40", "left": "40", "right": "40"}),
            width="45%", vertical_align="top"),
    ], align="wide", gap="60", vertical_align="top"),
    style_variation="is-style-section-soft", pad=("60", "70"), gap="0")
write_pattern("blog-contact", title="Blog: pitches and contact", cats=B + ", unapp_utility, contact",
              keywords="blog, contact, pitch, write for us, submissions, editor",
              desc="Contact details for a publication, including what a pitch should contain and what it pays.",
              body=body)

print("batch 16 written: 3 portfolio + 3 blog patterns")
