<?php
class GFFormsModifier extends GFForms {
    public static function all_leads_page() {
        require_once PHILLY_GF_PATH . "classes/class.GF-Entry-List-Modifier.php";

		if ( parent::maybe_display_wizard() ) {
			return;
		};

		$view    = rgget( 'view' );
		$lead_id = rgget( 'lid' );

		if ( $view == 'entry' && ( rgget( 'lid' ) || ! rgblank( rgget( 'pos' ) ) ) ) {
			require_once( GFCommon::get_base_path() . '/entry_detail.php' );
			GFEntryDetail::lead_detail_page();
		} else if ( $view == 'entries' || empty( $view ) ) {
			require_once( GFCommon::get_base_path() . '/entry_list.php' );
			GFEntryListModifier::all_entries_page();
		} else {
			$form_id = rgget( 'id' );
			$form_id = absint( $form_id );
			/**
			 * Fires when viewing entries of a certain form
			 *
			 * @since Unknown
			 *
			 * @param string $view    The current view/entry type
			 * @param string $form_id The current form ID
			 * @param string $lead_id The current entry ID
			 */
			do_action( 'gform_entries_view', $view, $form_id, $lead_id );
		}

	}

}