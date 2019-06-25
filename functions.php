<?php
/**
 * unapp functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * @link https://codex.wordpress.org/Theme_Development
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @since unapp 1.0
 */

/**
 * unapp only works in WordPress version 4.9.6 or later
 */

if( ! function_exists( 'unapp_setup' ) ) {
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 *
	 * Create your own unapp_setup() function to override in a child theme.
	 *
	 * @since unapp 1.0
	 */
	function unapp_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on unapp, use a find and replace
		 * to change 'unapp' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'unapp' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		 /*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );
		add_theme_support( 'custom-header' );
		add_theme_support( 'custom-background' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'unapp-post-thumb', 680, 470, true );
		add_image_size( 'unapp-portfolio-thumb', 360, 270, true );
		add_image_size( 'unapp-portfolio-big', 570, 450, true );
		add_image_size( 'unapp-footer-blog', 70, 60, true );

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus( array(
			'header_menu' => esc_html__( 'Header Menu', 'unapp' ),
			'footer_menu'  => esc_html__( 'Footer Menu', 'unapp' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
			'gallery',
			'audio',
		) );

		add_theme_support(
			'custom-logo',
			array(
				'flex-width'  => true,
				'flex-height' => true,
			)
		);

		add_theme_support(
			'custom-header',
			array(
				'width'              => 1920,
				'default-image'      => get_template_directory_uri() . '/assets/images/00_header_01.jpg',
				'height'             => 600,
				'flex-height'        => true,
				'flex-width'         => true,
				'default-text-color' => '#232323',
				'header-text'        => false,
				'uploads'            => true,
				'video'              => false,
			)
		);
		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );
	}
}
add_action( 'after_setup_theme', 'unapp_setup' );

/**
 * Registers a widget area.
 *
 * @link https://developer.wordpress.org/reference/functions/register_sidebar/
 *
 * @since unapp 1.0
 */
function unapp_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'unapp' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'unapp' ),
			'before_widget' => '<article id="%1$s" class="widget %2$s">',
			'after_widget'  => '</article>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer One', 'unapp' ),
			'id'            => 'footer-sidebar-1',
			'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'unapp' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Two', 'unapp' ),
			'id'            => 'footer-sidebar-2',
			'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'unapp' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Three', 'unapp' ),
			'id'            => 'footer-sidebar-3',
			'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'unapp' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Four', 'unapp' ),
			'id'            => 'footer-sidebar-4',
			'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'unapp' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);
}
add_action( 'widgets_init', 'unapp_widgets_init' );

/**
 * unapp Enqueue scripts and styles
 */
function unapp_scripts() {
	// Theme style
	wp_enqueue_style( 'unapp-style', get_stylesheet_uri() );
	// Animation style
	wp_enqueue_style( 'unapp-animate', get_template_directory_uri() . '/assets/css/animate.css', array(), false, 'all' );
	// Font Awesome
	wp_enqueue_style( 'unapp-font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css', array(), false, 'all' );
	// Icomoon Icon Fonts
	wp_enqueue_style( 'unapp-icomoon', get_template_directory_uri() . '/assets/css/icomoon.css', array(), false, 'all' );
	// bootstrap framework
	wp_enqueue_style( 'unapp-bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.css', array(), false, 'all' );
	// magnific-popup
	wp_enqueue_style( 'unapp-magnific-popup', get_template_directory_uri() . '/assets/css/magnific-popup.css', array(), false, 'all' );
	// owl carousel
	wp_enqueue_style( 'unapp-owl-carousel-min', get_template_directory_uri() . '/assets/css/owl.carousel.min.css', array(), false, 'all' );
	wp_enqueue_style( 'unapp-owl-theme-default-min', get_template_directory_uri() . '/assets/css/owl.theme.default.min.css', array(), false, 'all' );

	//Google Font
	wp_enqueue_style( 'google-font', esc_url('https://fonts.googleapis.com/css?family=Poppins:300,400,500,600|Nunito:200,300,400'), array(), false, 'all' );

	// Custom style
	wp_enqueue_style( 'unapp-main', get_template_directory_uri() . '/assets/css/style.css', array(), false, 'all' );
	// Load jQuery
	wp_enqueue_script( 'jQuery' );
	//modernizr
	wp_enqueue_script( 'unapp-modernizr-min', get_template_directory_uri() . '/assets/js/modernizr-2.6.2.min.js', array( 'jquery' ), false, true );
	//respond.min
	wp_enqueue_script( 'unapp-respond-min', get_template_directory_uri() . '/assets/js/respond.min.js', array( 'jquery' ), false, true );
	//Jquery easing
	wp_enqueue_script( 'unapp-jquery-easing', get_template_directory_uri() . '/assets/js/jquery.easing.1.3.js', array( 'jquery' ), false, true );
	// Bootstrap
	wp_enqueue_script( 'unapp-bootstrap-min', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array( 'jquery' ), false, true );
	// Waypoints
	wp_enqueue_script( 'unapp-waypoints-min', get_template_directory_uri() . '/assets/js/jquery.waypoints.min.js', array( 'jquery' ), false, true );
	// Stellar
	wp_enqueue_script( 'unapp-stellar-min', get_template_directory_uri() . '/assets/js/jquery.stellar.min.js', array( 'jquery' ), false, true );
	// YTPlayer JS
	wp_enqueue_script( 'unapp-mb-YTPlayer-min', get_template_directory_uri() . '/assets/js/jquery.mb.YTPlayer.min.js', array( 'jquery' ), false, true );
	// Owl Carousel
	wp_enqueue_script( 'unapp-owl-carousel-min', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', array( 'jquery' ), false, true );
	// Magnific Popup JS
	wp_enqueue_script( 'unapp-magnific-popup-min', get_template_directory_uri() . '/assets/js/jquery.magnific-popup.min.js', array( 'jquery' ), false, true );
	wp_enqueue_script( 'unapp-magnific-popup-options', get_template_directory_uri() . '/assets/js/magnific-popup-options.js', array( 'jquery' ), false, true );
	// Countdown JS
	wp_enqueue_script( 'unapp-countTo', get_template_directory_uri() . '/assets/js/jquery.countTo.js', array( 'jquery' ), false, true );
	// Theme main JS
	wp_enqueue_script( 'unapp-main', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery' ), false, true );
	// reply comments
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'unapp_scripts' );

/**
 * Registers an editor stylesheet for the theme.
 */
function unapp_theme_add_editor_styles() {
    add_editor_style( get_template_directory_uri() . '/assets/css/custom-editor-style.css' );
}
add_action( 'admin_init', 'unapp_theme_add_editor_styles' );

// Require WordPress nav walker menu
require get_parent_theme_file_path() . '/inc/unapp_navwalker.php';

// Require theme custom functions
require get_parent_theme_file_path() . '/inc/unapp_functions.php';

// Require theme custom widget
require get_parent_theme_file_path() . '/inc/widget/widget_setting.php';

// Require theme Demo Data
require get_parent_theme_file_path() . '/inc/demo-data/demo-import.php';

// Require Epsilon Cutomizer API
require get_parent_theme_file_path() . '/inc/class-unapp-autoloader.php';
if( class_exists( 'Epsilon_Framework' ) ){
	$unapp = new Unapp();
}
