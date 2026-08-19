<?php
/**
 * Title: Agency: the studio
 * Slug: unapp/agency-team
 * Categories: unapp, unapp_agency, unapp_company, team
 * Keywords: agency, team, studio, people
 * Viewport Width: 1400
 * Description: Four of the studio with portraits and roles, and a note that there is no account layer.
 *
 * @package Unapp
 */

$unapp_agency_team = array(
	array(
		'image' => 'avatar-2',
		'name' => _x( "Nadia Feld", 'Studio member', 'unapp' ),
		'role' => _x( "Founder, strategy", 'Studio member', 'unapp' ),
	),
	array(
		'image' => 'avatar-5',
		'name' => _x( "Callum Reece", 'Studio member', 'unapp' ),
		'role' => _x( "Design director", 'Studio member', 'unapp' ),
	),
	array(
		'image' => 'avatar-9',
		'name' => _x( "Yumi Adeyemi", 'Studio member', 'unapp' ),
		'role' => _x( "Product design", 'Studio member', 'unapp' ),
	),
	array(
		'image' => 'avatar-7',
		'name' => _x( "Marek Stolar", 'Studio member', 'unapp' ),
		'role' => _x( "Engineering", 'Studio member', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'The studio', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Nine people, four of whom you will meet', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'No account layer. The people who design the work are the people in the room.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"unapp-grid-4","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":4}} -->
<div class="wp-block-group alignwide unapp-grid-4">
<?php foreach ( $unapp_agency_team as $unapp_agency_person ) : ?>
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group">
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","width":"96px","height":"96px","style":{"border":{"radius":"999px"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo get_theme_file_uri( 'assets/images/avatars/' . $unapp_agency_person['image'] . '.svg' ); ?>" alt="<?php echo $unapp_agency_person['name']; ?>" style="border-radius:999px;width:96px;height:96px;"/></figure>
<!-- /wp:image -->
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo $unapp_agency_person['name']; ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"primary","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.06em","textTransform":"uppercase"}}} -->
<p class="has-primary-color has-text-color has-small-font-size" style="font-weight:600;letter-spacing:0.06em;text-transform:uppercase;"><?php echo $unapp_agency_person['role']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
