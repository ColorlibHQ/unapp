"""Finance starter sections, on the house style."""
import os, sys; sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *
from batch13 import php_rows, loop

N = "unapp, unapp_finance"

# ---------------------------------------------------------------- how advice works
STEPS = [
    ("A conversation", "An hour on the phone or in the office, at our cost. You describe what you are worried about; we tell you honestly whether we can help."),
    ("A written plan", "Where you stand today, what you want, and the gap between the two — in a document your family could read without us in the room."),
    ("Putting it in place", "We do the paperwork, the transfers and the chasing. You sign things and otherwise get on with your life."),
    ("A review every year", "Markets move, tax rules change and so do you. We meet each year and adjust, or confirm that nothing needs adjusting."),
]
prelude = php_rows("unapp_finance_steps", ("title", "text"), STEPS, "Advice step")
prelude += "$unapp_finance_step_number = 0;\n"
step = stack(
    label(php("str_pad( (string) ++$unapp_finance_step_number, 2, '0', STR_PAD_LEFT )")) + "\n" +
    card_title(php("$unapp_finance_step['title']")) + "\n" +
    para(php("$unapp_finance_step['text']"), color="muted", size="small"),
    gap="20")
body = section_std(
    intro(eyebrow_text=t("How it works", "Section eyebrow label"),
          title=t("Four steps, and you can stop after any of them"),
          lead=t("Nothing is charged until you have seen the plan and agreed to it in writing.")) + "\n" +
    grid(loop("unapp_finance_steps", "unapp_finance_step", step), cols=4))
write_pattern("finance-process", title="Finance: how advice works", cats=N + ", unapp_features, steps",
              keywords="finance, adviser, process, steps, how it works, advice",
              desc="The four stages of an advice relationship, numbered, with the reassurance that you can stop at any of them.",
              body=body, php_prelude=prelude)

# ---------------------------------------------------------------- advisers
TEAM = [
    ("avatar-5", "Helen Ashworth", "Chartered Financial Planner · FCII, CFP",
     "Twenty-eight years advising families through retirement, divorce and inheritance. Founded the firm in 2009."),
    ("avatar-10", "Idris Mahmood", "Financial Planner · DipPFS",
     "Specialises in company directors and the awkward business of extracting money from your own company sensibly."),
    ("avatar-4", "Sarah Whitcombe", "Paraplanner · DipPFS",
     "Writes the plans, models the scenarios and finds the pension nobody remembered they had."),
]
person = card(
    avatar(php("get_theme_file_uri( 'assets/images/avatars/' . $unapp_finance_person['image'] . '.svg' )"),
           php("$unapp_finance_person['name']")) + "\n" +
    card_title(php("$unapp_finance_person['name']")) + "\n" +
    label(php("$unapp_finance_person['role']")) + "\n" +
    para(php("$unapp_finance_person['note']"), color="muted", size="small"))
body = section_std(
    intro(eyebrow_text=t("The team", "Section eyebrow label"),
          title=t("Three people, and you will deal with all of them"),
          lead=t("No call centre, and no account manager who leaves every eighteen months.")) + "\n" +
    grid(loop("unapp_finance_team", "unapp_finance_person", person), cols=3))
write_pattern("finance-team", title="Finance: advisers", cats=N + ", unapp_company, team",
              keywords="finance, adviser, team, chartered, planner, qualifications",
              desc="Three advisers with their qualifications spelled out — the credential check a prospective client makes first.",
              body=body,
              php_prelude=php_rows("unapp_finance_team", ("image", "name", "role", "note"), TEAM, "Adviser"))

# ---------------------------------------------------------------- fees
FEES = [
    ("Initial advice", "£1,800", "Fixed, quoted before we start and payable only once you have the written plan in your hands."),
    ("Ongoing advice", "0.65%", "A year on the money we look after, billed monthly. It covers the annual review and everything in between."),
    ("Nothing else", "£0", "No commission, no product kickbacks, and no charge for phoning us with a question."),
]
fee = stack(
    label(php("$unapp_finance_fee['label']"), color="muted") + "\n" +
    para(php("$unapp_finance_fee['amount']"), size="xxx-large", weight="700", line_height="1") + "\n" +
    para(php("$unapp_finance_fee['text']"), color="muted", size="small"),
    gap="20")
body = section_std(
    intro(eyebrow_text=t("What it costs", "Section eyebrow label"),
          title=t("Our fees, on the website, like a plumber"),
          lead=t("You should not have to sit through a meeting to find out what advice costs.")) + "\n" +
    grid(loop("unapp_finance_fees", "unapp_finance_fee", fee), cols=3),
    variation="is-style-section-soft")
write_pattern("finance-fees", title="Finance: fees", cats=N + ", unapp_pricing, pricing",
              keywords="finance, fees, cost, pricing, charges, transparent",
              desc="Three fee cards stating the initial charge, the ongoing percentage and the fact that nothing else is charged.",
              body=body,
              php_prelude=php_rows("unapp_finance_fees", ("label", "amount", "text"), FEES, "Fee"))

# ---------------------------------------------------------------- questions
FAQ = [
    ("Are you actually independent?",
     "Yes. We are independent financial advisers, which is a defined term: we consider the whole of the market and we take no commission from any provider. Our only income is the fee you pay us."),
    ("Is there a minimum I need to invest?",
     "For ongoing advice, around £150,000 — below that our percentage fee is poor value for you, and we will say so. One-off planning work has no minimum at all."),
    ("Who actually holds my money?",
     "Never us. Your money sits with the platform or provider in your own name, and we only ever have permission to advise on it. You can see every account yourself, at any time."),
    ("What if I want to leave?",
     "You give us a month's notice, we hand everything over to your new adviser, and there is no exit charge. Your investments stay exactly where they are."),
]
body = section_std(
    intro(eyebrow_text=t("Questions", "Section eyebrow label"), title=t("The four we are asked most")) + "\n" +
    faq_list([(t(q, "FAQ question"), t(a, "FAQ answer")) for q, a in FAQ]))
write_pattern("finance-faq", title="Finance: questions", cats=N + ", unapp_utility, faq",
              keywords="finance, faq, questions, independent, fees, minimum",
              desc="Independence, minimums, custody of your money and how to leave — answered plainly.",
              body=body)

# ---------------------------------------------------------------- book a call
details_card = card(
    para(t("Office"), weight="600") + "\n" +
    para(t("14 Rodney Street, Liverpool L1 2TE"), color="muted", size="small") + "\n" +
    separator(style="wide", color="border") + "\n" +
    para(t("Telephone"), weight="600") + "\n" +
    para(t("0151 555 0188, 9:00–17:00 weekdays"), color="muted", size="small") + "\n" +
    separator(style="wide", color="border") + "\n" +
    para(t("Email"), weight="600") + "\n" +
    para(t("advice@rodneystreet.example"), color="muted", size="small") + "\n" +
    separator(style="wide", color="border") + "\n" +
    para(t("Regulation"), weight="600") + "\n" +
    para(t("Authorised and regulated by the Financial Conduct Authority, firm reference 000000."),
         color="muted", size="small"))
body = section_std(
    split(
        eyebrow(t("Talk to us", "Section eyebrow label"), align="left") + "\n" +
        heading(t("The first conversation costs nothing")) + "\n" +
        para(t("An hour, on the phone or at the office on Rodney Street, with whichever of us is the right fit. If we are not the right firm for you we will say so, and where we can we will tell you who is."),
             color="muted", size="large") + "\n" +
        buttons([{"text": t("Book a call"), "url": "#book"},
                 {"text": t("Send an email"), "url": "#email", "style": "outline"}]),
        details_card, align="top"),
    gap="0")
write_pattern("finance-contact", title="Finance: book a call", cats=N + ", unapp_utility, contact",
              keywords="finance, contact, book, call, office, regulated",
              desc="An invitation to a free first conversation beside the office, phone, email and FCA registration details.",
              body=body)

print("batch 15 rewritten: 5 finance patterns on the house style")
