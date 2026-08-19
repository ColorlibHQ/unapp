<?php
/**
 * Title: Practice: patient questions
 * Slug: unapp/medical-faq
 * Categories: unapp, unapp_medical, unapp_utility, faq
 * Keywords: medical, dental, faq, nhs, emergency, patients
 * Viewport Width: 1400
 * Description: The four questions asked at reception most days, answered without jargon.
 *
 * @package Unapp
 */

?>
<!-- wp:group {"align":"full","className":"is-style-section-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-soft" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontFamily":"heading","fontSize":"small","style":{"typography":{"fontWeight":"600","letterSpacing":"0.12em","textTransform":"uppercase"}}} -->
<p class="has-text-align-center has-primary-color has-text-color has-heading-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html_x( 'Questions', 'Section eyebrow label', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Asked at reception most days', 'unapp' ); ?></h2>
<!-- /wp:heading -->
</div>
<!-- /wp:group -->
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"760px"}} -->
<div class="wp-block-group">
<!-- wp:details {"summary":"<?php echo esc_html_x( 'Are you taking NHS patients?', 'FAQ question', 'unapp' ); ?>","className":"is-style-faq-card"} -->
<details class="wp-block-details is-style-faq-card"><summary><?php echo esc_html_x( 'Are you taking NHS patients?', 'FAQ question', 'unapp' ); ?></summary>
<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php echo esc_html_x( 'For children, always. For adults the list opens a few times a year and we announce it here and on the door — there is no waiting list to join, which we know is annoying, but it is fairer than a list nobody moves up.', 'FAQ answer', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
<!-- wp:details {"summary":"<?php echo esc_html_x( 'What if something happens at the weekend?', 'FAQ question', 'unapp' ); ?>","className":"is-style-faq-card"} -->
<details class="wp-block-details is-style-faq-card"><summary><?php echo esc_html_x( 'What if something happens at the weekend?', 'FAQ question', 'unapp' ); ?></summary>
<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php echo esc_html_x( 'Ring the practice number and the answerphone gives you the out-of-hours service. For anything involving swelling that is spreading, or difficulty swallowing, go to A&E rather than waiting for us.', 'FAQ answer', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
<!-- wp:details {"summary":"<?php echo esc_html_x( 'I have not been to a dentist in years.', 'FAQ question', 'unapp' ); ?>","className":"is-style-faq-card"} -->
<details class="wp-block-details is-style-faq-card"><summary><?php echo esc_html_x( 'I have not been to a dentist in years.', 'FAQ question', 'unapp' ); ?></summary>
<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php echo esc_html_x( 'Then you are in good company, and nobody here will make a thing of it. Book a check-up, say on the phone that it has been a while, and we will give you a longer appointment.', 'FAQ answer', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
<!-- wp:details {"summary":"<?php echo esc_html_x( 'Do you take card, and can I pay monthly?', 'FAQ question', 'unapp' ); ?>","className":"is-style-faq-card"} -->
<details class="wp-block-details is-style-faq-card"><summary><?php echo esc_html_x( 'Do you take card, and can I pay monthly?', 'FAQ question', 'unapp' ); ?></summary>
<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php echo esc_html_x( 'Card, yes. There is also a monthly plan that covers check-ups, hygiene and a discount on everything else — ask at reception for the current price.', 'FAQ answer', 'unapp' ); ?></p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
