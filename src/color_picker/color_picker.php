<?php // phpcs:ignore

namespace ORC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;     // Exit if accessed directly.
}

/**
 * Class to handle the color picker for the website
 *
 * @since 0.1.10
 * @package ORC
 */
class ColorPicker {

	/**
	 * Constructor for the color picker class.
	 */
	public function __construct() {

		add_action( 'admin_enqueue_scripts', array( $this, 'add_color_picker' ) );

	}

	/**
	 * Add the color picker styles and scripts.
	 */
	public function add_color_picker() {

		if ( is_admin() ) {

			$script = '/color_picker/color_picker.js';

			// Add the color picker css file.
			wp_enqueue_style( 'wp-color-picker' );

			// Include our custom jQuery file with WordPress Color Picker dependency.
			wp_enqueue_script(
				'orc-color-picker',
				plugins_url( 'color_picker.js', __FILE__ ),
				array( 'jquery', 'wp-color-picker' ),
				filemtime( plugin_dir_path( __DIR__ ) . $script ),
				true
			);
		}
	}

}
