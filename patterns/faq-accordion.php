<?php
/**
 * Title: FAQ accordion (WordPress 7.0)
 * Slug: unapp/faq-accordion
 * Categories: unapp, unapp_utility, text, featured
 * Keywords: faq, questions, accordion, support, help
 * Viewport Width: 1400
 * Description: Expandable questions built on the core Accordion block, with one panel open by default.
 *
 * @package Unapp
 */

$unapp_faqs = array(
	array(
		'q' => _x( 'Can I try Unapp before paying?', 'FAQ question', 'unapp' ),
		'a' => _x( 'Yes. Every plan starts with a 14-day trial of the Team tier — no card, no sales call. When the trial ends you drop to the free plan rather than losing your data.', 'FAQ answer', 'unapp' ),
	),
	array(
		'q' => _x( 'What happens to my data if I cancel?', 'FAQ question', 'unapp' ),
		'a' => _x( 'You can export everything as CSV or through the API at any time. We keep a backup for 30 days after cancellation, then delete it for good.', 'FAQ answer', 'unapp' ),
	),
	array(
		'q' => _x( 'Do you offer discounts for non-profits?', 'FAQ question', 'unapp' ),
		'a' => _x( 'We do — registered non-profits and educational institutions get 50% off any paid plan. Email us from your organisation address and we will set it up.', 'FAQ answer', 'unapp' ),
	),
	array(
		'q' => _x( 'Where is my data stored?', 'FAQ question', 'unapp' ),
		'a' => _x( 'In the region you choose when the workspace is created: the EU, the United States or Australia. Data never leaves that region.', 'FAQ answer', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)">
	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"align":"center","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.12em","fontWeight":"600"}},"textColor":"primary","fontSize":"small","fontFamily":"heading"} -->
		<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase"><?php echo esc_html_x( 'FAQ', 'Section eyebrow label', 'unapp' ); ?></p>
		<!-- /wp:paragraph -->
		<!-- wp:heading {"textAlign":"center"} -->
		<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Questions we get a lot', 'unapp' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
		<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Still stuck? The support team answers in under an hour on weekdays.', 'unapp' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"layout":{"type":"constrained","contentSize":"760px"}} -->
	<div class="wp-block-group">
		<!-- wp:accordion {"autoclose":true} -->
		<div role="group" class="wp-block-accordion">
			<?php foreach ( $unapp_faqs as $unapp_index => $unapp_faq ) : ?>
			<?php $unapp_open = 0 === $unapp_index; ?>
			<!-- wp:accordion-item <?php echo $unapp_open ? '{"openByDefault":true} ' : ''; ?>-->
			<div class="wp-block-accordion-item<?php echo $unapp_open ? ' is-open' : ''; ?>">
				<!-- wp:accordion-heading {"level":3} -->
				<h3 class="wp-block-accordion-heading"><button type="button" class="wp-block-accordion-heading__toggle"><span class="wp-block-accordion-heading__toggle-title"><?php echo esc_html( $unapp_faq['q'] ); ?></span><span class="wp-block-accordion-heading__toggle-icon" aria-hidden="true">+</span></button></h3>
				<!-- /wp:accordion-heading -->
				<!-- wp:accordion-panel -->
				<div role="region" class="wp-block-accordion-panel">
					<!-- wp:paragraph {"textColor":"muted"} -->
					<p class="has-muted-color has-text-color"><?php echo esc_html( $unapp_faq['a'] ); ?></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:accordion-panel -->
			</div>
			<!-- /wp:accordion-item -->
			<?php endforeach; ?>
		</div>
		<!-- /wp:accordion -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
