import { analyze_quantities, update_serving_size } from '../../../../../assets/js/public/servings-changer';

import '../../css/public/unit-conversion.scss';

export function set_print_system(system) {
    var recipe = jQuery('body'),
        ingredients = recipe.find('.wprm-recipe-ingredient'),
        ingredients_data = window['wprmpuc_ingredients'];

    // Only change if this system isn't already on the page.
    if ( 0 === recipe.find( '.wprm-recipe-ingredient-unit-system-' + system ).length ) {
        change_to_system(recipe, ingredients, ingredients_data, system);
    }
};

function change_to_system(recipe, ingredients, ingredients_data, system) {
    for (var i = 0, l = ingredients_data.length; i < l; i++) {
        var ingredient = ingredients.eq(i),
            converted = ingredients_data[i].converted || {};

        if(1 === system || converted.hasOwnProperty(system)) {
            var amount = 1 === system ? ingredients_data[i].amount : converted[system].amount,
                unit = 1 === system ? ingredients_data[i].unit : converted[system].unit;

            ingredient.find('.wprm-recipe-ingredient-amount').html(amount);
            ingredient.find('.wprm-recipe-ingredient-unit').html(unit);
        }
    }

    analyze_quantities(recipe);
    update_serving_size(recipe);
};

jQuery(document).ready(function($) {
    jQuery(document).on('click', '.wprm-unit-conversion', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var link = jQuery(this),
            recipe_id = link.data('recipe'),
            recipe = jQuery('#wprm-recipe-container-' + recipe_id),
            ingredients = recipe.find('.wprm-recipe-ingredient'),
            ingredients_data = window['wprmpuc_recipe_' + recipe_id].ingredients,
            system = parseInt( link.data('system') );

        if (!link.hasClass('wprmpuc-active')) {
            link.siblings('.wprm-unit-conversion').removeClass('wprmpuc-active');
            link.addClass('wprmpuc-active');

            change_to_system(recipe, ingredients, ingredients_data, system);
        }
    });
    jQuery(document).on('change', '.wprm-unit-conversion-dropdown', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var dropdown = jQuery(this),
            recipe_id = dropdown.data('recipe'),
            recipe = jQuery('#wprm-recipe-container-' + recipe_id),
            ingredients = recipe.find('.wprm-recipe-ingredient'),
            ingredients_data = window['wprmpuc_recipe_' + recipe_id].ingredients,
            system = parseInt( dropdown.val() );

        change_to_system(recipe, ingredients, ingredients_data, system);
    });
});