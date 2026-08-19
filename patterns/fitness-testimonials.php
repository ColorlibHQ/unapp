<?php
/**
 * Title: Fitness: member stories
 * Slug: unapp/fitness-testimonials
 * Categories: unapp, unapp_fitness, unapp_proof, testimonials
 * Keywords: fitness, gym, testimonials, members, reviews, stories
 * Viewport Width: 1400
 * Description: Three member quotes with portraits and how long each has trained here.
 *
 * @package Unapp
 */

$unapp_fitness_stories = array(
	array(
		'image' => 'avatar-2',
		'name'  => _x( 'Priya Raman', 'Member name', 'unapp' ),
		'meta'  => _x( 'Member for 2 years', 'Member since', 'unapp' ),
		'quote' => _x( 'I joined at forty-one having never lifted anything heavier than a toddler. I deadlift a hundred kilos now. Nobody was ever weird about it.', 'Member quote', 'unapp' ),
	),
	array(
		'image' => 'avatar-7',
		'name'  => _x( 'Mark Ellison', 'Member name', 'unapp' ),
		'meta'  => _x( 'Member for 8 months', 'Member since', 'unapp' ),
		'quote' => _x( 'I have had three gym memberships and used none of them. This is the first place where a coach noticed I had stopped coming and messaged me.', 'Member quote', 'unapp' ),
	),
	array(
		'image' => 'avatar-9',
		'name'  => _x( 'Chloe Boateng', 'Member name', 'unapp' ),
		'meta'  => _x( 'Member for 4 years', 'Member since', 'unapp' ),
		'quote' => _x( 'I came back six weeks after having my son. They rewrote the whole programme around what my body could actually do that month.', 'Member quote', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Members', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'People who were nervous on day one', 'unapp' ); ?></h2>
<!-- /wp:heading -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"unapp-grid-3","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3}} -->
<div class="wp-block-group alignwide unapp-grid-3">
<?php foreach ( $unapp_fitness_stories as $unapp_fitness_story ) : ?>
<!-- wp:group {"className":"is-style-card","style":{"border":{"radius":"18px"},"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|24"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group is-style-card" style="border-radius:18px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40);">
<!-- wp:paragraph {"fontSize":"large","style":{"typography":{"lineHeight":"1.5"}}} -->
<p class="has-large-font-size" style="line-height:1.5;"><?php echo $unapp_fitness_story['quote']; ?></p>
<!-- /wp:paragraph -->
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|14"}},"layout":{"type":"flex","orientation":"horizontal","verticalAlignment":"center"}} -->
<div class="wp-block-group">
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","width":"48px","height":"48px","style":{"border":{"radius":"999px"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo get_theme_file_uri( 'assets/images/avatars/' . $unapp_fitness_story['image'] . '.svg' ); ?>" alt="<?php echo $unapp_fitness_story['name']; ?>" style="border-radius:999px;width:48px;height:48px;"/></figure>
<!-- /wp:image -->
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|0"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"fontSize":"small","style":{"typography":{"fontWeight":"600"}}} -->
<p class="has-small-font-size" style="font-weight:600;"><?php echo $unapp_fitness_story['name']; ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo $unapp_fitness_story['meta']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
