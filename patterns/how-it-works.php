<?php
/**
 * Title: How it works: numbered steps
 * Slug: unapp/how-it-works
 * Categories: unapp, unapp_features, featured, text
 * Keywords: steps, process, how it works, onboarding, numbered
 * Viewport Width: 1400
 * Description: Three numbered steps describing a process, in equal columns.
 *
 * @package Unapp
 */

$unapp_steps = array(
	array(
		'title' => _x( 'Import your work', 'Step title', 'unapp' ),
		'text'  => _x( 'Bring boards, issues and documents across from the tools you already use. Nothing is lost in translation.', 'Step description', 'unapp' ),
	),
	array(
		'title' => _x( 'Invite the team', 'Step title', 'unapp' ),
		'text'  => _x( 'Roles, permissions and notification rules are set once and inherited by every new project.', 'Step description', 'unapp' ),
	),
	array(
		'title' => _x( 'Ship, then report', 'Step title', 'unapp' ),
		'text'  => _x( 'Progress rolls up automatically, so status meetings turn into two-minute reads.', 'Step description', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-soft" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'How it works', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Live in three steps', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Most teams are running their first sprint in Unapp before lunch.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns alignwide">
<?php foreach ( $unapp_steps as $unapp_index => $unapp_step ) : ?>
<!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-column">
<!-- wp:paragraph {"textColor":"primary","fontFamily":"heading","fontSize":"xx-large","style":{"typography":{"fontWeight":"600","lineHeight":"1"}}} -->
<p class="has-primary-color has-text-color has-heading-font-family has-xx-large-font-size" style="font-weight:600;line-height:1;"><?php echo esc_html( str_pad( (string) ( $unapp_index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo esc_html( $unapp_step['title'] ); ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php echo esc_html( $unapp_step['text'] ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<?php endforeach; ?>
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
