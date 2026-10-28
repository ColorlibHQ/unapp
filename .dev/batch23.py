"""Free local-service starters: home services, cleaning, salon and barber."""
import os, sys
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *

SITES = [
    dict(slug="home-services", name="Home Services", category="unapp_home_services",
         brand="Northline Property Care · Bristol", headline="A shorter list of jobs around the house.",
         lead="Repairs, decorating and practical help with the jobs that keep getting put off. Tell us what needs doing and we will agree the work before we begin.",
         alt="Hand tools and a cordless drill on an oak workbench in a bright home hallway",
         action="Request a quote", anchor="quote-request", secondary="Explore our services", services_anchor="home-services",
         note="A clear scope, an agreed price and a visit arranged with you.", email="hello@northline.example", phone="+44 113 555 0177",
         services_title="Practical help for a home that works",
         services_lead="A single repair or a planned list of improvements. Share a few photos so we can understand the job.",
         services=[("Repairs and assembly", "Shelves, flat-pack furniture, sticking doors and the little fixes that make a difference."),
                   ("Decorating and finishing", "Refresh a room with careful preparation, painting and finishing touches, with the scope agreed in advance."),
                   ("Planned property care", "Bring several jobs into one visit, or discuss a maintenance plan for your home or rental property.")],
         process_title="Know what happens before anyone arrives",
         process=[("Describe the job", "Send a short list and a few photos. Include your area and any access or timing needs."),
                  ("Agree the work", "We discuss the scope, materials and price. If a visit is needed before quoting, we explain that first."),
                  ("Arrange a visit", "Choose an agreed time, prepare access and let us take care of the work on your list.")],
         area_title="Local jobs, planned around your day", area="Based in Bristol, with visits arranged across the city and nearby neighbourhoods. Ask about your address when you enquire.",
         area_note="Appointments by arrangement. Tell us about parking, stairs or access before the visit.",
         cta_title="What would you like taken off your list?", cta_text="Email a description, your area and a few photos. We will discuss the job and confirm the next step before arranging a visit.", subject="Home services quote", call="Talk about a job"),
    dict(slug="cleaning", name="Cleaning", category="unapp_cleaning",
         brand="Clear Day Cleaning · Bristol", headline="A clean space. A little more time for you.",
         lead="Regular home cleaning, one-off deep cleans and small workplace visits. We agree a checklist around your space, rather than leave you guessing what is included.",
         alt="Unbranded cleaning supplies and folded teal cloths on a sunlit kitchen island",
         action="Get a cleaning quote", anchor="cleaning-enquiry", secondary="See what we clean", services_anchor="cleaning-services",
         note="Tell us about your space. We confirm scope, price and availability before booking.", email="hello@clearday.example", phone="+44 114 555 0128",
         services_title="The right clean for your space",
         services_lead="Choose the starting point that fits your needs. We agree priorities and any extras before the first visit.",
         services=[("Regular home cleaning", "A weekly or fortnightly visit covering agreed rooms, surfaces, floors and bathroom cleaning."),
                   ("One-off deep cleaning", "A more detailed clean for a fresh start. Tell us about the condition of the space and any areas that need extra attention."),
                   ("Small workplaces", "Arrange cleaning around your opening hours, with a checklist for desks, shared spaces, kitchens and washrooms.")],
         process_title="A clear checklist from the first conversation",
         process=[("Tell us about the space", "Share the property size, rooms, postcode and whether you need a regular or one-off visit."),
                  ("Agree what is included", "We discuss priorities, access, supplies and any extras, then confirm a quote for the agreed work."),
                  ("Choose a suitable visit", "We confirm availability with you. Your enquiry is a request, not an automatic booking.")],
         area_title="Cleaning that fits your routine", area="Home and small workplace visits across Bristol and surrounding neighbourhoods. Contact us to check availability for your postcode.",
         area_note="Let us know about pets, parking and access arrangements. Oven, carpet and exterior-window cleaning are quoted separately when available.",
         cta_title="Let us know what needs a fresh start", cta_text="Send your postcode, property size and the kind of clean you need. We will confirm the checklist, price and a suitable time with you.", subject="Cleaning quote", call="Ask a cleaning question"),
    dict(slug="salon", name="Salon & Barber", category="unapp_salon",
         brand="Juniper Hair Studio · Bristol", headline="Good hair starts with a conversation.",
         lead="Cuts, styling, barbering and colour consultations in a relaxed neighbourhood studio. Bring an idea or ask us to help you find a look that fits your day.",
         alt="Tan styling chair, rounded mirror and neatly arranged tools in a warm independent hair studio",
         action="Request an appointment", anchor="appointments", secondary="Explore the service menu", services_anchor="salon-services",
         note="Tell us your service and preferred times. We confirm availability before your visit.", email="hello@juniperhair.example", phone="+44 117 555 0192",
         services_title="Find your next appointment",
         services_lead="A starting point for your visit. We discuss the finish you want and confirm the price before the service begins.",
         services=[("Cuts and styling", "A consultation, cut and finish shaped around your hair and the way you like to wear it. Ask about the right appointment length."),
                   ("Barbering and shaping", "Clipper cuts, scissor work and beard shaping. Tell us what you have in mind when requesting a visit."),
                   ("Colour consultations", "Talk through your colour goals, hair history and maintenance routine. We explain any checks needed before a colour appointment.")],
         process_title="Your visit, without the guesswork",
         process=[("Choose a starting point", "Tell us the service you are interested in, your current hair and the change you have in mind."),
                  ("Request a suitable time", "Share a few preferred dates. We confirm availability and any consultation needed before booking."),
                  ("Agree the plan together", "At your visit, discuss the look, upkeep and price before the service starts.")],
         area_title="A neighbourhood studio with time to listen", area="Visit our Bristol studio by appointment. Ask for directions, access information and current opening hours when arranging your visit.",
         area_note="If plans change, please get in touch so we can discuss moving your appointment. Booking and cancellation terms are confirmed with you.",
         cta_title="Ready for your next look?", cta_text="Email the service you want and a few preferred times, or call to talk it through. We will confirm availability and the right next step.", subject="Appointment request", call="Call the studio"),
]

for site in SITES:
    slug = site['slug']
    cats = 'unapp, ' + site['category']
    def emit(part, title, body, anchor=None):
        write_pattern(slug + '-' + part, title=title, cats=cats + ', unapp_content',
                      keywords=slug.replace('-', ', ') + ', local, services',
                      desc=title + ' using editable native WordPress blocks.', body=body, anchor=anchor)

    hero = starter_hero(t(site['brand'], 'Starter hero eyebrow'), t(site['headline']), t(site['lead']),
                        'assets/images/showcase/niche-' + slug + '.webp', tattr(site['alt']),
                        [{'text': t(site['action']), 'url': home_anchor(site['anchor'])},
                         {'text': t(site['secondary']), 'url': home_anchor(site['services_anchor']), 'style': 'outline'}],
                        note=t(site['note']))
    emit('hero', site['name'] + ': introduction', hero)
    cards = '\n'.join(card(card_title(t(title)) + '\n' + para(t(text), color='muted')) for title, text in site['services'])
    emit('services', site['name'] + ': services', section_std(
        intro(eyebrow_text=t('Our services', 'Section eyebrow label'), title=t(site['services_title']), lead=t(site['services_lead'])) + '\n' + grid(cards, cols=3)), site['services_anchor'])
    steps = '\n'.join(card(label(t('Step ' + str(index + 1))) + '\n' + card_title(t(title)) + '\n' + para(t(text), color='muted')) for index, (title, text) in enumerate(site['process']))
    emit('process', site['name'] + ': how it works', section_std(
        intro(eyebrow_text=t('How it works', 'Section eyebrow label'), title=t(site['process_title'])) + '\n' + grid(steps, cols=3),
        variation='is-style-section-soft'), slug + '-process')
    emit('location', site['name'] + ': location and visits', section_std(
        intro(eyebrow_text=t('Plan your visit' if slug == 'salon' else 'Where we work', 'Section eyebrow label'), title=t(site['area_title']), lead=t(site['area'])) + '\n' +
        group(para(t(site['area_note']), color='muted'), layout='constrained', content_size=READ_WIDTH)), slug + '-location')
    emit('cta', site['name'] + ': contact request', band(t(site['cta_title']), t(site['cta_text']),
         [{'text': t(site['action']), 'url': mailto(site['email'], site['subject']), 'bg': 'base', 'color': 'contrast'},
          {'text': t(site['call']), 'url': tel(site['phone']), 'style': 'outline', 'color': 'base'}]), site['anchor'])
    emit('contact', site['name'] + ': enquiry details', section_std(
        intro(eyebrow_text=t('Get in touch', 'Section eyebrow label'), title=t(site['cta_title']), lead=t(site['cta_text'])) + '\n' +
        group(contact_form(site['action'], site['email']), layout='constrained', content_size=READ_WIDTH)))
    body = '\n'.join('<!-- wp:pattern {"slug":"unapp/' + slug + '-' + part + '"} /-->' for part in ('hero', 'services', 'process', 'location', 'cta'))
    write_pattern('demo-' + slug, title='Starter site: ' + site['name'], cats='unapp_page',
                  keywords=slug.replace('-', ', ') + ', starter, local, business',
                  desc='A complete ' + site['name'].lower() + ' homepage.', body=body,
                  block_types='core/post-content', post_types='page, wp_template')
