<?php
/**
 * Title: Documentation topics
 * Slug: unapp/docs-topics
 * Categories: unapp, unapp_content, unapp_utility
 * Keywords: docs, help, support, knowledge base, topics, cards
 * Viewport Width: 1400
 * Description: A grid of help-centre categories with icons.
 *
 * @package Unapp
 */

$unapp_docs = array(
	array( 'icon' => 'book-open', 'title' => _x( 'Getting started', 'Docs category', 'unapp' ), 'text' => _x( 'Install, import your data and invite the team.', 'Docs category description', 'unapp' ) ),
	array( 'icon' => 'sliders', 'title' => _x( 'Workspace settings', 'Docs category', 'unapp' ), 'text' => _x( 'Roles, permissions, billing and branding.', 'Docs category description', 'unapp' ) ),
	array( 'icon' => 'terminal', 'title' => _x( 'API reference', 'Docs category', 'unapp' ), 'text' => _x( 'REST endpoints, webhooks and rate limits.', 'Docs category description', 'unapp' ) ),
	array( 'icon' => 'life-buoy', 'title' => _x( 'Troubleshooting', 'Docs category', 'unapp' ), 'text' => _x( 'Sync problems, imports and account recovery.', 'Docs category description', 'unapp' ) ),
	array( 'icon' => 'users', 'title' => _x( 'Teams and projects', 'Docs category', 'unapp' ), 'text' => _x( 'Structure work the way your company runs.', 'Docs category description', 'unapp' ) ),
	array( 'icon' => 'shield', 'title' => _x( 'Security', 'Docs category', 'unapp' ), 'text' => _x( 'SSO, audit logs and data residency.', 'Docs category description', 'unapp' ) ),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Help centre', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Browse by topic', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Short guides, written by the people who build the thing.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid"}} -->
<div class="wp-block-group alignwide">
<?php foreach ( $unapp_docs as $unapp_doc ) : ?>
<!-- wp:group {"className":"is-style-card","style":{"border":{"radius":"16px"},"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group is-style-card" style="border-radius:16px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40);">
<!-- wp:group {"style":{"border":{"radius":"14px"},"spacing":{"padding":{"top":"12px","bottom":"12px","left":"12px","right":"12px"}}},"backgroundColor":"primary","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group has-primary-background-color has-background" style="border-radius:14px;padding-top:12px;padding-right:12px;padding-bottom:12px;padding-left:12px">
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","width":"24px","height":"24px"} -->
<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icons/' . $unapp_doc['icon'] . '.svg' ) ); ?>" alt="" style="width:24px;height:24px;"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:group -->
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo esc_html( $unapp_doc['title'] ); ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo esc_html( $unapp_doc['text'] ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
