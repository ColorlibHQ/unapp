<?php
/**
 * @Packge     : Unapp
 * @Version    : 1.0
 * @Author     : Colorlib
 * @Author URI : http://colorlib.com/wp/
 *
 */

// Block direct access
if( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

// enqueue css
function unapp_common_custom_css() {

	wp_enqueue_style( 'unapp-common', get_template_directory_uri() . '/assets/css/overrides.css' );

	$accent_color_1   = esc_attr( unapp_opt( 'epsilon_accent_color' ) );
	$accent_color_2   = esc_attr( unapp_opt( 'epsilon_accent_color_second' ) );
	$accent_color_3   = esc_attr( unapp_opt( 'epsilon_accent_color_third' ) );
	$title_color      = esc_attr( unapp_opt( 'epsilon_title_color' ) );
	$text_color       = esc_attr( unapp_opt( 'epsilon_text_color' ) );
	$link_color       = esc_attr( unapp_opt( 'epsilon_link_color' ) );
	$link_hover_color = esc_attr( unapp_opt( 'epsilon_link_hover_color' ) );
	$menu_item_color  = esc_attr( unapp_opt( 'epsilon_menu_item_color' ) );
	$menu_hover_color = esc_attr( unapp_opt( 'epsilon_menu_item_hover_color' ) );
	$menu_active_color= esc_attr( unapp_opt( 'epsilon_menu_item_active_color' ) );
	$dropdown_menu_bg = esc_attr( unapp_opt( 'epsilon_dropdown_menu_background' ) );
	$drop_menu_color  = esc_attr( unapp_opt( 'epsilon_dropdown_menu_item_color' ) );
	$drop_menu_hover_color  = esc_attr( unapp_opt( 'epsilon_dropdown_menu_item_hover_color' ) );
	$drop_menu_active_color  = esc_attr( unapp_opt( 'epsilon_dropdown_menu_item_active_color' ) );
	$footer_contact_bg  = esc_attr( unapp_opt( 'epsilon_footer_contact_background' ) );
	$footer_bg        = esc_attr( unapp_opt( 'epsilon_footer_background' ) );
	$footer_title_color = esc_attr( unapp_opt( 'epsilon_footer_title_color' ) );
	$footer_text_color = esc_attr( unapp_opt( 'epsilon_footer_text_color' ) );
	$footer_link_color = esc_attr( unapp_opt( 'epsilon_footer_link_color' ) );
	$footer_link_hover_color = esc_attr( unapp_opt( 'epsilon_footer_link_hover_color' ) );
	
	

	$customcss ="

			.services .icon i,
			.colorlib-work-featured .mobile-wrap .desc .features .icon i
			{
				color: {$accent_color_1};
			}
			
			.colorlib-video a,
			.colorlib-intro .play a,
			.pulse,
			.product-entry .product-img .tag .sale {
				background-color: {$accent_color_1};
			}
			
			.video-hero .ewf-section__overlay-color,
			.colorlib-counters .ewf-section__overlay-color,
			.colorlib-subscribe .ewf-section__overlay-color {
				background: {$accent_color_3};
			    background: -moz-linear-gradient(-45deg, {$accent_color_3} 0%%, {$accent_color_1} 100%%);
			    background: -webkit-gradient(left top, right bottom, color-stop(0%%, {$accent_color_3}), color-stop(100%%, #798eea));
			    background: -webkit-linear-gradient(-45deg, {$accent_color_3} 0%%, {$accent_color_1} 100%%);
			    background: -o-linear-gradient(-45deg, {$accent_color_3} 0%%, {$accent_color_1} 100%%);
			    background: -ms-linear-gradient(-45deg, {$accent_color_3}, {$accent_color_1} 100%%);
			    background: -webkit-linear-gradient(315deg, {$accent_color_3} 0%%, {$accent_color_1} 100%%);
			    background: -o-linear-gradient(315deg, {$accent_color_3} 0%%, {$accent_color_1} 100%%);
			    background: linear-gradient(135deg, {$accent_color_3} 0%%, {$accent_color_1} 100%%);
			    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{$accent_color_3}', endColorstr='{$accent_color_1}', GradientType=1 );
			}
			
			
			.work-grid .work-img .icon,
			.product-entry .product-img .tag .new,
			.product-entry .product-img .cart p span a:hover,
			.mc4wp-form button,
			.pagination li a:hover,
			.pagination li a:focus,
			.pagination li.active a,
			.pagination li.active a:hover,
			.pagination li.active a:focus,
			.btn-primary,
			.btn-primary.btn-outline:hover,
			.btn-primary.btn-outline:focus,
			.btn-primary.btn-outline:active,
			button,
			html input[type=\"button\"],
			input[type=\"reset\"],
			input[type=\"submit\"],
			.colorlib-nav ul li.btn-cta a {
				background-color: {$accent_color_2};
			}
			::-webkit-selection {
			  background: {$accent_color_2};
			}
			::-moz-selection {
			  background: {$accent_color_2};
			}
			::selection {
			  background: {$accent_color_2};
			}
			.work-grid .desc h2 a:hover,
			.product-entry .desc .price span,
			.pricing .price,
			.contact-info-wrap-flex p span i,
			.colorlib-social-icons li a,
			.btn-primary.btn-outline {
				color: {$accent_color_2};
			}
			
			.pagination li a:hover,
			.pagination li a:focus,
			.pagination li.active a,
			.pagination li.active a:hover,
			.pagination li.active a:focus,
			#colorlib-offcanvas ul li.btn-cta a,
			.btn-primary,
			.btn-primary.btn-outline,
			button,
			html input[type=\"button\"],
			input[type=\"reset\"],
			input[type=\"submit\"] {
				border-color: {$accent_color_2};
			}

			
			.video-hero {
				background-color: {$accent_color_3};
			}
			.colorlib-work-featured .mobile-wrap .desc .features .icon i {
				color: {$accent_color_3};
			}
			
			h1, h2, h3, h4, h5, h6, figure,
			.work-flex .half .desc h2 a,
			.work-grid .desc h2 a,
			article h2 a,
			article .admin span,
			article p.author-wrap .author,
			.pricing .pricing-heading {
				color: {$title_color};
			}
			
			body,
			.pricing p,
			.post p,
			#colorlib-footer .colorlib-widget p {
				color: {$text_color};
			}
			
			a{
				color: {$link_color}
			}
			a:hover,
			a:active,
			a:focus {
				color: {$link_hover_color};
			}
			
			.colorlib-nav ul li a {
				color: {$menu_item_color};
			}
			
			.colorlib-nav ul li a:hover {
				color: {$menu_hover_color};
			}
			
			.colorlib-nav ul li.current_page_item > a,
			.colorlib-nav ul li.current_page_item > a:hover {
				color: {$menu_active_color};
			}

			.colorlib-nav ul li.has-dropdown .dropdown {
				-webkit-box-shadow: 0px 14px 33px -9px {$dropdown_menu_bg};
			    -moz-box-shadow: 0px 14px 33px -9px {$dropdown_menu_bg};
			    box-shadow: 0px 14px 33px -9px {$dropdown_menu_bg};
			    background-color: {$dropdown_menu_bg};
			}
			.colorlib-nav ul li.has-dropdown .dropdown:before {
			  border-bottom-color: {$dropdown_menu_bg};
			}
			
			.colorlib-nav ul li.has-dropdown .dropdown li a {
				color: {$drop_menu_color};
			}
			.colorlib-nav ul li.has-dropdown .dropdown li a:hover {
				color: {$drop_menu_hover_color};
			}

			.colorlib-nav ul li.has-dropdown .dropdown li.current_page_item > a {
				color: {$drop_menu_active_color};
			}

			#colorlib-footer {
				background: {$footer_contact_bg};
			    color: {$footer_text_color};
			}
			
			.copy {
			    background: {$footer_bg};
			}
			
			#colorlib-footer h4,
			#colorlib-footer .f-blog .desc h2 a,
			.copy p {
				color: {$footer_title_color};
			}
			
			#colorlib-footer .colorlib-widget p {
				color: {$footer_text_color};
			}
			
			#colorlib-footer a {
				color: {$footer_link_color};
			}
			#colorlib-footer a:hover {
				color: {$footer_link_hover_color};
			}

        ";

	wp_add_inline_style( 'unapp-common', $customcss );

}
add_action( 'wp_enqueue_scripts', 'unapp_common_custom_css', 50 );