<?php
/**
 * Title: Event: tickets
 * Slug: unapp/events-tickets
 * Categories: unapp, unapp_events, unapp_pricing, pricing
 * Keywords: events, tickets, pricing, conference, early bird
 * Viewport Width: 1400
 * Description: Three ticket tiers including free supported places, with what each includes.
 *
 * @package Unapp
 */

$unapp_tickets = array(
	array(
		'name'     => _x( 'Early', 'Ticket name', 'unapp' ),
		'price'    => _x( '£180', 'Ticket price', 'unapp' ),
		'note'     => _x( "Until 31 January, or until three hundred have gone.", 'Ticket note', 'unapp' ),
		'featured' => false,
		'features' => array( _x( 'Both days', 'Ticket feature', 'unapp' ), _x( 'Lunch and coffee', 'Ticket feature', 'unapp' ), _x( 'The bar', 'Ticket feature', 'unapp' ) ),
	),
	array(
		'name'     => _x( 'Standard', 'Ticket name', 'unapp' ),
		'price'    => _x( '£240', 'Ticket price', 'unapp' ),
		'note'     => _x( "From February, and on the door if any are left.", 'Ticket note', 'unapp' ),
		'featured' => true,
		'features' => array( _x( 'Both days', 'Ticket feature', 'unapp' ), _x( 'Lunch and coffee', 'Ticket feature', 'unapp' ), _x( 'The bar', 'Ticket feature', 'unapp' ), _x( 'Talk recordings', 'Ticket feature', 'unapp' ) ),
	),
	array(
		'name'     => _x( 'Supported', 'Ticket name', 'unapp' ),
		'price'    => _x( '£0', 'Ticket price', 'unapp' ),
		'note'     => _x( "Thirty places, no questions asked beyond a sentence about why.", 'Ticket note', 'unapp' ),
		'featured' => false,
		'features' => array( _x( 'Both days', 'Ticket feature', 'unapp' ), _x( 'Lunch and coffee', 'Ticket feature', 'unapp' ), _x( 'Travel help if needed', 'Ticket feature', 'unapp' ) ),
	),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Tickets', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Three prices, one of them nothing', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Supported tickets come out of the standard ones. That is the whole funding model.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"unapp-grid-3","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3}} -->
<div class="wp-block-group alignwide unapp-grid-3">
<?php foreach ( $unapp_tickets as $unapp_ticket ) : ?>
<?php if ( $unapp_ticket['featured'] ) : ?>
<!-- wp:group {"className":"is-style-elevated","style":{"border":{"radius":"20px"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group is-style-elevated" style="border-radius:20px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50);">
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo $unapp_ticket['name']; ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"xxx-large","style":{"typography":{"fontWeight":"700","lineHeight":"1"}}} -->
<p class="has-xxx-large-font-size" style="font-weight:700;line-height:1;"><?php echo $unapp_ticket['price']; ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo $unapp_ticket['note']; ?></p>
<!-- /wp:paragraph -->
<!-- wp:list {"className":"is-style-checklist"} -->
<ul class="wp-block-list is-style-checklist">
<?php foreach ( $unapp_ticket['features'] as $unapp_ticket_feature ) : ?>
<!-- wp:list-item -->
<li><?php echo esc_html( $unapp_ticket_feature ); ?></li>
<!-- /wp:list-item -->
<?php endforeach; ?>
</ul>
<!-- /wp:list -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"width":100} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link wp-element-button" href="#tickets"><?php esc_html_e( 'Buy', 'unapp' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
<?php else : ?>
<!-- wp:group {"className":"is-style-card","style":{"border":{"radius":"20px"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group is-style-card" style="border-radius:20px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50);">
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo $unapp_ticket['name']; ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"xxx-large","style":{"typography":{"fontWeight":"700","lineHeight":"1"}}} -->
<p class="has-xxx-large-font-size" style="font-weight:700;line-height:1;"><?php echo $unapp_ticket['price']; ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo $unapp_ticket['note']; ?></p>
<!-- /wp:paragraph -->
<!-- wp:list {"className":"is-style-checklist"} -->
<ul class="wp-block-list is-style-checklist">
<?php foreach ( $unapp_ticket['features'] as $unapp_ticket_feature ) : ?>
<!-- wp:list-item -->
<li><?php echo esc_html( $unapp_ticket_feature ); ?></li>
<!-- /wp:list-item -->
<?php endforeach; ?>
</ul>
<!-- /wp:list -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"width":100} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link wp-element-button" href="#tickets"><?php esc_html_e( 'Buy', 'unapp' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
<?php endif; ?>
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
