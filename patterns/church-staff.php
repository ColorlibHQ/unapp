<?php
/**
 * Title: Church: staff
 * Slug: unapp/church-staff
 * Categories: unapp, unapp_church, unapp_company, team
 * Keywords: church, staff, team, pastor, leaders, people
 * Viewport Width: 1400
 * Description: The four people a visitor is likely to meet, with what each of them actually does.
 *
 * @package Unapp
 */

$unapp_church_staff = array(
	array(
		'image' => 'avatar-1',
		'name'  => _x( 'Tom Iredale', 'Church staff name', 'unapp' ),
		'role'  => _x( 'Lead pastor', 'Church staff role', 'unapp' ),
		'note'  => _x( 'Here since 2014. Preaches most Sundays and answers every email eventually.', 'Church staff note', 'unapp' ),
	),
	array(
		'image' => 'avatar-6',
		'name'  => _x( 'Grace Okonjo', 'Church staff name', 'unapp' ),
		'role'  => _x( 'Families and children', 'Church staff role', 'unapp' ),
		'note'  => _x( 'Runs the Sunday groups and the Tuesday toddler morning.', 'Church staff note', 'unapp' ),
	),
	array(
		'image' => 'avatar-3',
		'name'  => _x( 'Danny Ferreira', 'Church staff name', 'unapp' ),
		'role'  => _x( 'Music', 'Church staff role', 'unapp' ),
		'note'  => _x( 'Leads the band and will teach anyone who asks to play.', 'Church staff note', 'unapp' ),
	),
	array(
		'image' => 'avatar-8',
		'name'  => _x( 'Ruth Nakamura', 'Church staff name', 'unapp' ),
		'role'  => _x( 'Church administrator', 'Church staff role', 'unapp' ),
		'note'  => _x( 'Bookings, the building, and the person who knows where everything is.', 'Church staff note', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Who you will meet', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'The people here on a Sunday', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Four of us are paid; most of what happens here is done by people who are not.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"unapp-grid-4","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":4}} -->
<div class="wp-block-group alignwide unapp-grid-4">
<?php foreach ( $unapp_church_staff as $unapp_church_person ) : ?>
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|12"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group">
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","width":"120px","height":"120px","style":{"border":{"radius":"999px"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo get_theme_file_uri( 'assets/images/avatars/' . $unapp_church_person['image'] . '.svg' ); ?>" alt="<?php echo $unapp_church_person['name']; ?>" style="border-radius:999px;width:120px;height:120px;"/></figure>
<!-- /wp:image -->
<!-- wp:heading {"level":3,"fontSize":"medium"} -->
<h3 class="wp-block-heading has-medium-font-size"><?php echo $unapp_church_person['name']; ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"primary","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.04em","textTransform":"uppercase"}}} -->
<p class="has-primary-color has-text-color has-small-font-size" style="font-weight:600;letter-spacing:0.04em;text-transform:uppercase;"><?php echo $unapp_church_person['role']; ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo $unapp_church_person['note']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
