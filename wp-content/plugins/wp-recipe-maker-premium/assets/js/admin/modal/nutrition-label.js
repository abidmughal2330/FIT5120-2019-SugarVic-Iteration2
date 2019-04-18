WPRecipeMaker.admin.Modal.actions.reset_nutrition_label = function(args) {
    jQuery('#wprm-nutrition-label-id').val('0').trigger('change');
};

WPRecipeMaker.admin.Modal.actions.insert_nutrition_label = function(button) {
    var id = parseInt(jQuery('#wprm-nutrition-label-id').val()),
        align = jQuery('#wprm-nutrition-label-align').val(),
        shortcode = '[wprm-nutrition-label';

    if (id > 0) {
        shortcode += ' id="' + id + '"';
    }

    if (align !== 'left') {
        shortcode += ' align="' + align + '"';
    }

    shortcode += ']';

    WPRecipeMaker.admin.utils.add_text_to_editor(shortcode);
    WPRecipeMaker.admin.Modal.close();
};

const recipeClear = WPRecipeMaker.admin.Recipe.clear;
WPRecipeMaker.admin.Recipe.clear = function() {
    recipeClear.call(WPRecipeMaker.admin.Recipe);

    jQuery('#wprm-recipe-nutrition-serving').val('');
    jQuery('#wprm-recipe-nutrition-serving-unit').val('');
    jQuery('#wprm-recipe-carbohydrates').val('');

    jQuery('#wprm-recipe-protein').val('');
    jQuery('#wprm-recipe-total-fat').val('');
    jQuery('#wprm-recipe-saturated-fat').val('');

    jQuery('#wprm-recipe-polyunsaturated-fat').val('');
    jQuery('#wprm-recipe-monounsaturated-fat').val('');
    jQuery('#wprm-recipe-trans-fat').val('');

    jQuery('#wprm-recipe-cholesterol').val('');
    jQuery('#wprm-recipe-sodium').val('');
    jQuery('#wprm-recipe-potassium').val('');

    jQuery('#wprm-recipe-fiber').val('');
    jQuery('#wprm-recipe-sugar').val('');
    jQuery('#wprm-recipe-vitamin-a').val('');

    jQuery('#wprm-recipe-vitamin-c').val('');
    jQuery('#wprm-recipe-calcium').val('');
    jQuery('#wprm-recipe-iron').val('');
};

const recipeSet = WPRecipeMaker.admin.Recipe.set;
WPRecipeMaker.admin.Recipe.set = function(recipe) {
    recipeSet.call(WPRecipeMaker.admin.Recipe, recipe);

    var serving_size = false !== recipe.nutrition.serving_size ? parseFloat(recipe.nutrition.serving_size) : '',
        serving_unit = false !== recipe.nutrition.serving_unit ? recipe.nutrition.serving_unit : '',
        carbohydrates = false !== recipe.nutrition.carbohydrates ? parseFloat(recipe.nutrition.carbohydrates) : '',

        protein = false !== recipe.nutrition.protein ? parseFloat(recipe.nutrition.protein) : '',
        fat = false !== recipe.nutrition.fat ? parseFloat(recipe.nutrition.fat) : '',
        saturated_fat = false !== recipe.nutrition.saturated_fat ? parseFloat(recipe.nutrition.saturated_fat) : '',

        polyunsaturated_fat = false !== recipe.nutrition.polyunsaturated_fat ? parseFloat(recipe.nutrition.polyunsaturated_fat) : '',
        monounsaturated_fat = false !== recipe.nutrition.monounsaturated_fat ? parseFloat(recipe.nutrition.monounsaturated_fat) : '',
        trans_fat = false !== recipe.nutrition.trans_fat ? parseFloat(recipe.nutrition.trans_fat) : '',

        cholesterol = false !== recipe.nutrition.cholesterol ? parseFloat(recipe.nutrition.cholesterol) : '',
        sodium = false !== recipe.nutrition.sodium ? parseFloat(recipe.nutrition.sodium) : '',
        potassium = false !== recipe.nutrition.potassium ? parseFloat(recipe.nutrition.potassium) : '',

        fiber = false !== recipe.nutrition.fiber ? parseFloat(recipe.nutrition.fiber) : '',
        sugar = false !== recipe.nutrition.sugar ? parseFloat(recipe.nutrition.sugar) : '',
        vitamin_a = false !== recipe.nutrition.vitamin_a ? parseFloat(recipe.nutrition.vitamin_a) : '',

        vitamin_c = false !== recipe.nutrition.vitamin_c ? parseFloat(recipe.nutrition.vitamin_c) : '',
        calcium = false !== recipe.nutrition.calcium ? parseFloat(recipe.nutrition.calcium) : '',
        iron = false !== recipe.nutrition.iron ? parseFloat(recipe.nutrition.iron) : '';

    jQuery('#wprm-recipe-nutrition-serving').val(serving_size);
    jQuery('#wprm-recipe-nutrition-serving-unit').val(serving_unit);
    jQuery('#wprm-recipe-carbohydrates').val(carbohydrates);

    jQuery('#wprm-recipe-protein').val(protein);
    jQuery('#wprm-recipe-total-fat').val(fat);
    jQuery('#wprm-recipe-saturated-fat').val(saturated_fat);

    jQuery('#wprm-recipe-polyunsaturated-fat').val(polyunsaturated_fat);
    jQuery('#wprm-recipe-monounsaturated-fat').val(monounsaturated_fat);
    jQuery('#wprm-recipe-trans-fat').val(trans_fat);

    jQuery('#wprm-recipe-cholesterol').val(cholesterol);
    jQuery('#wprm-recipe-sodium').val(sodium);
    jQuery('#wprm-recipe-potassium').val(potassium);

    jQuery('#wprm-recipe-fiber').val(fiber);
    jQuery('#wprm-recipe-sugar').val(sugar);
    jQuery('#wprm-recipe-vitamin-a').val(vitamin_a);

    jQuery('#wprm-recipe-vitamin-c').val(vitamin_c);
    jQuery('#wprm-recipe-calcium').val(calcium);
    jQuery('#wprm-recipe-iron').val(iron);
}

const recipeGet = WPRecipeMaker.admin.Recipe.get;
WPRecipeMaker.admin.Recipe.get = function() {
    let recipe = recipeGet.call(WPRecipeMaker.admin.Recipe);

    recipe.nutrition = {
        serving_size:           jQuery('#wprm-recipe-nutrition-serving').val(),
        serving_unit:           jQuery('#wprm-recipe-nutrition-serving-unit').val(),
        calories:               jQuery('#wprm-recipe-calories').val(),
        carbohydrates:          jQuery('#wprm-recipe-carbohydrates').val(),

        protein:                jQuery('#wprm-recipe-protein').val(),
        fat:                    jQuery('#wprm-recipe-total-fat').val(),
        saturated_fat:          jQuery('#wprm-recipe-saturated-fat').val(),

        polyunsaturated_fat:    jQuery('#wprm-recipe-polyunsaturated-fat').val(),
        monounsaturated_fat:    jQuery('#wprm-recipe-monounsaturated-fat').val(),
        trans_fat:              jQuery('#wprm-recipe-trans-fat').val(),

        cholesterol:            jQuery('#wprm-recipe-cholesterol').val(),
        sodium:                 jQuery('#wprm-recipe-sodium').val(),
        potassium:              jQuery('#wprm-recipe-potassium').val(),

        fiber:                  jQuery('#wprm-recipe-fiber').val(),
        sugar:                  jQuery('#wprm-recipe-sugar').val(),
        vitamin_a:              jQuery('#wprm-recipe-vitamin-a').val(),

        vitamin_c:              jQuery('#wprm-recipe-vitamin-c').val(),
        calcium:                jQuery('#wprm-recipe-calcium').val(),
        iron:                   jQuery('#wprm-recipe-iron').val()
    };

    return recipe;
}