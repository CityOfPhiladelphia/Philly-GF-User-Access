<?php
/*
Plugin Name: Philly Gravity Forms User Access
Plugin URI: http://forms.phila.gov
Description: This plugin is a Gravity forms customization in Phila Forms. ACF Field Name => g_form_list; Type => Select; UserRole => form_manager
Version: 0.0.1
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

// Globals
global $allowed_forms;
$allowed_forms = array();

add_action( 'admin_init', 'pgf_set_config' );
function pgf_set_config() {
	global $allowed_forms;

	$current_user = wp_get_current_user();
	$allowed_forms = get_field( 'g_form_list', "user_{$current_user->ID}" );
	if ( empty( $allowed_forms  ) ) $allowed_forms  = array();

	if ( RGForms::is_gravity_page() ) {
		wp_register_script( 'pgf-scripts', PHILLY_GF_URL . 'js/pgf-scripts.js', array(
			'jquery',
			'gform_chosen'
		), '1.0', false );
		
		wp_localize_script( 'pgf-scripts', 'pgf',
			array(
				'allowed_forms' => $allowed_forms
			)
		);
	}
}

add_filter('gform_noconflict_scripts', 'register_safe_script' );
function register_safe_script( $scripts ){
    $scripts[] = "pgf-scripts";
    return $scripts;
}

add_action( 'admin_head', 'enqueue_styles_for_pgfs' );
function enqueue_styles_for_pgfs(){
    if ( RGForms::is_gravity_page() ) {
        wp_enqueue_style( 'pgf-styles', PHILLY_GF_URL . 'css/styles.css' );
    }
}
 
add_filter( 'gform_noconflict_styles', 'register_style' );
function register_style( $styles ) {
     $styles[] = 'pgf-styles';
    return $styles;
}

require_once PHILLY_GF_PATH . "classes/class.pgf-main.php";