function NoAccessFormExport() {
    gfSpinner.destroy();
    alert('You do not have access to the selected form');
}

jQuery(function() {
    jQuery('select#form_switcher option, select#export_form option').each(function() {
        var _value = jQuery(this).attr('value');
        if ( _value ) {
            if ( pgf.allowed_forms.indexOf(_value) === -1 ) {
                jQuery(this).remove();
            }
        }
    });
    jQuery('#form_switcher').trigger('chosen:updated');
});