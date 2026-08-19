<?php
/**
 * Title: Restaurant: menu
 * Slug: unapp/restaurant-menu
 * Categories: unapp, unapp_restaurant, unapp_features, text
 * Keywords: restaurant, menu, food, dishes, prices, courses
 * Viewport Width: 1400
 * Description: Three courses with dishes and prices, and a tasting-menu line underneath.
 *
 * @package Unapp
 */

$unapp_menu = array(
	array(
		'course' => _x( 'To start', 'Menu course', 'unapp' ),
		'dishes' => array( array( 'dish' => _x( "Sourdough, cultured butter", 'Menu dish', 'unapp' ), 'price' => '5' ), array( 'dish' => _x( "Devilled egg, brown crab", 'Menu dish', 'unapp' ), 'price' => '9' ), array( 'dish' => _x( "Chicory, pear, blue cheese", 'Menu dish', 'unapp' ), 'price' => '11' ) ),
	),
	array(
		'course' => _x( 'Mains', 'Menu course', 'unapp' ),
		'dishes' => array( array( 'dish' => _x( "Hake, mussels, sea vegetables", 'Menu dish', 'unapp' ), 'price' => '24' ), array( 'dish' => _x( "Short rib, onions, bone marrow", 'Menu dish', 'unapp' ), 'price' => '27' ), array( 'dish' => _x( "Celeriac, hazelnut, sage", 'Menu dish', 'unapp' ), 'price' => '19' ) ),
	),
	array(
		'course' => _x( 'To finish', 'Menu course', 'unapp' ),
		'dishes' => array( array( 'dish' => _x( "Burnt custard, rhubarb", 'Menu dish', 'unapp' ), 'price' => '8' ), array( 'dish' => _x( "Chocolate, olive oil, salt", 'Menu dish', 'unapp' ), 'price' => '9' ), array( 'dish' => _x( "Three cheeses, oatcakes", 'Menu dish', 'unapp' ), 'price' => '12' ) ),
	),
);
?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-soft" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'This week', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'The menu changes on Wednesday', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Whatever the boats and the market gave us. Tell us about allergies when you book and we will work around them.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"unapp-grid-3","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3}} -->
<div class="wp-block-group alignwide unapp-grid-3">
<?php foreach ( $unapp_menu as $unapp_course ) : ?>
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"textColor":"primary","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.06em","textTransform":"uppercase"}}} -->
<p class="has-primary-color has-text-color has-small-font-size" style="font-weight:600;letter-spacing:0.06em;text-transform:uppercase;"><?php echo $unapp_course['course']; ?></p>
<!-- /wp:paragraph -->
<?php foreach ( $unapp_course['dishes'] as $unapp_dish ) : ?>
<!-- wp:columns {"verticalAlignment":"top","isStackedOnMobile":false,"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|20","left":"var:preset|spacing|20"}}}} -->
<div class="wp-block-columns are-vertically-aligned-top is-not-stacked-on-mobile">
<!-- wp:column {"verticalAlignment":"top","width":"80%"} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:80%;">
<!-- wp:paragraph -->
<p><?php echo $unapp_dish['dish']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"top","width":"20%"} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:20%;">
<!-- wp:paragraph {"align":"right","textColor":"muted"} -->
<p class="has-text-align-right has-muted-color has-text-color"><?php echo '£' . $unapp_dish['price']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"small"} -->
<p class="has-text-align-center has-muted-color has-text-color has-small-font-size"><?php esc_html_e( 'Five courses at the chef\'s discretion, £48. The whole table, please.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
