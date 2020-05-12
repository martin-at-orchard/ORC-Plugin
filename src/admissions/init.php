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
	 * Possible attributes:
	 *   numPosts    (isset - number of posts else all)
	 *   wantExcerpt (isset - false else true)
	 *   wantLink    (isset - false else true)
	 *   wantButton  (isset - false else true)
	 *   buttonText  (isset - text string else null string)
	 *
	 * @param array $attributes Attributes from the block.
	 */
	public function render( $attributes ) {

		$num_posts    = ( isset( $attributes['numPosts'] ) && $attributes['numPosts'] > 0 ) ? $attributes['numPosts'] : -1;
		$want_link    = ( isset( $attributes['wantLink'] ) ) ? false : true;
		$want_link    = ( isset( $attributes['wantLink'] ) ) ? false : true;
		$want_excerpt = ( isset( $attributes['wantExcerpt'] ) ) ? false : true;
		$want_button  = ( isset( $attributes['wantButton'] ) ) ? false : true;
		$button_text  = ( isset( $attributes['buttonText'] ) ) ? $attributes['buttonText'] : 'View More';

		$args = array(
			'post_type'      => array( self::POST_TYPE ),
			'post_status'    => array( 'publish' ),
			'orderby'        => 'date',
			'order'          => 'ASC',
			'posts_per_page' => $num_posts,
		);

		$posts = get_posts( $args );

		$div = '<div class="wp-block-orc-admissions';
		if ( isset( $attributes['align'] ) ) {
			$div .= ' ' . esc_attr( $attributes['align'] ) . '-align';
		}
		$div .= '">';

		\ob_start();
		echo $div;      // phpcs:ignore
		foreach ( $posts as $post ) {
			echo '<div class="admission" id="post-' . esc_attr( $post->ID ) . '">';
			if ( $want_link ) {
				echo '<span data-link="' . esc_url( get_post_permalink( $post->ID ) ) . '"></span>';
			}
			echo '<h3>' . esc_attr( $post->post_title ) . '</h3>';
			if ( has_post_thumbnail( $post->ID ) ) {
				echo get_the_post_thumbnail( $post->ID );
			}
			if ( $want_excerpt ) {
				echo '<div class="excerpt">' . esc_attr( $post->post_excerpt ) . '</div> <!-- /.excerpt -->';
			}
			if ( $want_button && $want_link ) {
				echo '<input type="button" value="' . esc_attr( $button_text ) . '" aria-label="Learn more about ' . esc_attr( $post->post_title ) . '" />';
			}
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
			'name'                     => __( 'Admissions', 'orc-plugin' ),
			'singular_name'            => __( 'Admission', 'orc-plugin' ),
			'add_new'                  => __( 'Add New Admission', 'orc-plugin' ),
			'add_new_item'             => __( 'Add New Admission', 'orc-plugin' ),
			'edit_item'                => __( 'Edit Admission', 'orc-plugin' ),
			'new_item'                 => __( 'New Admission', 'orc-plugin' ),
			'view_item'                => __( 'View Admission', 'orc-plugin' ),
			'view_items'               => __( 'View Admissions', 'orc-plugin' ),
			'search_items'             => __( 'Search Admissions', 'orc-plugin' ),
			'not_found'                => __( 'No Admissions found', 'orc-plugin' ),
			'not_found_in_trash'       => __( 'No Admissions found in trash', 'orc-plugin' ),
			'all_items'                => __( 'All Admissions', 'orc-plugin' ),
			'attributes'               => __( 'Admission attributes', 'orc-plugin' ),
			'insert_into_item'         => __( 'Insert into Admission', 'orc-plugin' ),
			'uploaded_to_this_item'    => __( 'Uploaded to Admission', 'orc-plugin' ),
			'featured_image'           => __( 'Admission image', 'orc-plugin' ),
			'set_featured_image'       => __( 'Set Admission image', 'orc-plugin' ),
			'remove_featured_image'    => __( 'Remove Admission image', 'orc-plugin' ),
			'used_featured_image'      => __( 'Use as Admission image', 'orc-plugin' ),
			'item_published'           => __( 'Admission published', 'orc-plugin' ),
			'item_published_privately' => __( 'Admission published privately', 'orc-plugin' ),
			'item_reverted_to_draft'   => __( 'Admission reverted to draft', 'orc-plugin' ),
			'item_scheduled'           => __( 'Admission scheduled', 'orc-plugin' ),
			'item_updated'             => __( 'Admission updated', 'orc-plugin' ),
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
