<?php
/**
 * Title: Stats counter
 * Slug: unapp/stats
 * Categories: unapp, featured, text
 * Keywords: stats, numbers, counter, metrics, achievements
 * Viewport Width: 1400
 * Description: Four key numbers on a soft background. Numbers animate upward when scrolled into view (respects reduced-motion).
 *
 * @package Unapp
 */

$unapp_stats = array(
	array(
		'value' => '10,000+',
		'label' => _x( 'Active teams', 'Stat label', 'unapp' ),
	),
	array(
		'value' => '1.2M',
		'label' => _x( 'Tasks completed', 'Stat label', 'unapp' ),
	),
	array(
		'value' => '99.9%',
		'label' => _x( 'Uptime last 12 months', 'Stat label', 'unapp' ),
	),
	array(
		'value' => '4.9/5',
		'label' => _x( 'Average customer rating', 'Stat label', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-soft" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
	<div class="wp-block-columns alignwide">
		<?php foreach ( $unapp_stats as $unapp_stat ) : ?>
		<!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
		<div class="wp-block-column">
			<!-- wp:paragraph {"align":"center","className":"unapp-count","style":{"typography":{"fontWeight":"600","lineHeight":"1.1"}},"textColor":"primary","fontSize":"xx-large","fontFamily":"heading"} -->
			<p class="has-text-align-center unapp-count has-primary-color has-text-color has-heading-font-family has-xx-large-font-size" style="font-weight:600;line-height:1.1"><?php echo esc_html( $unapp_stat['value'] ); ?></p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"align":"center","textColor":"muted"} -->
			<p class="has-text-align-center has-muted-color has-text-color"><?php echo esc_html( $unapp_stat['label'] ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
		<?php endforeach; ?>
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
