import os, sys; sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *

PROOF = "unapp, unapp_proof, testimonials, featured"

# ------------------------------------------------------------- testimonials grid
prelude = """$unapp_quotes = array(
	array(
		'quote'  => _x( 'We replaced three tools with Unapp in a fortnight. Our release notes now write themselves from the board.', 'Testimonial quote', 'unapp' ),
		'name'   => _x( 'Priya Raman', 'Testimonial author', 'unapp' ),
		'role'   => _x( 'Head of Product, Northwind', 'Testimonial author role', 'unapp' ),
		'avatar' => 'avatar-2',
	),
	array(
		'quote'  => _x( 'Onboarding used to take a week. New engineers now ship something real on day two.', 'Testimonial quote', 'unapp' ),
		'name'   => _x( 'Tom Alvarez', 'Testimonial author', 'unapp' ),
		'role'   => _x( 'Engineering Manager, Vertex', 'Testimonial author role', 'unapp' ),
		'avatar' => 'avatar-5',
	),
	array(
		'quote'  => _x( 'The reporting alone paid for the year. I can finally answer "where is it?" without a meeting.', 'Testimonial quote', 'unapp' ),
		'name'   => _x( 'Béatrice Laurent', 'Testimonial author', 'unapp' ),
		'role'   => _x( 'COO, Lumen Studio', 'Testimonial author role', 'unapp' ),
		'avatar' => 'avatar-7',
	),
);"""
card_inner = (
    image(uri("assets/images/ui/stars-5.svg"), tattr("Rated 5 out of 5"), width="96px") + "\n" +
    para("<?php echo esc_html( $unapp_quote['quote'] ); ?>", size="large") + "\n" +
    group(
        image("<?php echo esc_url( get_theme_file_uri( 'assets/images/avatars/' . $unapp_quote['avatar'] . '.svg' ) ); ?>",
              "<?php echo esc_attr( $unapp_quote['name'] ); ?>", width="44px", height="44px", radius="999px") + "\n" +
        group(
            para("<?php echo esc_html( $unapp_quote['name'] ); ?>", weight="600", font="heading", size="medium") + "\n" +
            para("<?php echo esc_html( $unapp_quote['role'] ); ?>", color="muted", size="small"),
            gap="20", layout="default"),
        layout="flex", wrap="nowrap", gap="30", vertical_align="center"))
body = section(
    intro(eyebrow_text=t("Customers", "Section eyebrow label"),
          title=t("Teams don't go back"),
          lead=t("A few words from the people who run their week inside Unapp.")) + "\n" +
    columns([column(group(card_inner, style_variation="is-style-card", radius="20px", gap="30", layout="default"))
             .replace("$unapp_quote", "$unapp_quote")], align="wide", gap="40")
        .replace('<div class="wp-block-columns alignwide">\n',
                 '<div class="wp-block-columns alignwide">\n<?php foreach ( $unapp_quotes as $unapp_quote ) : ?>\n')
        .replace('\n</div>\n<!-- /wp:columns -->', '\n<?php endforeach; ?>\n</div>\n<!-- /wp:columns -->'),
    pad=("70", "70"), gap="60")
write_pattern("testimonials", title="Testimonials grid", cats=PROOF,
              keywords="testimonials, quotes, reviews, customers, social proof",
              desc="Three testimonial cards with a rating, quote, portrait and role.",
              body=body, php_prelude=prelude)

# ------------------------------------------------------------- testimonial feature
body = section(
    columns([
        column(image(uri("assets/images/avatars/avatar-3.svg"), tattr("Portrait of the quoted customer"),
                     radius="20px"), width="32%", vertical_align="center"),
        column(
            image(uri("assets/images/logos/harbor.svg"), tattr("Harbor logo"), height="26px") + "\n" +
            para(t("“Unapp gave us one calendar, one backlog and one source of truth. Six months in, nobody asks for the old spreadsheets.”"),
                 size="x-large", font="heading", line_height="1.35", weight="500") + "\n" +
            para(t("Nadia Okafor · VP Engineering, Harbor"), color="muted") + "\n" +
            buttons([{"text": t("Read the case study"), "style": "is-style-outline"}], margin={"top": "30"}),
            width="68%", vertical_align="center", gap="30"),
    ], align="wide", gap="60", vertical_align="center"),
    style_variation="is-style-section-soft", pad=("70", "70"), gap="0")
write_pattern("testimonial-feature", title="Testimonial: single quote", cats=PROOF,
              keywords="testimonial, quote, customer, story, featured",
              desc="One large customer quote with a portrait, logo and a link to the full story.",
              body=body)

# ------------------------------------------------------------- case study
metric = lambda value, label: column(
    para(value, size="xx-large", font="heading", weight="600", line_height="1.1", color="primary") + "\n" +
    para(label, color="muted", size="small"), gap="20")
body = section(
    intro(eyebrow_text=t("Case study", "Section eyebrow label"),
          title=t("How Meridian cut release cycles in half")) + "\n" +
    columns([
        column(image(uri("assets/images/office.avif"), tattr("Meridian's studio"), radius="16px"),
               width="46%", vertical_align="center"),
        column(
            para(t("Meridian ships a design system used by nine product teams. Before Unapp, every release meant a week of chasing status in chat."), size="large") + "\n" +
            lst([t("One shared roadmap across nine teams"),
                 t("Automatic release notes from completed work"),
                 t("Reporting the leadership team actually reads")]) + "\n" +
            columns([metric(t("2×"), t("faster releases")), metric(t("40%"), t("fewer meetings"))], gap="40"),
            width="54%", vertical_align="center", gap="40"),
    ], align="wide", gap="60", vertical_align="center"),
    pad=("70", "70"), gap="60")
write_pattern("case-study", title="Case study", cats="unapp, unapp_proof, featured, media",
              keywords="case study, customer story, results, metrics, proof",
              desc="Customer story with a photo, outcome checklist and two headline metrics.",
              body=body)

# ------------------------------------------------------------- ratings band
prelude = """$unapp_ratings = array(
	array( 'score' => '4.9/5', 'source' => _x( 'G2 · 480 reviews', 'Review source', 'unapp' ) ),
	array( 'score' => '4.8/5', 'source' => _x( 'Capterra · 310 reviews', 'Review source', 'unapp' ) ),
	array( 'score' => '4.9/5', 'source' => _x( 'App Store · 12k ratings', 'Review source', 'unapp' ) ),
	array( 'score' => '4.7/5', 'source' => _x( 'Google Play · 9k ratings', 'Review source', 'unapp' ) ),
);"""
rating_col = column(
    image(uri("assets/images/ui/stars-5.svg"), tattr("Five star rating"), width="96px", align="center") + "\n" +
    para("<?php echo esc_html( $unapp_rating['score'] ); ?>", align="center", size="x-large",
         font="heading", weight="600") + "\n" +
    para("<?php echo esc_html( $unapp_rating['source'] ); ?>", align="center", color="muted", size="small"),
    gap="20")
body = section(
    columns([rating_col], align="wide", gap="50")
        .replace('<div class="wp-block-columns alignwide">\n',
                 '<div class="wp-block-columns alignwide">\n<?php foreach ( $unapp_ratings as $unapp_rating ) : ?>\n')
        .replace('\n</div>\n<!-- /wp:columns -->', '\n<?php endforeach; ?>\n</div>\n<!-- /wp:columns -->'),
    style_variation="is-style-section-soft", pad=("60", "60"), gap="0")
write_pattern("ratings", title="Review scores", cats=PROOF,
              keywords="ratings, reviews, stars, scores, trust",
              desc="A band of review-platform scores with star graphics.",
              body=body, php_prelude=prelude)
print("batch 2 written")
