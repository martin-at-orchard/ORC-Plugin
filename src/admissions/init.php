<?php // phpcs:ignore

namespace ORC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;     // Exit if accessed directly.
}

/**
 * Class to handle all the admissions at the Orchard.
 *
 * @since 0.1.4
 * @package ORC
 */
class Admissions {

	const POST_TYPE = 'orc-admissions';

	/**
	 * Constructor for the class
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'register' ) );
		add_action( 'init', array( $this, 'create_posttype' ) );
	}

	/**
	 * Register the block.
	 */
	public function register() {

		register_block_type(
			Plugin::NAME . '/admissions',
			array(
				'style'           => Plugin::FRONTEND_STYLE_HANDLE,
				'editor_script'   => Plugin::BACKEND_SCRIPT_HANDLE,
				'editor_style'    => Plugin::BACKEND_STYLE_HANDLE,
				'render_callback' => array( $this, 'render' ),
			)
		);

	}

	/**
	 * Render function for the program custom blocks.
	 *
	 * @param array $attributes Attributes from the block.
	 */
	public function render( $attributes ) {

		$args = array(
			'post_type'      => array( self::POST_TYPE ),
			'post_status'    => array( 'publish' ),
			'orderby'        => 'date',
			'posts_per_page' => -1,
		);

		$posts = get_posts( $args );

		\ob_start();
		foreach ( $posts as $post ) {
			echo '<h2>' . esc_attr( $post->post_title ) . '</h2>';
			echo get_the_post_thumbnail( $post->ID );
			echo '<p>' . esc_attr( $post->post_excerpt ) . '</p>';
			echo get_the_content( null, false, $post->ID ); // phpcs:ignore
			echo '<hr>';
		}
		return \ob_get_clean();

	}

	/**
	 * Create Program Custom Post Type
	 */
	public function create_posttype() {

		$labels = array(
			'name'                     => __( 'Admissions', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'singular_name'            => __( 'Admission', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'add_new'                  => __( 'Add New Admission', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'add_new_item'             => __( 'Add New Admission', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'edit_item'                => __( 'Edit Admission', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'new_item'                 => __( 'New Admission', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'view_item'                => __( 'View Admission', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'view_items'               => __( 'View Admissions', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'search_items'             => __( 'Search Admissions', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'not_found'                => __( 'No Admissions found', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'not_found_in_trash'       => __( 'No Admissions found in trash', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'all_items'                => __( 'All Admissions', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'attributes'               => __( 'Admission attributes', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'insert_into_item'         => __( 'Insert into Admission', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'uploaded_to_this_item'    => __( 'Uploaded to Admission', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'featured_image'           => __( 'Admission image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'set_featured_image'       => __( 'Set Admission image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'remove_featured_image'    => __( 'Remove Admission image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'used_featured_image'      => __( 'Use as Admission image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_published'           => __( 'Admission published', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_published_privately' => __( 'Admission published privately', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_reverted_to_draft'   => __( 'Admission reverted to draft', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_scheduled'           => __( 'Admission scheduled', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_updated'             => __( 'Admission updated', Plugin::TEXT_DOMAIN ), // phpcs:ignore
		);

		$supports = array(
			'title',
			'editor',
			'thumbnail',
			'excerpt',
		);

		$args = array(
			'labels'              => $labels,
			'public'              => true,
			'has_archive'         => false,
			'query_var'           => true,
			'capability_type'     => 'post',
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'show_in_nav_menus'   => false,
			'show_in_rest'        => true,
			'menu_position'       => 200,
			'menu_icon'           => 'dashicons-tickets-alt',
			'rewrite'             => array( 'slug' => 'admissions' ),
			'supports'            => $supports,
		);

		register_post_type(
			self::POST_TYPE,
			$args,
		);

	}

}
