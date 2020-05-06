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

		$options = shortcode_atts(
			array(
				'local'          => '',
				'tollfree'       => '',
				'text'           => '',
				'fax'            => '',
				'intake'         => '',
				'communications' => '',
				'hr'             => '',
				'alumni'         => '',
				'website'        => '',
				'privacy'        => '',
				'facebook'       => '',
				'instagram'      => '',
				'twitter'        => '',
				'youtube'        => '',
				'main'           => '',
				'xmas'           => '',
				'google'         => '',
				'facebook_app'   => '',
				'facebook_pixel' => '',
				'bing'           => '',
				'linkedin'       => '',
				'twitter_tag'    => '',
				'use_smtp'       => '',
				'smtp_host'      => '',
				'smtp_port'      => '',
				'smtp_auth'      => '',
				'smtp_user'      => '',
				'smtp_name'      => '',
				'smtp_secure'    => '',

			),
			$options,
			'register_settings_page'
		);

		$fields = new Fields();

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
			array( $fields, 'text' ),
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
			array( $fields, 'text' ),
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
			array( $fields, 'text' ),
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
			array( $fields, 'text' ),
			Plugin::NAME,
			'contacts',
			array(
				'classes' => '',
				'value'   => $options[ $id ],
				'name'    => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'      => $id,
			)
		);

		$id = 'intake';
		add_settings_field(
			$id,
			esc_html__( 'Intake Email Address:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $fields, 'text' ),
			Plugin::NAME,
			'contacts',
			array(
				'classes' => 'widefat',
				'value'   => $options[ $id ],
				'name'    => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'      => $id,
			)
		);

		$id = 'communications';
		add_settings_field(
			$id,
			esc_html__( 'Communications Email Address:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $fields, 'text' ),
			Plugin::NAME,
			'contacts',
			array(
				'classes' => 'widefat',
				'value'   => $options[ $id ],
				'name'    => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'      => $id,
			)
		);

		$id = 'hr';
		add_settings_field(
			$id,
			esc_html__( 'HR Email Address:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $fields, 'text' ),
			Plugin::NAME,
			'contacts',
			array(
				'classes' => 'widefat',
				'value'   => $options[ $id ],
				'name'    => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'      => $id,
			)
		);

		$id = 'alumni';
		add_settings_field(
			$id,
			esc_html__( 'Alumni Email Address:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $fields, 'text' ),
			Plugin::NAME,
			'contacts',
			array(
				'classes' => 'widefat',
				'value'   => $options[ $id ],
				'name'    => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'      => $id,
			)
		);

		$id = 'website';
		add_settings_field(
			$id,
			esc_html__( 'Website Email Address:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $fields, 'text' ),
			Plugin::NAME,
			'contacts',
			array(
				'classes' => 'widefat',
				'value'   => $options[ $id ],
				'name'    => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'      => $id,
			)
		);

		$id = 'privacy';
		add_settings_field(
			$id,
			esc_html__( 'Privacy Email Address:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $fields, 'text' ),
			Plugin::NAME,
			'contacts',
			array(
				'classes' => 'widefat',
				'value'   => $options[ $id ],
				'name'    => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'      => $id,
			)
		);

		$id = 'facebook';
		add_settings_field(
			$id,
			esc_html__( 'Facebook Link ID:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $fields, 'text' ),
			Plugin::NAME,
			'contacts',
			array(
				'classes'     => '',
				'value'       => $options[ $id ],
				'name'        => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'          => $id,
				'description' => 'ID is after https://facebook.com/',
			)
		);

		$id = 'instagram';
		add_settings_field(
			$id,
			esc_html__( 'Instagram Link ID:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $fields, 'text' ),
			Plugin::NAME,
			'contacts',
			array(
				'classes'     => '',
				'value'       => $options[ $id ],
				'name'        => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'          => $id,
				'description' => 'ID is after https://www.instagram.com/',
			)
		);

		$id = 'twitter';
		add_settings_field(
			$id,
			esc_html__( 'Twitter Link ID:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $fields, 'text' ),
			Plugin::NAME,
			'contacts',
			array(
				'classes'     => '',
				'value'       => $options[ $id ],
				'name'        => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'          => $id,
				'description' => 'ID is after https://twitter.com/',
			)
		);

		$id = 'youtube';
		add_settings_field(
			$id,
			esc_html__( 'YouTube Channel ID:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $fields, 'text' ),
			Plugin::NAME,
			'contacts',
			array(
				'classes'     => '',
				'value'       => $options[ $id ],
				'name'        => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'          => $id,
				'description' => 'ID is after https://www.youtube.com/channel/',
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
			array( $fields, 'text' ),
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
			array( $fields, 'text' ),
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
			array( $fields, 'text' ),
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
			array( $fields, 'text' ),
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
			array( $fields, 'text' ),
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
			array( $fields, 'text' ),
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
			array( $fields, 'text' ),
			Plugin::NAME,
			'analytics',
			array(
				'classes' => '',
				'value'   => $options[ $id ],
				'name'    => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'      => $id,
			)
		);

		$id = 'twitter_tag';
		add_settings_field(
			$id,
			esc_html__( 'Twitter Universal Website Tag:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $fields, 'text' ),
			Plugin::NAME,
			'analytics',
			array(
				'classes' => '',
				'value'   => $options[ $id ],
				'name'    => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'      => $id,
			)
		);

		add_settings_section(
			'smtp',
			'SMTP Options',
			null,
			Plugin::NAME
		);

		$id      = 'use_smtp';
		$checked = ( '1' === $options[ $id ] ) ? 'checked' : '';
		add_settings_field(
			$id,
			esc_html__( 'Use SMTP:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $fields, 'checkbox' ),
			Plugin::NAME,
			'smtp',
			array(
				'classes'     => '',
				'value'       => $options[ $id ],
				'name'        => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'          => $id,
				'checked'     => $checked,
				'description' => 'Use SMTP rather than mail (requires host/user/password/etc)',
			)
		);

		$id = 'smtp_host';
		add_settings_field(
			$id,
			esc_html__( 'SMTP Host:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $fields, 'text' ),
			Plugin::NAME,
			'smtp',
			array(
				'classes' => 'widefat',
				'value'   => $options[ $id ],
				'name'    => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'      => $id,
			)
		);

		$id = 'smtp_port';
		add_settings_field(
			$id,
			esc_html__( 'SMTP Port:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $fields, 'number' ),
			Plugin::NAME,
			'smtp',
			array(
				'classes'     => '',
				'value'       => $options[ $id ],
				'name'        => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'          => $id,
				'max'         => 65535,
				'description' => 'Usually 25, 465 or 567',
			)
		);

		$id      = 'smtp_auth';
		$checked = ( '1' === $options[ $id ] ) ? 'checked' : '';
		add_settings_field(
			$id,
			esc_html__( 'SMTP Authentication:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $fields, 'checkbox' ),
			Plugin::NAME,
			'smtp',
			array(
				'classes'     => '',
				'name'        => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'          => $id,
				'checked'     => $checked,
				'description' => 'SMTP authentication through user/password',
			)
		);

		$id = 'smtp_user';
		add_settings_field(
			$id,
			esc_html__( 'SMTP Username:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $fields, 'text' ),
			Plugin::NAME,
			'smtp',
			array(
				'classes'     => 'widefat',
				'value'       => $options[ $id ],
				'name'        => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'          => $id,
				'description' => 'Password must be set in wp-config.php with name ORC_SMTP_PASS',
			)
		);

		$id = 'smtp_name';
		add_settings_field(
			$id,
			esc_html__( 'SMTP From Name:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $fields, 'text' ),
			Plugin::NAME,
			'smtp',
			array(
				'classes'     => 'widefat',
				'value'       => $options[ $id ],
				'name'        => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'id'          => $id,
				'description' => 'SMTP From Name in email header',
			)
		);

		$id = 'smtp_secure';
		add_settings_field(
			$id,
			esc_html__( 'SMTP Secure:', Plugin::TEXT_DOMAIN ),     // phpcs:ignore
			array( $fields, 'radio' ),
			Plugin::NAME,
			'smtp',
			array(
				'classes'     => '',
				'value'       => $options[ $id ],
				'name'        => Plugin::SETTINGS_KEY . '[' . $id . ']',
				'options'     => array(
					'ssl'      => 'SSL',
					'tls'      => 'TLS',
					'starttls' => 'STARTTLS',
				),
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
				case 'intake':
				case 'communications':
				case 'hr':
				case 'alumni':
				case 'website':
				case 'privacy':
				case 'smtp_user':
					$output[ $id ] = sanitize_email( $value );
					break;
				default:
					$output[ $id ] = sanitize_text_field( $value );
			}
		}

		if ( ! empty( $input['use_smtp'] ) && '1' === $input['use_smtp'] ) {
			$all_requirements_set = ( ! empty( $input['smtp_host'] ) ) &&
									( ! empty( $input['smtp_port'] ) ) &&
									( ! empty( $input['smtp_auth'] ) ) &&
									( ! empty( $input['smtp_user'] ) ) &&
									( ! empty( $input['smtp_name'] ) ) &&
									( ! empty( $input['smtp_secure'] ) );
			if ( ! $all_requirements_set ) {
				$output['use_smtp'] = '';
			}
		}

		return apply_filters( 'orc_validate_data', $output, $input );

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
