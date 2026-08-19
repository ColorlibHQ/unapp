<?php
/**
 * Title: Review scores
 * Slug: unapp/ratings
 * Categories: unapp, unapp_proof, testimonials, featured
 * Keywords: ratings, reviews, stars, scores, trust
 * Viewport Width: 1400
 * Description: A band of review-platform scores with star graphics.
 *
 * @package Unapp
 */

$unapp_ratings = array(
	array( 'score' => '4.9/5', 'source' => _x( 'G2 · 480 reviews', 'Review source', 'unapp' ) ),
	array( 'score' => '4.8/5', 'source' => _x( 'Capterra · 310 reviews', 'Review source', 'unapp' ) ),
	array( 'score' => '4.9/5', 'source' => _x( 'App Store · 12k ratings', 'Review source', 'unapp' ) ),
	array( 'score' => '4.7/5', 'source' => _x( 'Google Play · 9k ratings', 'Review source', 'unapp' ) ),
);
?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"},"blockGap":"var:preset|spacing|0"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-soft" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);">
<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns alignwide">
<?php foreach ( $unapp_ratings as $unapp_rating ) : ?>
<!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-column">
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","align":"center","width":"96px"} -->
<figure class="wp-block-image aligncenter size-full is-resized"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/ui/stars-5.svg' ) ); ?>" alt="<?php esc_attr_e( 'Five star rating', 'unapp' ); ?>" style="width:96px;"/></figure>
<!-- /wp:image -->
<!-- wp:paragraph {"align":"center","fontFamily":"heading","fontSize":"x-large","style":{"typography":{"fontWeight":"600"}}} -->
<p class="has-text-align-center has-heading-font-family has-x-large-font-size" style="font-weight:600;"><?php echo esc_html( $unapp_rating['score'] ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"small"} -->
<p class="has-text-align-center has-muted-color has-text-color has-small-font-size"><?php echo esc_html( $unapp_rating['source'] ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<?php endforeach; ?>
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
