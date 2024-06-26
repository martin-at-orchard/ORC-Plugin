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
	 * Possible attributes:
	 *   numPosts           (isset - number of posts else all)
	 *   wantPosition       (isset - false else true)
	 *   wantQualifications (isset - false else true)
	 *   wantLink           (isset - false else true)
	 *   wantExcerpt        (isset - false else true)
	 *   wantButton         (isset - false else true)
	 *   buttonText         (isset - text string else null string)
	 *
	 * @param array $attributes Attributes from the block editor.
	 */
	public function render( $attributes ) {

		$selected_department = ( isset( $attributes['selectedDepartment'] ) ) ? $attributes['selectedDepartment'] : 0;
		$num_posts           = ( isset( $attributes['numPosts'] ) && $attributes['numPosts'] > 0 ) ? $attributes['numPosts'] : -1;
		$want_position       = ( isset( $attributes['wantPosition'] ) ) ? false : true;
		$want_qualifications = ( isset( $attributes['wantQualifications'] ) ) ? false : true;
		$want_link           = ( isset( $attributes['wantLink'] ) ) ? false : true;
		$want_excerpt        = ( isset( $attributes['wantExcerpt'] ) ) ? false : true;
		$want_button         = ( isset( $attributes['wantButton'] ) ) ? false : true;
		$button_text         = ( isset( $attributes['buttonText'] ) ) ? $attributes['buttonText'] : 'View More';

		$args = array(
			'post_type'      => array( self::POST_TYPE ),
			'post_status'    => array( 'publish' ),
			'orderby'        => 'date',
			'order'          => 'ASC',
			'posts_per_page' => $num_posts,
		);

		if ( $selected_department > 0 ) {
			$args['tax_query'] = array( // phpcs:ignore
				array(
					'taxonomy' => 'orc-departments',
					'field'    => 'term_id',
					'terms'    => $selected_department,
				),
			);
		} elseif ( '-1' === $selected_department ) {
			$args['meta_key']   = 'orc-staff-homepage'; // phpcs:ignore
			$args['meta_value'] = 1; // phpcs:ignore
		}

		$posts = get_posts( $args );

		$div = '<div class="wp-block-orc-staff';
		if ( isset( $attributes['align'] ) ) {
			$div .= ' ' . $attributes['align'] . '-align';
		}
		$div .= '">';

		\ob_start();
		echo $div;    // phpcs:ignore
		foreach ( $posts as $post ) {
			$department_terms = get_the_terms( $post->ID, 'orc-departments' );
			$staff_classes    = '';
			if ( count( $department_terms ) > 0 ) {
				foreach ( $department_terms as $term ) {
					$staff_classes .= esc_attr( $term->slug ) . ' ';
				}
			}
			$staff_classes = trim( $staff_classes );
			$staff_classes = 'class="staff ' . $staff_classes . '"';
			$staff_id      = 'id="staff-' . esc_attr( $post->ID ) . '"';
			echo '<div ' . $staff_classes . ' ' . $staff_id . '>'; // phpcs:ignore
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
			if ( $want_position ) {
				$position = esc_attr( get_post_meta( $post->ID, 'orc-staff-position', true ) );
				if ( '' !== $position ) {
					echo '<div class="position" aria-label="Staff member position">' . esc_attr( $position ) . '</div>';
				}
			}
			if ( $want_qualifications ) {
				$qualifications = esc_attr( get_post_meta( $post->ID, 'orc-staff-qualifications', true ) );
				if ( '' !== $qualifications ) {
					echo '<div class="qualifications" aria-label="Staff member qualifications">' . esc_attr( $qualifications ) . '</div>';
				}
			}
			if ( $want_button && $want_link ) {
				echo '<input type="button" value="' . esc_attr( $button_text ) . '" aria-label="Learn more about staff member ' . esc_attr( $post->post_title ) . '" />';
			}
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
			'name'                     => __( 'Staff Members', 'orc-plugin' ),
			'singular_name'            => __( 'Staff Member', 'orc-plugin' ),
			'add_new'                  => __( 'Add New Staff Member', 'orc-plugin' ),
			'add_new_item'             => __( 'Add New Staff Member', 'orc-plugin' ),
			'edit_item'                => __( 'Edit Staff Member', 'orc-plugin' ),
			'new_item'                 => __( 'New Staff Member', 'orc-plugin' ),
			'view_item'                => __( 'View Staff Member', 'orc-plugin' ),
			'view_items'               => __( 'View Staff Members', 'orc-plugin' ),
			'search_items'             => __( 'Search Staff Members', 'orc-plugin' ),
			'not_found'                => __( 'No Staff Members found', 'orc-plugin' ),
			'not_found_in_trash'       => __( 'No Staff Members found in trash', 'orc-plugin' ),
			'all_items'                => __( 'All Staff Members', 'orc-plugin' ),
			'attributes'               => __( 'Staff Member attributes', 'orc-plugin' ),
			'insert_into_item'         => __( 'Insert into Staff Member', 'orc-plugin' ),
			'uploaded_to_this_item'    => __( 'Uploaded to Staff Member', 'orc-plugin' ),
			'featured_image'           => __( 'Staff Member image', 'orc-plugin' ),
			'set_featured_image'       => __( 'Set Staff Member image', 'orc-plugin' ),
			'remove_featured_image'    => __( 'Remove Staff Member image', 'orc-plugin' ),
			'used_featured_image'      => __( 'Use as Staff Member image', 'orc-plugin' ),
			'item_published'           => __( 'Staff Member published', 'orc-plugin' ),
			'item_published_privately' => __( 'Staff Member published privately', 'orc-plugin' ),
			'item_reverted_to_draft'   => __( 'Staff Member reverted to draft', 'orc-plugin' ),
			'item_scheduled'           => __( 'Staff Member scheduled', 'orc-plugin' ),
			'item_updated'             => __( 'Staff Member updated', 'orc-plugin' ),
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
			__( 'Staff Information', 'orc-plugin' ),
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
		<label for="orc-staff-position"><?php _e( 'Job/Position', 'orc-plugin' ); // phpcs:ignore ?></label>
		<input class="widefat" type="text" name="orc-staff-position" id="orc-staff-position" value="<?php echo $position;// phpcs:ignore ?>" />
		<label for="orc-staff-qualifications"><?php _e( 'Qualifications', 'orc-plugin' ); // phpcs:ignore ?></label>
		<input class="widefat" type="text" name="orc-staff-qualifications" id="orc-staff-qualifications" value="<?php echo $qualifications;// phpcs:ignore ?>" />
		<input type="checkbox" name="orc-staff-homepage" id="orc-staff-homepage" value="1" <?php echo $checked;// phpcs:ignore ?>/>
		<label for="orc-staff-homepage"><?php _e( 'On Home Page?', 'orc-plugin' ); // phpcs:ignore ?></label>
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
		$newcols['orc-staff-position']       = __( 'Position', 'orc-plugin' );
		$newcols['orc-staff-qualifications'] = __( 'Qualifications', 'orc-plugin' );
		$newcols['orc-staff-homepage']       = __( 'On Homepage', 'orc-plugin' );

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
			echo esc_attr( get_post_meta( $post_id, 'orc-staff-position', true ) );
		} elseif ( 'orc-staff-qualifications' === $column_name ) {
			echo esc_attr( get_post_meta( $post_id, 'orc-staff-qualifications', true ) );
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
			'name'              => __( 'Staff Departments', 'orc-plugin' ),
			'singular_name'     => __( 'Staff Department', 'orc-plugin' ),
			'search_items'      => __( 'Search Staff Departments', 'orc-plugin' ),
			'all_items'         => __( 'All Staff Departments', 'orc-plugin' ),
			'parent_item'       => __( 'Parent Staff Department', 'orc-plugin' ),
			'parent_item_colon' => __( 'Parent Staff Department:', 'orc-plugin' ),
			'edit_item'         => __( 'Edit Staff Department', 'orc-plugin' ),
			'update_item'       => __( 'Update Staff Department', 'orc-plugin' ),
			'add_new_item'      => __( 'Add Staff Department', 'orc-plugin' ),
			'new_item_name'     => __( 'New Staff Department', 'orc-plugin' ),
			'menu_name'         => __( 'Staff Departments', 'orc-plugin' ),
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
