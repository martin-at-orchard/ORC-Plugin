<?php // phpcs:ignore
/**
 * Plugin Name: Orchard Recovery Center Blocks/CPT Plugin
 * Plugin URI: https://github.com/martin-at-orchard/orc-plugin
 * Description: Gutenberg plugin created via create-guten-block that contains all the custom code for the Orchard Recovery Center Website
 * Author: Martin Wedepohl
 * Author URI: https://wedepohlengineering.com/
 * Version: 0.3.2
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package ORC
 */

namespace ORC;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Composer autoload.
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Class for the plugin.
 */
class Plugin {

	const DEVELOPMENT            = true;
	const NAME                   = 'orc';
	const VERSION                = '0.3.2';
	const BLOCKS_NAME            = 'orc-blocks';
	const TEXT_DOMAIN            = 'orc-plugin';
	const SETTINGS_KEY           = 'orc-options';
	const SETTINGS               = 'orc-settings';
	const FRONTEND_STYLE_HANDLE  = 'orc-plugin-style';
	const BACKEND_STYLE_HANDLE   = 'orc-plugin-editor-style';
	const BACKEND_SCRIPT_HANDLE  = 'orc-plugin-script';
	const FRONTEND_SCRIPT_HANDLE = 'orc-frontend-script';

	/**
	 * The basename for the plugin.
	 *
	 * @var string $basename The Basename for the plugin.
	 */
	public static $basename = null;

	/**
	 * Class constructor
	 */
	public function __construct() {

		self::$basename = plugin_basename( __FILE__ );

		add_action( 'init', array( $this, 'enqueue' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend' ) );
		add_filter( 'block_categories', array( $this, 'block_category' ), 10, 2 );

	}

	/**
	 * Enqueue all the styles and scripts used by the plugin.
	 */
	public function enqueue() {

		$frontend_style = '/' . self::NAME . '/dist/blocks.style.build.css';
		$backend_style  = '/' . self::NAME . '/dist/blocks.editor.build.css';
		$backend_js     = '/' . self::NAME . '/dist/blocks.build.js';

		// Enqueue Font Awesome.
		wp_enqueue_style(
			'load-fa',
			'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
			null,
			'4.7.0'
		);

		// Register style for the front end of the website.
		wp_register_style(
			self::FRONTEND_STYLE_HANDLE,
			plugins_url( $frontend_style, dirname( __FILE__ ) ),
			is_admin() ? array( 'wp-editor' ) : null,
			true === self::DEVELOPMENT ? ( filemtime( plugin_dir_path( __DIR__ ) . $frontend_style ) ) : self::VERSION
		);

		// Register block editor styles for the backend of the website.
		wp_register_style(
			self::BACKEND_STYLE_HANDLE,
			plugins_url( $backend_style, dirname( __FILE__ ) ),
			array( 'wp-edit-blocks' ),
			true === self::DEVELOPMENT ? ( filemtime( plugin_dir_path( __DIR__ ) . $backend_style ) ) : self::VERSION
		);

		// Register scripts for the backend of the website.
		wp_register_script(
			self::BACKEND_SCRIPT_HANDLE,
			plugins_url( $backend_js, dirname( __FILE__ ) ),
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
			true === self::DEVELOPMENT ? ( filemtime( plugin_dir_path( __DIR__ ) . $backend_js ) ) : self::VERSION,
			true
		);

		// WP Localized globals. Use dynamic PHP stuff in JavaScript via `cgbGlobal` object.
		wp_localize_script(
			self::BACKEND_SCRIPT_HANDLE,
			'cgbGlobal', // Array containing dynamic data for a JS Global.
			array(
				'pluginDirPath' => plugin_dir_path( __DIR__ ),
				'pluginDirUrl'  => plugin_dir_url( __DIR__ ),
				// Add more data here that you want to access from `cgbGlobal` object.
			)
		);

	}

	/**
	 * Enqueue all the styles and scripts used by the frontend of the website.
	 */
	public function enqueue_frontend() {

		$frontend_js = '/' . self::NAME . '/dist/frontend.min.js';

		// Register scripts for the frontend of the website.
		wp_enqueue_script(
			self::FRONTEND_SCRIPT_HANDLE,
			plugins_url( $frontend_js, dirname( __FILE__ ) ),
			array(),
			true === self::DEVELOPMENT ? ( filemtime( plugin_dir_path( __DIR__ ) . $frontend_js ) ) : self::VERSION,
			true
		);

	}

	/**
	 * Add ORC custom category to the block list.
	 *
	 * @param array $categories Array of current block categories.
	 * @param int   $post       ID of the post.
	 *
	 * @return array Array with our new category.
	 */
	public function block_category( $categories, $post ) {

		return array_merge(
			$categories,
			array(
				array(
					'slug'  => self::BLOCKS_NAME,
					'title' => __( 'ORC Blocks', self::TEXT_DOMAIN ), // phpcs:ignore
				),
			)
		);

	}

}

new Plugin();

/**
 * Plugin classes.
 */

new Settings( Plugin::NAME );
new ColorPicker();
new Debug();
$shorcodes = new Shortcodes();
$shorcodes->initialize();

/**
 * Block Initializer.
 */
new Admissions();
new Media_Coverage();
new Programs();
new Staff();
new Testimonials();
new Tours();
new Videos();
