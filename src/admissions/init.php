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
	 * Render function for the admissions custom blocks.
	 *
	 * @param array $attributes Attributes from the block.
	 */
	public function render( $attributes ) {

		$div = '<div class="wp-block-orc-admissions';
		if ( isset( $attributes['align'] ) ) {
			$div .= ' ' . $attributes['align'] . '-align';
		}
		$div .= '">';

		$args = array(
			'post_type'      => array( self::POST_TYPE ),
			'post_status'    => array( 'publish' ),
			'orderby'        => 'date',
			'posts_per_page' => -1,
		);

		$posts = get_posts( $args );

		\ob_start();
		echo $div;      // phpcs:ignore
		foreach ( $posts as $post ) {
			echo '<div class="admission" id="post-' . $post->ID . '">';     // phpcs:ignore
			echo '<span data-link="' . esc_url( get_post_permalink( $post->ID ) ) . '"></span>';
			echo '<h3>' . esc_attr( $post->post_title ) . '</h3>';
			echo get_the_post_thumbnail( $post->ID );
			echo '<div class="excerpt">' . esc_attr( $post->post_excerpt ) . '</div> <!-- /.excerpt -->';
			echo '<input type="button" value="View More" />';
			echo '</div> <!-- /.admission -->';
		}
		echo '</div> <!-- /.wp-block-orc-admissions -->';
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
			'show_in_menu'        => Plugin::NAME,
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
