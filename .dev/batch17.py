"""A footer per starter, on the house style."""
import os, sys; sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *

DIM = "rgba(255,255,255,0.72)"

COPYRIGHT = '''<!-- wp:paragraph {"style":{"color":{"text":"rgba(255,255,255,0.75)"}},"fontSize":"small"} -->
<p class="has-text-color has-small-font-size" style="color:rgba(255,255,255,0.75)">
<?php
printf(
\t/* translators: 1: current year, 2: site name. */
\tesc_html__( '&copy; %1$s %2$s. All rights reserved.', 'unapp' ),
\tesc_html( date_i18n( 'Y' ) ),
\tesc_html( get_bloginfo( 'name' ) )
);
?>
</p>
<!-- /wp:paragraph -->'''

CREDIT = '''<!-- wp:paragraph {"align":"right","style":{"color":{"text":"rgba(255,255,255,0.75)"}},"fontSize":"small"} -->
<p class="has-text-align-right has-text-color has-small-font-size" style="color:rgba(255,255,255,0.75)"><?php esc_html_e( 'Built with the Unapp theme', 'unapp' ); ?></p>
<!-- /wp:paragraph -->'''

FOOTER_ELEMENTS = {
    "link": {"color": {"text": "var:preset|color|base"},
             ":hover": {"color": {"text": "var:preset|color|secondary"}}},
    "heading": {"color": {"text": "var:preset|color|base"}},
}


def niche_footer(slug, *, title, cats, keywords, desc, blurb, links_head, links,
                 contact_head, contact_lines, socials, posts_head):
    link_items = "\n".join(
        f'<!-- wp:navigation-link {{"label":"{tattr(text)}","url":"{url}","kind":"custom","isTopLevelLink":true}} /-->'
        for text, url in links)
    nav = ('<!-- wp:navigation {"overlayMenu":"never","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},'
           '"fontSize":"small","layout":{"type":"flex","orientation":"vertical"}} -->\n'
           + link_items + '\n<!-- /wp:navigation -->')
    contact = "\n".join(para(t(line, "Footer contact line"), custom_color=DIM, size="small")
                        for line in contact_lines)
    posts = '<!-- wp:latest-posts {"postsToShow":3,"displayPostDate":true,"fontSize":"small"} /-->'

    def head(text):
        return heading(t(text, "Footer column heading"), level=2, size="small", weight="600",
                       letter="0.06em")

    cols = columns([
        column('<!-- wp:site-title {"level":0,"fontSize":"large"} /-->' + "\n" +
               para(t(blurb, "Footer tagline"), custom_color=DIM, size="small") + "\n" +
               social(socials, size="has-small-icon-size", color="base", value="#ffffff", gap="30"),
               width="34%", gap=CARD_GAP),
        column(head(links_head) + "\n" + nav, width="22%", gap=CARD_GAP),
        column(head(posts_head) + "\n" + posts, width="22%", gap=CARD_GAP),
        column(head(contact_head) + "\n" + contact, width="22%", gap=CARD_GAP),
    ], align="wide", gap="50")

    bar = group(
        columns([
            column(COPYRIGHT, width="60%", vertical_align="center"),
            column(CREDIT, width="40%", vertical_align="center"),
        ], align="wide", gap="30", vertical_align="center", is_stacked=False),
        align="wide", border_top=("rgba(255,255,255,0.15)", "1px", "solid"), pad={"top": "40"})

    body = section_std(cols + "\n" + bar, bg="dark", text="base", pad=("70", "40"),
                       elements=FOOTER_ELEMENTS)
    write_pattern(slug, title=title, cats=cats, keywords=keywords, desc=desc, body=body,
                  block_types="core/template-part/footer")


SOCIAL_STD = [("facebook", "https://facebook.com"), ("instagram", "https://instagram.com")]

niche_footer(
    "footer-church", title="Footer: church", cats="unapp, unapp_church, footer",
    keywords="footer, church, contact, service times",
    desc="A church footer: what the church is, quick links, recent news and where to find it.",
    blurb="A church on Mill Lane since 1886. Services at 9:30 and 11:15 every Sunday, and coffee from 9:00.",
    links_head="Visit", links=[("Plan your visit", "#visit"), ("Service times", "#times"),
                               ("Ministries", "#ministries"), ("Give", "#give")],
    contact_head="Find us",
    contact_lines=["Riverside Church, 12 Mill Lane", "Chesterfield S40 1RT",
                   "01246 555 0114", "hello@riverside.example"],
    socials=SOCIAL_STD + [("youtube", "https://youtube.com")], posts_head="Church news")

niche_footer(
    "footer-fitness", title="Footer: fitness studio", cats="unapp, unapp_fitness, footer",
    keywords="footer, fitness, gym, hours, contact",
    desc="A gym footer: the studio in a sentence, timetable links, opening hours and the address.",
    blurb="A barbell gym under the arches. Twelve people per class, a coach on the floor at every session.",
    links_head="Train", links=[("Timetable", "#timetable"), ("Memberships", "#memberships"),
                               ("Coaches", "#coaches"), ("Book a session", "#book")],
    contact_head="The studio",
    contact_lines=["Arch 12, Bonnington Yard", "Edinburgh EH6 5NX",
                   "Mon–Thu 06:00–21:00, Fri to 20:00", "hello@archtwelve.example"],
    socials=SOCIAL_STD + [("youtube", "https://youtube.com")], posts_head="From the blog")

niche_footer(
    "footer-finance", title="Footer: finance and advisory", cats="unapp, unapp_finance, footer",
    keywords="footer, finance, adviser, regulated, fca, contact",
    desc="An adviser footer carrying the regulatory line every financial firm has to display.",
    blurb="Independent financial planning for families and company directors. Chartered, fee-only, no commission.",
    links_head="Advice", links=[("Services", "#services"), ("Our fees", "#fees"),
                                ("The team", "#team"), ("Book a call", "#book")],
    contact_head="Office",
    contact_lines=["14 Rodney Street, Liverpool L1 2TE", "0151 555 0188", "advice@rodneystreet.example",
                   "Authorised and regulated by the Financial Conduct Authority, firm reference 000000."],
    socials=[("linkedin", "https://linkedin.com"), ("x", "https://x.com")], posts_head="Insights")

niche_footer(
    "footer-portfolio", title="Footer: portfolio", cats="unapp, unapp_portfolio, footer",
    keywords="footer, portfolio, designer, availability, contact",
    desc="A freelancer's footer: what you do, what you are booking, and the one email address that matters.",
    blurb="Brand and product design for teams between five and fifty people. Four or five projects a year.",
    links_head="Work", links=[("Selected work", "#work"), ("Services and rates", "#services"),
                              ("About", "#about"), ("Enquiries", "#contact")],
    contact_head="Say hello",
    contact_lines=["mara@lindqvist.example", "Booking from February", "Stockholm, and remote"],
    socials=[("instagram", "https://instagram.com"), ("x", "https://x.com"),
             ("linkedin", "https://linkedin.com")], posts_head="Writing")

niche_footer(
    "footer-blog", title="Footer: blog and magazine", cats="unapp, unapp_blog, footer",
    keywords="footer, blog, magazine, subscribe, rss",
    desc="A publication footer: what it is, how often it appears, and where to subscribe.",
    blurb="Essays on making software carefully. One most Fridays, about a thousand words, no sponsorship.",
    links_head="Read", links=[("Archive", "#archive"), ("Topics", "#topics"),
                              ("About", "#about"), ("Pitch an essay", "#pitch")],
    contact_head="Elsewhere",
    contact_lines=["editor@theslowbuild.example", "RSS feed",
                   "Corrections published as corrections"],
    socials=[("x", "https://x.com"), ("mastodon", "https://mastodon.social"),
             ("rss", "https://example.com/feed")], posts_head="Recent essays")

niche_footer(
    "footer-restaurant", title="Footer: restaurant", cats="unapp, unapp_restaurant, footer",
    keywords="footer, restaurant, hours, booking, address",
    desc="A restaurant footer: what the kitchen does, opening nights and where to find the room.",
    blurb="Ten tables on Wharf Street. Dinner Wednesday to Saturday, lunch on Sunday, and a menu that changes weekly.",
    links_head="Eat", links=[("This week's menu", "#menu"), ("Book a table", "#book"),
                             ("The kitchen", "#kitchen"), ("Private dining", "#private")],
    contact_head="The room",
    contact_lines=["41 Wharf Street, Bristol BS1 4RW", "0117 555 0192",
                   "Wed–Sat dinner · Sun lunch", "eat@wharfstreet.example"],
    socials=SOCIAL_STD + [("tripadvisor", "https://tripadvisor.com")], posts_head="From the kitchen")

niche_footer(
    "footer-agency", title="Footer: agency", cats="unapp, unapp_agency, footer",
    keywords="footer, agency, studio, contact, work",
    desc="A studio footer: what the studio does, the work, and how to start a project.",
    blurb="An independent studio of nine in Manchester. Brand, product, and the software to run both.",
    links_head="Studio", links=[("Work", "#work"), ("Capabilities", "#services"),
                                ("Engagements", "#engagements"), ("Start a project", "#contact")],
    contact_head="Get in touch",
    contact_lines=["studio@northgate.example", "0161 555 0134",
                   "Northgate Works, Manchester M4 5JW", "Booked about six weeks out"],
    socials=[("linkedin", "https://linkedin.com"), ("instagram", "https://instagram.com"),
             ("x", "https://x.com")], posts_head="Journal")

niche_footer(
    "footer-shop", title="Footer: shop", cats="unapp, unapp_shop, footer",
    keywords="footer, shop, store, delivery, returns, ecommerce",
    desc="A storefront footer: what the workshop makes, shop links, and the delivery and returns lines buyers look for.",
    blurb="A small workshop in Leeds making bags and aprons in runs of about two hundred. Cut, stitched and finished here.",
    links_head="Shop", links=[("Everything in stock", "/shop/"), ("How it is made", "#making"),
                              ("Delivery and returns", "#delivery"), ("Repairs", "#repairs")],
    contact_head="The workshop",
    contact_lines=["Unit 4, Sheaf Works, Leeds LS10 1EE", "0113 555 0177",
                   "Sixty-day returns, postage paid", "workshop@sheafworks.example"],
    socials=SOCIAL_STD + [("pinterest", "https://pinterest.com")], posts_head="Journal")

print("batch 17 rewritten: 8 niche footers on the house style")
