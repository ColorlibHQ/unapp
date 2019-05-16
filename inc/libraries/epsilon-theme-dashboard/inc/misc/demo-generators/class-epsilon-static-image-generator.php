<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Download a random image from Unsplash and add it as post thumbnail
 *
 * @since 1.2.0
 * Class Epsilon_Image_Generator
 */
class Epsilon_Static_Image_Generator {
	/**
	 * @var string
	 */
	public $url = '';
	/**
	 * @var string
	 */
	public $option = '';
	/**
	 * @var array
	 */
	public $uploaded = array();

	/**
	 * Epsilon_Static_Image_Generator constructor.
	 *
	 */
	public function __construct() {
		$theme          = wp_get_theme();
		$this->option   = 'epsilon_' . $theme->get( 'TextDomain' ) . '_uploaded_images';
		$this->uploaded = get_transient( $this->option );
	}

	/**
	 *
	 * @return Epsilon_Static_Image_Generator
	 */
	public static function get_instance() {
		static $inst;
		if ( ! $inst ) {
			$inst = new Epsilon_Static_Image_Generator();
		}

		return $inst;
	}

	public function add_url( $url = '' ) {
		$this->url = $url;
	}

	/**
	 * @return mixed|string
	 */
	public function get_image() {
		if ( is_array( $this->uploaded ) && array_key_exists( $this->url, $this->uploaded ) ) {
			return $this->uploaded[ $this->url ];
		}

		return $this->generate_image();
	}

	/**
	 * @return int|mixed|object
	 */
	public function generate_image() {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/post.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';

		$tmp  = download_url( $this->url );
		$file = array(
			'name'     => basename( 'unsplash-image-' . rand( 1, 123123123 ) ) . '.jpg',
			'tmp_name' => $tmp,
		);
		if ( is_wp_error( $tmp ) ) {
			unlink( $file['tmp_name'] );

			return $tmp;
		}

		$id = media_handle_sideload( $file, 0, 'Image downloaded from unsplash' );
		if ( is_wp_error( $id ) ) {
			unlink( $file['tmp_name'] );

			return $id;
		}

		$this->uploaded[ $this->url ] = wp_get_attachment_url( $id );

		set_transient( $this->option, $this->uploaded, strtotime( '+5 minutes' ) );

		return $this->uploaded[ $this->url ];
	}
}
