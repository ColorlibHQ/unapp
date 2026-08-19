<?php
/**
 * Title: Practice: clinicians
 * Slug: unapp/medical-team
 * Categories: unapp, unapp_medical, unapp_company, team
 * Keywords: medical, dental, team, clinicians, dentists, staff
 * Viewport Width: 1400
 * Description: Three clinicians with qualifications — the credential check a patient makes first.
 *
 * @package Unapp
 */

$unapp_clinicians = array(
	array(
		'image' => 'avatar-5',
		'name' => _x( "Dr Anna Petrou", 'Clinician', 'unapp' ),
		'role' => _x( "Principal dentist · BDS, MFDS RCS", 'Clinician', 'unapp' ),
		'note' => _x( "Here since 2004. Special interest in nervous patients, which is most of us.", 'Clinician', 'unapp' ),
	),
	array(
		'image' => 'avatar-10',
		'name' => _x( "Dr Samuel Oyelaran", 'Clinician', 'unapp' ),
		'role' => _x( "Dentist · BDS, MSc", 'Clinician', 'unapp' ),
		'note' => _x( "Implants and restorative work. Trains other dentists on Fridays.", 'Clinician', 'unapp' ),
	),
	array(
		'image' => 'avatar-4',
		'name' => _x( "Marie Colbert", 'Clinician', 'unapp' ),
		'role' => _x( "Hygienist · RDH", 'Clinician', 'unapp' ),
		'note' => _x( "Runs the hygiene programme and the school visits.", 'Clinician', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-soft" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'The team', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Who will be treating you', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Every clinician\'s registration number is on the wall in reception and on their page here.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"unapp-grid-3","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3}} -->
<div class="wp-block-group alignwide unapp-grid-3">
<?php foreach ( $unapp_clinicians as $unapp_clinician ) : ?>
<!-- wp:group {"className":"is-style-card","style":{"border":{"radius":"20px"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group is-style-card" style="border-radius:20px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50);">
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","width":"96px","height":"96px","style":{"border":{"radius":"999px"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo get_theme_file_uri( 'assets/images/avatars/' . $unapp_clinician['image'] . '.svg' ); ?>" alt="<?php echo $unapp_clinician['name']; ?>" style="border-radius:999px;width:96px;height:96px;"/></figure>
<!-- /wp:image -->
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo $unapp_clinician['name']; ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"primary","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.06em","textTransform":"uppercase"}}} -->
<p class="has-primary-color has-text-color has-small-font-size" style="font-weight:600;letter-spacing:0.06em;text-transform:uppercase;"><?php echo $unapp_clinician['role']; ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo $unapp_clinician['note']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
