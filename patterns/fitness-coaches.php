<?php
/**
 * Title: Fitness: coaches
 * Slug: unapp/fitness-coaches
 * Categories: unapp, unapp_fitness, team
 * Keywords: fitness, coaches, trainers, team, staff
 * Viewport Width: 1400
 * Description: Three coaches with a portrait, discipline and a one-line biography.
 *
 * @package Unapp
 */

$unapp_coaches = array(
	array( 'avatar' => 'avatar-6', 'name' => _x( 'Danny Osei', 'Coach name', 'unapp' ), 'role' => _x( 'Head coach · Strength', 'Coach role', 'unapp' ), 'bio' => _x( 'Fifteen years coaching, two of them with the national team.', 'Coach bio', 'unapp' ) ),
	array( 'avatar' => 'avatar-8', 'name' => _x( 'Sofia Marchetti', 'Coach name', 'unapp' ), 'role' => _x( 'Conditioning', 'Coach role', 'unapp' ), 'bio' => _x( 'Ex-rower. Believes everything is fixable with better breathing.', 'Coach bio', 'unapp' ) ),
	array( 'avatar' => 'avatar-9', 'name' => _x( 'Ruth Okonkwo', 'Coach name', 'unapp' ), 'role' => _x( 'Mobility and rehab', 'Coach role', 'unapp' ), 'bio' => _x( 'Physiotherapist who would rather you never needed a physiotherapist.', 'Coach bio', 'unapp' ) ),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Coaches', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Who you will be training with', 'unapp' ); ?></h2>
<!-- /wp:heading -->
</div>
<!-- /wp:group -->
<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns alignwide">
<?php foreach ( $unapp_coaches as $unapp_coach ) : ?>
<!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-column">
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","aspectRatio":"1","scale":"cover","style":{"border":{"radius":"20px"}}} -->
<figure class="wp-block-image size-full has-custom-border"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/avatars/' . $unapp_coach['avatar'] . '.svg' ) ); ?>" alt="<?php echo esc_attr( $unapp_coach['name'] ); ?>" style="border-radius:20px;aspect-ratio:1;object-fit:cover;"/></figure>
<!-- /wp:image -->
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo esc_html( $unapp_coach['name'] ); ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600"}}} -->
<p class="has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;"><?php echo esc_html( $unapp_coach['role'] ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo esc_html( $unapp_coach['bio'] ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<?php endforeach; ?>
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
