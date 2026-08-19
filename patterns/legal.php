<?php
/**
 * Title: Legal document
 * Slug: unapp/legal
 * Categories: unapp, unapp_utility, text
 * Keywords: legal, privacy, terms, policy, document
 * Viewport Width: 900
 * Description: A readable legal page: title, last-updated line and numbered sections.
 *
 * @package Unapp
 */

$unapp_sections = array(
	array(
		'title' => _x( 'Who we are', 'Legal section title', 'unapp' ),
		'text'  => _x( 'This policy explains what we collect, why we collect it and what you can ask us to do with it. Replace this placeholder with your own wording before you publish.', 'Legal section body', 'unapp' ),
	),
	array(
		'title' => _x( 'Information we collect', 'Legal section title', 'unapp' ),
		'text'  => _x( 'Account details you give us, usage data from the product itself, and the technical information your browser sends with every request.', 'Legal section body', 'unapp' ),
	),
	array(
		'title' => _x( 'How we use it', 'Legal section title', 'unapp' ),
		'text'  => _x( 'To run the service, to answer your questions and to work out which parts of the product deserve more attention. We do not sell it.', 'Legal section body', 'unapp' ),
	),
	array(
		'title' => _x( 'Your rights', 'Legal section title', 'unapp' ),
		'text'  => _x( 'You can ask for a copy of your data, ask us to correct it, or ask us to delete it. Write to the address at the bottom of this page and we will reply within 30 days.', 'Legal section body', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained","contentSize":"720px"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"textColor":"muted","fontFamily":"heading","fontSize":"small","style":{"typography":{"letterSpacing":"0.06em","textTransform":"uppercase"}}} -->
<p class="has-muted-color has-text-color has-heading-font-family has-small-font-size" style="letter-spacing:0.06em;text-transform:uppercase;"><?php esc_html_e( 'Last updated: 12 August 2026', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"fontSize":"xxx-large"} -->
<h1 class="wp-block-heading has-xxx-large-font-size"><?php esc_html_e( 'Privacy policy', 'unapp' ); ?></h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"large"} -->
<p class="has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'A plain-language summary of how this site handles your information.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:separator {"className":"is-style-wide","backgroundColor":"border"} -->
<hr class="wp-block-separator has-text-color has-border-color has-border-border-color has-alpha-channel-opacity has-border-background-color has-background is-style-wide"/>
<!-- /wp:separator -->
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"default"}} -->
<div class="wp-block-group">
<?php foreach ( $unapp_sections as $unapp_section ) : ?>
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:heading {"fontSize":"x-large"} -->
<h2 class="wp-block-heading has-x-large-font-size"><?php echo esc_html( $unapp_section['title'] ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php echo esc_html( $unapp_section['text'] ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
