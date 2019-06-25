<!DOCTYPE HTML>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<!--<div class="colorlib-loader"></div>-->

<div id="page">
    <nav class="colorlib-nav">
        <div class="top-menu">
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <div id="colorlib-logo">
	                        <?php
	                        if ( function_exists( 'the_custom_logo' ) ) {
		                        if ( has_custom_logo() ) {
			                        Epsilon_Helper::get_image_with_custom_dimensions( 'unapp_logo_dimensions' );
		                        }
		                        else{
			                        ?>
                                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo('name')?></a>
                                    <span class="header_tagline"><?php echo get_bloginfo('description'); ?></span>
			                        <?php
		                        }
	                        }
	                        ?>
                        </div>
                    </div>
                    <div class="col-md-9 text-right menu-1">
						<?php
						wp_nav_menu( array(
							'theme_location'    => 'header_menu',
							'container'             => true,
							'fallback_cb' => '',
							'walker'            => new Unapp_navwalker()
						) );
						?>
                    </div>
                </div>
            </div>
        </div>
    </nav>

