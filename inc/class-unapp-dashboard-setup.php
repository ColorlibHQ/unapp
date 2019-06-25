<?php
/**
 * Unapp Dashboard
 *
 * @package Unapp
 * @since   1.0
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

class Unapp_Dashboard_Setup {
	/**
	 * Theme array
	 *
	 * @var array
	 */
	public $theme = array();
	/**
	 * Notice layout
	 *
	 * @var string
	 */
	private $notice = '';

	/**
	 * Unapp_Dashboard_Setup constructor.
	 *
	 * @param array $theme
	 */
	public function __construct( $theme = array() ) {
		$this->theme = $theme;

		$theme = wp_get_theme();
		$arr   = array(
			'theme-name'    => $theme->get( 'Name' ),
			'theme-slug'    => $theme->get( 'TextDomain' ),
			'theme-version' => $theme->get( 'Version' ),
		);

		$this->theme = wp_parse_args( $this->theme, $arr );
	}

	/**
	 * @param array $theme
	 *
	 * @return Unapp_Dashboard_Setup
	 */
	public static function get_instance( $theme = array() ) {
		static $inst;
		if ( ! $inst ) {
			$inst = new Unapp_Dashboard_Setup( $theme );
		}

		return $inst;
	}

	/**
	 * Adds an admin notice in the backend
	 *
	 * If the Epsilon Notification class does not exist, we stop
	 */
	public function add_admin_notice() {
		if ( ! class_exists( 'Epsilon_Notifications' ) ) {
			return;
		}

		if ( ! empty( $_GET ) && isset( $_GET['page'] ) && 'epsilon-onboarding' === $_GET['page'] ) {
			return;
		}

		$used_onboarding = get_theme_mod( $this->theme['theme-slug'] . '_used_onboarding', false );
		if ( $used_onboarding ) {
			return;
		}

		$imported_demo = Unapp_Notify_System::check_installed_data();
		if ( $imported_demo ) {
			return;
		}

		if ( empty( $this->notice ) ) {
			$this->notice .= '<img src="' . esc_url( get_template_directory_uri() ) . '/inc/libraries/epsilon-theme-dashboard/assets/images/macho-themes-logo-black.png" class="epsilon-author-logo" />';


			/* Translators: Notice Title */
			$this->notice .= '<h1>' . sprintf( esc_html__( 'Welcome to %1$s', 'unapp' ), $this->theme['theme-name'] ) . '</h1>';
			$this->notice .= '<p>';
			$this->notice .=
				sprintf( /* Translators: Notice */
					esc_html__( 'Welcome! Thank you for choosing %3$s! To fully take advantage of the best our theme can offer please make sure you visit our %1$swelcome page%2$s.', 'unapp' ),
					'<a href="' . esc_url( admin_url( 'themes.php?page=' . $this->theme['theme-slug'] . '-dashboard' ) ) . '">',
					'</a>',
					$this->theme['theme-name']
				);
			$this->notice .= '</p>';
			/* Translators: Notice URL */
			$this->notice .= '<p><a href="' . esc_url( admin_url( '?page=epsilon-onboarding' ) ) . '" class="button button-primary button-hero" style="text-decoration: none;"> ' . sprintf( esc_html__( 'Get started with %1$s', 'unapp' ), $this->theme['theme-name'] ) . '</a></p>';
		}
		$notifications = Epsilon_Notifications::get_instance();
		$notifications->add_notice(
			array(
				'id'      => 'notification_testing',
				'type'    => 'notice epsilon-big',
				'message' => $this->notice,
			)
		);
	}

	/**
	 * Edd params
	 *
	 * @return array
	 */
	public function get_edd( $setup = array() ) {
		$options = get_option( $setup['theme']['theme-slug'] . '_license_object', array() );
		$options = wp_parse_args(
			$options,
			array(
				'expires'       => false,
				'licenseStatus' => false,
			)
		);

		return array(
			'license'       => trim( get_option( $setup['theme']['theme-slug'] . '_license_key', false ) ),
			'licenseOption' => $setup['theme']['theme-slug'] . '_license_key',
			'downloadId'    => '221300',
			'expires'       => $options['expires'],
			'status'        => $options['licenseStatus']
		);
	}

	/**
	 * Onboarding steps
	 *
	 * @return array
	 */
	public function get_steps() {
		return array(
			array(
				'id'       => 'landing',
				'title'    => __( 'Welcome to Unapp', 'unapp' ),
				'content'  => array(
					'paragraphs' => array(
						__( ' This wizard will set up your theme, install plugins and import demo content. It is optional & should take less than a minute.', 'unapp' ),
					),
				),
				'progress' => __( 'Getting Started', 'unapp' ),
				'buttons'  => array(
					'next' => array(
						'action' => 'next',
						'label'  => __( 'Let\'s get started <span class="dashicons dashicons-arrow-right-alt2"></span>', 'unapp' ),
					),
				),
			),
			array(
				'id'       => 'plugins',
				'title'    => __( 'Install Recommended Plugins', 'unapp' ),
				'content'  => array(
					'paragraphs' => array(
						__( 'Unapp integrates tightly with a few plugins that we recommend installing to get the full theme experience, as we\'ve intended it to be. This is an optional step, but we recommend installing them as we think these hand-picked plugins work really nice with Unapp and help enhance the overall experience.', 'unapp' ),
					),
				),
				'progress' => __( 'Plugins', 'unapp' ),
				'buttons'  => array(
					'next' => array(
						'action' => 'next',
						'label'  => __( 'Next <span class="dashicons dashicons-arrow-right-alt2"></span>', 'unapp' ),
					),
				),
			),
//			array(
//				'id'       => 'demos',
//				'title'    => __( 'Import Demo Content', 'unapp' ),
//				'content'  => array(
//					'paragraphs' => array(
//						wp_kses_post( __( 'We\'ve made it easy for you to get up and running in a jiffy. Just pick any of the theme demos below, click on Select, Import and you\'ll be ready in no time. Feel free to skip this step if you\'d like to create the content yourself.', 'unapp' ) ),
//						wp_kses_post( __( '<em>Note: This is the easiest way to see what goes where. After you\'ve finished the import, you can edit the content using the built-in Customizer, available under Appearance -> Customize.</em>', 'unapp' ) )
//					),
//				),
//				'progress' => __( 'Demos', 'unapp' ),
//				'demos'    => get_template_directory() . '/inc/customizer/demo/demo.json',
//				'buttons'  => array(
//					'next' => array(
//						'action' => 'next',
//						'label'  => __( 'Next <span class="dashicons dashicons-arrow-right-alt2"></span>', 'unapp' ),
//					),
//				),
//			),
			array(
				'id'       => 'finish',
				'title'    => __( 'AYou\'re ready', 'unapp' ),
				'content'  => array(
					'paragraphs' => array(
						__( 'Your new theme has been all set up. Enjoy your new theme by <a href="https://colorlib.com">Colorlib</a>.', 'unapp' ),
					),
				),
				'progress' => __( 'Finished', 'unapp' ),
				'buttons'  => array(
					'next' => array(
						'action' => 'finish',
						'label'  => __( 'Finish', 'unapp' ),
					),
				),
			),
		);
	}

	/**
	 * Returns a html string
	 *
	 * @return string
	 */
	public function get_permission_content() {
		$html = '<div class="permission-request">';
		$html .= '<a href="#hidden-permissions" id="hidden-permissions-toggle"> ' . __( 'What permissions are being granted', 'unapp' ) . ' <span class="dashicons dashicons-arrow-down"></span></a>';
		$html .= '<div id="hidden-permissions" >
			<ul>
				<li>
					<span class="dashicons dashicons-admin-users"></span>
					<span class="content">
						<strong>' . __( 'YOUR PROFILE OVERVIEW', 'unapp' ) . '</strong>
						<small>' . __( 'Name and email address', 'unapp' ) . '</small>		
					</span>
				</li>
				<li>
					<span class="dashicons dashicons-admin-settings"></span>
					<span class="content">
						<strong>' . __( 'YOUR SITE OVERVIEW', 'unapp' ) . '</strong>
						<small>' . __( 'Site URL, WP Version, PHP Version, plugins and themes', 'unapp' ) . '</small>		
					</span>
				</li>
				<li>
					<span class="dashicons dashicons-admin-plugins"></span>
					<span class="content">
						<strong>' . __( 'CURRENT PLUGIN EVENTS', 'unapp' ) . '</strong>
						<small>' . __( 'Activation, deactivation and uninstall', 'unapp' ) . '</small>		
					</span>
				</li>
			</ul>
			</div>
		</div>';

		return $html;
	}

	/**
	 * @param bool $integrated
	 *
	 * @return array
	 */
	public function get_plugins( $integrated = false ) {
		$arr = array(
			'contact-form-7' => array(
				'integration' => true,
				'recommended' => false,
			),
			'mailchimp-for-wp' => array(
				'integration' => true,
				'recommended' => false,
			),
            'one-click-demo-import' => array(
                'integration' => true,
                'recommended' => false,
            ),
		);

		if ( ! $integrated ) {
			unset( $arr['contact-form-7'] );
			unset( $arr['mailchimp-for-wp'] );
			unset( $arr['one-click-demo-import'] );

		}

		return $arr;
	}

	/**
	 * Dashboard actions
	 */
	public function get_actions() {
		if ( is_customize_preview() ) {
			return $this->_customizer_actions();
		}

		return array(
//			array(
//				'id'          => 'unapp-import-data',
//				'title'       => esc_html__( 'Add sample content', 'unapp' ),
//				'description' => esc_html__( 'Clicking the button below will add content/sections/settings and recommended plugins to your WordPress installation. Click advanced to customize the import process in the dedicated tab.', 'unapp' ),
//				'check'       => Unapp_Notify_System::check_installed_data(),
//				'state'       => false,
//				'actions'     => array(
//					array(
//						'label'   => esc_html__( 'Advanced', 'unapp' ),
//						'type'    => 'change-page',
//						'handler' => 'epsilon-demo',
//					),
//				),
//			),
			array(
				'id'          => 'unapp-check-cf7',
				'title'       => Unapp_Notify_System::plugin_verifier( 'contact-form-7', 'title', 'Contact Form 7', 'verify_cf7' ),
				'description' => Unapp_Notify_System::plugin_verifier( 'contact-form-7', 'description', 'Contact Form 7', 'verify_cf7' ),
				'plugin_slug' => 'contact-form-7',
				'state'       => false,
				'check'       => defined( 'WPCF7_VERSION' ),
				'actions'     => array(
					array(
						'label'   => Unapp_Notify_System::plugin_verifier( 'contact-form-7', 'installed', 'Contact Form 7', 'verify_cf7' ) ? __( 'Activate Plugin', 'unapp' ) : __( 'Install Plugin', 'unapp' ),
						'type'    => 'handle-plugin',
						'handler' => Unapp_Notify_System::plugin_verifier( 'contact-form-7', 'installed', 'Contact Form 7', 'verify_cf7' ),
					),
				),
			),
			array(
				'id'          => 'unapp-check-mailchimp-for-wp',
				'title'       => Unapp_Notify_System::plugin_verifier( 'mailchimp-for-wp', 'title', 'Mailchimp', 'verify_mc4wp' ),
				'description' => Unapp_Notify_System::plugin_verifier( 'mailchimp-for-wp', 'description', 'Mailchimp', 'verify_mc4wp' ),
				'plugin_slug' => 'mailchimp-for-wp',
				'state'       => false,
				'check'       => defined( 'MC4WP_VERSION' ),
				'actions'     => array(
					array(
						'label'   => Unapp_Notify_System::plugin_verifier( 'mailchimp-for-wp', 'installed', 'Mailchimp', 'verify_mc4wp' ) ? __( 'Activate Plugin', 'unapp' ) : __( 'Install Plugin', 'unapp' ),
						'type'    => 'handle-plugin',
						'handler' => Unapp_Notify_System::plugin_verifier( 'mailchimp-for-wp', 'installed', 'Mailchimp', 'verify_mc4wp' ),
					),
				),
			),
            array(
                'id'          => 'unapp-check-ocdi',
                'title'       => Unapp_Notify_System::plugin_verifier( 'one-click-demo-import', 'title', 'One Click Demo Import' ),
                'description' => Unapp_Notify_System::plugin_verifier( 'one-click-demo-import', 'description', 'One Click Demo Import' ),
                'plugin_slug' => 'one-click-demo-import',
                'state'       => false,
                'check'       => defined( 'PT_OCDI_VERSION' ),
                'actions'     => array(
                    array(
                        'label'   => Unapp_Notify_System::plugin_verifier( 'one-click-demo-import', 'installed', 'One Click Demo Import' ) ? __( 'Activate Plugin', 'unapp' ) : __( 'Install Plugin', 'unapp' ),
                        'type'    => 'handle-plugin',
                        'handler' => Unapp_Notify_System::plugin_verifier( 'one-click-demo-import', 'installed', 'One Click Demo Import' ),
                    ),
                ),
            ),
		);
	}

	/**
	 * Render customizer actions
	 */
	private function _customizer_actions() {
		return array(
//			array(
//				'id'          => 'unapp-import-data',
//				'title'       => esc_html__( 'Add sample content', 'unapp' ),
//				'description' => esc_html__( 'Clicking the button below will add content/sections/settings and recommended plugins to your WordPress installation. Click advanced to customize the import process in the dedicated tab.', 'unapp' ),
//				'check'       => Unapp_Notify_System::check_installed_data(),
//				'help'        => '<a class="button button-primary" id="" href="' . esc_url( admin_url( sprintf( 'themes.php?page=%1$s-dashboard', 'unapp' ) ) ) . '">' . __( 'Import Demo Content', 'unapp' ) . '</a>',
//			),
			array(
				'id'          => 'unapp-check-cf7',
				'title'       => Unapp_Notify_System::plugin_verifier( 'contact-form-7', 'title', 'Contact Form 7', 'verify_cf7' ),
				'description' => Unapp_Notify_System::plugin_verifier( 'contact-form-7', 'description', 'Contact Form 7', 'verify_cf7' ),
				'plugin_slug' => 'contact-form-7',
				'check'       => defined( 'WPCF7_VERSION' ),
			),
			array(
				'id'          => 'unapp-check-mailchimp',
				'title'       => Unapp_Notify_System::plugin_verifier( 'mailchimp-for-wp', 'title', 'Mailchimp', 'verify_mc4wp' ),
				'description' => Unapp_Notify_System::plugin_verifier( 'mailchimp-for-wp', 'description', 'Mailchimp', 'verify_mc4wp' ),
				'plugin_slug' => 'mailchimp-for-wp',
				'check'       => defined( 'MC4WP_VERSION' ),
			),
            array(
                'id'          => 'unapp-check-ocdi',
                'title'       => Unapp_Notify_System::plugin_verifier( 'one-click-demo-import', 'title', 'One Click Demo Import' ),
                'description' => Unapp_Notify_System::plugin_verifier( 'one-click-demo-import', 'description', 'One Click Demo Import' ),
                'plugin_slug' => 'one-click-demo-import',
                'check'       => defined( 'PT_OCDI_VERSION' ),
            ),
		);
	}

	/**
	 * Welcome Screen tabs
	 *
	 * @param $setup array
	 *
	 * @return array
	 */
	public function get_tabs( $setup = array() ) {
		$theme = wp_get_theme();

		return array(
			array(
				'id'      => 'epsilon-getting-started',
				'title'   => esc_html__( 'Getting Started', 'unapp' ),
				'hidden'  => false,
				'type'    => 'info',
				'content' => array(
					array(
						'title'     => esc_html__( 'Step 1 - Implement recommended actions', 'unapp' ),
						'paragraph' => esc_html__( 'We compiled a list of steps for you, to take make sure the experience you will have using one of our products is very easy to follow.', 'unapp' ),
						'action'    => '<a href="' . esc_url( admin_url() . '?page=epsilon-onboarding' ) . '" class="button button-primary">' . __( 'Launch wizard', 'unapp' ) . '</a>',
					),
					array(
						'title'     => esc_html__( 'Step 2 - Check our documentation', 'unapp' ),
						'paragraph' => esc_html__( 'Even if you are a long-time WordPress user, we still believe you should give our documentation a very quick Read.', 'unapp' ),
						'action'    => '<a target="_blank" href="https://colorlib.com/wp/forums/">' . __( 'Full documentation', 'unapp' ) . '</a>',
					),
					array(
						'title'     => esc_html__( 'Step 3 - Customize everything', 'unapp' ),
						'paragraph' => esc_html__( 'Using the WordPress Customizer you can easily customize every aspect of the theme.', 'unapp' ),
						'action'    => '<a target="_blank" href="' . esc_url( admin_url() . 'customize.php' ) . '" class="button button-primary">' . esc_html__( 'Go to Customizer', 'unapp' ) . '</a>',
					),
				),
			),
//			array(
//				'id'      => 'epsilon-demo',
//				'title'   => esc_html__( 'Demo Content', 'unapp' ),
//				'type'    => 'demos',
//				'hidden'  => false,
//				'content' => array(
//					'title'          => esc_html__( 'Install your demo content', 'unapp' ),
//					'titleAlternate' => esc_html__( 'Demo content already installed', 'unapp' ),
//					'paragraph'      => esc_html__( 'Since Unapp is a versatile theme we provided a few sample demo styles for you, please choose one from the following pages so you will have to work as little as you should. Click on the style and press next!', 'unapp' ),
//					'check'          => Unapp_Notify_System::check_installed_data(),
//					'demos'          => get_template_directory() . '/inc/customizer/demo/demo.json',
//				),
//			),
			array(
				'id'      => 'epsilon-actions',
				'title'   => esc_html__( 'Actions', 'unapp' ),
				'type'    => 'actions',
				'hidden'  => $this->theme['theme-slug'] . '_recommended_actions',
				'content' => $this->get_actions(),
			),
//			array(
//				'id'     => 'epsilon-plugins',
//				'title'  => esc_html__( 'Recommended Plugins', 'unapp' ),
//				'hidden' => $this->theme['theme-slug'] . '_recommended_plugins',
//				'type'   => 'plugins',
//			),
			array(
				'id'      => 'epsilon-privacy',
				'title'   => esc_html__( 'Privacy', 'unapp' ),
				'type'    => 'option-page',
				'hidden'  => false,
				'content' => array(
					'title'      => esc_html__( 'We believe in a better and streamlined user experiences', 'unapp' ),
					'paragraphs' => array(
						esc_html__( 'And as such, we\'ve made it easy for you - our user, to disable all of our theme recommendations.', 'unapp' ),
						esc_html__( 'Mind you that we use these as a way to facilitate compatible product discovery - as in: plugins that enhance the user experience with any of our products. But, in the end, the user should always have a say in it.', 'unapp' ),
						wp_kses_post( __( 'By turning the toggle below to the <span style="color: green;">ON</span> position you\'ll be able to hide our theme recommendations.', 'unapp' ) ),
						wp_kses_post( __( '<u>We really hope</u> you\'ll enjoy using our products as much as we\'ve enjoyed building them.', 'unapp' ) ),
					),
				),
				'fields'  => array(
					array(
						'id'      => $this->theme['theme-slug'] . '_recommended_actions',
						'type'    => 'epsilon-toggle',
						'value'   => true,
						'label'   => esc_html__( 'Hide Customizer Recommended Actions', 'unapp' ),
						'checked' => get_option( $this->theme['theme-slug'] . '_recommended_actions', false ),
					),
				),
			),
		);
	}

	/**
	 * Return privacy options
	 *
	 * @return array
	 */
	public function get_privacy_options() {
		$arr = array(
			$this->theme['theme-slug'] . '_recommended_actions' => get_option( $this->theme['theme-slug'] . '_recommended_actions', false ),
			$this->theme['theme-slug'] . '_recommended_plugins' => get_option( $this->theme['theme-slug'] . '_recommended_plugins', false ),
			$this->theme['theme-slug'] . '_lite_vs_pro'         => get_option( $this->theme['theme-slug'] . '_lite_vs_pro', 'NA' ),
			$this->theme['theme-slug'] . '_theme_upsells'       => get_option( $this->theme['theme-slug'] . '_theme_upsells', 'NA' ),
			$this->theme['theme-slug'] . '_tracking_enable'     => get_option( $this->theme['theme-slug'] . '_tracking_enable', false ),
		);

		foreach ( $arr as $id => $val ) {
			$arr[ $id ] = empty( $val ) ? false : true;
		}

		return $arr;
	}
}
