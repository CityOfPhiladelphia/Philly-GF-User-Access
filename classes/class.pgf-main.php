<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Philly_GF_Main {
    // forms_page_gf_export & view=export_form && import_form
    static $_restrict = array( 'toplevel_page_gf_edit_forms', 'forms_page_gf_new_form', 'forms_page_gf_settings', 'forms_page_gf_addons', 'forms_page_gf_system_status', 'forms_page_gf_help' );
    function __construct() {
        add_filter( 'acf/load_field/name=g_form_list', array( $this, 'acf_load_g_form_list_field_choices' ) );

        if ( current_user_can( 'administrator' ) || current_user_can( 'gform_full_access' ) ) return;

        // Filter form export fields, if the user does not have access this filter kills the AJAX request.
        add_filter( "gform_export_fields", array( $this, 'filter_gform_form_export_page_form_id' ), 10, 1 );


        if ( defined('DOING_AJAX') && DOING_AJAX ) return;

        add_action( 'current_screen', array( $this, 'current_page_check' ), 1 );
        add_action( 'admin_init', array( $this, 'menu_adjustments' ), 999 );
    }

    function force_clean_parent( $menu_slug ) {
        remove_menu_page( $menu_slug );
        $menu_slug = plugin_basename( $menu_slug );
        $hookname = get_plugin_page_hookname( $menu_slug, '' );
        remove_all_actions( $hookname );
    }

    function force_clean_child( $menu_slug, $parent_slug ) {
        remove_submenu_page( $parent_slug, $menu_slug );
        $menu_slug = plugin_basename( $menu_slug );
        $parent_slug = plugin_basename( $parent_slug);
        $hookname = get_plugin_page_hookname( $menu_slug, $parent_slug);
        remove_all_actions( $hookname );
    }

    function menu_adjustments () {
        if( class_exists( 'GFForms' ) ) {
            $addon_menus = apply_filters( 'gform_addon_navigation', $addon_menus );
            $parent_menu = GFForms::get_parent_menu( $addon_menus );

            if ( class_exists( 'GFEntryList' ) && ! isset( $_GET['id'] ) ) {
                require_once PHILLY_GF_PATH . "classes/class.GFForms-Modifier.php";
                $this->force_clean_parent( $parent_menu['name'] );
                $this->force_clean_child( 'gf_entries', $parent_menu['name'] );

                $admin_icon = GFForms::get_admin_icon_b64( GFForms::is_gravity_page() ? '#fff' : false );
                add_menu_page( __( 'Forms', 'gravityforms' ), __( 'Forms', 'gravityforms' ), 'form_manager', $parent_menu['name'],  array( 'GFFormsModifier', 'all_leads_page' ), $admin_icon, 16.95 );

                $entries_hook_suffix = add_submenu_page( $parent_menu['name'], __( 'Entries', 'gravityforms' ), __( 'Entries', 'gravityforms' ), 'gravityforms_view_entries', 'gf_entries', array( 'GFFormsModifier', 'all_leads_page' ) );
            }

            remove_submenu_page( $parent_menu['name'], 'gf_export' );
            remove_submenu_page( $parent_menu['name'], 'gf_help' );

            add_submenu_page( $parent_menu['name'], __( 'Import/Export', 'gravityforms' ), __( 'Import/Export', 'gravityforms' ), 'gravityforms_export_entries', 'gf_export', array(
                'GFForms',
                'export_page'
            ) );

            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_pgf_scripts' ) );
        }
    }

    function current_page_check() {
        $current_screen = get_current_screen();

        if ( empty( $current_screen->base ) ) return;

        if ( in_array( $current_screen->base, self::$_restrict ) ) {
            wp_die( 'Well, well, well... You\'re trying to access a restricted area.', 403 );
        }

        global $allowed_forms;

        if ( empty ( $allowed_forms ) && ( $current_screen->base === 'toplevel_page_gf_entries' || $current_screen->base === 'forms_page_gf_export' ) ) {
            wp_die( 'Well, well, well... You\'re trying to access a restricted area.', 403 );
        }

        if ( $current_screen->base === 'toplevel_page_gf_entries' && ( ! empty ( $_GET['id'] ) && ! in_array ( $_GET['id'], $allowed_forms ) ) ) {
            wp_die( 'Well, well, well... You\'re trying to access a restricted area.', 403 );
        }
    }

    function acf_load_g_form_list_field_choices( $field ) {
        global $wpdb;

        if ( ! current_user_can( 'administrator' ) ) return null;

        // reset choices
        $field['choices'] = array();

        $choices = $wpdb->get_results( "SELECT title, id FROM {$wpdb->prefix}rg_form WHERE is_active = 1 AND is_trash = 0" );

        // loop through array and add to field 'choices'
        if( is_array($choices) ) {
            foreach( $choices as $choice ) {
                $field['choices'][ $choice->id ] = trim( $choice->title );
            }
        }

        // return the field
        return $field;
    }

    function enqueue_pgf_scripts() {
        wp_enqueue_script( 'pgf-scripts' );
    }

    // define the gform_form_export_page_<form_id> callback 
    function filter_gform_form_export_page_form_id( $form ) {
        global $allowed_forms;
        if ( ! in_array( $form['id'], $allowed_forms ) ) {
            wp_die( "NoAccessFormExport();" );
        }

        return $form;
    }
}