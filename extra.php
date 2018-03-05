<?php
add_action( 'wp_enqueue_scripts', 'enqueue_scripts_and_styles_for_pgfs' );
function enqueue_scripts_and_styles_for_pgfs(){
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
        wp_enqueue_style( 'pgf-styles', PHILLY_GF_URL . 'css/styles.css' );
    }
}
 
add_filter( 'gform_noconflict_styles', 'register_style' );
function register_style( $styles ) {
     $styles[] = 'pgf-styles';
    return $styles;
}

add_filter('gform_noconflict_scripts', 'register_safe_script' );
function register_safe_script( $scripts ){
    $scripts[] = "pgf-scripts";
    return $scripts;
}