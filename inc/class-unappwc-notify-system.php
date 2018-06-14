<?php

if ( ! class_exists( 'Unapp_Notify_System' ) ) {
	/**
	 * Class Unapp_Notify_System
	 */
	class Unapp_Notify_System extends Epsilon_Notify_System {
		/**
		 * @param $ver
		 *
		 * @return mixed
		 */
		public static function unapp_version_check( $ver ) {
			$unapp = wp_get_theme();

			return version_compare( $unapp['Version'], $ver, '>=' );
		}

		/**
		 * @return bool
		 */
		public static function unapp_is_not_static_page() {
			return 'page' == get_option( 'show_on_front' ) ? true : false;
		}


		/**
		 * @return bool
		 */
		public static function unapp_has_content() {
			$option = get_option( 'unapp_imported_demo', false );
			if ( $option ) {
				return true;
			};

			return false;
		}

		/**
		 * @return bool|mixed
		 */
		public static function unapp_check_import_req() {
			$needs = array(
				'has_content' => self::unapp_has_content(),
				//'has_plugin'  => self::unapp_has_plugin( 'unapp-companion' ),
			);

			if ( $needs['has_content'] ) {
				return true;
			}

			if ( $needs['has_plugin'] ) {
				return false;
			}

			return true;
		}

		/**
		 * @return bool
		 */
		public static function unapp_check_plugin_is_installed( $slug ) {
			$slug2 = $slug;
			if ( 'wordpress-seo' === $slug ) {
				$slug2 = 'wp-seo';
			}

			$path = WPMU_PLUGIN_DIR . '/' . $slug . '/' . $slug2 . '.php';
			if ( ! file_exists( $path ) ) {
				$path = WP_PLUGIN_DIR . '/' . $slug . '/' . $slug2 . '.php';

				if ( ! file_exists( $path ) ) {
					$path = false;
				}
			}

			if ( file_exists( $path ) ) {
				return true;
			}

			return false;
		}

		/**
		 * @return bool
		 */
		public static function unapp_check_plugin_is_active( $slug ) {
			$slug2 = $slug;
			if ( 'wordpress-seo' === $slug ) {
				$slug2 = 'wp-seo';
			}

			$path = WPMU_PLUGIN_DIR . '/' . $slug . '/' . $slug2 . '.php';
			if ( ! file_exists( $path ) ) {
				$path = WP_PLUGIN_DIR . '/' . $slug . '/' . $slug2 . '.php';
				if ( ! file_exists( $path ) ) {
					$path = false;
				}
			}

			if ( file_exists( $path ) ) {
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

				return is_plugin_active( $slug . '/' . $slug2 . '.php' );
			}
		}

		public static function unapp_has_plugin( $slug = null ) {

			$check = array(
				'installed' => self::check_plugin_is_installed( $slug ),
				'active'    => self::check_plugin_is_active( $slug ),
			);

			if ( ! $check['installed'] || ! $check['active'] ) {
				return false;
			}

			return true;
		}

		public static function unapp_yoast_title() {
			$installed = self::check_plugin_is_installed( 'wordpress-seo' );
			if ( ! $installed ) {
				return esc_html__( 'Install: Yoast SEO Plugin', 'unapp' );
			}

			$active = self::check_plugin_is_active( 'wordpress-seo' );
			if ( $installed && ! $active ) {
				return esc_html__( 'Activate: Yoast SEO Plugin', 'unapp' );
			}

			return esc_html__( 'Install: Yoast SEO Plugin', 'unapp' );
		}

		public static function unapp_mailchimp_title() {
			$installed = self::check_plugin_is_installed( 'mailchimp-for-wp' );
			if ( ! $installed ) {
				return esc_html__( 'Install: MailChimp by WordPress', 'unapp' );
			}

			$active = self::check_plugin_is_active( 'mailchimp-for-wp' );
			if ( $installed && ! $active ) {
				return esc_html__( 'Activate: MailChimp by WordPress', 'unapp' );
			}

			return esc_html__( 'Install: MailChimp by WordPress', 'unapp' );
		}

		public static function unapp_cf7_title() {
			$installed = self::check_plugin_is_installed( 'contac-form-7' );
			if ( ! $installed ) {
				return esc_html__( 'Activate: Contact Form 7', 'unapp' );
			}

			$active = self::check_plugin_is_active( 'contac-form-7' );
			if ( $installed && ! $active ) {
				return esc_html__( 'Activate: Contact Form 7', 'unapp' );
			}

			return esc_html__( 'Install: Contact Form 7', 'unapp' );
		}

		public static function unapp_ocdi_title() {
			$installed = self::check_plugin_is_installed( 'one-click-demo-import' );
			if ( ! $installed ) {
				return esc_html__( 'Install: One Click Demo Import', 'unapp' );
			}

			$active = self::check_plugin_is_active( 'one-click-demo-import' );
			if ( $installed && ! $active ) {
				return esc_html__( 'Activate: One Click Demo Import', 'unapp' );
			}

			return esc_html__( 'Install: One Click Demo Import', 'unapp' );
		}

		/**
		 * @return string
		 */

		/**
		 * @return string
		 */
		public static function unapp_mailchimp_description() {
			$installed = self::check_plugin_is_installed( 'mailchimp-for-wp' );

			if ( ! $installed ) {
				return esc_html__( 'Please install MailChimp by WordPress. Note that you won\'t be able to use the Testimonials and Portfolio widgets without it.', 'unapp' );
			}

			$active = self::check_plugin_is_active( 'mailchimp-for-wp' );
			if ( $installed && ! $active ) {
				return esc_html__( 'Please activate MailChimp by WordPress. Note that you won\'t be able to use the Testimonials and Portfolio widgets without it.', 'unapp' );
			}

			return esc_html__( 'Please install MailChimp by WordPress. Note that you won\'t be able to use the Testimonials and Portfolio widgets without it.', 'unapp' );
		}

		public static function unapp_cf7_description() {
			$installed = self::check_plugin_is_installed( 'contac-form-7' );

			if ( ! $installed ) {
				return esc_html__( 'Please install Contact Form 7. Note that you won\'t be able to use Contact widget without it.', 'unapp' );
			}

			$active = self::check_plugin_is_active( 'contac-form-7' );
			if ( $installed && ! $active ) {
				return esc_html__( 'Please activate Contact Form 7. Note that you won\'t be able to use Contact widget without it.', 'unapp' );
			}

			return esc_html__( 'Please install Contact Form 7. Note that you won\'t be able to use Contact widget without it.', 'unapp' );
		}

		public static function unapp_ocdi_description() {
			$installed = self::check_plugin_is_installed( 'one-click-demo-import' );

			if ( ! $installed ) {
				return esc_html__( 'Please install One Click Demo Import. Note that there is no setting to “connect” authors from the demo import file to the existing users in your WP site (like there is in the original WP Importer plugin).', 'unapp' );
			}

			$active = self::check_plugin_is_active( 'one-click-demo-import' );
			if ( $installed && ! $active ) {
				return esc_html__( 'Please activate Click Demo Import. Note that there is no setting to “connect” authors from the demo import file to the existing users in your WP site (like there is in the original WP Importer plugin).', 'unapp' );
			}

			return esc_html__( 'Please install Click Demo Import. Note that there is no setting to “connect” authors from the demo import file to the existing users in your WP site (like there is in the original WP Importer plugin).', 'unapp' );
		}

		public static function unapp_yoast_description() {
			$installed = self::check_plugin_is_installed( 'wordpress-seo' );
			if ( ! $installed ) {
				return esc_html__( 'Please install Yoast SEO plugin.', 'unapp' );
			}

			$active = self::check_plugin_is_active( 'wordpress-seo' );
			if ( $installed && ! $active ) {
				return esc_html__( 'Please activate Yoast SEO plugin.', 'unapp' );
			}

			return esc_html__( 'Please install Yoast SEO plugin.', 'unapp' );

		}

		/**
		 * @return bool
		 */
		public static function unapp_is_not_template_front_page() {
			$page_id = get_option( 'page_on_front' );

			return get_page_template_slug( $page_id ) == 'page-templates/frontpage-template.php' ? true : false;
		}
	}
}// End if().
