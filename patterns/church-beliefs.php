<?php
/**
 * Title: Church: what we believe
 * Slug: unapp/church-beliefs
 * Categories: unapp, unapp_church, unapp_features
 * Keywords: church, beliefs, faith, values, about
 * Viewport Width: 1400
 * Description: Three belief cards in plain language, for an About page that does not read like a doctrinal statement.
 *
 * @package Unapp
 */

$unapp_church_beliefs = array(
	array(
		'icon' => 'book-open',
		'title' => _x( "The Bible", 'Church belief', 'unapp' ),
		'text' => _x( "We read it together, in order, and we do not skip the parts that are hard to hear.", 'Church belief', 'unapp' ),
	),
	array(
		'icon' => 'heart',
		'title' => _x( "Grace", 'Church belief', 'unapp' ),
		'text' => _x( "Nobody here has earned their place. That is rather the point of the whole thing.", 'Church belief', 'unapp' ),
	),
	array(
		'icon' => 'users',
		'title' => _x( "One another", 'Church belief', 'unapp' ),
		'text' => _x( "Christianity is a team sport. Sunday is where it starts, not where it stops.", 'Church belief', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'What we believe', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Three things, honestly held', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'There is a longer statement of faith and we will happily send it to you. This is the short version.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"unapp-grid-3","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3}} -->
<div class="wp-block-group alignwide unapp-grid-3">
<?php foreach ( $unapp_church_beliefs as $unapp_church_belief ) : ?>
<!-- wp:group {"className":"is-style-card","style":{"border":{"radius":"20px"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group is-style-card" style="border-radius:20px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50);">
<!-- wp:group {"style":{"border":{"radius":"14px"},"spacing":{"padding":{"top":"14px","bottom":"14px","left":"14px","right":"14px"}}},"backgroundColor":"secondary","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group has-secondary-background-color has-background" style="border-radius:14px;padding-top:14px;padding-right:14px;padding-bottom:14px;padding-left:14px">
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","width":"24px","height":"24px"} -->
<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icons/' . $unapp_church_belief['icon'] . '.svg' ) ); ?>" alt="" style="width:24px;height:24px;"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:group -->
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo $unapp_church_belief['title']; ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php echo $unapp_church_belief['text']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
