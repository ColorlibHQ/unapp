<?php
/**
 * Title: Press mentions
 * Slug: unapp/press
 * Categories: unapp, unapp_proof, about
 * Keywords: press, media, mentions, quotes, coverage
 * Viewport Width: 1400
 * Description: Publication logos with a pull quote from each.
 *
 * @package Unapp
 */

$unapp_press = array(
	array( 'logo' => 'foundry', 'quote' => _x( '“The most opinionated project tool since the original Basecamp — and that is a compliment.”', 'Press quote', 'unapp' ) ),
	array( 'logo' => 'kite', 'quote' => _x( '“Unapp has quietly become the default for small product teams who hate ceremony.”', 'Press quote', 'unapp' ) ),
	array( 'logo' => 'meridian', 'quote' => _x( '“Reporting that a chief executive can read without a translator.”', 'Press quote', 'unapp' ) ),
);
?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-soft" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Press', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'What people are writing', 'unapp' ); ?></h2>
<!-- /wp:heading -->
</div>
<!-- /wp:group -->
<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns alignwide">
<?php foreach ( $unapp_press as $unapp_item ) : ?>
<!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column">
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","height":"24px"} -->
<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/logos/' . $unapp_item['logo'] . '.svg' ) ); ?>" alt="<?php echo esc_attr( $unapp_item['logo'] ); ?>" style="height:24px;"/></figure>
<!-- /wp:image -->
<!-- wp:paragraph {"fontSize":"large","style":{"typography":{"lineHeight":"1.5"}}} -->
<p class="has-large-font-size" style="line-height:1.5;"><?php echo esc_html( $unapp_item['quote'] ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<?php endforeach; ?>
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
