<?php // phpcs:ignore

namespace ORC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;     // Exit if accessed directly.
}

/**
 * Class to handle all the options for the website
 *
 * @since 0.1.13
 * @package ORC
 */
class Settings {

	/**
	 * Class constructor.
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
		add_filter( 'plugin_action_links_' . Plugin::$basename, array( $this, 'add_settings_link' ) );
		add_filter( 'custom_menu_order', array( $this, 'reorder_admin_menu' ) );

	}

	/**
	 * Add the menu to the WordPress menu.
	 *
	 * Can be displayed under a sub menu or as a primary menu.
	 */
	public function add_menu() {

		$this->options_page = add_menu_page(
			esc_html__( 'ORC Plugin Settings', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			esc_html__( 'ORC Plugin', Plugin::TEXT_DOMAIN ),            // phpcs:ignore
			'edit_posts',
			Plugin::NAME,
			array( $this, 'create_menu_page' ),
			'dashicons-hammer',
			500,
		);

		add_submenu_page(
			Plugin::NAME,
			esc_html__( 'ORC Plugin Settings', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			esc_html__( 'Settings', Plugin::TEXT_DOMAIN ),                // phpcs:ignore
			'edit_posts',
			Plugin::NAME,
			array( $this, 'create_menu_page' ),
		);

		add_submenu_page(
			Plugin::NAME,
			esc_html__( 'ORC Plugin Instructions', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			esc_html__( 'Instructions', Plugin::TEXT_DOMAIN ),                // phpcs:ignore
			'edit_posts',
			Plugin::NAME . '-info',
			array( $this, 'display_info' ),
		);

	}

	/**
	 * Re-order the custom menu
	 *
	 * @global array   $submenu    The submenu.
	 *
	 * @param  boolean $menu_order The order of the menu.
	 *
	 * @return boolean
	 */
	public function reorder_admin_menu( $menu_order ) {

		global $submenu;

		// Get our menu.
		$menu = $submenu[ Plugin::NAME ];

		// Get the options/instructions.
		$key1 = array_search(
			'Settings',
			array_column(
				$menu,
				0
			),
			true
		);
		$key2 = array_search(
			'Instructions',
			array_column(
				$menu,
				0
			),
			true
		);

		// Create a new submenu.
		$newmenu   = array();
		$newmenu[] = $menu[ $key1 ];
		$newmenu[] = $menu[ $key2 ];

		// Remove the settings/instructions.
		unset( $menu[ $key1 ] );
		unset( $menu[ $key2 ] );

		// Add in the other menu items.
		foreach ( $menu as $key => $menu ) {
			$newmenu[] = $menu;
		}

		// Update the main menu.
		$submenu[ Plugin::NAME ] = $newmenu;     // phpcs:ignore

		return $menu_order;

	}

	/**
	 * Create the menu page that will show all the options associated with the plugin.
	 */
	public function create_menu_page() {

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page', Plugin::TEXT_DOMAIN ) );     // phpcs:ignore
		}

		printf( '<div class="wrap"><h2>%s</h2><form action="options.php" method="post">', esc_html__( 'ORC Plugin Settings', Plugin::TEXT_DOMAIN ) );     // phpcs:ignore

		settings_fields( Plugin::SETTINGS );
		do_settings_sections( Plugin::NAME );
		submit_button();
		settings_errors();

		printf( '</form></div> <!-- /.wrap -->' );
		printf( '<div class="wrap"><p>%s %s</p></div> <!-- /.wrap -->', esc_html__( 'Plugin Version:', Plugin::TEXT_DOMAIN ), Plugin::VERSION );     // phpcs:ignore

	}

	/**
	 * Display the Plugin Options Instructions Page
	 */
	public function display_info() {

		require_once plugin_dir_path( __FILE__ ) . 'orc-instructions.php';

	}

	/**
	 * Register the settings page with settings sections and fields.
	 */
	public function register_settings_page() {

		$options = get_option( Plugin::SETTINGS_KEY );

		register_setting(
			Plugin::SETTINGS,
			Plugin::SETTINGS_KEY,
			array(
				'sanitize_callback' => array( $this, 'validate_data' ),
			)
		);

		add_settings_section(
			'contacts',
			'Contact Options',
			null,
			Plugin::NAME
		);

		$id = 'local';
		add_settings_field(
			$id,
			esc_html__( 'Local Phone Number:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $this, 'text_field' ),
			Plugin::NAME,
			'contacts',
			array(
				'classes' => '',
				'value'   => $options[ $id ],
				'name'    => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'      => $id,
			)
		);

		$id = 'tollfree';
		add_settings_field(
			$id,
			esc_html__( 'Toll Free Phone Number:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $this, 'text_field' ),
			Plugin::NAME,
			'contacts',
			array(
				'classes' => '',
				'value'   => $options[ $id ],
				'name'    => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'      => $id,
			)
		);

		$id = 'text';
		add_settings_field(
			$id,
			esc_html__( 'Text Number:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $this, 'text_field' ),
			Plugin::NAME,
			'contacts',
			array(
				'classes' => '',
				'value'   => $options[ $id ],
				'name'    => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'      => $id,
			)
		);

		$id = 'fax';
		add_settings_field(
			$id,
			esc_html__( 'Fax Number:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $this, 'text_field' ),
			Plugin::NAME,
			'contacts',
			array(
				'classes' => '',
				'value'   => $options[ $id ],
				'name'    => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'      => $id,
			)
		);

		add_settings_section(
			'videos',
			'Video Options',
			null,
			Plugin::NAME
		);

		$id = 'main';
		add_settings_field(
			$id,
			esc_html__( 'Main Video ID:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $this, 'text_field' ),
			Plugin::NAME,
			'videos',
			array(
				'classes' => '',
				'value'   => $options[ $id ],
				'name'    => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'      => $id,
			)
		);

		$id = 'xmas';
		add_settings_field(
			$id,
			esc_html__( 'Christmas Video ID:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $this, 'text_field' ),
			Plugin::NAME,
			'videos',
			array(
				'classes' => '',
				'value'   => $options[ $id ],
				'name'    => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'      => $id,
			)
		);

		add_settings_section(
			'analytics',
			'Analytics/Tracking Codes',
			null,
			Plugin::NAME
		);

		$id = 'google';
		add_settings_field(
			$id,
			esc_html__( 'Google Analytics Code:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $this, 'text_field' ),
			Plugin::NAME,
			'analytics',
			array(
				'classes' => '',
				'value'   => $options[ $id ],
				'name'    => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'      => $id,
			)
		);

		$id = 'facebook_app';
		add_settings_field(
			$id,
			esc_html__( 'Facebook App ID:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $this, 'text_field' ),
			Plugin::NAME,
			'analytics',
			array(
				'classes' => '',
				'value'   => $options[ $id ],
				'name'    => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'      => $id,
			)
		);

		$id = 'facebook_pixel';
		add_settings_field(
			$id,
			esc_html__( 'Facebook Pixel ID:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $this, 'text_field' ),
			Plugin::NAME,
			'analytics',
			array(
				'classes' => '',
				'value'   => $options[ $id ],
				'name'    => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'      => $id,
			)
		);

		$id = 'bing';
		add_settings_field(
			$id,
			esc_html__( 'Bing Tracking:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $this, 'text_field' ),
			Plugin::NAME,
			'analytics',
			array(
				'classes' => '',
				'value'   => $options[ $id ],
				'name'    => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'      => $id,
			)
		);

		$id = 'linkedin';
		add_settings_field(
			$id,
			esc_html__( 'LinkedIn Partner Code:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $this, 'text_field' ),
			Plugin::NAME,
			'analytics',
			array(
				'classes' => '',
				'value'   => $options[ $id ],
				'name'    => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'      => $id,
			)
		);

		$id = 'twitter';
		add_settings_field(
			$id,
			esc_html__( 'Twitter Universal Website Tag:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $this, 'text_field' ),
			Plugin::NAME,
			'analytics',
			array(
				'classes' => '',
				'value'   => $options[ $id ],
				'name'    => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'      => $id,
			)
		);

	}

	/**
	 * Called when the save changes button has been pressed to save the plugin options and used
	 * to validate all the input fields.
	 *
	 * @param array $input Array of input settings to validate.
	 *
	 * @return array Sanitized settings.
	 */
	public function validate_data( $input ) {

		$output = array();

		foreach ( $input as $id => $value ) {
			switch ( $id ) {
				case 'local':
				case 'tollfree':
				case 'text':
				case 'fax':
				case 'main':
				case 'xmas':
				case 'google':
				case 'facebook_app':
				case 'facebook_pixel':
				case 'bing':
				case 'linkedin':
				case 'twitter':
					$output[ $id ] = sanitize_text_field( $value );
					break;
				default:
			}
		}

		return apply_filters( 'orc_validate_data', $output, $input );

	}

	/**
	 * Display a text field in the form.
	 *
	 * @param array $args The arguments passed to the function.
	 */
	public function text_field( $args ) {

		$args = shortcode_atts(
			array(
				'classes'     => '',
				'name'        => '',
				'id'          => '',
				'value'       => '',
				'description' => '',
			),
			$args
		);

		printf(
			'<input type="text" class="%s" name="%s" id="%s" value="%s" /><span class="description"> %s</span>',
			esc_attr( $args['classes'] ),
			esc_attr( $args['name'] ),
			esc_attr( $args['id'] ),
			esc_attr( $args['value'] ),
			esc_attr( $args['description'] )
		);

	}

	/**
	 * Display a text area field in the form.
	 *
	 * @param array $args The arguments passed to the function.
	 */
	public function text_area_field( $args ) {

		$args = shortcode_atts(
			array(
				'classes'     => '',
				'name'        => '',
				'id'          => '',
				'value'       => '',
				'description' => '',
				'cols'        => '100',
				'rows'        => '4',
				'style'       => 'style="font-family:Courier New;"',
			),
			$args
		);

		$val = str_replace(
			'\n',
			'',
			$args['value']
		);
		printf(
			'<textarea class="%s" name="%s" id="%s" rows="%s" cols="%s" %s>%s</textarea><span class="description"> %s</span>',
			esc_attr( $args['classes'] ),
			esc_attr( $args['name'] ),
			esc_attr( $args['id'] ),
			esc_attr( $args['rows'] ),
			esc_attr( $args['cols'] ),
			esc_attr( $args['style'] ),
			esc_attr( $val ),
			esc_attr( $args['description'] )
		);

	}

	/**
	 * Display a number field in the form.
	 *
	 * @param array $args The arguments passed to the function.
	 */
	public function number_field( $args ) {

		$args = shortcode_atts(
			array(
				'classes'     => '',
				'name'        => '',
				'id'          => '',
				'value'       => '',
				'description' => '',
				'min'         => '0',
				'max'         => '200',
			),
			$args
		);

		printf(
			'<input type="number" min="%s" max="%s" class="%s" name="%s" id="%s" value="%s" /><span class="description"> %s</span>',
			esc_attr( $args['min'] ),
			esc_attr( $args['max'] ),
			esc_attr( $args['classes'] ),
			esc_attr( $args['name'] ),
			esc_attr( $args['id'] ),
			esc_attr( $args['value'] ),
			esc_attr( $args['description'] )
		);

	}

	/**
	 * Display a checkbox field in the form.
	 *
	 * @param array $args The arguments passed to the function.
	 */
	public function checkbox_field( $args ) {

		$args = shortcode_atts(
			array(
				'classes'     => '',
				'name'        => '',
				'id'          => '',
				'description' => '',
				'checked'     => '',
			),
			$args
		);

		printf(
			'<input type="checkbox" class="%s" name="%s" id="%s" value="1" %s /><span class="description"> %s</span>',
			esc_attr( $args['classes'] ),
			esc_attr( $args['name'] ),
			esc_attr( $args['id'] ),
			esc_attr( $args['checked'] ),
			esc_attr( $args['description'] )
		);

	}

	/**
	 * Enqueue plugin styles and scripts used in the plugin.
	 */
	public function enqueue() {

		$style = '/instructions/instructions.css';

		wp_enqueue_style(
			'orc-admin',
			plugins_url( "..{$style}", __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __DIR__ ) . $style ),
		);

	}

	/**
	 * Display a settings link on the plugins page.
	 *
	 * @param array $links All the links on the plugin page.
	 *
	 * @return array Returned array with new links merged.
	 */
	public function add_settings_link( $links ) {

		$settings = array(
			'settings'     => sprintf(
				'<a href="%s">%s</a>',
				admin_url( 'admin.php?page=' . Plugin::NAME ),
				'Settings'
			),
			'instructions' => sprintf(
				'<a href="%s">%s</a>',
				admin_url( 'admin.php?page=' . Plugin::NAME ) . '-info',
				'Instructions'
			),
		);

		return array_merge( $settings, $links );

	}

}
