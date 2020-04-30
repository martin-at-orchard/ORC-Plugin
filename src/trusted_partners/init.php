<?php // phpcs:ignore

namespace ORC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;     // Exit if accessed directly.
}

/**
 * Class to handle all the trusted partners at the Orchard.
 *
 * @since 0.1.6
 * @package ORC
 */
class Trusted_Partners {

	const POST_TYPE  = 'orc-partners';
	const POST_NONCE = 'orc-partners-post-class-nonce';

	/**
	 * Constructor for the class
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
	 * Enqueue Gutenberg block assets for both frontend + backend.
	 */
	public function register() {

		register_block_type(
			Plugin::NAME . '/partners',
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
	 * Possible attributes:
	 *   numPosts    (isset - number of posts else all)
	 *   wantLink    (isset - false else true)
	 *
	 * @param array $attributes Attributes from the block.
	 */
	public function render( $attributes ) {

		$num_posts = ( isset( $attributes['numPosts'] ) && $attributes['numPosts'] > 0 ) ? $attributes['numPosts'] : -1;
		$width     = ( isset( $attributes['width'] ) && $attributes['width'] > 0 ) ? $attributes['width'] : 200;
		$want_link = ( isset( $attributes['wantLink'] ) ) ? false : true;

		$args = array(
			'post_type'      => array( self::POST_TYPE ),
			'post_status'    => array( 'publish' ),
			'orderby'        => 'title',
			'order'          => 'ASC',
			'posts_per_page' => $num_posts,
		);

		$posts = get_posts( $args );

		$div = '<div class="wp-block-orc-partners';
		if ( isset( $attributes['align'] ) ) {
			$div .= ' ' . $attributes['align'] . '-align';
		}
		$div .= '">';

		\ob_start();
		echo $div;      // phpcs:ignore
		foreach ( $posts as $post ) {
			if ( has_post_thumbnail( $post->ID ) ) {
				echo '<div class="partner" id="post-' . $post->ID . '">';     // phpcs:ignore
				$html = '';
				if ( $want_link ) {
					$link = get_post_meta( $post->ID, 'orc-partners-link', true );
					if ( '' !== $link ) {
						$title = 'Click to visit ' . $post->post_title;
						$html  = '<a href="' . esc_url( $link ) . '" title="' . esc_attr( $title ) . '" target="_blank">';
					}
				}
				$html .= get_the_post_thumbnail( $post->ID, array( $width, 0 ) );
				if ( $want_link ) {
					$html .= '</a>';
				}
				echo $html;     // phpcs:ignore
				echo '</div> <!-- /.partner -->';
			}
		}
		echo '</div> <!-- /.wp-block-orc-partners -->';
		return \ob_get_clean();

	}

	/**
	 * Create Program Custom Post Type
	 */
	public function create_posttype() {

		$labels = array(
			'name'                     => __( 'Trusted Partners', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'singular_name'            => __( 'Trusted Partner', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'add_new'                  => __( 'Add New Trusted Partner', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'add_new_item'             => __( 'Add New Trusted Partner', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'edit_item'                => __( 'Edit Trusted Partner', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'new_item'                 => __( 'New Trusted Partner', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'view_item'                => __( 'View Trusted Partner', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'view_items'               => __( 'View Trusted Partners', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'search_items'             => __( 'Search Trusted Partners', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'not_found'                => __( 'No Trusted Partners found', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'not_found_in_trash'       => __( 'No Trusted Partners found in trash', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'all_items'                => __( 'All Trusted Partners', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'attributes'               => __( 'Trusted Partner attributes', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'insert_into_item'         => __( 'Insert into Trusted Partner', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'uploaded_to_this_item'    => __( 'Uploaded to Trusted Partner', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'featured_image'           => __( 'Trusted Partner image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'set_featured_image'       => __( 'Set Trusted Partner image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'remove_featured_image'    => __( 'Remove Trusted Partner image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'used_featured_image'      => __( 'Use as Trusted Partner image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_published'           => __( 'Trusted Partner published', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_published_privately' => __( 'Trusted Partner published privately', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_reverted_to_draft'   => __( 'Trusted Partner reverted to draft', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_scheduled'           => __( 'Trusted Partner scheduled', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_updated'             => __( 'Trusted Partner updated', Plugin::TEXT_DOMAIN ), // phpcs:ignore
		);

		$supports = array(
			'title',
			'thumbnail',
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
			'menu_icon'           => 'dashicons-thumbs-up',
			'rewrite'             => array( 'slug' => 'trusted-partners' ),
			'supports'            => $supports,
		);

		register_post_type(
			self::POST_TYPE,
			$args,
		);

	}

	/**
	 * Add a meta box for the Trusted Partners custom post type
	 */
	public function meta_box() {

		add_meta_box(
			'partners-meta-box',
			__( 'Trusted Partners Information', Plugin::TEXT_DOMAIN ), // phpcs:ignore
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

		$link = esc_attr( get_post_meta( $post->ID, 'orc-partners-link', true ) );
		?>
		<?php wp_nonce_field( basename( __FILE__ ), self::POST_NONCE ); ?>
		<label for="orc-partners-link"><?php esc_attr_e( 'Link to Trusted Partner', Plugin::TEXT_DOMAIN ); ?></label> <?php // phpcs:ignore ?>
		<input class="widefat" type="text" name="orc-partners-link" id="orc-partners-link" value="<?php echo $link; ?>" /> <?php // phpcs:ignore ?>
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
		$meta_id  = 'orc-partners-link';
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
		$newcols['orc-partners-link']       = __( 'Link', Plugin::TEXT_DOMAIN ); // phpcs:ignore

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

		if ( 'orc-partners-link' === $column_name ) {
			$link = get_post_meta( $post_id, 'orc-partners-link', true );
			echo esc_attr( $link );
		}
	}

}
