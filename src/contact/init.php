<?php // phpcs:ignore
/**
 * Orchard Recovery Contact
 *
 * Register the contact blocks.
 *
 * @since   0.3.6
 * @package ORC
 */

namespace ORC;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class to create a block and custom post type for
 * the Orchard Recovery Center contact shortcodes.
 */
class Contact {

	const NONCE = 'orc-contact-nonce';

	/**
	 * Array of keys and names for the emailing functions.
	 *
	 * @var array $email_to Emailing array.
	 */
	public static $email_to = array(
		'intake'         => 'Orchard Recovery',
		'communications' => 'Orchard Communications',
		'hr'             => 'Orchard Human Resources',
		'alumni'         => 'Orchard Alumni Coordinator',
		'website'        => 'Orchard Website Administrator',
		'privacy'        => 'Orchard Privacy Officer',
	);

	/**
	 * Load the template
	 *
	 * Order of checking for the template
	 *
	 * 1) wp-content/themes/CHILD-THEME/plugin/orc/templates/FILENAME
	 * 2) wp-content/themes/PARENT-THEME/plugin/orc/templates/FILENAME
	 * 3) wp-content/plugins/orc/templates/FILENAME
	 *
	 * @param string $template_name The name of the template to look for.
	 *
	 * @return mixed The template if loaded, false if not
	 */
	private function get_template_page( $template_name ) {

		$located         = false;
		$child_template  = trailingslashit( get_stylesheet_directory() ) . 'plugins/orc/templates/' . $template_name;
		$parent_template = trailingslashit( get_template_directory() ) . 'plugins/orc/templates/' . $template_name;
		$plugin_template = trailingslashit( plugin_dir_path( __FILE__ ) ) . '../../templates/' . $template_name;

		if ( file_exists( $child_template ) ) {
			// Check child theme first to see if the template is being overridden.
			$located = $child_template;
		} elseif ( file_exists( $parent_template ) ) {
			// Check parent theme next to see if the template is being overridden.
			$located = $parent_template;
		} elseif ( file_exists( $plugin_template ) ) {
			// Check plugin for template.
			$located = $plugin_template;
		}

		if ( ! empty( $located ) ) {
			load_template( $located, true );
		}

		return $located;

	}

	/**
	 * Constructor for the staff class
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'register' ) );
		add_action( 'template_redirect', array( $this, 'load_template' ) );
		add_action( 'admin_post_nopriv_contact_form', array( $this, 'send_contact_form' ) );
		add_action( 'admin_post_contact_form', array( $this, 'send_contact_form' ) );
		add_action( 'phpmailer_init', array( $this, 'mailer_config' ), 10, 1 );

	}

	/**
	 * Return the email to message for a particular email
	 * 
	 * @param string $to The email to string.
	 * 
	 * @return string The email to string.
	 */
	public static function get_email_to( $to ) {
		if ( isset( self::$email_to[ $to ] ) ) {

			return self::$email_to[ $to ];

		}

		return self::$email_to['intake'];

	}

	/**
	 * Register the block on the server-side to ensure that the block
	 * scripts and styles are enqueued when the editor loads.
	 * Provides a render function for the front end.
	 */
	public function register() {

		global $wpdb;

		register_block_type(
			Plugin::NAME . '/contact',
			array(
				'style'           => Plugin::FRONTEND_STYLE_HANDLE,
				'editor_script'   => Plugin::BACKEND_SCRIPT_HANDLE,
				'editor_style'    => Plugin::BACKEND_STYLE_HANDLE,
				'render_callback' => array( $this, 'render' ),
			)
		);

	}

	/**
	 * Load the templates for the custom contact/status page.
	 *
	 * @return mixed The template if loaded, false if not
	 */
	public function load_template() {

		global $wp;

		if ( isset( $wp->query_vars['pagename'] ) ) {

			if ( 'contact' === $wp->query_vars['pagename'] ) {
				return $this->get_template_page( 'orc_contact.php' );
			}

			if ( 'email-status' === $wp->query_vars['pagename'] ) {
				return $this->get_template_page( 'orc_status.php' );
			}

		}

		return false;

	}

	/**
	 * Set the emailer to use SMTP authentication.
	 *
	 * @param \PHPMailer $mailer The PHP mailer.
	 */
	public function mailer_config( \PHPMailer $mailer ) {

		$options = get_option( Plugin::SETTINGS_KEY );

		if ( isset( $options['use_smtp'] ) && '1' === $options['use_smtp'] ) {
			$mailer->IsSMTP();
			$mailer->Host       = esc_attr( $options['smtp_host'] );
			$mailer->Port       = esc_attr( $options['smtp_port'] );
			$mailer->SMTPAuth   = esc_attr( $options['smtp_auth'] );
			$mailer->SMTPSecure = esc_attr( $options['smtp_secure'] );
			$mailer->Sender     = esc_attr( $options['smtp_user'] );
			$mailer->Username   = esc_attr( $options['smtp_user'] );
			$mailer->From       = esc_attr( $options['smtp_user'] );
			$mailer->FromName   = esc_attr( $options['smtp_name'] );
			$mailer->Password   = esc_attr( ORC_SMTP_PASS );
		}

	}

	/**
	 * Send the contact form.
	 * 
	 * Validate all the data including the nonce
	 */
	public function send_contact_form() {

		$is_valid_nonce = ( isset( $_POST[ Contact::NONCE ] ) && wp_verify_nonce( $_POST[ Contact::NONCE ], 'Contact::orc-contact.php' ) ) ? true : false; // phpcs:ignore

		if ( ! $is_valid_nonce ) {
			$_SESSION['post-data']          = $_POST;
			$_SESSION['post-data']['error'] = 'Nonce Error';

			$url = get_bloginfo( 'url' ) . '/contact/';
			header( "Location: $url" );

			exit();
		}

		// Trim the inputs and filter the input.
		$inputs             = array();
		$inputs['name']     = isset( $_POST['name'] )     ? filter_var( trim( $_POST['name'] ),     FILTER_SANITIZE_STRING ) : '';     // phpcs:ignore
		$inputs['email']    = isset( $_POST['email'] )    ? filter_var( trim( $_POST['email'] ),    FILTER_SANITIZE_EMAIL )  : '';     // phpcs:ignore
		$inputs['subject']  = isset( $_POST['subject'] )  ? filter_var( trim( $_POST['subject'] ),  FILTER_SANITIZE_STRING ) : '';     // phpcs:ignore
		$inputs['message']  = isset( $_POST['message'] )  ? filter_var( trim( $_POST['message'] ),  FILTER_SANITIZE_STRING ) : '';     // phpcs:ignore
		$inputs['privacy']  = isset( $_POST['privacy'] )  ? filter_var(       $_POST['privacy'],    FILTER_VALIDATE_INT )    : 0;      // phpcs:ignore
		$inputs['email-to'] = isset( $_POST['email-to'] ) ? filter_var( trim( $_POST['email-to'] ), FILTER_SANITIZE_STRING ) : '';     // phpcs:ignore

		// Escape the inputs using WordPress functions.
		$inputs['name']    = esc_attr( $inputs['name'] );
		$inputs['email']   = sanitize_email( $inputs['email'] );
		$inputs['subject'] = esc_attr( $inputs['subject'] );
		$inputs['message'] = esc_textarea( $inputs['message'] );

		/**
		 * Validate the input.
		 *
		 * Require:
		 *   privacy = 1
		 *   name, subject
		 *   valid email
		 */

		$valid         = true;
		$privacy_error = '';
		$name_error    = '';
		$email_error   = '';
		$subject_error = '';
		$message_error = '';

		// Require the acknowledge privacy checkbox to be checked.
		if ( isset( $inputs['privacy'] ) ) {
			if ( 1 !== $inputs['privacy'] ) {
				$valid         = false;
				$privacy_error = 'Acceptance of the privacy policy is required';
			}
		} else {
			$valid         = false;
			$privacy_error = 'Acceptance of the privacy policy is required';
		}

		// Require a name with only text characters.
		if ( isset( $inputs['name'] ) ) {
			if ( '' === $inputs['name'] ) {
				$valid      = false;
				$name_error = 'Your name is required';
			} elseif ( $inputs['name'] !== trim( $_POST['name'] ) ) {     // phpcs:ignore
				$valid      = false;
				$name_error = 'Invalid characters in your name';
			}
		} else {
			$valid      = false;
			$name_error = 'Your name is required';
		}

		// Require a valid email address.
		if ( isset( $inputs['email'] ) ) {
			$inputs['email'] = filter_var( $inputs['email'], FILTER_VALIDATE_EMAIL );
			if ( false === $inputs['email'] ) {
				$valid       = false;
				$email_error = 'Your email address is not valid';
			} elseif ( '' === $inputs['email'] ) {
				$valid       = false;
				$email_error = 'The email address is required';
			} elseif ( $inputs['email'] !== trim( $_POST['email'] ) ) {     // phpcs:ignore
				$valid       = false;
				$email_error = 'Invalid characters in your email address';
			}
		} else {
			$valid       = false;
			$email_error = 'Your email address is required';
		}

		// Require a subject with only text characters.
		if ( isset( $inputs['subject'] ) ) {
			if ( '' === $inputs['subject'] ) {
				$valid         = false;
				$subject_error = 'The subject is required';
			} elseif ( $inputs['subject'] !== trim( $_POST['subject'] ) ) {     // phpcs:ignore
				$valid         = false;
				$subject_error = 'Invalid characters in the subject';
			}
		} else {
			$valid         = false;
			$subject_error = 'The subject is required';
		}

		// Only text characters allowed in the message.
		if ( isset( $inputs['message'] ) ) {
			if ( $inputs['message'] !== trim( $_POST['message'] ) ) {     // phpcs:ignore
				$valid         = false;
				$message_error = 'Invalid characters in the message';
			}
		}

		// Ensure the send to email address is valid. No address will default to intake.
		if ( isset( $inputs['email-to'] ) && '' !== $inputs['email-to'] ) {
			switch ( $inputs['email-to'] ) {
				case 'intake':
				case 'communications':
				case 'hr':
				case 'alumni':
				case 'website':
				case 'privacy':
					break;
				default:
					$valid         = false;
					$message_error = 'Invalid email to address';
			}
		}

		// Errors are returned in the session variable.
		session_start();

		if ( $valid ) {
			$headers = array(
				"Reply-To: {$inputs['name']} <{$inputs['email']}>",
				"Return-Path: {$inputs['name']} <{$inputs['email']}>",
			);

			// Find who to send the email to.
			$options = get_option( Plugin::SETTINGS_KEY );
			if ( isset( $options[ $inputs['email-to'] ] ) ) {
				$to = $options[ $inputs['email-to'] ];
			} else {
				$to = 'intake@orchardrecovery.com';
			}
			$email_sent = wp_mail( $to, $inputs['subject'], $inputs['message'], $headers );

			if ( $email_sent ) {
				$_SESSION['post-data']['email-to'] = $inputs['email-to'];
				$_SESSION['post-data']['success']  = 'Email successfuly sent';

				// Go to the success page.
				$url = get_bloginfo( 'url' ) . '/email-status/';
			} else {
				$_SESSION['post-data']['error'] = 'Error sending email';

				// Return to the contact page.
				$url = get_bloginfo( 'url' ) . '/contact/';
			}
		} else {
			$_SESSION['post-data']                  = $_POST;
			$_SESSION['post-data']['error']         = 'Please fix the errors in the submission form';
			$_SESSION['post-data']['privacy-error'] = $privacy_error;
			$_SESSION['post-data']['name-error']    = $name_error;
			$_SESSION['post-data']['email-error']   = $email_error;
			$_SESSION['post-data']['subject-error'] = $subject_error;
			$_SESSION['post-data']['message-error'] = $message_error;

			// Return to the contact page.
			$url = get_bloginfo( 'url' ) . '/contact/';
		}

		header( "Location: $url" );

		exit();

	}

	/**
	 * Render function for the contact shortcode.
	 *
	 * Possible attributes:
	 *   type   (isset - type of contact else null string)
	 *   icon   (isset - false else true)
	 *   link   (isset - false else true)
	 *   prefix (isset - prefix else null string)
	 *   suffix (isset - suffix else null string)
	 *   class  (isset - class else null string)
	 *
	 * Output [orc_contact type="T" icon="I" link="L" prefix="P" suffix="S" class="C"]
	 *
	 * @param array $attributes Attributes from the block editor.
	 */
	public function render( $attributes ) {

		$type      = ( isset( $attributes['type'] ) ) ? $attributes['type'] : 'local';
		$want_icon = ( isset( $attributes['wantIcon'] ) ) ? false : true;
		$want_link = ( isset( $attributes['wantLink'] ) ) ? false : true;
		$prefix    = ( isset( $attributes['prefix'] ) ) ? $attributes['prefix'] : '';
		$suffix    = ( isset( $attributes['suffix'] ) ) ? $attributes['suffix'] : '';
		$class     = ( isset( $attributes['theClass'] ) ) ? $attributes['theClass'] : '';

		$shortcode = '[orc_contact type="' . $type . '"';
		if ( $want_icon ) {
			$shortcode .= ' icon="true"';
		}
		if ( $want_link ) {
			$shortcode .= ' link="true"';
		}
		if ( '' !== $prefix ) {
			$shortcode .= ' prefix="' . $prefix . '"';
		}
		if ( '' !== $suffix ) {
			$shortcode .= ' suffix="' . $suffix . '"';
		}
		if ( '' !== $class ) {
			$shortcode .= ' class="' . $class . '"';
		}
		$shortcode .= ']';

		\ob_start();
		echo do_shortcode( $shortcode );
		return \ob_get_clean();

	}

}
