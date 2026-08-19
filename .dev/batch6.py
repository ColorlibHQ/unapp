import os, sys; sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *
CONTENT = "unapp, unapp_content, posts, query"

# ------------------------------------------------------------- blog featured (1 big + 2 small)
big_post = ('<!-- wp:query {"queryId":21,"query":{"perPage":1,"pages":0,"offset":0,"postType":"post","order":"desc",'
            '"orderBy":"date","author":"","search":"","exclude":[],"sticky":"exclude","inherit":false},"layout":{"type":"default"}} -->\n'
            '<div class="wp-block-query">\n'
            '<!-- wp:post-template -->\n'
            + group(
                '<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9","style":{"border":{"radius":"16px"}}} /-->\n'
                + '<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->\n'
                  '<div class="wp-block-group">\n'
                  '<!-- wp:post-terms {"term":"category","fontSize":"small"} /-->\n'
                  '<!-- wp:post-date {"fontSize":"small"} /-->\n'
                  '</div>\n<!-- /wp:group -->\n'
                + '<!-- wp:post-title {"isLink":true,"fontSize":"xx-large"} /-->\n'
                + '<!-- wp:post-excerpt {"excerptLength":32} /-->',
                layout="default", gap="30")
            + '\n<!-- /wp:post-template -->\n</div>\n<!-- /wp:query -->')
small_posts = ('<!-- wp:query {"queryId":22,"query":{"perPage":3,"pages":0,"offset":1,"postType":"post","order":"desc",'
               '"orderBy":"date","author":"","search":"","exclude":[],"sticky":"exclude","inherit":false},"layout":{"type":"default"}} -->\n'
               '<div class="wp-block-query">\n'
               '<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"default"}} -->\n'
               + group(
                   '<!-- wp:post-date {"fontSize":"small"} /-->\n'
                   '<!-- wp:post-title {"isLink":true,"level":3,"fontSize":"large"} /-->\n'
                   + separator(style="wide", color="border"),
                   layout="default", gap="20")
               + '\n<!-- /wp:post-template -->\n</div>\n<!-- /wp:query -->')
body = section(
    intro(eyebrow_text=t("Blog", "Section eyebrow label"), title=t("Fresh from the team"), lead=None) + "\n" +
    columns([column(big_post, width="58%"), column(small_posts, width="42%", gap="40")],
            align="wide", gap="60"),
    pad=("70", "70"), gap="50")
write_pattern("blog-featured", title="Blog: featured post + list", cats=CONTENT,
              keywords="blog, posts, featured, query, magazine",
              desc="One large lead post beside a compact list of the next three.",
              body=body)

# ------------------------------------------------------------- blog list (rows)
row_inner = columns([
    column('<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3","style":{"border":{"radius":"12px"}}} /-->',
           width="30%", vertical_align="center"),
    column('<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->\n'
           '<div class="wp-block-group">\n'
           '<!-- wp:post-date {"fontSize":"small"} /-->\n'
           '<!-- wp:post-terms {"term":"category","fontSize":"small"} /-->\n'
           '</div>\n<!-- /wp:group -->\n'
           '<!-- wp:post-title {"isLink":true,"level":3,"fontSize":"x-large"} /-->\n'
           '<!-- wp:post-excerpt {"excerptLength":26} /-->',
           width="70%", vertical_align="center", gap="20"),
], gap="40", vertical_align="center")
body = section(
    '<!-- wp:query {"queryId":23,"query":{"perPage":6,"pages":0,"offset":0,"postType":"post","order":"desc",'
    '"orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true},"align":"wide","layout":{"type":"default"}} -->\n'
    '<div class="wp-block-query alignwide">\n'
    '<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|60"}},"layout":{"type":"default"}} -->\n'
    + row_inner +
    '\n<!-- /wp:post-template -->\n'
    '<!-- wp:query-pagination {"paginationArrow":"chevron","style":{"spacing":{"margin":{"top":"var:preset|spacing|60"}}},"layout":{"type":"flex","justifyContent":"center"}} -->\n'
    '<!-- wp:query-pagination-previous /-->\n<!-- wp:query-pagination-numbers /-->\n<!-- wp:query-pagination-next /-->\n'
    '<!-- /wp:query-pagination -->\n'
    '<!-- wp:query-no-results -->\n'
    + para(t("No posts yet — the first one is on its way."), align="center", color="muted") +
    '\n<!-- /wp:query-no-results -->\n'
    '</div>\n<!-- /wp:query -->',
    pad=("60", "70"), gap="50")
write_pattern("blog-list", title="Blog: list with thumbnails", cats=CONTENT,
              keywords="blog, posts, list, rows, archive, query",
              desc="Post rows with a thumbnail beside the title and excerpt. Inherits the page query, so it works on archives too.",
              body=body)

# ------------------------------------------------------------- author box
body = group(
    columns([
        column('<!-- wp:avatar {"size":96,"style":{"border":{"radius":"999px"}}} /-->', width="20%"),
        column('<!-- wp:post-author-name {"fontSize":"large","style":{"typography":{"fontWeight":"600"}},"fontFamily":"heading"} /-->\n'
               '<!-- wp:post-author-biography {"textColor":"muted"} /-->\n'
               + social([("x", "https://x.com"), ("linkedin", "https://linkedin.com")], size="has-small-icon-size"),
               width="80%", gap="20"),
    ], gap="40", vertical_align="center"),
    style_variation="is-style-card", radius="20px", layout="default",
    pad={"top": "50", "bottom": "50", "left": "50", "right": "50"})
write_pattern("author-box", title="Author box", cats="unapp, unapp_content, posts",
              keywords="author, bio, byline, post, avatar",
              desc="Author avatar, name, biography and links — drop it under the post content in the Single template.",
              body=body, viewport=900)

# ------------------------------------------------------------- related posts
body = section(
    heading(t("Keep reading"), size="x-large", align="center") + "\n" +
    '<!-- wp:query {"queryId":24,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc",'
    '"orderBy":"date","author":"","search":"","exclude":[],"sticky":"exclude","inherit":false},"align":"wide","layout":{"type":"default"}} -->\n'
    '<div class="wp-block-query alignwide">\n'
    '<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"grid","columnCount":3}} -->\n'
    + group('<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/10","style":{"border":{"radius":"12px"}}} /-->\n'
            '<!-- wp:post-date {"fontSize":"small"} /-->\n'
            '<!-- wp:post-title {"isLink":true,"level":3,"fontSize":"large"} /-->',
            layout="default", gap="20") +
    '\n<!-- /wp:post-template -->\n</div>\n<!-- /wp:query -->',
    style_variation="is-style-section-soft", pad=("60", "60"), gap="50")
write_pattern("related-posts", title="Related posts", cats=CONTENT,
              keywords="related, posts, more, keep reading, query",
              desc="Three more posts in a soft band — for the end of the Single template.",
              body=body)

# ------------------------------------------------------------- changelog
prelude = """$unapp_releases = array(
	array(
		'version' => '2.4.0',
		'date'    => _x( '12 August 2026', 'Changelog date', 'unapp' ),
		'title'   => _x( 'Timeline view and faster search', 'Changelog entry title', 'unapp' ),
		'items'   => array(
			_x( 'New timeline view for any project or roadmap', 'Changelog item', 'unapp' ),
			_x( 'Search results now return in under 50ms for workspaces up to 100k tasks', 'Changelog item', 'unapp' ),
			_x( 'Fixed: recurring tasks skipped a week when the month rolled over', 'Changelog item', 'unapp' ),
		),
	),
	array(
		'version' => '2.3.2',
		'date'    => _x( '29 July 2026', 'Changelog date', 'unapp' ),
		'title'   => _x( 'Quality of life', 'Changelog entry title', 'unapp' ),
		'items'   => array(
			_x( 'Keyboard shortcuts for every board action', 'Changelog item', 'unapp' ),
			_x( 'Bulk edit up to 500 tasks at a time', 'Changelog item', 'unapp' ),
		),
	),
);"""
entry = group(
    group(
        para("<?php echo esc_html( $unapp_release['version'] ); ?>", size="small", font="heading",
             weight="600", color="primary") + "\n" +
        para("<?php echo esc_html( $unapp_release['date'] ); ?>", size="small", color="muted"),
        layout="flex", wrap="wrap", gap="30") + "\n" +
    heading("<?php echo esc_html( $unapp_release['title'] ); ?>", level=3, size="x-large") + "\n" +
    '<!-- wp:list {"className":"is-style-dash"} -->\n<ul class="wp-block-list is-style-dash">\n'
    '<?php foreach ( $unapp_release[\'items\'] as $unapp_item ) : ?>\n'
    '<!-- wp:list-item -->\n<li><?php echo esc_html( $unapp_item ); ?></li>\n<!-- /wp:list-item -->\n'
    '<?php endforeach; ?>\n</ul>\n<!-- /wp:list -->\n' + separator(style="wide", color="border"),
    layout="default", gap="30")
body = section(
    intro(eyebrow_text=t("Changelog", "Section eyebrow label"),
          title=t("What shipped recently"),
          lead=t("Every release, in plain language.")) + "\n" +
    group('<?php foreach ( $unapp_releases as $unapp_release ) : ?>\n' + entry + '\n<?php endforeach; ?>',
          layout="constrained", content_size="760px", gap="60"),
    pad=("70", "70"), gap="60")
write_pattern("changelog", title="Changelog entries", cats="unapp, unapp_content, text",
              keywords="changelog, releases, updates, versions, product",
              desc="Dated release entries with a version chip and a list of changes.",
              body=body, php_prelude=prelude)

# ------------------------------------------------------------- docs / help cards
prelude = """$unapp_docs = array(
	array( 'icon' => 'book-open', 'title' => _x( 'Getting started', 'Docs category', 'unapp' ), 'text' => _x( 'Install, import your data and invite the team.', 'Docs category description', 'unapp' ) ),
	array( 'icon' => 'sliders', 'title' => _x( 'Workspace settings', 'Docs category', 'unapp' ), 'text' => _x( 'Roles, permissions, billing and branding.', 'Docs category description', 'unapp' ) ),
	array( 'icon' => 'terminal', 'title' => _x( 'API reference', 'Docs category', 'unapp' ), 'text' => _x( 'REST endpoints, webhooks and rate limits.', 'Docs category description', 'unapp' ) ),
	array( 'icon' => 'life-buoy', 'title' => _x( 'Troubleshooting', 'Docs category', 'unapp' ), 'text' => _x( 'Sync problems, imports and account recovery.', 'Docs category description', 'unapp' ) ),
	array( 'icon' => 'users', 'title' => _x( 'Teams and projects', 'Docs category', 'unapp' ), 'text' => _x( 'Structure work the way your company runs.', 'Docs category description', 'unapp' ) ),
	array( 'icon' => 'shield', 'title' => _x( 'Security', 'Docs category', 'unapp' ), 'text' => _x( 'SSO, audit logs and data residency.', 'Docs category description', 'unapp' ) ),
);"""
doc_tile = group(
    icon_badge_expr("$unapp_doc['icon']", bg="primary", pad=12) + "\n" +
    heading("<?php echo esc_html( $unapp_doc['title'] ); ?>", level=3, size="large") + "\n" +
    para("<?php echo esc_html( $unapp_doc['text'] ); ?>", color="muted", size="small"),
    style_variation="is-style-card", radius="16px", gap="20", layout="flex", orientation="vertical",
    pad={"top": "40", "bottom": "40", "left": "40", "right": "40"})
body = section(
    intro(eyebrow_text=t("Help centre", "Section eyebrow label"),
          title=t("Browse by topic"),
          lead=t("Short guides, written by the people who build the thing.")) + "\n" +
    group('<?php foreach ( $unapp_docs as $unapp_doc ) : ?>\n' + doc_tile + '\n<?php endforeach; ?>',
          align="wide", layout="grid", gap="40"),
    pad=("70", "70"), gap="50")
write_pattern("docs-topics", title="Documentation topics", cats="unapp, unapp_content, unapp_utility",
              keywords="docs, help, support, knowledge base, topics, cards",
              desc="A grid of help-centre categories with icons.",
              body=body, php_prelude=prelude)
print("batch 6 written")
