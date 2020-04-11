<?php // phpcs:ignore

namespace ORC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;     // Exit if accessed directly.
}

/**
 * Class to handle all the programs at the Orchard.
 *
 * @since 0.1.3
 * @package ORC
 */
class Programs {

	const POST_TYPE = 'orc-programs';

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
			Plugin::NAME . '/programs',
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
			'orderby'        => 'post_date',
			'order'          => 'ASC',
			'posts_per_page' => -1,
		);

		$posts = get_posts( $args );

		\ob_start();
		echo '<div class="wp-block-orc-programs">';
		foreach ( $posts as $post ) {
			echo '<div class="program" id="program-' . $post->ID . '">';     // phpcs:ignore
			echo '<h3>' . esc_attr( $post->post_title ) . '</h3>';
			echo get_the_post_thumbnail( $post->ID );
			echo '<p>' . esc_attr( $post->post_excerpt ) . '</p>';
			echo '<input type="button" value="View More" />';
			echo '<span data-link="' . esc_url( get_post_permalink( $post->ID ) ) . '"></span>';
			echo '</div>';
		}
		echo '</div>';
		return \ob_get_clean();

	}

	/**
	 * Create Program Custom Post Type
	 */
	public function create_posttype() {

		$labels = array(
			'name'                     => __( 'Programs', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'singular_name'            => __( 'Program', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'add_new'                  => __( 'Add New Program', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'add_new_item'             => __( 'Add New Program', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'edit_item'                => __( 'Edit Program', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'new_item'                 => __( 'New Program', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'view_item'                => __( 'View Program', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'view_items'               => __( 'View Programs', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'search_items'             => __( 'Search Programs', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'not_found'                => __( 'No Programs found', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'not_found_in_trash'       => __( 'No Programs found in trash', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'all_items'                => __( 'All Programs', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'attributes'               => __( 'Program attributes', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'insert_into_item'         => __( 'Insert into Program', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'uploaded_to_this_item'    => __( 'Uploaded to Program', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'featured_image'           => __( 'Program image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'set_featured_image'       => __( 'Set Program image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'remove_featured_image'    => __( 'Remove Program image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'used_featured_image'      => __( 'Use as Program image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_published'           => __( 'Program published', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_published_privately' => __( 'Program published privately', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_reverted_to_draft'   => __( 'Program reverted to draft', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_scheduled'           => __( 'Program scheduled', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_updated'             => __( 'Program updated', Plugin::TEXT_DOMAIN ), // phpcs:ignore
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
			'menu_icon'           => 'dashicons-list-view',
			'rewrite'             => array( 'slug' => 'programs' ),
			'supports'            => $supports,
		);

		register_post_type(
			self::POST_TYPE,
			$args,
		);

	}

}
