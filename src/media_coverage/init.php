<?php // phpcs:ignore
/**
 * Orchard Recovery Media Coverage
 *
 * Register the media coverage block.
 * Create staff custom post type.
 *
 * @since   0.1.7
 * @package ORC
 */

namespace ORC;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class to create a block and custom post type for
 * the Orchard Recovery Center media coverage.
 */
class Media_Coverage {

	const POST_TYPE  = 'orc-media-coverage';
	const POST_NONCE = 'orc-media-coverage-post-class-nonce';

	/**
	 * Constructor for the media coverage class
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
			Plugin::NAME . '/media-coverage',
			array(
				'style'           => Plugin::FRONTEND_STYLE_HANDLE,
				'editor_script'   => Plugin::BACKEND_SCRIPT_HANDLE,
				'editor_style'    => Plugin::BACKEND_STYLE_HANDLE,
				'render_callback' => array( $this, 'render' ),
			)
		);

	}

	/**
	 * Render function for the media coverage custom blocks.
	 *
	 * Possible attributes:
	 *   numPosts    (isset - number of posts else all)
	 *   wantExcerpt (isset - false else true)
	 *   wantLink    (isset - false else true)
	 *   wantButton  (isset - false else true)
	 *   buttonText  (isset - text string else null string)
	 *
	 * @param array $attributes Attributes from the block editor.
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

		$div = '<div class="wp-block-orc-media-coverage';
		if ( isset( $attributes['align'] ) ) {
			$div .= ' ' . $attributes['align'] . '-align';
		}
		$div .= '">';

		\ob_start();
		echo $div;      // phpcs:ignore
		foreach ( $posts as $post ) {
			$featured = esc_attr( get_post_meta( $post->ID, 'orc-media-coverage-featured', true ) );
			$url      = esc_attr( get_post_meta( $post->ID, 'orc-media-coverage-url', true ) );
			echo '<div class="media-coverage" id="post-' . $post->ID . '">';     // phpcs:ignore
			if ( $want_link ) {
				echo '<span data-link="' . esc_url( get_post_permalink( $post->ID ) ) . '"></span>';
			}
			echo '<h3>' . esc_attr( $post->post_title ) . '</h3>';
			if ( has_post_thumbnail( $post->ID ) ) {
				echo get_the_post_thumbnail( $post->ID );
			}
			if ( $want_excerpt ) {
				echo '<div class="excerpt">' . esc_attr( $post->post_excerpt ) . '</div>';
			}
			echo '<div class="featured">' . esc_attr( $featured ) . '</div>';
			echo '<div class="url">' . esc_attr( $url ) . '</div>';
			if ( $want_button && $want_link ) {
				echo '<input type="button" value="View More" />';
			}
			echo '</div> <!-- /.media-coverage -->';
		}
		echo '</div> <!-- /.wp-block-orc-media-coverage -->';
		return \ob_get_clean();

	}

	/**
	 * Create Media Coverage Custom Post Type
	 */
	public function create_posttype() {

		$labels = array(
			'name'                     => __( 'Media Coverage', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'singular_name'            => __( 'Media Coverage', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'add_new'                  => __( 'Add New Media Coverage', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'add_new_item'             => __( 'Add New Media Coverage', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'edit_item'                => __( 'Edit Media Coverage', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'new_item'                 => __( 'New Media Coverage', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'view_item'                => __( 'View Media Coverage', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'view_items'               => __( 'View Media Coverage', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'search_items'             => __( 'Search Media Coverage', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'not_found'                => __( 'No Media Coverage found', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'not_found_in_trash'       => __( 'No Media Coverage found in trash', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'all_items'                => __( 'All Media Coverage', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'attributes'               => __( 'Media Coverage attributes', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'insert_into_item'         => __( 'Insert into Media Coverage', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'uploaded_to_this_item'    => __( 'Uploaded to Media Coverage', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'featured_image'           => __( 'Media Coverage image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'set_featured_image'       => __( 'Set Media Coverage image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'remove_featured_image'    => __( 'Remove Media Coverage image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'used_featured_image'      => __( 'Use as Media Coverage image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_published'           => __( 'Media Coverage published', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_published_privately' => __( 'Media Coverage published privately', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_reverted_to_draft'   => __( 'Media Coverage reverted to draft', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_scheduled'           => __( 'Media Coverage scheduled', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_updated'             => __( 'Media Coverage updated', Plugin::TEXT_DOMAIN ), // phpcs:ignore
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
			'menu_icon'           => 'dashicons-admin-media',
			'rewrite'             => array( 'slug' => 'media-coverage' ),
			'supports'            => $supports,
		);

		register_post_type(
			self::POST_TYPE,
			$args,
		);

	}

	/**
	 * Add a meta box for the Media Coverage custom post type
	 */
	public function meta_box() {

		add_meta_box(
			'media-coverage-meta-box',
			__( 'Media Coverage Information', Plugin::TEXT_DOMAIN ), // phpcs:ignore
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

		$featured = esc_attr( get_post_meta( $post->ID, 'orc-media-coverage-featured', true ) );
		$url      = esc_url( get_post_meta( $post->ID, 'orc-media-coverage-url', true ) );
		?>
		<?php wp_nonce_field( basename( __FILE__ ), self::POST_NONCE ); ?>
		<label for="orc-media-coverage-featured"><?php _e( 'Featured On', Plugin::TEXT_DOMAIN ); ?></label> <?php // phpcs:ignore ?>
		<input class="widefat" type="text" name="orc-media-coverage-featured" id="orc-media-coverage-featured" value="<?php echo $featured; ?>" /> <?php // phpcs:ignore ?>
		<label for="orc-media-coverage-url"><?php _e( 'Original URL', Plugin::TEXT_DOMAIN ); ?></label> <?php // phpcs:ignore ?>
		<input class="widefat" type="text" name="orc-media-coverage-url" id="orc-media-coverage-url" value="<?php echo $url; ?>" /> <?php // phpcs:ignore ?>
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

		// Handle the position.
		$meta_id      = 'orc-media-coverage-position';
		$position     = get_post_meta( $post_id, $meta_id, true );
		$new_position = ( isset( $_POST[ $meta_id ] ) ? sanitize_text_field( wp_unslash( $_POST[ $meta_id ] ) ) : '' );
		if ( '' !== $new_position && '' === $position ) {
			add_post_meta( $post_id, $meta_id, $new_position, true );
		} elseif ( '' !== $new_position && $new_position !== $position ) {
			update_post_meta( $post_id, $meta_id, $new_position );
		} elseif ( '' === $new_position && '' !== $position ) {
			delete_post_meta( $post_id, $meta_id, $position );
		}

		// Handle the qualifications.
		$meta_id            = 'orc-media-coverage-qualifications';
		$qualifications     = get_post_meta( $post_id, $meta_id, true );
		$new_qualifications = ( isset( $_POST[ $meta_id ] ) ? sanitize_text_field( wp_unslash( $_POST[ $meta_id ] ) ) : '' );
		if ( '' !== $new_qualifications && '' === $qualifications ) {
			add_post_meta( $post_id, $meta_id, $new_qualifications, true );
		} elseif ( '' !== $new_qualifications && $new_qualifications !== $qualifications ) {
			update_post_meta( $post_id, $meta_id, $new_qualifications );
		} elseif ( '' === $new_qualifications && '' !== $qualifications ) {
			delete_post_meta( $post_id, $meta_id, $qualifications );
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
		$newcols['orc-media-coverage-position']       = __( 'Position', Plugin::TEXT_DOMAIN ); // phpcs:ignore
		$newcols['orc-media-coverage-qualifications'] = __( 'Qualifications', Plugin::TEXT_DOMAIN ); // phpcs:ignore

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

		if ( 'orc-media-coverage-position' === $column_name ) {
			$position = esc_attr( get_post_meta( $post_id, 'orc-media-coverage-position', true ) );
			echo $position; // phpcs:ignore
		} elseif ( 'orc-media-coverage-qualifications' === $column_name ) {
			$qualifications = esc_attr( get_post_meta( $post_id, 'orc-media-coverage-qualifications', true ) );
			echo $qualifications; // phpcs:ignore
		}
	}

}
