<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Philly_GF_Configuration {

    function __construct() {
        add_action( 'acf/init', array( $this, 'create_acf_field' ) );
    }

    function create_acf_field() {
        if( function_exists('acf_add_local_field_group') ):
            acf_add_local_field_group(array(
                'key' => 'group_pgf_5a9d75f43e1d5',
                'title' => 'Allowed Forms',
                'fields' => array(
                    array(
                        'key' => 'field_pgf_5a9d767d4b86f',
                        'label' => 'Allowed Form List',
                        'name' => 'g_form_list',
                        'type' => 'select',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(),
                        'default_value' => array(),
                        'allow_null' => 0,
                        'multiple' => 1,
                        'ui' => 0,
                        'ajax' => 0,
                        'return_format' => 'value',
                        'placeholder' => '',
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'user_role',
                            'operator' => '==',
                            'value' => 'all',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            ));
        endif;
    }

    function deactivate_plugin() {
    }
}