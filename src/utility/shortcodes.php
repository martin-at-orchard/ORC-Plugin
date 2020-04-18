<?php // phpcs:ignore

namespace ORC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;     // Exit if accessed directly.
}

/**
 * Class to handle all the shortcodes for the Orchard website.
 *
 * @since 0.1.2
 * @package ORC
 */
class Shortcodes {

	/**
	 * Initialize the Shortcodes.
	 */
	public function initialize() {

		add_shortcode( 'orc_contact', array( new Shortcodes(), 'contact' ) );
		add_shortcode( 'orc_years_since', array( new Shortcodes(), 'years_since' ) );

	}

	/**
	 * Return the contact.
	 *
	 * Format [orc_contact type="T" icon="I" prefix="P" suffix="S"]
	 * Where  T is the type of contact (required)
	 *        I is for displaying an icon before the contact (optional Default='false')
	 *        P is the prefix (optional Default='')
	 *        S is the suffix (optional Default='')
	 *
	 * @param array $atts Attributes passed in from the shortcode.
	 * @return string Text string for the number of years or an error if the year is not supplied
	 */
	public function contact( $atts ) {

		do_action( 'orc_before_contact' );

		// Process the attributes.
		$atts = shortcode_atts(
			array(
				'type'   => '',
				'icon'   => 'false',
				'prefix' => '',
				'suffix' => '',
			),
			$atts,
			'orc_before_contact'
		);

		if ( '' === $atts['type'] ) {
			$html  = '<p>';
			$html  = __( 'ERROR: A contact type is required', Plugin::TEXT_DOMAIN ); // phpcs:ignore
			$html  = '</p>';
			$error = true;
		} else {
			$settings = get_option( Plugin::SETTINGS_KEY );
			if ( isset( $settings[ $atts['type'] ] ) ) {
				$contact = $settings[ $atts['type'] ];
				$error   = false;
			}
			switch ( $atts['type'] ) {
				case 'local':
				case 'tollfree':
					$icon = 'phone';
					break;
				case 'text':
					$icon = 'mobile';
					break;
				case 'fax':
					$icon = 'fax';
					break;
				default:
					$html  = '<p>';
					$html .= __( 'ERROR: Invalid contact type: ', Plugin::TEXT_DOMAIN ); // phpcs:ignore
					$html .= $atts['type'];
					$html .= __( ' found', Plugin::TEXT_DOMAIN ); // phpcs:ignore
					$html .= '</p>';
					$error = true;
					break;
			}
		}

		if ( false === $error ) {
			if ( 'true' === $atts['icon'] ) {
				$html .= '<i class="fa fa-' . $icon . '" aria-hidden="true"></i> ';
			}
			$html .= $contact;
			if ( '' !== $atts['prefix'] ) {
				$html = $atts['prefix'] . ' ' . $html;
			}
			if ( '' !== $atts['suffix'] ) {
				$html .= ' ' . $atts['suffix'];
			}
			$html = '<p>' . $html . '</p>';
		}

		do_action( 'orc_after_contact' );

		$html = apply_filters( 'orc_contact', $html );

		return $html;

	}

/**
	 * Calculate the number of years since a date.
	 *
	 * Format [orc_years_since year="Y" month="M" day="D"]
	 * Where  Y is the year (required)
	 *        M is the month number (optional - defaults to 1)
	 *        D is the day number (optional - defaults to 1)
	 *
	 * @param array $atts Attributes passed in from the shortcode.
	 * @return string Text string for the number of years or an error if the year is not supplied
	 */
	public function years_since( $atts ) {

		do_action( 'orc_before_years_since' );

		// Process the attributes.
		$atts = shortcode_atts(
			array(
				'year'  => 0,
				'month' => 1,
				'day'   => 1,
			),
			$atts,
			'orc_before_years_since'
		);

		if ( $atts['year'] <= 0 ) {
			$html = __( 'ERROR: A year is required', Plugin::TEXT_DOMAIN ); // phpcs:ignore
		} elseif ( $atts['month'] < 1 || $atts['month'] > 12 ) {
			$html = __( 'ERROR: Month must be in the range (1-12) found: ', Plugin::TEXT_DOMAIN ) . $atts['month']; // phpcs:ignore
		} elseif ( $atts['day'] < 1 || $atts['day'] > 31 ) {
			$html = __( 'ERROR: Day must be in the range (1-31) found: ', Plugin::TEXT_DOMAIN ) . $atts['day']; // phpcs:ignore
		} else {
			$now      = current_time( 'Y-m-d H:i:s' );
			$now_date = new \DateTime( $now );
			$date     = new \DateTime( $atts['year'] . '-' . $atts['month'] . '-' . $atts['day'] );
			$diff     = $date->diff( $now_date );
			$html     = $diff->y;
		}

		do_action( 'orc_after_years_since' );

		$html = apply_filters( 'orc_years_since', $html );

		return $html;

	}

}
