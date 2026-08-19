<?php
/**
 * Title: Logo cloud
 * Slug: unapp/logo-cloud
 * Categories: unapp, unapp_proof, featured, gallery
 * Keywords: logos, customers, clients, trusted by, social proof
 * Viewport Width: 1400
 * Description: A row of customer logos under a small label. Replace the placeholder marks with your own.
 *
 * @package Unapp
 */

$unapp_logos = array(
	'northwind' => _x( 'Northwind', 'Placeholder customer name', 'unapp' ),
	'vertex'    => _x( 'Vertex', 'Placeholder customer name', 'unapp' ),
	'lumen'     => _x( 'Lumen', 'Placeholder customer name', 'unapp' ),
	'cobalt'    => _x( 'Cobalt', 'Placeholder customer name', 'unapp' ),
	'harbor'    => _x( 'Harbor', 'Placeholder customer name', 'unapp' ),
	'meridian'  => _x( 'Meridian', 'Placeholder customer name', 'unapp' ),
);
?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"},"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-soft" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);">
<!-- wp:paragraph {"align":"center","textColor":"muted","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-muted-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php esc_html_e( 'Trusted by product teams at', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:group {"align":"wide","className":"unapp-logo-cloud","style":{"spacing":{"blockGap":"var:preset|spacing|60"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"}} -->
<div class="wp-block-group alignwide unapp-logo-cloud">
<?php foreach ( $unapp_logos as $unapp_slug => $unapp_name ) : ?>
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","height":"26px"} -->
<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/logos/' . $unapp_slug . '.svg' ) ); ?>" alt="<?php echo esc_attr( $unapp_name ); ?>" style="height:26px;"/></figure>
<!-- /wp:image -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
