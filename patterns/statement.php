<?php
/**
 * Title: Statement
 * Slug: unapp/statement
 * Categories: unapp, unapp_content, text
 * Keywords: statement, quote, manifesto, full width, dark
 * Viewport Width: 1400
 * Description: One sentence, full bleed on the dark ground. A pause between two busy sections.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","backgroundColor":"dark","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"},"blockGap":"0"},"elements":{"heading":{"color":{"text":"var:preset|color|base"}}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-dark-background-color has-background has-base-color has-text-color" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"900px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"base","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-base-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'The short version', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center","textColor":"base","fontSize":"xxx-large","style":{"typography":{"lineHeight":"1.1"}}} -->
<h2 class="wp-block-heading has-text-align-center has-base-color has-text-color has-xxx-large-font-size" style="line-height:1.1;"><?php esc_html_e( 'Software should make the work smaller, not the week longer', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","fontSize":"large","style":{"color":{"text":"rgba(255,255,255,0.8)"}}} -->
<p class="has-text-align-center has-text-color has-large-font-size" style="color:rgba(255,255,255,0.8);"><?php esc_html_e( 'Everything else on this page is detail underneath that sentence.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
