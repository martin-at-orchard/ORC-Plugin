<?php
/**
 * Plugin Name: orc
 * Plugin URI: https://github.com/ahmadawais/create-guten-block/
 * Description: orc — is a Gutenberg plugin created via create-guten-block that contains all the custom code for the Orchard Recovery Center Website
 * Author: Martin Wedepohl
 * Author URI: https://wedepohlengineering.com/
 * Version: 0.1.0
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package ORC
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';
