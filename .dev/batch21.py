"""Real estate, medical, education and events starter sections."""
import os, sys; sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *

RE = "unapp, unapp_realestate"
ME = "unapp, unapp_medical"
ED = "unapp, unapp_education"
EV = "unapp, unapp_events"

# ================================================================= REAL ESTATE
body = section_std(
    split(
        eyebrow(t("Sales and lettings, Bath", "Estate agent hero eyebrow"), align="left") + "\n" +
        heading(t("We only take on houses we would live in ourselves"), size="xxx-large",
                line_height="1.05") + "\n" +
        para(t("A small agency covering Bath and the villages east of it. Twelve properties on the books at a time, because that is how many we can show properly."),
             color="muted", size="large") + "\n" +
        buttons([{"text": t("See what is for sale"), "url": "#listings"},
                 {"text": t("Book a valuation"), "url": "#valuation", "style": "outline"}]),
        image(uri("assets/images/abstract/skyline.svg"), tattr("Bath rooftops"), radius=CARD_RADIUS),
        left_width="55%", right_width="45%"),
    gap="0")
write_pattern("realestate-hero", title="Property: introduction", cats=RE + ", banner, featured",
              keywords="real estate, property, estate agent, hero, homes",
              desc="An estate agency introduction with a valuation call to action.",
              body=body)

LISTINGS = [
    ("skyline", "Lansdown Crescent", "£1,250,000", "4 bed · 2 bath · Grade I listed"),
    ("desk", "Walcot Street", "£465,000", "2 bed · 1 bath · Top-floor flat"),
    ("gathering", "Bathford Mill", "£720,000", "3 bed · 2 bath · Converted mill"),
    ("studio-1", "Bear Flat", "£389,000", "2 bed · 1 bath · Victorian terrace"),
]
prelude = "$unapp_listings = array(\n"
for img, name, price, meta in LISTINGS:
    prelude += ("\tarray(\n"
                f"\t\t'image' => '{img}',\n"
                f"\t\t'name'  => _x( '{name}', 'Property name', 'unapp' ),\n"
                f"\t\t'price' => _x( '{price}', 'Property price', 'unapp' ),\n"
                f"\t\t'meta'  => _x( '{meta}', 'Property details', 'unapp' ),\n"
                "\t),\n")
prelude += ");\n"
listing = stack(
    image(php("get_theme_file_uri( 'assets/images/abstract/' . $unapp_listing['image'] . '.svg' )"),
          php("$unapp_listing['name']"), radius=CARD_RADIUS, aspect="4/3", scale="cover") + "\n" +
    card_title(php("$unapp_listing['name']")) + "\n" +
    para(php("$unapp_listing['price']"), color="primary", weight="700") + "\n" +
    para(php("$unapp_listing['meta']"), color="muted", size="small"),
    gap="20")
body = section_std(
    intro(eyebrow_text=t("For sale", "Section eyebrow label"),
          title=t("On the books this week"),
          lead=t("Everything we are selling, with the price on it. No 'guide price on application'.")) + "\n" +
    grid(loop("unapp_listings", "unapp_listing", listing), cols=4))
write_pattern("realestate-listings", title="Property: listings", cats=RE + ", unapp_content",
              keywords="real estate, property, listings, for sale, homes, grid",
              desc="Four properties with photograph, price and the room count.",
              body=body, php_prelude=prelude)

FEES = [
    ("Sole agency", "1.2%", "Plus VAT, payable on completion and nothing before it."),
    ("Photography and floor plan", "Included", "Taken by a photographer, not by whoever is free that morning."),
    ("Lettings management", "8%", "Of the rent, monthly. Tenant find only is one month's rent."),
]
prelude = php_rows("unapp_estate_fees", ("label", "amount", "text"), FEES, "Agency fee")
fee = stack(
    label(php("$unapp_estate_fee['label']"), color="muted") + "\n" +
    para(php("$unapp_estate_fee['amount']"), size="xxx-large", weight="700", line_height="1") + "\n" +
    para(php("$unapp_estate_fee['text']"), color="muted", size="small"),
    gap="20")
body = section_std(
    intro(eyebrow_text=t("What we charge", "Section eyebrow label"),
          title=t("Our fees, in public")) + "\n" +
    grid(loop("unapp_estate_fees", "unapp_estate_fee", fee), cols=3),
    variation="is-style-section-soft")
write_pattern("realestate-fees", title="Property: fees", cats=RE + ", unapp_pricing, pricing",
              keywords="real estate, fees, commission, lettings, charges",
              desc="Three published agency fees, so a seller knows the cost before the valuation.",
              body=body, php_prelude=prelude)

body = section_std(
    split(
        eyebrow(t("Book a valuation", "Section eyebrow label"), align="left") + "\n" +
        heading(t("An hour, and an honest number")) + "\n" +
        para(t("We will tell you what your house is worth and what it would take to get there — including the jobs worth doing and the ones that are not."),
             color="muted", size="large") + "\n" +
        para(t("hello@lansdown.example · 01225 555 0163"), color="muted"),
        card(contact_form("Book a valuation", "hello@lansdown.example")),
        align="top"),
    gap="0")
write_pattern("realestate-valuation", title="Property: book a valuation", cats=RE + ", unapp_utility, contact",
              keywords="real estate, valuation, contact, book, appraisal",
              desc="A valuation enquiry section with the contact form.",
              body=body)

# ================================================================= MEDICAL
body = section_std(
    split(
        eyebrow(t("NHS and private · Est. 1994", "Practice hero eyebrow"), align="left") + "\n" +
        heading(t("A dental practice that runs on time")) + "\n" +
        para(t("Six surgeries on Fore Street, open six days a week, with emergency slots kept back every morning for the people who need them that day."),
             color="muted", size="large") + "\n" +
        buttons([{"text": t("Book an appointment"), "url": "#book"},
                 {"text": t("Our treatments"), "url": "#treatments", "style": "outline"}]),
        image(uri("assets/images/abstract/studio-2.svg"), tattr("The practice"), radius=CARD_RADIUS),
        left_width="55%", right_width="45%"),
    gap="0")
write_pattern("medical-hero", title="Practice: introduction", cats=ME + ", banner, featured",
              keywords="medical, dental, clinic, practice, hero, appointment",
              desc="A practice introduction with booking and treatments calls to action.",
              body=body)

TREATMENTS = [
    ("heart", "Check-ups and hygiene", "Twenty minutes, every six months, and a plan you can actually follow."),
    ("shield", "Fillings and crowns", "White fillings as standard. Crowns milled here, so it is one visit not three."),
    ("star", "Straightening", "Clear aligners and fixed braces, for adults as often as teenagers now."),
    ("life-buoy", "Emergencies", "Slots held back every morning. Ring before nine and you will be seen that day."),
]
body = section_std(
    intro(eyebrow_text=t("Treatments", "Section eyebrow label"),
          title=t("What we do, in plain English"),
          lead=t("Prices for everything are on the fees page, including the ones nobody likes talking about.")) + "\n" +
    grid(loop("unapp_treatments", "unapp_treatment",
              icon_card("$unapp_treatment['icon']",
                        php("$unapp_treatment['title']"),
                        php("$unapp_treatment['text']"),
                        variation="is-style-card")), cols=4))
write_pattern("medical-services", title="Practice: treatments", cats=ME + ", unapp_features",
              keywords="medical, dental, treatments, services, clinic",
              desc="Four treatment cards written for patients rather than for clinicians.",
              body=body,
              php_prelude=php_rows("unapp_treatments", ("icon", "title", "text"), TREATMENTS, "Treatment"))

TEAM = [
    ("avatar-5", "Dr Anna Petrou", "Principal dentist · BDS, MFDS RCS", "Here since 2004. Special interest in nervous patients, which is most of us."),
    ("avatar-10", "Dr Samuel Oyelaran", "Dentist · BDS, MSc", "Implants and restorative work. Trains other dentists on Fridays."),
    ("avatar-4", "Marie Colbert", "Hygienist · RDH", "Runs the hygiene programme and the school visits."),
]
person = card(
    avatar(php("get_theme_file_uri( 'assets/images/avatars/' . $unapp_clinician['image'] . '.svg' )"),
           php("$unapp_clinician['name']")) + "\n" +
    card_title(php("$unapp_clinician['name']")) + "\n" +
    label(php("$unapp_clinician['role']")) + "\n" +
    para(php("$unapp_clinician['note']"), color="muted", size="small"))
body = section_std(
    intro(eyebrow_text=t("The team", "Section eyebrow label"),
          title=t("Who will be treating you"),
          lead=t("Every clinician's registration number is on the wall in reception and on their page here.")) + "\n" +
    grid(loop("unapp_clinicians", "unapp_clinician", person), cols=3),
    variation="is-style-section-soft")
write_pattern("medical-team", title="Practice: clinicians", cats=ME + ", unapp_company, team",
              keywords="medical, dental, team, clinicians, dentists, staff",
              desc="Three clinicians with qualifications — the credential check a patient makes first.",
              body=body,
              php_prelude=php_rows("unapp_clinicians", ("image", "name", "role", "note"), TEAM, "Clinician"))

FAQ = [
    ("Are you taking NHS patients?",
     "For children, always. For adults the list opens a few times a year and we announce it here and on the door — there is no waiting list to join, which we know is annoying, but it is fairer than a list nobody moves up."),
    ("What if something happens at the weekend?",
     "Ring the practice number and the answerphone gives you the out-of-hours service. For anything involving swelling that is spreading, or difficulty swallowing, go to A&E rather than waiting for us."),
    ("I have not been to a dentist in years.",
     "Then you are in good company, and nobody here will make a thing of it. Book a check-up, say on the phone that it has been a while, and we will give you a longer appointment."),
    ("Do you take card, and can I pay monthly?",
     "Card, yes. There is also a monthly plan that covers check-ups, hygiene and a discount on everything else — ask at reception for the current price."),
]
body = section_std(
    intro(eyebrow_text=t("Questions", "Section eyebrow label"), title=t("Asked at reception most days")) + "\n" +
    faq_list([(t(q, "FAQ question"), t(a, "FAQ answer")) for q, a in FAQ]))
write_pattern("medical-faq", title="Practice: patient questions", cats=ME + ", unapp_utility, faq",
              keywords="medical, dental, faq, nhs, emergency, patients",
              desc="The four questions asked at reception most days, answered without jargon.",
              body=body)

HOURS = [("Monday – Thursday", "08:30 – 17:30"), ("Friday", "08:30 – 16:00"),
         ("Saturday", "09:00 – 13:00"), ("Emergencies", "Ring before 09:00 any weekday")]
rows = []
for day, hrs in HOURS:
    rows.append(columns([
        column(para(t(day, "Opening day"), weight="600"), width="46%", vertical_align="center"),
        column(para(t(hrs, "Opening hours"), color="muted", align="right"), width="54%", vertical_align="center"),
    ], gap="30", vertical_align="center", is_stacked=False))
hours_card = card(card_title(t("Opening hours")) + "\n" +
                  ("\n" + separator(style="wide", color="border") + "\n").join(rows))
body = section_std(
    split(
        eyebrow(t("Find the practice", "Section eyebrow label"), align="left") + "\n" +
        heading(t("42 Fore Street, with parking behind")) + "\n" +
        para(t("Level access from the street, a hearing loop at reception and a lift to the first-floor surgeries. The car park is free for patients for two hours."),
             color="muted", size="large") + "\n" +
        para(t("reception@forestreetdental.example · 01392 555 0146"), color="muted") + "\n" +
        buttons([{"text": t("Book an appointment"), "url": "#book"}]),
        hours_card, align="top"),
    gap="0")
write_pattern("medical-hours", title="Practice: hours and access", cats=ME + ", unapp_utility, contact",
              keywords="medical, dental, hours, address, access, parking",
              desc="Where the practice is, how accessible it is, and when it is open.",
              body=body)

print("batch 21 written: 4 property + 5 practice patterns")
