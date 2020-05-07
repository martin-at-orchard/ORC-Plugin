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
	 * Create a href link for a phone number
	 *
	 * @param string $contact  The contact number.
	 * @param string $type     The type of number (phone, text, fax, email).
	 * @param bool   $is_email If this is an email contact.
	 *
	 * @return string HTML link string
	 */
	private function create_link( $contact, $type, $is_email = false ) {

		if ( $is_email ) {
			$link = $type;
		} else {
			$num = preg_replace( '/[^0-9]/', '', $contact );

			if ( '' === $num ) {
				return '';
			}

			if ( '1' === substr( $num, 0, 1 ) ) {
				$link = '+' . $num;
			} else {
				$link = '+1' . $num;
			}
		}

		switch ( $type ) {
			case 'local':
			case 'tollfree':
				$href = '<a href="tel:' . $link . '">';
				break;
			case 'text':
				$href = '<a href="sms:' . $link . '">';
				break;
			case 'fax':
				$href = '<a href="fax:' . $link . '">';
				break;
			default:
				$href = '<a href="/contact?send-to=' . $link . '">';
		}

		return $href;

	}

	/**
	 * Create a href link for a social contact
	 *
	 * @param string $link The social link.
	 * @param string $type The type of social contact (facebook, instagram, twitter, youtube).
	 *
	 * @return string HTML link string
	 */
	private function create_social_link( $link, $type ) {

		switch ( $type ) {
			case 'facebook':
				$href = '<a href="https://www.facebook.com/' . $link . '" target="_blank" title="Visit Orchard Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>';
				break;
			case 'instagram':
				$href = '<a href="https://www.instagram.com/' . $link . '" target="_blank" title="Visit Orchard Instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a>';
				break;
			case 'twitter':
				$href = '<a href="https://twitter.com/' . $link . '" target="_blank" title="Visit Orchard Twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>';
				break;
			case 'youtube':
				$href = '<a href="https://www.youtube.com/channel/' . $link . '" target="_blank" title="Visit Orchard YouTube"><i class="fa fa-youtube" aria-hidden="true"></i></a>';
				break;
			default:
				$href = '';
		}

		return $href;

	}

	/**
	 * Initialize the Shortcodes.
	 */
	public function initialize() {

		add_shortcode( 'orc_contact', array( new Shortcodes(), 'contact' ) );
		add_shortcode( 'orc_social', array( new Shortcodes(), 'social' ) );
		add_shortcode( 'orc_years_since', array( new Shortcodes(), 'years_since' ) );

	}

	/**
	 * Return the contact.
	 *
	 * Format [orc_contact type="T" icon="I" link="L" prefix="P" suffix="S" class="C"]
	 * Where  T is the type of contact (required)
	 *        I is for displaying an icon before the contact (optional Default='false')
	 *        L for enabling a link (optional Default='false')
	 *        P is the prefix (optional Default='')
	 *        S is the suffix (optional Default='')
	 *        C is the class (optional Default='')
	 *
	 * @param array $atts Attributes passed in from the shortcode.
	 * @return string Text string for the contact link.
	 */
	public function contact( $atts ) {

		do_action( 'orc_before_contact' );

		// Process the attributes.
		$atts = shortcode_atts(
			array(
				'type'   => '',
				'icon'   => 'false',
				'link'   => 'false',
				'prefix' => '',
				'suffix' => '',
				'class'  => '',
			),
			$atts,
			'orc_contact'
		);

		$valid = true;
		if ( '' === $atts['type'] ) {
			$html  = '<p>';
			$html  = __( 'ERROR: A contact type is required', Plugin::TEXT_DOMAIN ); // phpcs:ignore
			$html  = '</p>';
			$valid = false;
		} else {
			$settings = get_option( Plugin::SETTINGS_KEY );
			if ( isset( $settings[ $atts['type'] ] ) ) {
				$is_email = false;
				switch ( $atts['type'] ) {
					case 'local':
					case 'tollfree':
						$contact = $settings[ $atts['type'] ];
						$icon    = 'phone';
						break;
					case 'text':
						$contact = $settings[ $atts['type'] ];
						$icon    = 'mobile';
						break;
					case 'fax':
						$contact = $settings[ $atts['type'] ];
						$icon    = 'fax';
						break;
					case 'intake':
					case 'communications':
					case 'hr':
					case 'alumni':
					case 'website':
					case 'privacy':
						$contact  = Contact::get_email_to( $atts['type'] );
						$icon     = 'paper-plane';
						$is_email = true;
						break;
					default:
						$html  = '<p>';
						$html .= __( 'ERROR: Invalid contact type: ', Plugin::TEXT_DOMAIN ); // phpcs:ignore
						$html .= $atts['type'];
						$html .= '</p>';
						$valid = false;
				}
			} else {
				$html  = '<p>';
				$html .= __( 'ERROR: Invalid contact type: ', Plugin::TEXT_DOMAIN ); // phpcs:ignore
				$html .= $atts['type'];
				$html .= '</p>';
				$valid = false;
			}
		}

		if ( true === $valid ) {
			$html  = '';
			$html .= $contact;
			if ( 'true' === $atts['link'] ) {
				$link = $this->create_link( $contact, $atts['type'], $is_email );
				if ( '' !== $link ) {
					$html = $link . $html . '</a>';
				}
			}
			if ( '' !== $atts['prefix'] ) {
				$html = $atts['prefix'] . ' ' . $html;
			}
			if ( '' !== $atts['suffix'] ) {
				$html .= ' ' . $atts['suffix'];
			}
			if ( 'true' === $atts['icon'] ) {
				$html = '<i class="fa fa-' . $icon . '" aria-hidden="true"></i> ' . $html;
			}
			$class = ( '' === $atts['class'] ) ? '' : ' class="' . $atts['class'] . '"';
			$html  = '<span' . $class . '>' . $html . '</span>';
		}

		do_action( 'orc_after_contact' );

		$html = apply_filters( 'orc_contact', $html );

		return $html;

	}

	/**
	 * Return the social link.
	 *
	 * Format [orc_social type="T" class="C"]
	 * Where  T is the type of social link (required)
	 *        C is the class (optional Default='')
	 *
	 * @param array $atts Attributes passed in from the shortcode.
	 * @return string Text string for the social link.
	 */
	public function social( $atts ) {

		do_action( 'orc_before_social' );

		// Process the attributes.
		$atts = shortcode_atts(
			array(
				'type'  => '',
				'class' => '',
			),
			$atts,
			'orc_social'
		);

		$valid = true;
		$valid = true;
		if ( '' === $atts['type'] ) {
			$html  = '<p>';
			$html  = __( 'ERROR: A contact type is required', Plugin::TEXT_DOMAIN ); // phpcs:ignore
			$html  = '</p>';
			$valid = false;
		} else {
			$settings = get_option( Plugin::SETTINGS_KEY );
			if ( isset( $settings[ $atts['type'] ] ) ) {
				$contact = $settings[ $atts['type'] ];
				$link    = $this->create_social_link( $contact, $atts['type'] );
			} else {
				$html  = '<p>';
				$html .= __( 'ERROR: Invalid social contact type: ', Plugin::TEXT_DOMAIN ); // phpcs:ignore
				$html .= $atts['type'];
				$html .= '</p>';
				$valid = false;
			}
		}

		if ( true === $valid ) {
			$html  = $link;
			$class = ( '' === $atts['class'] ) ? '' : ' class="' . $atts['class'] . '"';
			$html  = '<span ' . $class . '>' . $html . '</span>';
		}

		do_action( 'orc_after_social' );

		$html = apply_filters( 'orc_social', $html );

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
