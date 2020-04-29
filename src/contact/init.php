<?php // phpcs:ignore
/**
 * Orchard Recovery Contact
 *
 * Register the contact blocks.
 *
 * @since   0.3.6
 * @package ORC
 */

namespace ORC;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class to create a block and custom post type for
 * the Orchard Recovery Center contact shortcodes.
 */
class Contact {

	/**
	 * Constructor for the staff class
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'register' ) );

	}

	/**
	 * Register the block on the server-side to ensure that the block
	 * scripts and styles are enqueued when the editor loads.
	 * Provides a render function for the front end.
	 */
	public function register() {

		global $wpdb;

		register_block_type(
			Plugin::NAME . '/contact',
			array(
				'style'           => Plugin::FRONTEND_STYLE_HANDLE,
				'editor_script'   => Plugin::BACKEND_SCRIPT_HANDLE,
				'editor_style'    => Plugin::BACKEND_STYLE_HANDLE,
				'render_callback' => array( $this, 'render' ),
			)
		);

	}

	/**
	 * Render function for the contact shortcode.
	 *
	 * Possible attributes:
	 *   type   (isset - type of contact else null string)
	 *   icon   (isset - false else true)
	 *   link   (isset - false else true)
	 *   prefix (isset - prefix else null string)
	 *   suffix (isset - suffix else null string)
	 *   class  (isset - class else null string)
	 *
	 * Output [orc_contact type="T" icon="I" link="L" prefix="P" suffix="S" class="C"]
	 *
	 * @param array $attributes Attributes from the block editor.
	 */
	public function render( $attributes ) {

		$type      = ( isset( $attributes['type'] ) ) ? $attributes['type'] : '';
		$want_icon = ( isset( $attributes['wantIcon'] ) ) ? false : true;
		$want_link = ( isset( $attributes['wantLink'] ) ) ? false : true;
		$prefix    = ( isset( $attributes['prefix'] ) ) ? $attributes['prefix'] : '';
		$suffix    = ( isset( $attributes['suffix'] ) ) ? $attributes['suffix'] : '';
		$class     = ( isset( $attributes['theClass'] ) ) ? $attributes['theClass'] : '';

		$shortcode = '[orc_contact type="' . $type . '"';
		if ( $want_icon ) {
			$shortcode .= ' icon="true"';
		}
		if ( $want_link ) {
			$shortcode .= ' link="true"';
		}
		if ( '' !== $prefix ) {
			$shortcode .= ' prefix="' . $prefix . '"';
		}
		if ( '' !== $suffix ) {
			$shortcode .= ' suffix="' . $suffix . '"';
		}
		if ( '' !== $class ) {
			$shortcode .= ' class="' . $class . '"';
		}
		$shortcode .= ']';

		\ob_start();
		echo do_shortcode( $shortcode );
		return \ob_get_clean();

	}

}
