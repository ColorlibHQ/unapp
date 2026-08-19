<?php
/**
 * Title: Changelog entries
 * Slug: unapp/changelog
 * Categories: unapp, unapp_content, text
 * Keywords: changelog, releases, updates, versions, product
 * Viewport Width: 1400
 * Description: Dated release entries with a version chip and a list of changes.
 *
 * @package Unapp
 */

$unapp_releases = array(
	array(
		'version' => '2.4.0',
		'date'    => _x( '12 August 2026', 'Changelog date', 'unapp' ),
		'title'   => _x( 'Timeline view and faster search', 'Changelog entry title', 'unapp' ),
		'items'   => array(
			_x( 'New timeline view for any project or roadmap', 'Changelog item', 'unapp' ),
			_x( 'Search results now return in under 50ms for workspaces up to 100k tasks', 'Changelog item', 'unapp' ),
			_x( 'Fixed: recurring tasks skipped a week when the month rolled over', 'Changelog item', 'unapp' ),
		),
	),
	array(
		'version' => '2.3.2',
		'date'    => _x( '29 July 2026', 'Changelog date', 'unapp' ),
		'title'   => _x( 'Quality of life', 'Changelog entry title', 'unapp' ),
		'items'   => array(
			_x( 'Keyboard shortcuts for every board action', 'Changelog item', 'unapp' ),
			_x( 'Bulk edit up to 500 tasks at a time', 'Changelog item', 'unapp' ),
		),
	),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Changelog', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'What shipped recently', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Every release, in plain language.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained","contentSize":"760px"}} -->
<div class="wp-block-group">
<?php foreach ( $unapp_releases as $unapp_release ) : ?>
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600"}}} -->
<p class="has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;"><?php echo esc_html( $unapp_release['version'] ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo esc_html( $unapp_release['date'] ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:heading {"level":3,"fontSize":"x-large"} -->
<h3 class="wp-block-heading has-x-large-font-size"><?php echo esc_html( $unapp_release['title'] ); ?></h3>
<!-- /wp:heading -->
<!-- wp:list {"className":"is-style-dash"} -->
<ul class="wp-block-list is-style-dash">
<?php foreach ( $unapp_release['items'] as $unapp_item ) : ?>
<!-- wp:list-item -->
<li><?php echo esc_html( $unapp_item ); ?></li>
<!-- /wp:list-item -->
<?php endforeach; ?>
</ul>
<!-- /wp:list -->
<!-- wp:separator {"className":"is-style-wide","backgroundColor":"border"} -->
<hr class="wp-block-separator has-text-color has-border-color has-border-border-color has-alpha-channel-opacity has-border-background-color has-background is-style-wide"/>
<!-- /wp:separator -->
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
