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

		add_shortcode( 'orc_years_since', array( new Shortcodes(), 'years_since' ) );

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
