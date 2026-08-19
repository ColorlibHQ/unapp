import os, sys; sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *

PAGES = [
 ("page-saas", "SaaS landing page", "starter, saas, landing, home, marketing",
  "A conversion-focused landing page: split hero, logo cloud, features, proof, pricing and a closing call to action.",
  ["hero-split","logo-cloud","features-zigzag","how-it-works","testimonials","pricing","faq","cta-band"]),
 ("page-features", "Features page", "starter, features, product, tour",
  "A full product tour: bento grid, alternating rows, integrations, security and a call to action.",
  ["hero-minimal","features-bento","features-zigzag","feature-checklist","integrations","security","cta-band"]),
 ("page-customers", "Customers page", "starter, customers, testimonials, proof, case study",
  "Social proof end to end: logos, ratings, a case study, testimonials and press mentions.",
  ["logo-cloud","ratings","case-study","testimonials","testimonial-feature","press","cta-band"]),
 ("page-contact", "Contact page", "starter, contact, support, offices",
  "Contact details with room for your form block, office locations and the FAQ.",
  ["contact-split","offices","faq"]),
 ("page-careers", "Careers page", "starter, careers, jobs, hiring, culture",
  "Culture first, then the open roles: values, team, timeline, positions and a closing note.",
  ["values","team","timeline","careers","cta-band"]),
 ("page-help", "Help centre", "starter, help, docs, support, knowledge base",
  "Documentation landing page: search hero, topic cards, FAQ and a support call to action.",
  ["hero-minimal","docs-topics","faq","contact-split"]),
 ("page-changelog", "Changelog page", "starter, changelog, releases, updates",
  "Product release notes with a newsletter sign-up at the end.",
  ["changelog","newsletter"]),
 ("page-legal", "Legal page", "starter, legal, privacy, terms, policy",
  "A single-column legal document — privacy policy, terms or a data processing agreement.",
  ["legal"]),
 ("page-coming-soon", "Coming soon page", "starter, coming soon, waitlist, launch, pre-launch",
  "A one-screen pre-launch page: waitlist panel, a few features and review scores.",
  ["waitlist","services","ratings"]),
 ("page-pricing-full", "Pricing page (detailed)", "starter, pricing, plans, compare, faq",
  "Plans, a full comparison table, security notes and the pricing FAQ.",
  ["pricing","pricing-compare","security","faq","cta-band"]),
]
for slug, title, keywords, desc, refs in PAGES:
    body = "\n".join(pattern_ref("unapp/" + r) for r in refs)
    write_pattern(slug, title=title, cats="unapp_page", keywords=keywords, desc=desc,
                  body=body, block_types="core/post-content", post_types="page, wp_template")
print("batch 8 written:", len(PAGES))
