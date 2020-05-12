<?php // phpcs:ignore

namespace ORC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;     // Exit if accessed directly.
}

/**
 * Class to handle all the staff at the Orchard.
 *
 * @since 0.1.5
 * @package ORC
 */
class Testimonials {

	const POST_TYPE  = 'orc-testimonials';
	const POST_NONCE = 'orc-testimonials-post-class-nonce';

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
			Plugin::NAME . '/testimonials',
			array(
				'style'           => Plugin::FRONTEND_STYLE_HANDLE,
				'editor_script'   => Plugin::BACKEND_SCRIPT_HANDLE,
				'editor_style'    => Plugin::BACKEND_STYLE_HANDLE,
				'render_callback' => array( $this, 'render' ),
			)
		);

	}

	/**
	 * Render function for the staff custom blocks.
	 *
	 * Possible attributes:
	 *   christmas   (isset - Christmas else Normal)
	 *   numPosts    (isset - number of posts else all)
	 *   wantExcerpt (isset - false else true)
	 *   wantLink    (isset - false else true)
	 *   wantButton  (isset - false else true)
	 *   buttonText  (isset - text string else null string)
	 *
	 * @param array $attributes Attributes from the block.
	 */
	public function render( $attributes ) {

		$christmas     = ( isset( $attributes['christmas'] ) && '1' === $attributes['christmas'] ) ? true : false;
		$front_page    = ( isset( $attributes['frontPage'] ) ) ? true : false;
		$num_posts     = ( isset( $attributes['numPosts'] ) && $attributes['numPosts'] > 0 ) ? $attributes['numPosts'] : -1;
		$want_link     = ( isset( $attributes['wantLink'] ) ) ? false : true;
		$want_excerpt  = ( isset( $attributes['wantExcerpt'] ) ) ? false : true;
		$want_location = ( isset( $attributes['wantLocation'] ) ) ? false : true;
		$want_button   = ( isset( $attributes['wantButton'] ) ) ? false : true;
		$button_text   = ( isset( $attributes['buttonText'] ) ) ? $attributes['buttonText'] : 'View More';

		$args = array(
			'post_type'      => array( self::POST_TYPE ),
			'post_status'    => array( 'publish' ),
			'orderby'        => 'rand',
			'order'          => 'ASC',
			'posts_per_page' => $num_posts,
		);

		if ( $christmas && $front_page ) {
			// Both meta queries.
			$args['meta_query'] = array( // phpcs:ignore
				'relation' => 'AND',
				array(
					'key'   => 'orc-testimonial-christmas',
					'value' => 1,
				),
				array(
					'key'   => 'orc-testimonial-front-page',
					'value' => 1,
				),
			);
		} else {
			if ( $christmas ) {
				// Only christmas meta query.
				$args['meta_key']   = 'orc-testimonial-christmas'; // phpcs:ignore
				$args['meta_value'] = 1; // phpcs:ignore

			} elseif ( $front_page ) {
				// Only front page meta query.
				$args['meta_key']   = 'orc-testimonial-front-page'; // phpcs:ignore
				$args['meta_value'] = 1; // phpcs:ignore
			}
		}

		$posts = get_posts( $args );
		$div   = '<div class="wp-block-orc-testimonials';
		if ( isset( $attributes['align'] ) ) {
			$div .= ' ' . $attributes['align'] . '-align';
		}
		$div .= '">';

		\ob_start();
		echo $div;      // phpcs:ignore
		foreach ( $posts as $post ) {
			echo '<div class="testimonial" id="post-' . esc_attr( $post->ID ) . '">';
			if ( $want_link ) {
				echo '<span data-link="' . esc_url( get_post_permalink( $post->ID ) ) . '"></span>';
			}
			if ( $want_excerpt ) {
				echo '<blockquote class="excerpt">' . esc_attr( $post->post_excerpt );
			} else {
				$post    = get_post( $post->ID );
				$content = $post->post_content;
				$content = apply_filters( 'the_content', $content );
				$content = str_replace( ']]>', ']]>', $content );
				echo '<blockquote class="post-content">' . $content;     // phpcs:ignore
			}
			echo '<cite>' . esc_attr( $post->post_title ) . '<br>';
			if ( $want_location ) {
				$location = get_post_meta( $post->ID, 'orc-testimonial-location', true );
				echo '<span class="location">' . esc_attr( $location ) . '</span>';
			}
			echo '</cite>';
			echo '</blockquote>';
			if ( $want_button && $want_link ) {
				echo '<input type="button" value="' . esc_attr( $button_text ) . '" aria-label="Read more about testimonial from ' . esc_attr( $post->post_title ) . '" />';
			}
			echo '</div> <!-- /.testimonial -->';
		}
		echo '</div> <!-- /.wp-block-orc-testimonials -->';
		return \ob_get_clean();

	}

	/**
	 * Create Testimonials Custom Post Type
	 */
	public function create_posttype() {

		$labels = array(
			'name'                     => __( 'Testimonials', 'orc-plugin' ),
			'singular_name'            => __( 'Testimonial', 'orc-plugin' ),
			'add_new'                  => __( 'Add New Testimonial', 'orc-plugin' ),
			'add_new_item'             => __( 'Add New Testimonial', 'orc-plugin' ),
			'edit_item'                => __( 'Edit Testimonial', 'orc-plugin' ),
			'new_item'                 => __( 'New Testimonial', 'orc-plugin' ),
			'view_item'                => __( 'View Testimonial', 'orc-plugin' ),
			'view_items'               => __( 'View Testimonials', 'orc-plugin' ),
			'search_items'             => __( 'Search Testimonials', 'orc-plugin' ),
			'not_found'                => __( 'No Testimonials found', 'orc-plugin' ),
			'not_found_in_trash'       => __( 'No Testimonials found in trash', 'orc-plugin' ),
			'all_items'                => __( 'All Testimonials', 'orc-plugin' ),
			'attributes'               => __( 'Testimonial attributes', 'orc-plugin' ),
			'insert_into_item'         => __( 'Insert into Testimonial', 'orc-plugin' ),
			'uploaded_to_this_item'    => __( 'Uploaded to Testimonial', 'orc-plugin' ),
			'featured_image'           => __( 'Testimonial image', 'orc-plugin' ),
			'set_featured_image'       => __( 'Set Testimonial image', 'orc-plugin' ),
			'remove_featured_image'    => __( 'Remove Testimonial image', 'orc-plugin' ),
			'used_featured_image'      => __( 'Use as Testimonial image', 'orc-plugin' ),
			'item_published'           => __( 'Testimonial published', 'orc-plugin' ),
			'item_published_privately' => __( 'Testimonial published privately', 'orc-plugin' ),
			'item_reverted_to_draft'   => __( 'Testimonial reverted to draft', 'orc-plugin' ),
			'item_scheduled'           => __( 'Testimonial scheduled', 'orc-plugin' ),
			'item_updated'             => __( 'Testimonial updated', 'orc-plugin' ),
		);

		$supports = array(
			'title',
			'editor',
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
			'menu_icon'           => 'dashicons-format-status',
			'rewrite'             => array( 'slug' => 'testimonials' ),
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
			'testimonial-meta-box',
			__( 'Testimonial Information', 'orc-plugin' ),
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

		$location   = esc_attr( get_post_meta( $post->ID, 'orc-testimonial-location', true ) );
		$christmas  = esc_attr( get_post_meta( $post->ID, 'orc-testimonial-christmas', true ) );
		$front_page = esc_attr( get_post_meta( $post->ID, 'orc-testimonial-front-page', true ) );
		?>
		<?php wp_nonce_field( basename( __FILE__ ), self::POST_NONCE ); ?>
		<label for="orc-testimonial-location"><?php _e( 'City/Province', 'orc-plugin' ); // phpcs:ignore ?></label>
		<input class="widefat" type="text" name="orc-testimonial-location" id="orc-testimonial-location" value="<?php echo $location; // phpcs:ignore ?>" />
		<?php $checked = ( '' === $christmas ) ? '' : 'checked'; ?>
		<input type="checkbox" name="orc-testimonial-christmas" id="orc-testimonial-christmas" value="1" <?php echo $checked; // phpcs:ignore ?>/>
		<label for="orc-testimonial-christmas"><?php _e( 'Christmas Testimonial?', 'orc-plugin' ); // phpcs:ignore ?>
		<br>
		<?php $checked = ( '' === $front_page ) ? '' : 'checked'; ?>
		<input type="checkbox" name="orc-testimonial-front-page" id="orc-testimonial-front-page" value="1" <?php echo $checked; // phpcs:ignore ?>/>
		<label for="orc-testimonial-front-page"><?php _e( 'Allow to be displayed on the Front Page?', 'orc-plugin' ); // phpcs:ignore ?></label>
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

		// Handle the location.
		$meta_id      = 'orc-testimonial-location';
		$location     = get_post_meta( $post_id, $meta_id, true );
		$new_location = ( isset( $_POST[ $meta_id ] ) ? sanitize_text_field( wp_unslash( $_POST[ $meta_id ] ) ) : '' );
		if ( '' !== $new_location && '' === $location ) {
			add_post_meta( $post_id, $meta_id, $new_location, true );
		} elseif ( '' !== $new_location && $new_location !== $location ) {
			update_post_meta( $post_id, $meta_id, $new_location );
		} elseif ( '' === $new_location && '' !== $location ) {
			delete_post_meta( $post_id, $meta_id, $location );
		}

		// Handle the christmas testimonial.
		$meta_id       = 'orc-testimonial-christmas';
		$christmas     = get_post_meta( $post_id, $meta_id, true );
		$new_christmas = ( isset( $_POST[ $meta_id ] ) ? sanitize_text_field( wp_unslash( $_POST[ $meta_id ] ) ) : '' );
		if ( '' !== $new_christmas && '' === $christmas ) {
			add_post_meta( $post_id, $meta_id, $new_christmas, true );
		} elseif ( '' === $new_christmas && '' !== $christmas ) {
			delete_post_meta( $post_id, $meta_id, $christmas );
		}

		// Handle the front page testimonial.
		$meta_id        = 'orc-testimonial-front-page';
		$front_page     = get_post_meta( $post_id, $meta_id, true );
		$new_front_page = ( isset( $_POST[ $meta_id ] ) ? sanitize_text_field( wp_unslash( $_POST[ $meta_id ] ) ) : '' );
		if ( '' !== $new_front_page && '' === $front_page ) {
			add_post_meta( $post_id, $meta_id, $new_front_page, true );
		} elseif ( '' === $new_front_page && '' !== $front_page ) {
			delete_post_meta( $post_id, $meta_id, $front_page );
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
		$newcols['orc-testimonial-location']   = __( 'City/Province', 'orc-plugin' ); // phpcs:ignore
		$newcols['orc-testimonial-christmas']  = __( 'Christmas Testimonial', 'orc-plugin' ); // phpcs:ignore
		$newcols['orc-testimonial-front-page'] = __( 'Display on Front Page', 'orc-plugin' ); // phpcs:ignore

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

		if ( 'orc-testimonial-location' === $column_name ) {
			$location = esc_attr( get_post_meta( $post_id, 'orc-testimonial-location', true ) );
			echo $location; // phpcs:ignore
		} elseif ( 'orc-testimonial-christmas' === $column_name ) {
			$christmas = esc_attr( get_post_meta( $post_id, 'orc-testimonial-christmas', true ) );
			$checked   = ( '' === $christmas ) ? '' : 'TRUE';
			echo $checked; // phpcs:ignore
		} elseif ( 'orc-testimonial-front-page' === $column_name ) {
			$front_page = esc_attr( get_post_meta( $post_id, 'orc-testimonial-front-page', true ) );
			$checked    = ( '' === $front_page ) ? '' : 'TRUE';
			echo $checked; // phpcs:ignore
		}
	}

}
