"""Finance starter: process, advisers, fees, FAQ and contact in a regulated-adviser voice."""
import os, sys; sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *

N = "unapp, unapp_finance"

# ---------------------------------------------------------------- how advice works
STEPS = [
    ("A conversation", "An hour on the phone or in the office, at our cost. You describe what you are worried about; we tell you honestly whether we can help."),
    ("A written plan", "Where you stand today, what you want, and the gap between the two — in a document your family could read without us in the room."),
    ("Putting it in place", "We do the paperwork, the transfers and the chasing. You sign things and otherwise get on with your life."),
    ("A review every year", "Markets move, tax rules change and so do you. We meet each year and adjust, or confirm that nothing needs adjusting."),
]
prelude = "$unapp_finance_steps = array(\n"
for title, text in STEPS:
    prelude += ("\tarray(\n"
                f"\t\t'title' => _x( '{title}', 'Advice step title', 'unapp' ),\n"
                f"\t\t'text'  => _x( '{text}', 'Advice step text', 'unapp' ),\n"
                "\t),\n")
prelude += ");\n$unapp_finance_step_number = 0;\n"

card = group(
    para(php("str_pad( (string) ++$unapp_finance_step_number, 2, '0', STR_PAD_LEFT )"),
         color="primary", size="large", weight="700", letter="0.06em") + "\n" +
    heading(php("$unapp_finance_step['title']"), level=3, size="medium") + "\n" +
    para(php("$unapp_finance_step['text']"), color="muted", size="small"),
    layout="flex", orientation="vertical", gap="12")
loop = ("<?php foreach ( $unapp_finance_steps as $unapp_finance_step ) : ?>\n"
        + card + "\n<?php endforeach; ?>")
body = section(
    intro(eyebrow_text=t("How it works", "Section eyebrow label"),
          title=t("Four steps, and you can stop after any of them"),
          lead=t("Nothing is charged until you have seen the plan and agreed to it in writing.")) + "\n" +
    group(loop, align="wide", layout="grid", col_count=4, gap="40", class_name="unapp-grid-4"),
    pad=("60", "70"), gap="50")
write_pattern("finance-process", title="Finance: how advice works", cats=N + ", unapp_features, steps",
              keywords="finance, adviser, process, steps, how it works, advice",
              desc="The four stages of an advice relationship, numbered, with the reassurance that you can stop at any of them.",
              body=body, php_prelude=prelude)

# ---------------------------------------------------------------- advisers
TEAM = [
    ("avatar-5", "Helen Ashworth", "Chartered Financial Planner", "FCII, CFP",
     "Twenty-eight years advising families through retirement, divorce and inheritance. Founded the firm in 2009."),
    ("avatar-10", "Idris Mahmood", "Financial Planner", "DipPFS",
     "Specialises in company directors and the awkward business of extracting money from your own company sensibly."),
    ("avatar-4", "Sarah Whitcombe", "Paraplanner", "DipPFS",
     "Writes the plans, models the scenarios and finds the pension nobody remembered they had."),
]
prelude = "$unapp_finance_team = array(\n"
for img_slug, name, role, quals, note in TEAM:
    prelude += ("\tarray(\n"
                f"\t\t'image' => '{img_slug}',\n"
                f"\t\t'name'  => _x( '{name}', 'Adviser name', 'unapp' ),\n"
                f"\t\t'role'  => _x( '{role}', 'Adviser role', 'unapp' ),\n"
                f"\t\t'quals' => _x( '{quals}', 'Adviser qualifications', 'unapp' ),\n"
                f"\t\t'note'  => _x( '{note}', 'Adviser note', 'unapp' ),\n"
                "\t),\n")
prelude += ");\n"
card = group(
    image(php("get_theme_file_uri( 'assets/images/avatars/' . $unapp_finance_person['image'] . '.svg' )"),
          php("$unapp_finance_person['name']"), width="110px", height="110px", radius="999px") + "\n" +
    heading(php("$unapp_finance_person['name']"), level=3, size="medium") + "\n" +
    para(php("$unapp_finance_person['role'] . ' · ' . $unapp_finance_person['quals']"),
         color="primary", size="small", weight="600") + "\n" +
    para(php("$unapp_finance_person['note']"), color="muted", size="small"),
    style_variation="is-style-card", radius="18px", layout="flex", orientation="vertical", gap="14",
    pad={"top": "40", "bottom": "40", "left": "40", "right": "40"})
loop = ("<?php foreach ( $unapp_finance_team as $unapp_finance_person ) : ?>\n"
        + card + "\n<?php endforeach; ?>")
body = section(
    intro(eyebrow_text=t("The team", "Section eyebrow label"),
          title=t("Three people, and you will deal with all of them"),
          lead=t("No call centre, no account manager who leaves every eighteen months.")) + "\n" +
    group(loop, align="wide", layout="grid", col_count=3, gap="40", class_name="unapp-grid-3"),
    pad=("60", "70"), gap="50")
write_pattern("finance-team", title="Finance: advisers", cats=N + ", unapp_company, team",
              keywords="finance, adviser, team, chartered, planner, qualifications",
              desc="Three advisers with their qualifications spelled out — the credential check a prospective client makes first.",
              body=body, php_prelude=prelude)

# ---------------------------------------------------------------- fees
FEES = [
    ("Initial advice", "£1,800", "Fixed, quoted before we start and payable only once you have the written plan in your hands."),
    ("Ongoing advice", "0.65%", "A year on the money we look after, billed monthly. It covers the annual review and everything in between."),
    ("Nothing else", "£0", "No commission, no product kickbacks, no charge for phoning us with a question."),
]
prelude = "$unapp_finance_fees = array(\n"
for label, amount, text in FEES:
    prelude += ("\tarray(\n"
                f"\t\t'label'  => _x( '{label}', 'Fee label', 'unapp' ),\n"
                f"\t\t'amount' => _x( '{amount}', 'Fee amount', 'unapp' ),\n"
                f"\t\t'text'   => _x( '{text}', 'Fee description', 'unapp' ),\n"
                "\t),\n")
prelude += ");\n"
card = group(
    para(php("$unapp_finance_fee['label']"), color="muted", size="small", weight="600",
         letter="0.06em", transform="uppercase") + "\n" +
    para(php("$unapp_finance_fee['amount']"), size="xxx-large", weight="700", line_height="1") + "\n" +
    para(php("$unapp_finance_fee['text']"), color="muted", size="small"),
    layout="flex", orientation="vertical", gap="10")
loop = ("<?php foreach ( $unapp_finance_fees as $unapp_finance_fee ) : ?>\n"
        + card + "\n<?php endforeach; ?>")
body = section(
    intro(eyebrow_text=t("What it costs", "Section eyebrow label"),
          title=t("Our fees, on the website, like a plumber"),
          lead=t("You should not have to sit through a meeting to find out what advice costs.")) + "\n" +
    group(loop, align="wide", layout="grid", col_count=3, gap="40", class_name="unapp-grid-3"),
    style_variation="is-style-section-soft", pad=("60", "70"), gap="50")
write_pattern("finance-fees", title="Finance: fees", cats=N + ", unapp_pricing, pricing",
              keywords="finance, fees, cost, pricing, charges, transparent",
              desc="Three fee cards stating the initial charge, the ongoing percentage and the fact that nothing else is charged.",
              body=body, php_prelude=prelude)

# ---------------------------------------------------------------- FAQ
FAQ = [
    ("Are you actually independent?",
     "Yes. We are independent financial advisers, which is a defined term: we consider the whole of the market and we take no commission from any provider. Our only income is the fee you pay us."),
    ("Is there a minimum I need to invest?",
     "For ongoing advice, around £150,000 — below that our percentage fee is poor value for you and we will say so. One-off planning work has no minimum at all."),
    ("Who actually holds my money?",
     "Never us. Your money sits with the platform or provider in your own name, and we only ever have permission to advise on it. You can see every account yourself, at any time."),
    ("What if I want to leave?",
     "You give us a month's notice, we hand over everything to your new adviser, and there is no exit charge. Your investments stay exactly where they are."),
]
items = [details(t(q, "FAQ question"), para(t(a, "FAQ answer"), color="muted"),
                 class_name="is-style-faq-card") for q, a in FAQ]
body = section(
    intro(eyebrow_text=t("Questions", "Section eyebrow label"),
          title=t("The four we are asked most")) + "\n" +
    group("\n".join(items), layout="constrained", content_size="760px", gap="16"),
    pad=("60", "70"), gap="50")
write_pattern("finance-faq", title="Finance: questions", cats=N + ", unapp_utility, faq",
              keywords="finance, faq, questions, independent, fees, minimum",
              desc="Independence, minimums, custody of your money and how to leave — answered plainly.",
              body=body)

# ---------------------------------------------------------------- book a call
body = section(
    columns([
        column(
            eyebrow(t("Talk to us", "Section eyebrow label"), align="left") + "\n" +
            heading(t("The first conversation costs nothing")) + "\n" +
            para(t("An hour, on the phone or at the office on Rodney Street, with whichever of us is the right fit. If we are not the right firm for you we will tell you, and where possible we will tell you who is."),
                 color="muted", size="large") + "\n" +
            buttons([{"text": t("Book a call"), "url": "#book"},
                     {"text": t("Send an email"), "url": "#email", "style": "outline"}],
                    margin={"top": "10"}),
            width="52%", vertical_align="top", gap="20"),
        column(
            group(
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
                     color="muted", size="small"),
                style_variation="is-style-card", radius="18px", gap="12",
                pad={"top": "40", "bottom": "40", "left": "40", "right": "40"}),
            width="48%", vertical_align="top"),
    ], align="wide", gap="60", vertical_align="top"),
    pad=("60", "70"), gap="0")
write_pattern("finance-contact", title="Finance: book a call", cats=N + ", unapp_utility, contact",
              keywords="finance, contact, book, call, office, regulated",
              desc="An invitation to a free first conversation beside the office, phone, email and FCA registration details.",
              body=body)

print("batch 15 written: 5 finance patterns")
