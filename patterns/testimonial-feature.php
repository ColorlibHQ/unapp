<?php
/**
 * Title: Testimonial: single quote
 * Slug: unapp/testimonial-feature
 * Categories: unapp, unapp_proof, testimonials, featured
 * Keywords: testimonial, quote, customer, story, featured
 * Viewport Width: 1400
 * Description: One large customer quote with a portrait, logo and a link to the full story.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"0"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:columns {"verticalAlignment":"center","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center alignwide">
<!-- wp:column {"verticalAlignment":"center","width":"32%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:32%;">
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"20px"}}} -->
<figure class="wp-block-image size-full has-custom-border"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/avatars/avatar-3.svg' ) ); ?>" alt="<?php esc_attr_e( 'Portrait of the quoted customer', 'unapp' ); ?>" style="border-radius:20px;"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"center","width":"68%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:68%;">
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","height":"26px"} -->
<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/logos/harbor.svg' ) ); ?>" alt="<?php esc_attr_e( 'Harbor logo', 'unapp' ); ?>" style="height:26px;"/></figure>
<!-- /wp:image -->
<!-- wp:paragraph {"fontFamily":"heading","fontSize":"x-large","style":{"typography":{"fontWeight":"500","lineHeight":"1.35"}}} -->
<p class="has-heading-font-family has-x-large-font-size" style="font-weight:500;line-height:1.35;"><?php esc_html_e( '“Unapp gave us one calendar, one backlog and one source of truth. Six months in, nobody asks for the old spreadsheets.”', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php esc_html_e( 'Nadia Okafor · VP Engineering, Harbor', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|30"}}}} -->
<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--30);">
<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#"><?php esc_html_e( 'Read the case study', 'unapp' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
