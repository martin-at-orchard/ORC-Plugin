<?php // phpcs:ignore
/**
 * Orchard Recovery Staff
 *
 * Register the staff block.
 * Create staff custom post type.
 *
 * @since   0.1.2
 * @package ORC
 */

namespace ORC;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class to create a block and custom post type for
 * the Orchard Recovery Center staff.
 */
class Staff {

	const POST_TYPE  = 'orc-staff';
	const POST_NONCE = 'orc-staff-post-class-nonce';

	/**
	 * Constructor for the staff class
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'register' ) );
		add_action( 'init', array( $this, 'create_posttype' ) );
		add_action( 'add_meta_boxes_' . self::POST_TYPE, array( $this, 'meta_box' ) );
		add_action( 'save_post', array( $this, 'save_meta' ), 10, 2 );
		add_filter( 'manage_edit-' . self::POST_TYPE . '_columns', array( $this, 'table_head' ) );
		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array( $this, 'table_content' ), 10, 2 );
		add_action( 'init', array( $this, 'staff_taxonomies' ) );

	}

	/**
	 * Register the block on the server-side to ensure that the block
	 * scripts and styles are enqueued when the editor loads.
	 * Provides a render function for the front end.
	 * In addition retrieves the list of staff departments and passes them to the JavaScritp code.
	 */
	public function register() {

		global $wpdb;

		register_block_type(
			Plugin::NAME . '/staff',
			array(
				'style'           => Plugin::FRONTEND_STYLE_HANDLE,
				'editor_script'   => Plugin::BACKEND_SCRIPT_HANDLE,
				'editor_style'    => Plugin::BACKEND_STYLE_HANDLE,
				'render_callback' => array( $this, 'render' ),
			)
		);

		// Get the list of staff departments.
		$results = $wpdb->get_results( "SELECT tt.term_id AS `value`, t.name AS `label`, t.slug AS `key` FROM {$wpdb->prefix}terms t INNER JOIN {$wpdb->prefix}term_taxonomy tt ON t.term_id=tt.term_id WHERE tt.taxonomy='orc-departments' ORDER BY t.name" ); // phpcs:ignore

		// Pass the staff departments to JavaScript.
		wp_localize_script( Plugin::BACKEND_SCRIPT_HANDLE, 'departments', $results );

	}

	/**
	 * Render function for the staff custom blocks.
	 *
	 * @param array $attributes Attributes from the block editor.
	 */
	public function render( $attributes ) {

		$div = '<div class="wp-block-orc-staff';
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

		if ( $attributes['selectedDepartment'] > 0 ) {
			$args['tax_query'] = array( // phpcs:ignore
				array(
					'taxonomy' => 'orc-departments',
					'field'    => 'term_id',
					'terms'    => $attributes['selectedDepartment'],
				),
			);
		} elseif ( '-1' === $attributes['selectedDepartment'] ) {
			$args['meta_key']   = 'orc-staff-homepage'; // phpcs:ignore
			$args['meta_value'] = 1; // phpcs:ignore
		}

		$posts = get_posts( $args );

		\ob_start();
		echo $div;      // phpcs:ignore
		foreach ( $posts as $post ) {
			$department_terms = get_the_terms( $post->ID, 'orc-departments' );
			$staff_classes    = '';
			if ( count( $department_terms ) > 0 ) {
				foreach ( $department_terms as $term ) {
					$staff_classes .= $term->slug . ' ';
				}
			}
			$staff_classes  = trim( $staff_classes ); 
			$staff_classes  = 'class="staff ' . $staff_classes . '"';
			$staff_id       = 'id="staff-' . $post->ID . '"';
			$position       = esc_attr( get_post_meta( $post->ID, 'orc-staff-position', true ) );
			$qualifications = esc_attr( get_post_meta( $post->ID, 'orc-staff-qualifications', true ) );
			echo '<div ' . $staff_classes . ' ' . $staff_id . '>';  // phpcs:ignore
			echo '<span data-link="' . esc_url( get_post_permalink( $post->ID ) ) . '"></span>';
			echo '<h3>' . esc_attr( $post->post_title ) . '</h3>';
			echo get_the_post_thumbnail( $post->ID );
			echo '<div class="position">' . esc_attr( $position ) . '</div>';
			echo '<div class="qualifications">' . esc_attr( $qualifications ) . '</div>';
			echo '<input type="button" value="Read More" />';
			echo '</div> <!-- /.orc-staff -->';
		}
		echo '</div> <!-- /.wp-block-orc-staff -->';
		return \ob_get_clean();

	}

	/**
	 * Create Staff Members Custom Post Type
	 */
	public function create_posttype() {

		$labels = array(
			'name'                     => __( 'Staff Members', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'singular_name'            => __( 'Staff Member', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'add_new'                  => __( 'Add New Staff Member', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'add_new_item'             => __( 'Add New Staff Member', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'edit_item'                => __( 'Edit Staff Member', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'new_item'                 => __( 'New Staff Member', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'view_item'                => __( 'View Staff Member', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'view_items'               => __( 'View Staff Members', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'search_items'             => __( 'Search Staff Members', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'not_found'                => __( 'No Staff Members found', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'not_found_in_trash'       => __( 'No Staff Members found in trash', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'all_items'                => __( 'All Staff Members', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'attributes'               => __( 'Staff Member attributes', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'insert_into_item'         => __( 'Insert into Staff Member', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'uploaded_to_this_item'    => __( 'Uploaded to Staff Member', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'featured_image'           => __( 'Staff Member image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'set_featured_image'       => __( 'Set Staff Member image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'remove_featured_image'    => __( 'Remove Staff Member image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'used_featured_image'      => __( 'Use as Staff Member image', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_published'           => __( 'Staff Member published', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_published_privately' => __( 'Staff Member published privately', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_reverted_to_draft'   => __( 'Staff Member reverted to draft', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_scheduled'           => __( 'Staff Member scheduled', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'item_updated'             => __( 'Staff Member updated', Plugin::TEXT_DOMAIN ), // phpcs:ignore
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
			'menu_icon'           => 'dashicons-groups',
			'rewrite'             => array( 'slug' => 'staff_members' ),
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
			'staff-meta-box',
			__( 'Staff Information', Plugin::TEXT_DOMAIN ), // phpcs:ignore
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

		$position       = esc_attr( get_post_meta( $post->ID, 'orc-staff-position', true ) );
		$qualifications = esc_attr( get_post_meta( $post->ID, 'orc-staff-qualifications', true ) );
		$homepage       = esc_attr( get_post_meta( $post->ID, 'orc-staff-homepage', true ) );
		$checked        = ( '' === $homepage ) ? '' : 'checked';
		?>
		<?php wp_nonce_field( basename( __FILE__ ), self::POST_NONCE ); ?>
		<label for="orc-staff-position"><?php esc_attr_e( 'Job/Position', Plugin::TEXT_DOMAIN ); ?></label> <?php // phpcs:ignore ?>
		<input class="widefat" type="text" name="orc-staff-position" id="orc-staff-position" value="<?php echo $position; ?>" /> <?php // phpcs:ignore ?>
		<label for="orc-staff-qualifications"><?php esc_attr_e( 'Qualifications', Plugin::TEXT_DOMAIN ); ?></label> <?php // phpcs:ignore ?>
		<input class="widefat" type="text" name="orc-staff-qualifications" id="orc-staff-qualifications" value="<?php echo $qualifications; ?>" /> <?php // phpcs:ignore ?>
		<input type="checkbox" name="orc-staff-homepage" id="orc-staff-homepage" value="1" <?php echo $checked; ?>/> <?php // phpcs:ignore ?>
		<label for="orc-staff-homepage"><?php esc_attr_e( 'On Home Page?', Plugin::TEXT_DOMAIN ); ?></label> <?php // phpcs:ignore ?>
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
		$meta_id      = 'orc-staff-position';
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
		$meta_id            = 'orc-staff-qualifications';
		$qualifications     = get_post_meta( $post_id, $meta_id, true );
		$new_qualifications = ( isset( $_POST[ $meta_id ] ) ? sanitize_text_field( wp_unslash( $_POST[ $meta_id ] ) ) : '' );
		if ( '' !== $new_qualifications && '' === $qualifications ) {
			add_post_meta( $post_id, $meta_id, $new_qualifications, true );
		} elseif ( '' !== $new_qualifications && $new_qualifications !== $qualifications ) {
			update_post_meta( $post_id, $meta_id, $new_qualifications );
		} elseif ( '' === $new_qualifications && '' !== $qualifications ) {
			delete_post_meta( $post_id, $meta_id, $qualifications );
		}

		// Handle the homepage.
		$meta_id      = 'orc-staff-homepage';
		$homepage     = get_post_meta( $post_id, $meta_id, true );
		$new_homepage = ( isset( $_POST[ $meta_id ] ) ? sanitize_text_field( wp_unslash( $_POST[ $meta_id ] ) ) : '' );
		if ( '' !== $new_homepage && '' === $homepage ) {
			add_post_meta( $post_id, $meta_id, $new_homepage, true );
		} elseif ( '' === $new_homepage && '' !== $homepage ) {
			delete_post_meta( $post_id, $meta_id, $homepage );
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
		$newcols['orc-staff-position']       = __( 'Position', Plugin::TEXT_DOMAIN ); // phpcs:ignore
		$newcols['orc-staff-qualifications'] = __( 'Qualifications', Plugin::TEXT_DOMAIN ); // phpcs:ignore
		$newcols['orc-staff-homepage']       = __( 'On Homepage', Plugin::TEXT_DOMAIN ); // phpcs:ignore

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

		if ( 'orc-staff-position' === $column_name ) {
			$position = esc_attr( get_post_meta( $post_id, 'orc-staff-position', true ) );
			echo $position; // phpcs:ignore
		} elseif ( 'orc-staff-qualifications' === $column_name ) {
			$qualifications = esc_attr( get_post_meta( $post_id, 'orc-staff-qualifications', true ) );
			echo $qualifications; // phpcs:ignore
		} elseif ( 'orc-staff-homepage' === $column_name ) {
			$homepage = esc_attr( get_post_meta( $post_id, 'orc-staff-homepage', true ) );
			$checked  = ( '' === $homepage ) ? '' : 'TRUE';
			echo $checked; // phpcs:ignore
		}
	}

	/**
	 * Taxonomies for the staff
	 */
	public function staff_taxonomies() {

		$labels = array(
			'name'              => __( 'Staff Departments', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'singular_name'     => __( 'Staff Department', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'search_items'      => __( 'Search Staff Departments', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'all_items'         => __( 'All Staff Departments', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'parent_item'       => __( 'Parent Staff Department', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'parent_item_colon' => __( 'Parent Staff Department:', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'edit_item'         => __( 'Edit Staff Department', Plugin::TEXT_DOMAIN ),  // phpcs:ignore
			'update_item'       => __( 'Update Staff Department', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'add_new_item'      => __( 'Add Staff Department', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'new_item_name'     => __( 'New Staff Department', Plugin::TEXT_DOMAIN ), // phpcs:ignore
			'menu_name'         => __( 'Staff Departments', Plugin::TEXT_DOMAIN ), // phpcs:ignore
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => false,
			'show_in_nav_menus'  => false,
			'show_in_rest'       => true,
			'show_admin_column'  => true,
			'hierarchical'       => true,
		);

		register_taxonomy( 'orc-departments', self::POST_TYPE, $args );

	}
}
