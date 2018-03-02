<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Philly_GF_Validate_Requirements {
	var $name = "Philly GF User Access";

	public function __construct(){
		add_action( 'plugins_loaded', array( $this, 'validate_plugin_on_loaded' ) );
	}

	public function show_requirements_failed_error(){
		$name = $this->name;
		add_action( 'admin_notices', function() use ( $name ) {
			echo '<div class="error"><p>' .
				sprintf( __( 'The %s plugin requires the Advance Custom Fields and Gravity Forms plugins to be installed and activated.', PHILLY_GF_DOMAIN ), $name ) .
				'</p></div>';
		} );
	}

	public function validate_plugin_on_loaded(){
		if( ! $this->check_requirements() )
		{
			$this->show_requirements_failed_error();
		}else{
			new Philly_GF_Main();
		}
	}

	public function check_requirements(){
		if ( ! class_exists( 'GFForms' ) || ! class_exists( 'ACF' ) ) {
			return false;
		}

		return true;
	}
}