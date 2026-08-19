import os, sys; sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *

DEMOS = [
 ("demo-saas", "Starter site: SaaS & app", "starter site, saas, app, software, landing",
  "The home page for the SaaS starter site: split hero, logo cloud, features, steps, proof, pricing and a closing call to action.",
  ["hero-split","logo-cloud","features-zigzag","how-it-works","screens","testimonials","pricing","faq-accordion","cta-band"]),
 ("demo-portfolio", "Starter site: Portfolio", "starter site, portfolio, designer, freelance, creative",
  "The home page for the Portfolio starter site: introduction, selected work, about, process, rates, a client quote and current availability.",
  ["portfolio-hero","portfolio-work","portfolio-about","portfolio-process","portfolio-services","portfolio-testimonial","portfolio-contact"]),
 ("demo-church", "Starter site: Church", "starter site, church, faith, community, worship",
  "The home page for the Church starter site: welcome, service times, what to expect, ministries, staff, events, giving and an invitation.",
  ["church-hero","church-times","church-visit","church-events","church-staff","church-ministries","church-giving"]),
 ("demo-blog", "Starter site: Blog & magazine", "starter site, blog, magazine, writer, publication",
  "The home page for the Blog starter site: masthead, featured post, category tiles, author introduction and a subscribe band.",
  ["blog-masthead","blog-featured","blog-categories","blog-author-intro","blog-subscribe"]),
 ("demo-fitness", "Starter site: Fitness studio", "starter site, fitness, gym, studio, training",
  "The home page for the Fitness starter site: hero, the studio, timetable, coaches, numbers, member stories, memberships and a join band.",
  ["fitness-hero","fitness-intro","fitness-schedule","fitness-coaches","fitness-results","fitness-testimonials","fitness-memberships","fitness-cta"]),
 ("demo-finance", "Starter site: Finance & advisory", "starter site, finance, advisor, consulting, professional",
  "The home page for the Finance starter site: trust-led hero, credentials, services, process, advisers, questions, contact and the risk warning.",
  ["finance-hero","finance-credentials","finance-services","finance-process","finance-team","finance-faq","finance-contact","finance-disclaimer"]),
]
for slug, title, keywords, desc, refs in DEMOS:
    body = "\n".join(pattern_ref("unapp/" + r) for r in refs)
    write_pattern(slug, title=title, cats="unapp_page", keywords=keywords, desc=desc,
                  body=body, block_types="core/post-content", post_types="page, wp_template")
print("batch 12 written:", len(DEMOS))
