<?php // phpcs:ignore
/**
 * Orchard Recovery Videos
 *
 * Register the video block.
 * Create staff custom post type.
 *
 * @since   0.1.8
 * @package ORC
 */

namespace ORC;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class to create a block and custom post type for
 * the Orchard Recovery Center videos.
 */
class Videos {

	const POST_TYPE  = 'orc-videos';
	const POST_NONCE = 'orc-videos-post-class-nonce';

	/**
	 * Constructor for the videos class
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'register' ) );
		add_action( 'init', array( $this, 'create_posttype' ) );
		add_action( 'add_meta_boxes_' . self::POST_TYPE, array( $this, 'meta_box' ) );
		add_action( 'save_post', array( $this, 'save_meta' ), 10, 2 );
		add_filter( 'manage_edit-' . self::POST_TYPE . '_columns', array( $this, 'table_head' ) );
		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array( $this, 'table_content' ), 10, 2 );

	}

	/**
	 * Register the block on the server-side to ensure that the block
	 * scripts and styles are enqueued when the editor loads.
	 * Provides a render function for the front end.
	 */
	public function register() {

		register_block_type(
			Plugin::NAME . '/videos',
			array(
				'style'           => Plugin::FRONTEND_STYLE_HANDLE,
				'editor_script'   => Plugin::BACKEND_SCRIPT_HANDLE,
				'editor_style'    => Plugin::BACKEND_STYLE_HANDLE,
				'render_callback' => array( $this, 'render' ),
			)
		);

	}

	/**
	 * Render function for the videos custom blocks.
	 *
	 * @param array $attributes Attributes from the block editor.
	 */
	public function render( $attributes ) {

		if ( isset( $attributes['width'] ) && isset( $attributes['height'] ) ) {
			$iframe_style  = 'style="';
			$iframe_style .= 'max-width: ' . $attributes['width'] . 'px; width: ' . $attributes['width'] . 'px;';
			$iframe_style .= 'max-height: ' . $attributes['height'] . 'px; height: ' . $attributes['height'] . 'px;';
		} else {
			$iframe_style = '';
		}

		if ( isset( $attributes['align'] ) ) {
			if ( 'center' === $attributes['align'] ) {
				$iframe_style .= 'margin-left: auto;margin-right: auto;';
			} elseif ( 'left' === $attributes['align'] ) {
				$iframe_style .= 'margin-left: 0;margin-right: auto;';
			} elseif ( 'right' === $attributes['align'] ) {
				$iframe_style .= 'margin-left: auto;margin-right: 0;';
			}
		}

		if ( '' !== $iframe_style ) {
			$iframe_style .= '"';
		}

		$args = array(
			'post_type'      => array( self::POST_TYPE ),
			'post_status'    => array( 'publish' ),
			'orderby'        => 'date',
			'posts_per_page' => -1,
		);

		$posts = get_posts( $args );

		\ob_start();
		foreach ( $posts as $post ) {
			$link = esc_attr( get_post_meta( $post->ID, 'orc-video-link', true ) );
			echo '<h2>' . esc_attr( $post->post_title ) . '</h2>';
			echo '<div class="wp-block-orc-videos">';
			echo '<iframe ' . $iframe_style . ' src="https://youtube.com/embed/' . $link . '" frameborder="0"></iframe>'; // phpcs:ignore
			echo '</div>';
			echo '<hr>';
		}
		return \ob_get_clean();

	}

	/**
	 * Create Videos Custom Post Type
	 */
	public function create_posttype() {

		$labels = array(
			'name'                     => __( 'Videos', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'singular_name'            => __( 'Video', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'add_new'                  => __( 'Add New Video', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'add_new_item'             => __( 'Add New Video', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'edit_item'                => __( 'Edit Video', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'new_item'                 => __( 'New Video', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'view_item'                => __( 'View Video', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'view_items'               => __( 'View Video', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'search_items'             => __( 'Search Videos', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'not_found'                => __( 'No Videos found', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'not_found_in_trash'       => __( 'No Videos found in trash', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'all_items'                => __( 'All Videos', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'attributes'               => __( 'Video attributes', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'insert_into_item'         => __( 'Insert into Video', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'uploaded_to_this_item'    => __( 'Uploaded to Video', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'featured_image'           => __( 'Video image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'set_featured_image'       => __( 'Set Video image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'remove_featured_image'    => __( 'Remove Video image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'used_featured_image'      => __( 'Use as Video image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_published'           => __( 'Video published', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_published_privately' => __( 'Video published privately', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_reverted_to_draft'   => __( 'Video reverted to draft', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_scheduled'           => __( 'Video scheduled', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_updated'             => __( 'Video updated', Plugin::TEXT_DOMAIN ), // phpcs:ignore
		);

		$supports = array(
			'title',
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
			'menu_icon'           => 'dashicons-video-alt',
			'rewrite'             => array( 'slug' => 'videos' ),
			'supports'            => $supports,
		);

		register_post_type(
			self::POST_TYPE,
			$args,
		);

	}

	/**
	 * Add a meta box for the Videos custom post type
	 */
	public function meta_box() {

		add_meta_box(
			'video-meta-box',
			__( 'Video Information', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			array( $this, 'render_meta_box' ),
			self::POST_TYPE,
			'side',
			'default',
		);

	}

	/**
	 * Render the meta box in the post.
	 *
	 * @param array $post The post.
	 */
	public function render_meta_box( $post ) {

		$link = esc_attr( get_post_meta( $post->ID, 'orc-video-link', true ) );
		?>
		<?php wp_nonce_field( basename( __FILE__ ), self::POST_NONCE ); ?>
		<label for="orc-video-link"><?php _e( 'YouTube Link', Plugin::TEXT_DOMAIN ); ?></label> <?php // phpcs:ignore ?>
		<input class="widefat" type="text" name="orc-video-link" id="orc-video-link" value="<?php echo $link; ?>" /> <?php // phpcs:ignore ?>
		<?php

	}

	/**
	 * Save the meta box if:
	 *   - Not autosaving
	 *   - Not a revision save
	 *   - Has a valid NONCE
	 *   - The user can edit the post
	 *   - We are on the correct post
	 *
	 * @param int   $post_id The ID for the post.
	 * @param array $post    The post.
	 */
	public function save_meta( $post_id, $post ) {

		$is_autosave    = ( false === wp_is_post_autosave( $post_id ) ) ? false : true;
		$is_revision    = ( false === wp_is_post_revision( $post_id ) ) ? false : true;
		$is_valid_nonce = ( isset( $_POST[ self::POST_NONCE ] ) && wp_verify_nonce( $_POST[ self::POST_NONCE ], basename( __FILE__ ) ) ) ? true : false; // phpcs:ignore
		$can_edit       = current_user_can( 'edit_post', $post_id );
		$correct_post   = ( self::POST_TYPE === $post->post_type ) ? true : false;

		// Exit function if anything fails.
		if ( $is_autosave || $is_revision || ! $is_valid_nonce || ! $can_edit || ! $correct_post ) {
			return $post_id;
		}

		// Handle the link.
		$meta_id  = 'orc-video-link';
		$link     = get_post_meta( $post_id, $meta_id, true );
		$new_link = ( isset( $_POST[ $meta_id ] ) ? sanitize_text_field( wp_unslash( $_POST[ $meta_id ] ) ) : '' );
		if ( '' !== $new_link && '' === $link ) {
			add_post_meta( $post_id, $meta_id, $new_link, true );
		} elseif ( '' !== $new_link && $new_link !== $link ) {
			update_post_meta( $post_id, $meta_id, $new_link );
		} elseif ( '' === $new_link && '' !== $link ) {
			delete_post_meta( $post_id, $meta_id, $link );
		}

	}

	/**
	 * Display the table header for all the posts including all the added
	 * ones for the custom post type.
	 *
	 * @param array $columns The array of column headers.
	 * @return array The new array of column headers.
	 */
	public function table_head( $columns ) {

		$newcols = array();

		// Want the selection box and title (name for our custom post type) first.
		$newcols['cb'] = $columns['cb'];
		unset( $columns['cb'] );
		$newcols['title'] = 'Name';
		unset( $columns['title'] );

		// Our custom meta data columns.
		$newcols['orc-video-link'] = __( 'Link', Plugin::TEXT_DOMAIN ); // phpcs:ignore

		// Want date last.
		unset( $columns['date'] );

		// Add all other selected columns.
		foreach ( $columns as $col => $title ) {
			$newcols[ $col ] = $title;
		}

		// Add the date back.
		$newcols['date'] = 'Date';

		return $newcols;
	}

	/**
	 * Display the custom post data in the correct column
	 *
	 * @param string $column_name Name of the column.
	 * @param int    $post_id     The ID of this post.
	 */
	public function table_content( $column_name, $post_id ) {

		if ( 'orc-video-link' === $column_name ) {
			$link = esc_attr( get_post_meta( $post_id, 'orc-video-link', true ) );
			echo $link; // phpcs:ignore
		}
	}

}
