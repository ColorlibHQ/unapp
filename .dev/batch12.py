import os, sys; sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *

DEMOS = [
 ("demo-saas", "Starter site: SaaS & app", "starter site, saas, app, software, landing",
  "The home page for the SaaS starter site: split hero, logo cloud, features, steps, proof, pricing and a closing call to action.",
  ["hero-split","logo-cloud","features-zigzag","how-it-works","testimonials","pricing","faq-accordion","cta-band"]),
 ("demo-portfolio", "Starter site: Portfolio", "starter site, portfolio, designer, freelance, creative",
  "The home page for the Portfolio starter site: introduction, selected work, about, services and a quiet contact band.",
  ["portfolio-hero","portfolio-work","portfolio-about","portfolio-services","testimonial-feature","cta-band"]),
 ("demo-church", "Starter site: Church", "starter site, church, faith, community, worship",
  "The home page for the Church starter site: welcome, service times, ministries, staff, giving and directions.",
  ["church-hero","church-times","church-ministries","team","church-giving","offices"]),
 ("demo-blog", "Starter site: Blog & magazine", "starter site, blog, magazine, writer, publication",
  "The home page for the Blog starter site: masthead, featured post, category tiles, author introduction and a newsletter.",
  ["blog-masthead","blog-featured","blog-categories","blog-author-intro","newsletter"]),
 ("demo-fitness", "Starter site: Fitness studio", "starter site, fitness, gym, studio, training",
  "The home page for the Fitness starter site: hero, class timetable, coaches, memberships, results and a join band.",
  ["fitness-hero","fitness-schedule","fitness-coaches","pricing-two","stats","faq-accordion","cta-band"]),
 ("demo-finance", "Starter site: Finance & advisory", "starter site, finance, advisor, consulting, professional",
  "The home page for the Finance starter site: trust-led hero, credentials, services, process, advisors, FAQ and the risk warning.",
  ["finance-hero","finance-credentials","finance-services","how-it-works","team","faq-accordion","contact-split","finance-disclaimer"]),
]
for slug, title, keywords, desc, refs in DEMOS:
    body = "\n".join(pattern_ref("unapp/" + r) for r in refs)
    write_pattern(slug, title=title, cats="unapp_page", keywords=keywords, desc=desc,
                  body=body, block_types="core/post-content", post_types="page, wp_template")
print("batch 12 written:", len(DEMOS))
