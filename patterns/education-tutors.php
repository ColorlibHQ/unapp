<?php
/**
 * Title: Courses: tutors
 * Slug: unapp/education-tutors
 * Categories: unapp, unapp_education, unapp_company, team
 * Keywords: education, tutors, teachers, staff, instructors
 * Viewport Width: 1400
 * Description: Three tutors with what they make when they are not teaching.
 *
 * @package Unapp
 */

$unapp_tutors = array(
	array(
		'image' => 'avatar-3',
		'name' => _x( "Rob Feeny", 'Tutor', 'unapp' ),
		'role' => _x( "Woodwork", 'Tutor', 'unapp' ),
		'note' => _x( "Furniture maker for twenty-two years. Teaches the way he was taught, minus the shouting.", 'Tutor', 'unapp' ),
	),
	array(
		'image' => 'avatar-6',
		'name' => _x( "Ivy Sandoval", 'Tutor', 'unapp' ),
		'role' => _x( "Letterpress and bookbinding", 'Tutor', 'unapp' ),
		'note' => _x( "Runs a two-person press in the same building. Owns more type than sense.", 'Tutor', 'unapp' ),
	),
	array(
		'image' => 'avatar-9',
		'name' => _x( "Kwame Boakye", 'Tutor', 'unapp' ),
		'role' => _x( "Ceramics", 'Tutor', 'unapp' ),
		'note' => _x( "Production potter. Fires the kiln on Wednesdays, which is why glazing is on Tuesday.", 'Tutor', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-soft" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Who teaches', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'People who do it for a living', 'unapp' ); ?></h2>
<!-- /wp:heading -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"unapp-grid-3","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3}} -->
<div class="wp-block-group alignwide unapp-grid-3">
<?php foreach ( $unapp_tutors as $unapp_tutor ) : ?>
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group">
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","width":"96px","height":"96px","style":{"border":{"radius":"999px"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo get_theme_file_uri( 'assets/images/avatars/' . $unapp_tutor['image'] . '.svg' ); ?>" alt="<?php echo $unapp_tutor['name']; ?>" style="border-radius:999px;width:96px;height:96px;"/></figure>
<!-- /wp:image -->
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo $unapp_tutor['name']; ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"primary","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.06em","textTransform":"uppercase"}}} -->
<p class="has-primary-color has-text-color has-small-font-size" style="font-weight:600;letter-spacing:0.06em;text-transform:uppercase;"><?php echo $unapp_tutor['role']; ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo $unapp_tutor['note']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
