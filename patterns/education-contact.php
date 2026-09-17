<?php
/**
 * Title: Courses: contact the school
 * Slug: unapp/education-contact
 * Categories: unapp, unapp_education, unapp_utility, contact
 * Keywords: education, contact, courses, school, questions, booking
 * Viewport Width: 1400
 * Description: Where the school is, when the office is open and a form for questions about courses.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"0"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:columns {"verticalAlignment":"top","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns are-vertically-aligned-top alignwide">
<!-- wp:column {"verticalAlignment":"top","width":"52%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:52%;">
<!-- wp:paragraph {"align":"left","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-left has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Get in touch', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Ask before you book', 'unapp' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"large"} -->
<p class="has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Not sure which course suits you, whether your hands will cope, or when the next term opens? Ask. One of the tutors answers, usually within a day.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:group {"className":"is-style-card","style":{"border":{"radius":"20px"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group is-style-card" style="border-radius:20px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50);">
<!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}}} -->
<p style="font-weight:600;"><?php esc_html_e( 'The school', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php esc_html_e( 'The Old School Hall, Sheffield S3 8HL. Ten minutes on foot from the station, and the tram stops outside.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:separator {"className":"is-style-wide","backgroundColor":"border"} -->
<hr class="wp-block-separator has-text-color has-border-color has-border-border-color has-alpha-channel-opacity has-border-background-color has-background is-style-wide"/>
<!-- /wp:separator -->
<!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}}} -->
<p style="font-weight:600;"><?php esc_html_e( 'Office hours', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php esc_html_e( 'Tuesday to Saturday, 10:00–16:00. Evening courses run until nine.', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:separator {"className":"is-style-wide","backgroundColor":"border"} -->
<hr class="wp-block-separator has-text-color has-border-color has-border-border-color has-alpha-channel-opacity has-border-background-color has-background is-style-wide"/>
<!-- /wp:separator -->
<!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}}} -->
<p style="font-weight:600;"><?php esc_html_e( 'Telephone and email', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php esc_html_e( '0114 555 0128 · hello@oldschoolhall.example', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"top","width":"48%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:48%;">
<!-- wp:group {"className":"is-style-card","style":{"border":{"radius":"20px"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group is-style-card" style="border-radius:20px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50);">
<?php
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup; each part is escaped where unapp_contact_form() builds it.
echo unapp_contact_form(
	array(
		'title' => _x( 'Ask about a course', 'Contact form heading', 'unapp' ),
		'email' => 'hello@oldschoolhall.example',
	)
);
?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
