<?php
/**
 * Orchard Recovery Staff
 *
 * Register the staff block.
 * Create staff custom post type.
 *
 * @since   0.1.1
 * @package ORC
 */

namespace ORC;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Block {

	public function __construct() {

		add_action( 'init', array( $this, 'register' ) );

	}

	/**
	* Enqueue Gutenberg block assets for both frontend + backend.
	*
	* Assets enqueued:
	* 1. blocks.style.build.css - Frontend + Backend.
	* 2. blocks.build.js - Backend.
	* 3. blocks.editor.build.css - Backend.
	*
	* @uses {wp-blocks} for block type registration & related functions.
	* @uses {wp-element} for WP Element abstraction — structure of blocks.
	* @uses {wp-i18n} to internationalize the block's text.
	* @uses {wp-editor} for WP editor styles.

	* @since 0.1.1
	*/
	public function register() {

		/**
		 * Register Gutenberg block on server-side.
		 *
		 * Register the block on server-side to ensure that the block
		 * scripts and styles for both frontend and backend are
		 * enqueued when the editor loads.
		 *
		 * @link https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type#enqueuing-block-scripts
		 * @since 1.16.0
		 */
		register_block_type(
			Plugin::NAME . '/blocks',
			array(
				'style'         => Plugin::FRONTEND_STYLE_HANDLE,  // Enqueue blocks.style.build.css on both frontend & backend.
				'editor_script' => Plugin::BACKEND_SCRIPT_HANDLE,  // Enqueue blocks.build.js in the editor only.
				'editor_style'  => Plugin::BACKEND_STYLE_HANDLE,   // Enqueue blocks.editor.build.css in the editor only.
			)
		);

	}

}
