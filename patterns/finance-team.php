<?php
/**
 * Title: Finance: advisers
 * Slug: unapp/finance-team
 * Categories: unapp, unapp_finance, unapp_company, team
 * Keywords: finance, adviser, team, chartered, planner, qualifications
 * Viewport Width: 1400
 * Description: Three advisers with their qualifications spelled out — the credential check a prospective client makes first.
 *
 * @package Unapp
 */

$unapp_finance_team = array(
	array(
		'image' => 'avatar-5',
		'name'  => _x( 'Helen Ashworth', 'Adviser name', 'unapp' ),
		'role'  => _x( 'Chartered Financial Planner', 'Adviser role', 'unapp' ),
		'quals' => _x( 'FCII, CFP', 'Adviser qualifications', 'unapp' ),
		'note'  => _x( 'Twenty-eight years advising families through retirement, divorce and inheritance. Founded the firm in 2009.', 'Adviser note', 'unapp' ),
	),
	array(
		'image' => 'avatar-10',
		'name'  => _x( 'Idris Mahmood', 'Adviser name', 'unapp' ),
		'role'  => _x( 'Financial Planner', 'Adviser role', 'unapp' ),
		'quals' => _x( 'DipPFS', 'Adviser qualifications', 'unapp' ),
		'note'  => _x( 'Specialises in company directors and the awkward business of extracting money from your own company sensibly.', 'Adviser note', 'unapp' ),
	),
	array(
		'image' => 'avatar-4',
		'name'  => _x( 'Sarah Whitcombe', 'Adviser name', 'unapp' ),
		'role'  => _x( 'Paraplanner', 'Adviser role', 'unapp' ),
		'quals' => _x( 'DipPFS', 'Adviser qualifications', 'unapp' ),
		'note'  => _x( 'Writes the plans, models the scenarios and finds the pension nobody remembered they had.', 'Adviser note', 'unapp' ),
	),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'The team', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Three people, and you will deal with all of them', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'No call centre, no account manager who leaves every eighteen months.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"unapp-grid-3","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3}} -->
<div class="wp-block-group alignwide unapp-grid-3">
<?php foreach ( $unapp_finance_team as $unapp_finance_person ) : ?>
<!-- wp:group {"className":"is-style-card","style":{"border":{"radius":"18px"},"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|14"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group is-style-card" style="border-radius:18px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40);">
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","width":"110px","height":"110px","style":{"border":{"radius":"999px"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo get_theme_file_uri( 'assets/images/avatars/' . $unapp_finance_person['image'] . '.svg' ); ?>" alt="<?php echo $unapp_finance_person['name']; ?>" style="border-radius:999px;width:110px;height:110px;"/></figure>
<!-- /wp:image -->
<!-- wp:heading {"level":3,"fontSize":"medium"} -->
<h3 class="wp-block-heading has-medium-font-size"><?php echo $unapp_finance_person['name']; ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"primary","fontSize":"small","style":{"typography":{"fontWeight":"600"}}} -->
<p class="has-primary-color has-text-color has-small-font-size" style="font-weight:600;"><?php echo $unapp_finance_person['role'] . ' · ' . $unapp_finance_person['quals']; ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo $unapp_finance_person['note']; ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
