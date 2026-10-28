"""Coach, Photographer and Nonprofit starters built entirely from core blocks."""
import os, sys
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *


def emit(slug, part, title, body, anchor=None):
    names = {
        'coach': {'services': 'Ways to work together', 'process': 'Coaching process', 'about': 'The coaching approach', 'contact': 'Coaching enquiry'},
        'photographer': {'work': 'Selected photographs', 'services': 'Photography commissions', 'process': 'Planning a photography project', 'about': 'Behind the camera', 'contact': 'Photography enquiry'},
        'nonprofit': {'programmes': 'Community programmes', 'values': 'How we work together', 'support': 'Ways to support', 'accountability': 'Openness and questions', 'contact': 'Community enquiry'},
    }
    title = names.get(slug, {}).get(part, title)
    write_pattern(slug + '-' + part, title=title, cats='unapp, unapp_' + slug + ', unapp_content',
                  keywords=slug + ', starter, native', desc=title + ' in editable WordPress blocks.', body=body, anchor=anchor)


def cards(rows):
    return grid('\n'.join(card(card_title(t(title)) + '\n' + para(t(text), color='muted')) for title, text in rows), cols=3)


def compose(slug, name, parts):
    write_pattern('demo-' + slug, title='Starter site: ' + name, cats='unapp_page',
                  keywords=slug + ', starter, website', desc='The complete ' + name + ' homepage.',
                  body='\n'.join('<!-- wp:pattern {"slug":"unapp/' + slug + '-' + part + '"} /-->' for part in parts),
                  block_types='core/post-content', post_types='page, wp_template')


def enquiry(slug, name, title, text, action, email, phone, subject, anchor):
    emit(slug, 'cta', name + ': get in touch', band(t(title), t(text),
         [{'text': t(action), 'url': mailto(email, subject), 'bg': 'base', 'color': 'contrast'},
          {'text': t('Talk it through'), 'url': tel(phone), 'style': 'outline', 'color': 'base'}]), anchor)
    emit(slug, 'contact', name + ': enquiry details', section_std(
        intro(eyebrow_text=t('Get in touch', 'Section eyebrow label'), title=t(title), lead=t(text)) + '\n' +
        group(contact_form(action, email), layout='constrained', content_size=READ_WIDTH)))


# COACH & CONSULTANT — agreed goals and practical next steps, no outcome guarantees.
emit('coach', 'hero', 'Coach & Consultant: introduction', starter_hero(
    t('Northward Coaching · Clearer next steps', 'Starter hero eyebrow'), t('Make room for your next move.'),
    t('Business coaching and practical consulting for people leading a team, building a business or working through a change. Start with a conversation about what matters next.'),
    'assets/images/showcase/niche-coach.webp', tattr('Two comfortable chairs around a small table with a notebook in a calm coaching workspace'),
    [{'text': t('Request a discovery call'), 'url': home_anchor('coach-enquiry')},
     {'text': t('Explore ways to work together'), 'url': home_anchor('coaching-services'), 'style': 'outline'}],
    note=t('An initial conversation to discuss fit, scope and the right next step.')))
emit('coach', 'services', 'Coach & Consultant: ways to work together', section_std(
    intro(eyebrow_text=t('Ways to work together', 'Section eyebrow label'), title=t('Support shaped around the work in front of you'),
          lead=t('Choose a starting point. We agree the focus, format and fees before work begins.')) + '\n' + cards([
        ('One-to-one coaching', 'A focused space to explore decisions, leadership habits and the next steps you want to take.'),
        ('Team workshops', 'Practical sessions around shared priorities, communication and working together through change.'),
        ('Business consulting', 'Work through a specific business challenge with an agreed scope, useful outputs and a clear review point.'),
    ])), 'coaching-services')
emit('coach', 'process', 'Coach & Consultant: the process', section_std(
    intro(eyebrow_text=t('How it works', 'Section eyebrow label'), title=t('A clear agreement, then useful work')) + '\n' + cards([
        ('Start with a conversation', 'Share the situation, what you hope to change and any questions about working together.'),
        ('Agree the focus', 'Discuss scope, format, timing and fees. Decide together whether this is a useful fit.'),
        ('Work and review', 'Turn conversations into practical next steps, with time to reflect and adjust the approach.'),
    ]), variation='is-style-section-soft'), 'coaching-process')
emit('coach', 'about', 'Coach & Consultant: approach', section_std(
    intro(eyebrow_text=t('The approach', 'Section eyebrow label'), title=t('Space to think. A practical way forward.'),
          lead=t('Good work starts by understanding the situation, not arriving with a ready-made answer.')) + '\n' +
    group(para(t('My role is to listen carefully, ask useful questions and help make the work ahead more manageable. You bring the knowledge of your business and the decisions that are yours to make.'), color='muted') + '\n' +
          para(t('Sessions can be arranged online or in person where practical. Ask about experience relevant to your brief, availability and how we would work together.'), color='muted'), layout='constrained', content_size=READ_WIDTH)), 'coaching-approach')
enquiry('coach', 'Coach & Consultant', 'What would you like to work through?',
        'Tell me about your situation and a few suitable times to talk. I will reply with availability and next steps; sending an enquiry does not book a session.',
        'Request a discovery call', 'hello@northward.example', '+44 113 555 0177', 'Discovery call request', 'coach-enquiry')
compose('coach', 'Coach & Consultant', ['hero', 'services', 'process', 'about', 'cta'])

# PHOTOGRAPHER — original illustrative photographs, editable image-led work grid.
emit('photographer', 'hero', 'Photographer: introduction', starter_hero(
    t('Ellis Rowan · Photography', 'Starter hero eyebrow'), t('Photographs with a sense of place.'),
    t('Landscapes, objects and spaces, approached with patience and an eye for the details. Explore selected work, then tell me what you have in mind.'),
    'assets/images/showcase/niche-photographer.webp', tattr('Sea cliffs and a small sandy bay in soft golden dawn light'),
    [{'text': t('Discuss a project'), 'url': home_anchor('photography-enquiry')},
     {'text': t('Explore selected work'), 'url': home_anchor('selected-work'), 'style': 'outline'}],
    note=t('Editorial and commercial enquiries · Scope and availability agreed together')))
work = []
for title, text, filename, alt in [
    ('Coast and weather', 'Landscape studies in light, texture and changing weather.', 'niche-photographer.webp', 'Sunlit rock formations and surf along a quiet coastline'),
    ('Objects and light', 'Still-life compositions with honest materials and quiet detail.', 'niche-photographer-still-life.webp', 'Three handmade ceramic vessels on linen in warm window light'),
    ('Spaces and structure', 'Architectural studies of shape, rhythm and the spaces between.', 'niche-photographer-architecture.webp', 'A curved stone staircase with angular sunlight and a blue-sky opening'),
]:
    work.append(group(image(uri('assets/images/showcase/' + filename), tattr(alt), radius=CARD_RADIUS, aspect='4/3', scale='cover') + '\n' +
                      card_title(t(title)) + '\n' + para(t(text), color='muted'), layout='constrained', gap=CARD_GAP))
emit('photographer', 'work', 'Photographer: selected work', section_std(
    intro(eyebrow_text=t('Selected work', 'Section eyebrow label'), title=t('A few ways of seeing'), lead=t('Landscape, still life and architecture. Ask about a commission in a similar direction.')) + '\n' + grid('\n'.join(work), cols=3)), 'selected-work')
emit('photographer', 'services', 'Photographer: commissions', section_std(
    intro(eyebrow_text=t('Commissions', 'Section eyebrow label'), title=t('Images with a purpose beyond the frame')) + '\n' + cards([
        ('Editorial assignments', 'A considered set of photographs for a story, publication or place. Share your subject and intended use.'),
        ('Products and makers', 'Still-life and detail photography shaped around the objects, materials and people behind your work.'),
        ('Spaces and interiors', 'Photographs of architecture and interiors, with a plan for access, light and the details that matter.'),
    ]), variation='is-style-section-soft'), 'photography-services')
emit('photographer', 'process', 'Photographer: project process', section_std(
    intro(eyebrow_text=t('From brief to delivery', 'Section eyebrow label'), title=t('Make the important details clear from the start')) + '\n' + cards([
        ('Share the brief', 'Tell me what you need, where the images will be used, the location and your preferred timing.'),
        ('Agree a plan', 'We discuss the approach, deliverables, usage, fees and schedule before confirming the assignment.'),
        ('Create and deliver', 'Work to the agreed brief, then select and deliver the photographs in the formats we have discussed.'),
    ])), 'photography-process')
emit('photographer', 'about', 'Photographer: approach', section_std(
    intro(eyebrow_text=t('Behind the camera', 'Section eyebrow label'), title=t('Notice what is already there.'), lead=t('I am drawn to ordinary materials, changing light and the feeling of a place.')) + '\n' +
    group(para(t('The approach is patient and collaborative: understand the story, look closely and make a set of photographs that belongs together. For commissioned work, that starts with understanding your audience and the use you have in mind.'), color='muted') + '\n' +
          para(t('Get in touch to discuss your brief, ask about a relevant body of work or check availability. We can agree the right approach before making plans.'), color='muted'), layout='constrained', content_size=READ_WIDTH), variation='is-style-section-soft'), 'photographer-about')
enquiry('photographer', 'Photographer', 'What would you like to make?',
        'Send a short brief, intended use, location and timing. I will reply with availability and the next steps for your project.',
        'Discuss a project', 'hello@ellisrowan.example', '+44 114 555 0128', 'Photography enquiry', 'photography-enquiry')
compose('photographer', 'Photographer', ['hero', 'work', 'services', 'process', 'about', 'cta'])

# NONPROFIT — involvement and transparent giving enquiries, no checkout dependency.
emit('nonprofit', 'hero', 'Nonprofit: introduction', starter_hero(
    t('Common Ground Collective · Neighbours helping neighbours', 'Starter hero eyebrow'), t('A stronger community starts close to home.'),
    t('Bringing neighbours together through shared meals, practical support and opportunities to learn from one another. Find out what is happening and how you can help.'),
    'assets/images/showcase/niche-nonprofit.webp', tattr('Reusable bags of fresh vegetables and folded aprons on a community hall table'),
    [{'text': t('Ways to support'), 'url': home_anchor('support')},
     {'text': t('Explore our programmes'), 'url': home_anchor('programmes'), 'style': 'outline'}],
    note=t('Give time, offer practical support or ask about contributing.')))
emit('nonprofit', 'programmes', 'Nonprofit: programmes', section_std(
    intro(eyebrow_text=t('Our programmes', 'Section eyebrow label'), title=t('Practical ways to bring people together'), lead=t('Ask about current activities, who can take part and how to get involved.')) + '\n' + cards([
        ('Community meals', 'A place to share food and conversation. Contact us for current sessions and ways to help with preparation.'),
        ('Practical support', 'Connecting offers of everyday essentials with current local needs. Check what is useful before bringing items.'),
        ('Shared skills', 'Opportunities to learn from neighbours and share useful skills. Ask about upcoming activities or suggest an idea.'),
    ])), 'programmes')
emit('nonprofit', 'values', 'Nonprofit: how we work', section_std(
    intro(eyebrow_text=t('How we work', 'Section eyebrow label'), title=t('Listen first. Work alongside one another.')) + '\n' + cards([
        ('Start with local priorities', 'Listen to the people involved and shape activities around the needs they describe.'),
        ('Make involvement practical', 'Explain what is needed, how to join in and who to contact before someone commits their time.'),
        ('Keep the conversation open', 'Welcome questions, feedback and ideas about the work and how support is used.'),
    ]), variation='is-style-section-soft'), 'nonprofit-values')
support = []
for title, text, action, subject in [
    ('Give your time', 'Tell us what you enjoy doing, your availability and any access needs. We will discuss suitable opportunities.', 'Ask about volunteering', 'Volunteering enquiry'),
    ('Offer practical support', 'Ask which items or skills are currently useful before arranging a contribution or collection.', 'Check current needs', 'Practical support enquiry'),
    ('Ask about giving', 'Get in touch for current ways to contribute and information about how support is used.', 'Discuss a contribution', 'Giving enquiry'),
]:
    support.append(card(card_title(t(title)) + '\n' + para(t(text), color='muted') + '\n' + buttons([{'text': t(action), 'url': mailto('hello@commonground.example', subject)}])))
emit('nonprofit', 'support', 'Nonprofit: ways to support', section_std(
    intro(eyebrow_text=t('Ways to support', 'Section eyebrow label'), title=t('Find the way to help that fits you'), lead=t('A conversation is a useful first step. We will confirm current needs and the appropriate next step with you.')) + '\n' + grid('\n'.join(support), cols=3)), 'support')
emit('nonprofit', 'accountability', 'Nonprofit: openness and questions', section_std(
    intro(eyebrow_text=t('Questions are welcome', 'Section eyebrow label'), title=t('Understand the work before you decide to help.'),
          lead=t('Ask about current programmes, the people coordinating them and how contributions are used.')) + '\n' +
    group(para(t('We can share relevant information before you decide to volunteer, offer items or make a financial contribution. Contact us for current giving details rather than sending funds or items without checking first.'), color='muted'), layout='constrained', content_size=READ_WIDTH), variation='is-style-section-soft'), 'nonprofit-questions')
enquiry('nonprofit', 'Nonprofit', 'Let us know how you would like to get involved',
        'Ask about taking part, volunteering or supporting a programme. We will reply with current information and the right person to speak to.',
        'Get involved', 'hello@commonground.example', '+44 117 555 0192', 'Getting involved', 'get-involved')
compose('nonprofit', 'Nonprofit', ['hero', 'programmes', 'values', 'support', 'accountability', 'cta'])
