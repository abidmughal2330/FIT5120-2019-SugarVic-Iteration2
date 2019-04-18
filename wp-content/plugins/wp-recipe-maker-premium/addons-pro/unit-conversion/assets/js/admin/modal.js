import '../../css/admin/modal.scss';

import  { parseQuantity, formatQuantity } from '../../../../../assets/js/shared/quantities';

var wprmpuc_admin = wprmpuc_admin || {};

wprmpuc_admin.set_unit_conversion_table_from_ingredients = function() {
    var ingredients = [];
    jQuery('.wprm-recipe-ingredients').find('tr.wprm-recipe-ingredient').each(function() {
        var row = jQuery(this),
            uid = row.data('uid'),
            amount = row.find('.wprm-recipe-ingredient-amount').val(),
            unit = row.find('.wprm-recipe-ingredient-unit').val(),
            name = row.find('.wprm-recipe-ingredient-name').val();

        if(name.length > 0) {
            ingredients.push({
                uid: uid,
                amount: amount,
                unit: unit,
                name: name,
            });
        }
    });

    wprmpuc_admin.set_unit_conversion_form(ingredients);
};

wprmpuc_admin.set_unit_conversion_form = function(ingredients) {
    var container = jQuery('.wprm-unit-conversion-ingredients-container'),
        body = container.find('tbody'),
        calculation_table = jQuery('.wprm-unit-conversion-calculation-table'),
        calculation_body = calculation_table.find('tbody');

    // Empty and refill form from current ingredients.
    body.html('');
    calculation_body.html('');

    // Calculation overview select.
    var calculation_select = jQuery('.wprm-ingredient-conversion-calculation-type-placeholder').clone();
    calculation_select.removeClass('wprm-ingredient-conversion-calculation-type-placeholder')
                      .addClass('wprm-ingredient-conversion-calculation-type');

    for(var i=0,l=ingredients.length; i<l; i++) {
        var ingredient = ingredients[i];

        var converted_amount = '',
            converted_unit = '';

        if (WPRecipeMaker.admin.Recipe.ingredients.hasOwnProperty(ingredient['uid'])) {
            if(WPRecipeMaker.admin.Recipe.ingredients[ingredient['uid']].hasOwnProperty('converted')) {
                converted_amount = WPRecipeMaker.admin.Recipe.ingredients[ingredient['uid']]['converted'][2]['amount'];
                converted_unit = WPRecipeMaker.admin.Recipe.ingredients[ingredient['uid']]['converted'][2]['unit'];
            }
        }

        // Ingredients overview.
        var row = '<tr class="wprm-ingredient-conversion" id="wprm-ingredient-conversion-' + ingredient['uid'] + '" data-uid="' + ingredient['uid'] + '">';
        row += '<td><span class="wprmuc-ingredient-amount">' + ingredient['amount'] + '</span> <span class="wprmuc-ingredient-unit">' + ingredient['unit'] + '</span></td>';
        row += '<td><input type="text" class="wprmuc-system-2-amount" value="' + converted_amount + '"> <input type="text" class="wprmuc-system-2-unit" value="' + converted_unit + '"></td>';
        row += '<td><span class="wprmuc-ingredient-name">' + ingredient['name'] + '</span></td>';
        row += '</tr>';

        body.append(row);

        // Calculation overview.
        row = '<tr class="wprm-ingredient-conversion-calculation" id="wprm-ingredient-conversion-calculation-' + ingredient['uid'] + '" data-uid="' + ingredient['uid'] + '">';
        row += '<td><span class="wprmuc-ingredient-amount">' + ingredient['amount'] + '</span> <span class="wprmuc-ingredient-unit">' + ingredient['unit'] + '</span></td>';
        row += '<td>' + calculation_select[0].outerHTML + '</td>';
        row += '<td><span class="wprmuc-system-2-calculation-amount"></span> <span class="wprmuc-system-2-calculation-unit"></span></td>';
        row += '<td><span class="wprmuc-ingredient-name">' + ingredient['name'] + '</span></td>';
        row += '</tr>';

        calculation_body.append(row);
    }
    
    jQuery('.wprm-ingredient-conversion-calculation-type').select2_wprm({
        width: '95%'
    });
};

wprmpuc_admin.calculate_unit_conversion = function(system) {
    var ingredients = {};

    jQuery('.wprm-ingredient-conversion').each(function() {
        var row = jQuery(this),
            uid = row.data('uid'),
            amount = row.find('.wprmuc-ingredient-amount').text(),
            unit = row.find('.wprmuc-ingredient-unit').text(),
            name = row.find('.wprmuc-ingredient-name').text(),
            converted_amount = row.find('.wprmuc-system-' + system + '-amount').val().trim(),
            converted_unit = row.find('.wprmuc-system-' + system + '-unit').val().trim();

        if('' == converted_amount && '' == converted_unit) {
            ingredients[uid] = {
                amount: amount ? parseQuantity( amount ) : '',
                unit: unit,
                name: name
            };
        } else {
            var calculation_row = jQuery('#wprm-ingredient-conversion-calculation-' + uid);
            
            calculation_row.find('.wprm-ingredient-conversion-calculation-type').val('custom').change();
            calculation_row.find('.wprmuc-system-' + system + '-calculation-amount').text(converted_amount);
            calculation_row.find('.wprmuc-system-' + system + '-calculation-unit').text(converted_unit);
        }
    });

    var data = {
        action: 'wprm_calculate_unit_conversion',
        security: wprm_admin.nonce,
        ingredients: ingredients,
        system: system
    };

    jQuery('.wprm-unit-conversion-container').slideUp(200, function() {
        jQuery('.wprm-unit-conversion-loader').show();
    });

    jQuery.post(wprm_admin.ajax_url, data, function(out) {
        jQuery('.wprm-unit-conversion-loader').hide();
        if (out.success) {
            wprmpuc_admin.update_unit_conversion_from_calculation(system, out.data.conversion);
            jQuery('.wprm-unit-conversion-calculation-container').slideDown(200);
        } else {
            jQuery('.wprm-unit-conversion-container').slideDown(200);
        }
    }, 'json');
};

wprmpuc_admin.update_unit_conversion_from_calculation = function(system, conversion) {
    for (var uid in conversion) {
        var row = jQuery('#wprm-ingredient-conversion-calculation-' + uid);

        var select_val = 'automatic' === conversion[uid].type ? conversion[uid].unit : 'none';
        row.find('.wprm-ingredient-conversion-calculation-type').val(select_val).change();

        var amount = conversion[uid].amount ? formatQuantity(conversion[uid].amount, wprmp_admin.settings.unit_conversion_round_to_decimals) : '';

        row.find('.wprmuc-system-' + system + '-calculation-amount').text(amount);
        row.find('.wprmuc-system-' + system + '-calculation-unit').text(conversion[uid].alias);
    }
};

wprmpuc_admin.change_conversion_type = function(row, conversion_type) {
    var system = 2; // Hardcoded system.

    if('none' == conversion_type) {
        var amount = row.find('.wprmuc-ingredient-amount').text(),
            unit = row.find('.wprmuc-ingredient-unit').text();

        row.find('.wprmuc-system-' + system + '-calculation-amount').text(amount);
        row.find('.wprmuc-system-' + system + '-calculation-unit').text(unit);
    } else if('custom' == conversion_type) {
        row.find('.wprmuc-system-' + system + '-calculation-amount').text('');
        row.find('.wprmuc-system-' + system + '-calculation-unit').text('');
    } else {
        var uid = row.data('uid'),
            amount = row.find('.wprmuc-ingredient-amount').text(),
            unit = row.find('.wprmuc-ingredient-unit').text(),
            name = row.find('.wprmuc-ingredient-name').text(),
            ingredients = {};

        ingredients[uid] = {
            amount: parseQuantity( amount ),
            unit: unit,
            name: name,
        };

        if('automatic' != conversion_type) {
            ingredients[uid].units_to = [conversion_type];
        }

        var data = {
            action: 'wprm_calculate_unit_conversion',
            security: wprm_admin.nonce,
            ingredients: ingredients,
            system: system
        };

        row.find('.wprmuc-system-' + system + '-calculation-amount').text('...');
        row.find('.wprmuc-system-' + system + '-calculation-unit').text('');

        jQuery.post(wprm_admin.ajax_url, data, function(out) {
            if (out.success) {
                if('automatic' == conversion_type) {
                    var select_val = 'automatic' === out.data.conversion[uid].type ? out.data.conversion[uid].unit : 'none';
                    row.find('.wprm-ingredient-conversion-calculation-type').hide().val(select_val).change().show(); // Hide to prevent event trigger.
                }
                var amount = out.data.conversion[uid].amount ? formatQuantity(out.data.conversion[uid].amount, wprmp_admin.settings.unit_conversion_round_to_decimals) : '';

                row.find('.wprmuc-system-' + system + '-calculation-amount').text(amount);
                row.find('.wprmuc-system-' + system + '-calculation-unit').text(out.data.conversion[uid].alias);
            }
        }, 'json');
    }
};

wprmpuc_admin.use_calculated_unit_conversion = function(system) {
    jQuery('.wprm-unit-conversion-calculation-container').slideUp(200, function() {
        jQuery('.wprm-ingredient-conversion-calculation').each(function() {
            var row = jQuery(this),
                uid = row.data('uid'),
                amount = row.find('.wprmuc-system-' + system + '-calculation-amount').text(),
                unit = row.find('.wprmuc-system-' + system + '-calculation-unit').text(),
                update_row = jQuery('#wprm-ingredient-conversion-' + uid);

            update_row.find('.wprmuc-system-' + system + '-amount').val(amount).change();
            update_row.find('.wprmuc-system-' + system + '-unit').val(unit).change();
        });
        jQuery('.wprm-unit-conversion-container').slideDown(200);
    });
};

jQuery(document).ready(function() {
    // Open Unit Conversion tab.
    jQuery('.wprm-menu-item[data-tab="recipe-recipe-unit-conversion"]').on('click', function() {
        jQuery('.wprm-unit-conversion-calculation-container').hide();
        jQuery('.wprm-unit-conversion-container').show();

        wprmpuc_admin.set_unit_conversion_table_from_ingredients();
    });

    // Change unit system values.
    jQuery('.wprm-unit-conversion-ingredients-container').on('keyup change', 'input', function() {
        var row = jQuery(this).parents('.wprm-ingredient-conversion');

        if (row.length > 0) {
            var uid = row.data('uid'),
                amount = row.find('.wprmuc-system-2-amount').val(),
                unit = row.find('.wprmuc-system-2-unit').val();

            if(!WPRecipeMaker.admin.Recipe.ingredients[uid].hasOwnProperty('converted')) {
                WPRecipeMaker.admin.Recipe.ingredients[uid]['converted'] = {};
            }

            WPRecipeMaker.admin.Recipe.ingredients[uid]['converted'][2] = {
                amount: amount,
                unit: unit
            };
        }
    });

    // Click calculate button.
    jQuery('.wprm-button-calculate-unit-conversion').on('click', function() {
        jQuery('.wprm-button-action').hide();
	    jQuery('.wprm-button-action-save').hide();

        wprmpuc_admin.calculate_unit_conversion(2);
    });

    // Change conversion type.
    jQuery('.wprm-unit-conversion-calculation-container').on('change', '.wprm-ingredient-conversion-calculation-type', function() {
        var select = jQuery(this),
            row = select.parents('.wprm-ingredient-conversion-calculation'),
            conversion_type = select.val();

        if(select.is(':visible')) {
            wprmpuc_admin.change_conversion_type(row, conversion_type);
        }
    });

    // Click use button.
    jQuery('.wprm-button-use-unit-conversion').on('click', function() {
        wprmpuc_admin.use_calculated_unit_conversion(2);
        jQuery('.wprm-button-action').show();
	    jQuery('.wprm-button-action-save').show();
    });

    // Click cancel button.
    jQuery('.wprm-button-cancel-unit-conversion').on('click', function() {
        jQuery('.wprm-unit-conversion-calculation-container').slideUp(200, function() {
			jQuery('.wprm-unit-conversion-container').slideDown(200);
            jQuery('.wprm-button-action').show();
	        jQuery('.wprm-button-action-save').show();
		});
    });
});