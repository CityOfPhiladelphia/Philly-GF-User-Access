<?php
/*
Plugin Name: Philly Gravity Forms User Access
Plugin URI: http://forms.phila.gov
Description: This plugin is a Gravity forms customization in Phila Forms. ACF Field Name => g_form_list; Type => Select; UserRole => form_manager
Version: 1.0
Author: Alejandro Lopez
Author URI: http://phila.gov
License: GPL2
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! function_exists('log_me') )
{
	function log_me($message) {
		if (WP_DEBUG === true) {
			if (is_array($message) || is_object($message)) {
				error_log(print_r($message, true));
			} else {
				error_log($message);
			}
		}
	}
}

// Plugin constants.
$plugin_path      = trailingslashit( dirname( __FILE__ ) );
$puglin_dir       = plugin_dir_url( __FILE__ );
$plugin_constants = array(
	'PHILLY_GF_VERSION'    => '1.0.0',
	'PHILLY_GF_MAIN_FILE'  => __FILE__,
	'PHILLY_GF_URL'        => $puglin_dir,
	'PHILLY_GF_PATH'       => $plugin_path,
	'PHILLY_GF_DOMAIN'		=> 'pgf'
);

foreach ( $plugin_constants as $constant => $value ) {
	if ( ! defined( $constant ) ) {
		define( $constant, $value );
	}
}

require_once PHILLY_GF_PATH . "classes/class.pgf-validate-requirements.php";
new Philly_GF_Validate_Requirements();