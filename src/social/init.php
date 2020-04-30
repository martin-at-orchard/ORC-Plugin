<?php // phpcs:ignore
/**
 * Orchard Recovery Social
 *
 * Register the social blocks.
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
 * the Orchard Recovery Center social shortcodes.
 */
class Social {

	/**
	 * Social types array with all the possible social link.
	 *
	 * @var array $social_types Array of social links.
	 */
	private $social_types;

	/**
	 * Constructor for the staff class
	 */
	public function __construct() {

		$this->social_types = array(
			'facebook',
			'instagram',
			'twitter',
			'youtube',
		);

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
			Plugin::NAME . '/social',
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
	 *   type   (isset - type of contact else 'facebook')
	 *   class  (isset - class else null string)
	 *
	 * Output [orc_contact type="T" class="C"]
	 *
	 * @param array $attributes Attributes from the block editor.
	 */
	public function render( $attributes ) {

		$type  = ( isset( $attributes['type'] ) ) ? $attributes['type'] : 'all';
		$class = ( isset( $attributes['theClass'] ) ) ? $attributes['theClass'] : '';

		\ob_start();

		if ( 'all' === $type ) {
			foreach ( $this->social_types as $social ) {
				$shortcode = '[orc_social type="' . $social . '"';
				if ( '' !== $class ) {
					$shortcode .= ' class="' . $class . '-' . $social . '"';
				}
				$shortcode .= ']';
				echo do_shortcode( $shortcode );
			}
		} else {
			$shortcode = '[orc_social type="' . $type . '"';
			if ( '' !== $class ) {
				$shortcode .= ' class="' . $class . '"';
			}
			$shortcode .= ']';
			echo do_shortcode( $shortcode );
		}

		return \ob_get_clean();

	}

}
