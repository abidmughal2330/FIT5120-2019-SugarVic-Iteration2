import '../../css/admin/manage.scss';

// TODO Refactor quick and dirty solution. Reuse nutrients + modal.
var wprmpn_manage_custom_nutrition_nutrients = ['calories','carbohydrates','protein','fat','saturated-fat','polyunsaturated-fat','monounsaturated-fat','trans-fat','cholesterol','sodium','potassium','fiber','sugar','vitamin-a','vitamin-c','calcium','iron'];

jQuery(document).ready(function() {
    jQuery('.wprm-manage-custom-nutrition-create').on('click', function(e) {
        e.preventDefault();

        // Reset values.
        jQuery('#wprm-nutrition-saved-ingredient-id').val('');
        jQuery('#wprm-nutrition-saved-ingredient-amount').val('');
        jQuery('#wprm-nutrition-saved-ingredient-unit').val('');
        jQuery('#wprm-nutrition-saved-ingredient-name').val('');

        for ( var i=0, l=wprmpn_manage_custom_nutrition_nutrients.length; i<l; i++ ) {
            var nutrient = wprmpn_manage_custom_nutrition_nutrients[i];
            jQuery('#wprm-nutrition-saved-ingredient-' + nutrient).val('');
        }

        jQuery('.wprm-manage-custom-nutrition-modal').show();
    });

    jQuery('#wprm-manage-custom-nutrition').on('click', '.wprm-manage-custom-nutrition-edit', function(e) {
        e.preventDefault();

        var id = jQuery(this).parents('.wprm-manage-custom-nutrition-actions').data('id'),
            amount = jQuery('#wprm-manage-custom-nutrition-amount-' + id).text(),
            unit = jQuery('#wprm-manage-custom-nutrition-unit-' + id).text(),
            name = jQuery('#wprm-manage-custom-nutrition-name-' + id).text();

        // Set ID, amount, unit and name.
        jQuery('#wprm-nutrition-saved-ingredient-id').val(id);
        jQuery('#wprm-nutrition-saved-ingredient-amount').val(amount);
        jQuery('#wprm-nutrition-saved-ingredient-unit').val(unit);
        jQuery('#wprm-nutrition-saved-ingredient-name').val(name);

        // Loop over nutrients.
        for ( var i=0, l=wprmpn_manage_custom_nutrition_nutrients.length; i<l; i++ ) {
            var nutrient = wprmpn_manage_custom_nutrition_nutrients[i];
            var value = '';
            
            var saved_value = jQuery('#wprm-manage-custom-nutrition-nutrient-' + nutrient + '-' + id );
            if (saved_value.length) {
                value = saved_value.text();
            }

            jQuery('#wprm-nutrition-saved-ingredient-' + nutrient).val(value);
        }

        jQuery('.wprm-manage-custom-nutrition-modal').show();
    });

    jQuery('.wprm-manage-modal-save').on('click', function(e) {
        e.preventDefault();

        var id = jQuery('#wprm-nutrition-saved-ingredient-id').val();

        var ingredient = {};
        ingredient['amount'] = jQuery('#wprm-nutrition-saved-ingredient-amount').val();
        ingredient['unit'] = jQuery('#wprm-nutrition-saved-ingredient-unit').val();
        ingredient['name'] = jQuery('#wprm-nutrition-saved-ingredient-name').val();

        if ( ingredient['amount'] && ingredient['name'] ) {
            ingredient['nutrition'] = {};
            for ( var i=0, l=wprmpn_manage_custom_nutrition_nutrients.length; i<l; i++ ) {
                var nutrient = wprmpn_manage_custom_nutrition_nutrients[i];
                ingredient['nutrition'][nutrient] = jQuery('#wprm-nutrition-saved-ingredient-' + nutrient).val();
            }

            var data = {
                action: 'wprmpn_save_ingredient',
                security: wprm_admin.nonce,
                custom_nutrition_id: id,
                ingredient: ingredient
            };
        
            jQuery.post(wprm_admin.ajax_url, data, function() {
                jQuery('.wprm-manage-datatable').DataTable().ajax.reload(null, false);
            });

            jQuery('.wprm-manage-custom-nutrition-modal').hide();
        }
    });

    jQuery('.wprm-manage-modal-cancel').on('click', function(e) {
        e.preventDefault();
        jQuery('.wprm-manage-custom-nutrition-modal').hide();
    });

    jQuery('#wprm-manage-custom-nutrition').on('click', '.wprm-manage-custom-nutrition-delete', function(e) {
        e.preventDefault();

        var id = jQuery(this).parents('.wprm-manage-custom-nutrition-actions').data('id'),
            name = jQuery('#wprm-manage-custom-nutrition-name-' + id).text();

        if(confirm('Are you sure you want to delete "' + name + '"?')) {
            var data = {
                action: 'wprm_delete_or_merge_term',
                security: wprm_admin.nonce,
                term_id: id,
                taxonomy: 'nutrition_ingredient',
                new_term_id: 0
            };
        
            jQuery.post(wprm_admin.ajax_url, data, function() {
                jQuery('.wprm-manage-datatable').DataTable().ajax.reload(null, false);
            });
        }
    });
});