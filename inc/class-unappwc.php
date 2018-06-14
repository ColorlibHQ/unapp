<?php

class Unappwc {

	public $recommended_plugins = array(
		'unapp-portfolio' => array(
			'recommended' => false,
		),
	);

	public $recommended_actions;

	public $theme_slug = 'unapp';

	function __construct() {

		if ( ! is_admin() && ! is_customize_preview() ) {
			return;
		}

		$this->load_class();

		$this->recommended_actions = apply_filters(
			'unapp_required_actions', array(
				array(
					'id'          => 'unapp-req-ac-install-contact-form-7',
					'title'       => Unapp_Notify_System::unapp_cf7_title(),
					'description' => Unapp_Notify_System::unapp_cf7_description(),
					'check'       => Unapp_Notify_System::unapp_has_plugin( 'contact-form-7' ),
					'plugin_slug' => 'contact-form-7',
				),
				array(
					'id'          => 'unapp-req-ac-install-wp-mailchimp-plugin',
					'title'       => Unapp_Notify_System::unapp_mailchimp_title(),
					'description' => Unapp_Notify_System::unapp_mailchimp_description(),
					'check'       => Unapp_Notify_System::unapp_has_plugin( 'mailchimp-for-wp' ),
					'plugin_slug' => 'mailchimp-for-wp',
				),
				array(
					'id'          => 'unapp-req-ac-install-one-click-demo-import',
					'title'       => Unapp_Notify_System::unapp_ocdi_title(),
					'description' => Unapp_Notify_System::unapp_ocdi_description(),
					'check'       => Unapp_Notify_System::unapp_has_plugin( 'one-click-demo-import' ),
					'plugin_slug' => 'one-click-demo-import',
				),

			)
		);

		$this->init_epsilon();
		$this->init_welcome_screen();

		// Hooks
		add_action( 'customize_register', array( $this, 'init_customizer' ) );

	}

	public function load_class() {

		if ( ! is_admin() && ! is_customize_preview() ) {
			return;
		}

		require_once get_template_directory() . '/inc/libraries/epsilon-framework/class-epsilon-autoloader.php';
		require_once get_template_directory() . '/inc/class-unappwc-notify-system.php';
		require_once get_template_directory() . '/inc/libraries/welcome-screen/class-epsilon-welcome-screen.php';

	}

	public function init_epsilon() {

		$args = array(
			'controls' => array( 'slider', 'toggle' ), // array of controls to load
			'sections' => array( 'recommended-actions', 'pro' ), // array of sections to load
			'backup'   => false,
		);

		new Epsilon_Framework( $args );

	}

	public function init_welcome_screen() {

		Epsilon_Welcome_Screen::get_instance(
			$config = array(
				'theme-name' => 'Unapp',
				'theme-slug' => 'unapp',
				'actions'    => $this->recommended_actions,
				'plugins'    => $this->recommended_plugins,
			)
		);

	}

	public function init_customizer( $wp_customize ) {
		$current_theme = wp_get_theme();
		$wp_customize->add_section(
			new Epsilon_Section_Recommended_Actions(
				$wp_customize, 'epsilon_recomended_section', array(
					'title'                        => esc_html__( 'Recomended Actions', 'unapp' ),
					'social_text'                  => esc_html( $current_theme->get( 'Author' ) ) . esc_html__( ' is social :', 'unapp' ),
					'plugin_text'                  => esc_html__( 'Recomended Plugins :', 'unapp' ),
					'actions'                      => $this->recommended_actions,
					'plugins'                      => $this->recommended_plugins,
					'theme_specific_option'        => $this->theme_slug . '_show_required_actions',
					'theme_specific_plugin_option' => $this->theme_slug . '_show_required_plugins',
					'facebook'                     => 'https://www.facebook.com/colorlib',
					'twitter'                      => 'https://twitter.com/colorlib',
					'wp_review'                    => true,
					'priority'                     => 0,
				)
			)
		);

	}

	private function generate_action_html() {

		$import_actions = array(
			'set-frontpage'  => esc_html__( 'Set Static FrontPage', 'unapp' ),
			'import-widgets' => esc_html__( 'Import HomePage Widgets', 'unapp' ),
		);

		$import_plugins = array(
			'contact-form-7'    => esc_html__( 'Contact Form 7', 'unapp' ),
			'mailchimp-for-wp'           => esc_html__( 'Mailchimp', 'unapp' ),
			'one-click-demo-import'           => esc_html__( 'One Click Demo Import', 'unapp' ),
		);

		$plugins_html = '';

		if ( is_customize_preview() ) {
			$url  = 'themes.php?page=%1$s-welcome&tab=%2$s';
			$html = '<a class="button button-primary" id="" href="' . esc_url( admin_url( sprintf( $url, 'unapp', 'recommended-actions' ) ) ) . '">' . esc_html__( 'Import Demo Content', 'unapp' ) . '</a>';
		} else {
			$html  = '<p><a class="button button-primary cpo-import-button epsilon-ajax-button" data-action="import_demo" id="add_default_sections" href="#">' . esc_html__( 'Import Demo Content', 'unapp' ) . '</a>';
			$html .= '<a class="button epsilon-hidden-content-toggler" href="#welcome-hidden-content">' . esc_html__( 'Advanced', 'unapp' ) . '</a></p>';
			$html .= '<div class="import-content-container" id="welcome-hidden-content">';

			foreach ( $import_plugins as $id => $label ) {
				if ( ! Unapp_Notify_System::unapp_has_plugin( $id ) ) {
					$plugins_html .= $this->generate_checkbox( $id, $label, 'plugins' );
				}
			}

			if ( '' != $plugins_html ) {
				$html .= '<div class="plugins-container">';
				$html .= '<h4>' . esc_html__( 'Plugins', 'unapp' ) . '</h4>';
				$html .= '<div class="checkbox-group">';
				$html .= $plugins_html;
				$html .= '</div>';
				$html .= '</div>';
			}

			$html .= '<div class="demo-content-container">';
			$html .= '<h4>' . esc_html__( 'Demo Content', 'unapp' ) . '</h4>';
			$html .= '<div class="checkbox-group">';
			foreach ( $import_actions as $id => $label ) {
				$html .= $this->generate_checkbox( $id, $label );
			}
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
		}

		return $html;

	}

	private function generate_checkbox( $id, $label, $name = 'options', $block = false ) {
		$string = '<label><input checked type="checkbox" name="%1$s" class="demo-checkboxes"' . ( $block ? ' disabled ' : ' ' ) . 'value="%2$s">%3$s</label>';

		return sprintf( $string, $name, $id, $label );
	}

}

new Unappwc();
