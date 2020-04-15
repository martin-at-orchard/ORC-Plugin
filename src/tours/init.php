<?php // phpcs:ignore

namespace ORC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;     // Exit if accessed directly.
}

/**
 * Class to handle all the tours at the Orchard.
 *
 * @since 0.1.6
 * @package ORC
 */
class Tours {

	const POST_TYPE = 'orc-tours';

	/**
	 * Constructor for the class
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'register' ) );
		add_action( 'init', array( $this, 'create_posttype' ) );
	}

	/**
	 * Enqueue Gutenberg block assets for both frontend + backend.
	 */
	public function register() {

		register_block_type(
			Plugin::NAME . '/tours',
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
			'name'                     => __( 'Tours', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'singular_name'            => __( 'Tour', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'add_new'                  => __( 'Add New Tour', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'add_new_item'             => __( 'Add New Tour', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'edit_item'                => __( 'Edit Tour', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'new_item'                 => __( 'New Tour', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'view_item'                => __( 'View Tour', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'view_items'               => __( 'View Tours', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'search_items'             => __( 'Search Tours', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'not_found'                => __( 'No Tours found', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'not_found_in_trash'       => __( 'No Tours found in trash', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'all_items'                => __( 'All Tours', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'attributes'               => __( 'Tour attributes', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'insert_into_item'         => __( 'Insert into Tour', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'uploaded_to_this_item'    => __( 'Uploaded to Tour', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'featured_image'           => __( 'Tour image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'set_featured_image'       => __( 'Set Tour image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'remove_featured_image'    => __( 'Remove Tour image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'used_featured_image'      => __( 'Use as Tour image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_published'           => __( 'Tour published', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_published_privately' => __( 'Tour published privately', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_reverted_to_draft'   => __( 'Tour reverted to draft', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_scheduled'           => __( 'Tour scheduled', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_updated'             => __( 'Tour updated', Plugin::TEXT_DOMAIN ), // phpcs:ignore
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
			'show_in_menu'        => Plugin::NAME,
			'menu_position'       => 200,
			'menu_icon'           => 'dashicons-images-alt',
			'rewrite'             => array( 'slug' => 'tours' ),
			'supports'            => $supports,
		);

		register_post_type(
			self::POST_TYPE,
			$args,
		);

	}

}
