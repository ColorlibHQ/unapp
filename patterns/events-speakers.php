<?php
/**
 * Title: Event: speakers
 * Slug: unapp/events-speakers
 * Categories: unapp, unapp_events, unapp_company, team
 * Keywords: events, conference, speakers, line-up, talks
 * Viewport Width: 1400
 * Description: Four announced speakers with their day jobs.
 *
 * @package Unapp
 */

$unapp_speakers = array(
	array(
		'image' => 'avatar-2',
		'name' => _x( "Tess Oduya", 'Speaker', 'unapp' ),
		'role' => _x( "Principal engineer, Halden", 'Speaker', 'unapp' ),
	),
	array(
		'image' => 'avatar-7',
		'name' => _x( "Ben Marchetti", 'Speaker', 'unapp' ),
		'role' => _x( "CTO, Pier & Post", 'Speaker', 'unapp' ),
	),
	array(
		'image' => 'avatar-10',
		'name' => _x( "Amara Nwosu", 'Speaker', 'unapp' ),
		'role' => _x( "Head of platform, Verity", 'Speaker', 'unapp' ),
	),
	array(
		'image' => 'avatar-5',
		'name' => _x( "Joachim Reiss", 'Speaker', 'unapp' ),
		'role' => _x( "Author, The Long Rewrite", 'Speaker', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Speaking', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Four of the sixteen', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'The rest are announced in February, once they have all said yes in writing.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"unapp-grid-4","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":4}} -->
<div class="wp-block-group alignwide unapp-grid-4">
<?php foreach ( $unapp_speakers as $unapp_speaker ) : ?>
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group">
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","width":"96px","height":"96px","style":{"border":{"radius":"999px"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo get_theme_file_uri( 'assets/images/avatars/' . $unapp_speaker['image'] . '.svg' ); ?>" alt="<?php echo $unapp_speaker['name']; ?>" style="border-radius:999px;width:96px;height:96px;"/></figure>
<!-- /wp:image -->
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo $unapp_speaker['name']; ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"primary","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.06em","textTransform":"uppercase"}}} -->
<p class="has-primary-color has-text-color has-small-font-size" style="font-weight:600;letter-spacing:0.06em;text-transform:uppercase;"><?php echo $unapp_speaker['role']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
