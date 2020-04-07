<?php // phpcs:ignore

namespace ORC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;     // Exit if accessed directly.
}

/**
 * Class to handle all the debugging for the website
 *
 * @since 0.1.2
 * @package ORC
 *
 * @param object $data    The Object to write to the log
 * @param string $message Optional message to prepend to the log
 */
class Debug {

	/**
	 * Write to the error log
	 *
	 * @param object $data    Object to print (required).
	 * @param string $message Message to prepend to the object (optional).
	 */
	public static function write_log( $data, $message = null ) {

		$datastr = print_r( $data, true ); // phpcs:ignore
		if ( true === is_null( $message ) ) {
			$output = $datastr;
		} else {
			$output = $message . ': ' . $datastr;
		}
		error_log( $output ); // phpcs:ignore
	}

}
