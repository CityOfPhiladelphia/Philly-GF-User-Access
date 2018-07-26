<?php
class GFEntryListModifier extends GFEntryList {

    public static function get_ids_only( $el ) {
        return $el->id;
    }

    public static function all_entries_page() {

		if ( ! GFCommon::ensure_wp_version() ) {
			return;
		}

		$forms   = RGFormsModel::get_forms( null, 'title' );
		$form_id = RGForms::get( 'id' );

		if ( sizeof( $forms ) == 0 ) {
			?>
			<div style="margin:50px 0 0 10px;">
				<?php echo sprintf( esc_html__( "You don't have any active forms. Let's go %screate one%s", 'gravityforms' ), '<a href="?page=gf_new_form">', '</a>' ); ?>
			</div>
			<?php
		} else {
			if ( empty( $form_id ) ) {
                global $allowed_forms;

                $form_ids = array_map( array( 'GFEntryListModifier', 'get_ids_only' ), $forms );
                $form_ids = array_intersect( $form_ids, $allowed_forms );
                if ( ! empty( $form_ids ) ) {
                    $form_id = array_shift( $form_ids );
                }
			}

			/**
			 * Fires before the entry list content is generated.
			 *
			 * Echoed content would appear above the page title.
			 *
			 * @param int $form_id The ID of the form that the entry list is being displayed for.
			 */
			do_action( 'gform_pre_entry_list', $form_id );

			parent::leads_page( $form_id );

			/**
			 * Fires after the entry list content is generated.
			 *
			 * Echoed content would appear after the bulk actions/paging links below the entry list table.
			 *
			 * @param int $form_id The ID of the form that the entry list is being displayed for.
			 */
			do_action( 'gform_post_entry_list', $form_id );
		}
	}
}