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

	const POST_TYPE  = 'orc-programs';
	const POST_NONCE = 'orc-programs-post-class-nonce';

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

		$wide_class = '';
		if ( isset( $attributes['align'] ) ) {
			if ( 'full' === $attributes['align'] ) {
				$wide_class = 'wide-class';
			}
		}

		$args = array(
			'post_type'      => array( self::POST_TYPE ),
			'post_status'    => array( 'publish' ),
			'orderby'        => 'post_date',
			'order'          => 'ASC',
			'posts_per_page' => -1,
		);

		$posts = get_posts( $args );

		\ob_start();
		echo '<div class="wp-block-orc-programs ' . $wide_class . '">';      // phpcs:ignore
		foreach ( $posts as $post ) {
			$color = get_post_meta( $post->ID, 'orc-program-color', true );
			$style = 'style="border-color: ' . $color . ';"';
			echo '<div class="program" id="program-' . $post->ID . '" ' . $style . '>';     // phpcs:ignore
			echo '<span data-link="' . esc_url( get_post_permalink( $post->ID ) ) . '"></span>';
			echo '<h3>' . esc_attr( $post->post_title ) . '</h3>';
			echo get_the_post_thumbnail( $post->ID );
			echo '<p>' . esc_attr( $post->post_excerpt ) . '</p>';
			echo '<input type="button" value="View More" />';
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
			'show_in_menu'        => Plugin::NAME,
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

	/**
	 * Add a meta box for the Staff Members custom post type
	 */
	public function meta_box() {

		add_meta_box(
			'program-meta-box',
			__( 'Program Information', Plugin::TEXT_DOMAIN ), // phpcs:ignore
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

		$width = esc_attr( get_post_meta( $post->ID, 'orc-program-width', true ) );
		$color = esc_attr( get_post_meta( $post->ID, 'orc-program-color', true ) );
		?>
		<?php wp_nonce_field( basename( __FILE__ ), self::POST_NONCE ); ?>
		<label for="orc-program-width"><?php esc_attr_e( 'Width of border', Plugin::TEXT_DOMAIN ); ?></label> <?php // phpcs:ignore ?>
		<input class="widefat" type="number" name="orc-program-width" id="orc-program-width" min="0" max="100" step="1" value="<?php echo $width; ?>" /> <?php // phpcs:ignore ?>
		<label for="orc-program-color"><?php esc_attr_e( 'Color of border', Plugin::TEXT_DOMAIN ); ?></label> <?php // phpcs:ignore ?>
		<input class="widefat color-field" type="text" name="orc-program-color" id="orc-program-color" value="<?php echo $color; ?>" /> <?php // phpcs:ignore ?>
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

		// Handle the width.
		$meta_id   = 'orc-program-width';
		$width     = get_post_meta( $post_id, $meta_id, true );
		$new_width = ( isset( $_POST[ $meta_id ] ) ? sanitize_text_field( wp_unslash( $_POST[ $meta_id ] ) ) : '' );
		if ( '' !== $new_width && '' === $width ) {
			add_post_meta( $post_id, $meta_id, $new_width, true );
		} elseif ( '' !== $new_width && $new_width !== $width ) {
			update_post_meta( $post_id, $meta_id, $new_width );
		} elseif ( '' === $new_width && '' !== $width ) {
			delete_post_meta( $post_id, $meta_id, $width );
		}

		// Handle the color.
		$meta_id   = 'orc-program-color';
		$color     = get_post_meta( $post_id, $meta_id, true );
		$new_color = ( isset( $_POST[ $meta_id ] ) ? sanitize_text_field( wp_unslash( $_POST[ $meta_id ] ) ) : '' );
		if ( '' !== $new_color && '' === $color ) {
			add_post_meta( $post_id, $meta_id, $new_color, true );
		} elseif ( '' !== $new_color && $new_color !== $color ) {
			update_post_meta( $post_id, $meta_id, $new_color );
		} elseif ( '' === $new_color && '' !== $color ) {
			delete_post_meta( $post_id, $meta_id, $color );
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
		$newcols['orc-program-width'] = __( 'Border Width (px)', Plugin::TEXT_DOMAIN ); // phpcs:ignore
		$newcols['orc-program-color'] = __( 'Border Color', Plugin::TEXT_DOMAIN ); // phpcs:ignore

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

		if ( 'orc-program-width' === $column_name ) {
			echo esc_attr( get_post_meta( $post_id, 'orc-program-width', true ) );
		} elseif ( 'orc-program-color' === $column_name ) {
			$color = esc_attr( get_post_meta( $post_id, 'orc-program-color', true ) );
			echo '<div style="width:20px;height:20px;border-radius:10px;background-color:' . $color . '"></div>';   // phpcs:ignore
		}
	}

}
