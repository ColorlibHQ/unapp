import os, sys; sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *

B = "unapp, unapp_blog, unapp_content, posts, query"

# ---------------------------------------------------------------- magazine masthead
body = section(
    group(
        heading(t("The Slow Build"), align="center", size="xxx-large", line_height="1.05") + "\n" +
        para(t("Essays on making software carefully, published most Fridays."),
             align="center", color="muted", size="large") + "\n" +
        separator(style="gradient", color=None),
        layout="constrained", content_size="720px", gap="30"),
    pad=("70", "70"), gap="0")
write_pattern("blog-masthead", title="Blog: masthead", cats=B + ", header",
              keywords="blog, masthead, title, magazine, header",
              desc="A magazine-style masthead: publication name, standfirst and a gradient rule.",
              body=body)

# ---------------------------------------------------------------- category tiles (WP 7.0)
terms = ('<!-- wp:terms-query {"termQuery":{"taxonomy":"category","perPage":6,"hideEmpty":true,'
         '"orderBy":"count","order":"desc"},"align":"wide","layout":{"type":"default"}} -->\n'
         '<div class="wp-block-terms-query alignwide">\n'
         '<!-- wp:term-template {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},'
         '"layout":{"type":"grid","columnCount":3}} -->\n'
         + group(
             '<!-- wp:term-name {"isLink":true,"level":3,"fontSize":"large"} /-->\n'
             '<!-- wp:term-count {"fontSize":"small","textColor":"muted"} /-->',
             style_variation="is-style-card", radius="20px", layout="flex", orientation="vertical", gap="20",
             pad={"top": "50", "bottom": "50", "left": "50", "right": "50"})
         + '\n<!-- /wp:term-template -->\n</div>\n<!-- /wp:terms-query -->')
body = section(
    intro(eyebrow_text=t("Browse", "Section eyebrow label"), title=t("Pick a thread")) + "\n" + terms,
    pad=("70", "70"), gap="60")
write_pattern("blog-categories", title="Blog: category tiles", cats=B,
              keywords="blog, categories, topics, terms, browse, tiles",
              desc="Category tiles with post counts, built on the Terms Query block from WordPress 7.0.",
              body=body)

# ---------------------------------------------------------------- author intro
body = section(
    columns([
        column(image(uri("assets/images/avatars/avatar-4.svg"), tattr("Author portrait placeholder"),
                     width="96px", height="96px", radius="999px"), width="26%", vertical_align="center"),
        column(
            eyebrow(t("Written by", "Section eyebrow label"), align="left") + "\n" +
            heading(t("Ines Kovač")) + "\n" +
            para(t("Fifteen years building products, most of them too quickly. This is where I write down what I would do differently."),
                 color="muted", size="large") + "\n" +
            social([("x", "https://x.com"), ("linkedin", "https://linkedin.com"), ("github", "https://github.com")],
                   size="has-small-icon-size"),
            width="74%", vertical_align="center", gap="20"),
    ], align="wide", gap="50", vertical_align="center"),
    style_variation="is-style-section-soft", pad=("70", "70"), gap="0")
write_pattern("blog-author-intro", title="Blog: author introduction", cats=B,
              keywords="blog, author, about, bio, writer",
              desc="A short author introduction with a portrait and social links, for a blog home.",
              body=body)

print("batch 11 written")
