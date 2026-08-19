<?php
/**
 * Title: Church: what to expect
 * Slug: unapp/church-visit
 * Categories: unapp, unapp_church, unapp_features
 * Keywords: church, visit, first time, expect, welcome, newcomer
 * Viewport Width: 1400
 * Description: Four answers a first-time visitor actually wants: how long it lasts, where to park, what happens to the children and what to wear.
 *
 * @package Unapp
 */

$unapp_church_expect = array(
	array(
		'icon' => 'clock',
		'title' => _x( "Ninety minutes", 'Church first-visit card', 'unapp' ),
		'text' => _x( "Songs, a talk and coffee afterwards. You are free to leave whenever you like.", 'Church first-visit card', 'unapp' ),
	),
	array(
		'icon' => 'map-pin',
		'title' => _x( "Parking is free", 'Church first-visit card', 'unapp' ),
		'text' => _x( "Behind the building, entrance on Mill Lane. Ten spaces are kept for visitors.", 'Church first-visit card', 'unapp' ),
	),
	array(
		'icon' => 'users',
		'title' => _x( "Children are welcome", 'Church first-visit card', 'unapp' ),
		'text' => _x( "Creche for under-3s, groups up to Year 6, and nobody minds noise in the service.", 'Church first-visit card', 'unapp' ),
	),
	array(
		'icon' => 'heart',
		'title' => _x( "Wear whatever you own", 'Church first-visit card', 'unapp' ),
		'text' => _x( "You will see suits and you will see trainers. Both are entirely normal.", 'Church first-visit card', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Your first Sunday', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'What actually happens', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Nobody will ask you to stand up, introduce yourself or give anything.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"unapp-grid-4","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":4}} -->
<div class="wp-block-group alignwide unapp-grid-4">
<?php foreach ( $unapp_church_expect as $unapp_church_item ) : ?>
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group">
<!-- wp:group {"style":{"border":{"radius":"14px"},"spacing":{"padding":{"top":"14px","bottom":"14px","left":"14px","right":"14px"}}},"backgroundColor":"primary","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group has-primary-background-color has-background" style="border-radius:14px;padding-top:14px;padding-right:14px;padding-bottom:14px;padding-left:14px">
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","width":"24px","height":"24px"} -->
<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icons/' . $unapp_church_item['icon'] . '.svg' ) ); ?>" alt="" style="width:24px;height:24px;"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:group -->
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo $unapp_church_item['title']; ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php echo $unapp_church_item['text']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
