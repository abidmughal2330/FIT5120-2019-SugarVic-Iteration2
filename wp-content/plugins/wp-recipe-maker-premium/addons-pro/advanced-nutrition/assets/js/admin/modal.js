import '../../css/admin/modal.scss';

import  { parseQuantity } from '../../../../../assets/js/shared/quantities';

var wprmpn_admin = wprmpn_admin || {};

wprmpn_admin.ingredients = [];
wprmpn_admin.custom_ingredients_todo = [];
wprmpn_admin.custom_ingredient_todo = 0;
wprmpn_admin.searches = {};
wprmpn_admin.nutrition = {};
wprmpn_admin.custom_nutrition = {};
wprmpn_admin.saved_ingredient_search_results = [];

wprmpn_admin.getting_saved_ingredients = false;
wprmpn_admin.getting_api_ingredients = false;

wprmpn_admin.init_calculation = function() {
	var ingredients = WPRecipeMaker.admin.Recipe.getIngredients();

	var data = {
		action: 'wprm_search_ingredients',
		security: wprm_admin.nonce,
		recipe: { ingredients: ingredients }
	};

	jQuery('.wprm-nutrition-loader').show();
	jQuery.post(wprm_admin.ajax_url, data, function(out) {
		jQuery('.wprm-nutrition-loader').hide();
		if (out.success) {
			wprmpn_admin.ingredients = out.data.ingredients;
			wprmpn_admin.set_mapping_tab(out.data.mapping);
			jQuery('.wprm-nutrition-mapping-container').slideDown();
		} else {
			wprmpn_admin.reset_nutrition_tab();
		}
	}, 'json');
};

wprmpn_admin.set_mapping_tab = function(mapping) {
	var tbody = jQuery('.wprm-nutrition-mapping-container-table').find('tbody');
	tbody.html('');

	for(var i=0,l=mapping.length; i<l; i++) {
		var row = '';

		var line = mapping[i];

		if(line.prev_match) {
			line.matches = [{
				id: line.prev_match.id,
				name: line.prev_match.name
			}];
			line.source = line.prev_match.source;
		} else {
			line.source = 'api';
			wprmpn_admin.searches[line.ingredient.name] = line.matches;
		}

		var match = line.matches.length > 0 ? line.matches[0].id : 0;
		wprmpn_admin.ingredients[i].source = line.source;
		wprmpn_admin.ingredients[i].match = match;
		wprmpn_admin.ingredients[i].match_name = '';

		// Make sure amount is a regular quantity.
		var amount = parseQuantity(line.ingredient.amount);
		amount = amount ? amount : '';
		line.ingredient.amount = amount;
		wprmpn_admin.ingredients[i].amount = amount;

		row += '<tr id="wprm-nutrition-mapping-ingredient-' + i + '" class="wprm-nutrition-mapping-ingredient" data-index="' + i + '">';

		row += '<td class="wprm-nutrition-mapping-ingredient-original">';
		row += '<input type="text" class="wprm-nutrition-mapping-ingredient-amount" value="' + line.ingredient.amount + '"> ';
		row += '<input type="text" class="wprm-nutrition-mapping-ingredient-unit" value="' + line.ingredient.unit + '"> ';
		row += line.ingredient.name;
		if(line.ingredient.notes) row += ' (' + line.ingredient.notes + ')';
		row += '</td>';

		// Select
		var source_select = jQuery('.wprm-nutrition-mapping-source-placeholder').clone();
		source_select.removeClass('wprm-nutrition-mapping-source-placeholder')
					 .addClass('wprm-nutrition-mapping-source');

		row += '<td>' + source_select[0].outerHTML + '</td>';

		row += '<td>';

		if(match) {
			var match_name = line.matches[0].name;
			match_name += line.matches[0].aisle ? ' (' + line.matches[0].aisle.toLowerCase() + ')' : '';

			row += '<a href="#" class="wprm-nutrition-mapping-ingredient-match">';
			row += match_name;
			row += '</a>';

			wprmpn_admin.ingredients[i].match_name = match_name;
		} else {
			row += '<a href="#" class="wprm-nutrition-mapping-ingredient-match wprm-nutrition-mapping-ingredient-no-match">';
			row += 'no match found'
			row += '</a>';
		}

		row += '</td>';
		row += '</tr>';

		tbody.append(row);

		// Update select.
		if(line.source !== 'api') {
			jQuery('#wprm-nutrition-mapping-ingredient-' + i + '').find('.wprm-nutrition-mapping-source').val(line.source).change();
		}
	}

	jQuery('.wprm-nutrition-mapping-source').select2_wprm({
        width: '95%'
    });
};

wprmpn_admin.map_ingredient = function(index) {
	jQuery('.wprm-nutrition-mapping-container').slideUp(200, function() {
		var ingredient = wprmpn_admin.ingredients[index];

		var name = '';
		if(ingredient.amount) name += ingredient.amount + ' ';
		if(ingredient.unit) name += ingredient.unit + ' ';
		if(ingredient.name) name += ingredient.name;
		if(ingredient.notes) name += ' (' + ingredient.notes + ')';

		jQuery('.wprm-nutrition-mapping-ingredient-to-search').text(name);
		jQuery('#wprm-nutrition-mapping-ingredient-search').val(ingredient.name);
		jQuery('.wprm-nutrition-mapping-ingredient-search-results').data('index', index);
		wprmpn_admin.search_ingredient(ingredient.name);

		jQuery('.wprm-nutrition-mapping-ingredient-container').slideDown(200);
	});
};

wprmpn_admin.search_ingredient = function(name) {
	jQuery('.wprm-nutrition-mapping-ingredient-search-results').slideUp(200);

	if(wprmpn_admin.searches.hasOwnProperty(name)) {
		wprmpn_admin.show_ingredient_results(wprmpn_admin.searches[name]);
	} else {
		var data = {
			action: 'wprm_search_ingredient',
			security: wprm_admin.nonce,
			ingredient: name
		};

		jQuery('.wprm-nutrition-loader').show();
		jQuery.post(wprm_admin.ajax_url, data, function(out) {
			jQuery('.wprm-nutrition-loader').hide();
			if (out.success) {
				wprmpn_admin.show_ingredient_results(out.data.matches);
			} else {
				wprmpn_admin.show_ingredient_results([]);
			}
		}, 'json');
	}
};

wprmpn_admin.show_ingredient_results = function(results) {
	jQuery('.wprm-nutrition-mapping-ingredient-search-results').html('');

	for(var i=0, l=results.length; i<l; i++) {
		var result = '<div class="wprm-nutrition-mapping-ingredient-search-result" data-id="' + results[i].id + '">';
		if(results[i].image) {
			result += '<img class="wprm-nutrition-search-result-image" src="https://spoonacular.com/cdn/ingredients_100x100/' + results[i].image + '">';
		}
		result += '<div class="wprm-nutrition-search-result-name">' + results[i].name;
		if(results[i].aisle) {
			result += ' (' + results[i].aisle.toLowerCase() + ')';
		}
		result += '</div>'
		result += '</div>'
		jQuery('.wprm-nutrition-mapping-ingredient-search-results').append(result);
	}

	if(results.length == 0) {
		jQuery('.wprm-nutrition-mapping-ingredient-search-results').html('<p>No ingredients found.</p>');
	}

	jQuery('.wprm-nutrition-mapping-ingredient-search-results').slideDown(200);
};

wprmpn_admin.calculate_nutrition = function() {
	jQuery('.wprm-nutrition-mapping-container').slideUp();

	var ingredients = wprmpn_admin.ingredients;

	// Check if any custom/saved ingredients.
	wprmpn_admin.getting_saved_ingredients = false;
	wprmpn_admin.custom_ingredients_todo = [];

	for(var i=0, l=ingredients.length; i<l; i++) {
		var ingredient = ingredients[i];
		if(ingredient.source == 'custom') {
			wprmpn_admin.getting_saved_ingredients = true;
			wprmpn_admin.custom_ingredients_todo.push(i);
		}
	}

	var data = {
		action: 'wprm_get_nutrition_facts',
		security: wprm_admin.nonce,
		ingredients: ingredients
	};

	wprmpn_admin.getting_api_ingredients = true;

	if(wprmpn_admin.getting_saved_ingredients) {
		jQuery('.wprm-nutrition-saved-ingredients-container').slideDown();
		wprmpn_admin.custom_nutrition = {};
		wprmpn_admin.calculate_custom_nutrition();
	} else {
		jQuery('.wprm-nutrition-loader').show();
	}

	jQuery.post(wprm_admin.ajax_url, data, function(out) {
		wprmpn_admin.getting_api_ingredients = false;

		jQuery('.wprm-nutrition-loader').hide();
		if (out.success) {
			wprmpn_admin.nutrition = out.data.ingredients;

			if(!wprmpn_admin.getting_saved_ingredients) {
				wprmpn_admin.show_nutrition(wprmpn_admin.nutrition);
				jQuery('.wprm-nutrition-calculation-container').slideDown();
			}
		} else {
			if(!wprmpn_admin.getting_saved_ingredients) {
				jQuery('.wprm-nutrition-mapping-container').slideDown();
			}
		}
	}, 'json');
};

wprmpn_admin.calculate_custom_nutrition = function() {
	if(wprmpn_admin.custom_ingredients_todo.length == 0) {
		wprmpn_admin.getting_saved_ingredients = false;
		jQuery('.wprm-nutrition-saved-ingredients-container').slideUp();

		// If AJAX is finished loading we can continue.
		if(!wprmpn_admin.getting_api_ingredients) {
			wprmpn_admin.show_nutrition(wprmpn_admin.nutrition);
			jQuery('.wprm-nutrition-calculation-container').slideDown();
		} else {
			jQuery('.wprm-nutrition-loader').show();
		}
	} else {
		var index = wprmpn_admin.custom_ingredients_todo.pop(),
			ingredient = wprmpn_admin.ingredients[index],
			fields = '';

		wprmpn_admin.custom_ingredient_todo = index;

		// Set values.
		jQuery('.wprm-nutrition-saved-ingredient-amount').val(ingredient.amount);
		jQuery('.wprm-nutrition-saved-ingredient-unit').val(ingredient.unit);
		jQuery('.wprm-nutrition-saved-ingredient-name').val(ingredient.name);

		// Loop over fields we use.
		for(var i=0, l=wprmpn_admin.api_mapping.length; i<l; i++) {
			var mapping = wprmpn_admin.api_mapping[i],
				name = mapping.name.toLowerCase(),
				field = 'wprm-recipe-' + name.replace(' ', '-');

			fields += '<li><input type="text" class="wprm-nutrition-calculation-field ' + field + '" />' + mapping.unit + ' ' + name + '</li>';
		}

		jQuery('.wprm-nutrition-saved-ingredients-fields').html(fields);

		// Reset search.
		jQuery('#wprm-saved-ingredient-id').val(0).change();
	}
};

wprmpn_admin.save_custom_nutrition = function() {
	var amount = jQuery('.wprm-nutrition-saved-ingredient-amount').val().trim(),
	    unit = jQuery('.wprm-nutrition-saved-ingredient-unit').val().trim(),
	    name = jQuery('.wprm-nutrition-saved-ingredient-name').val().trim();

	if(!name) {
		alert('You need to enter a name for the new ingredient to save');
		return false;
	}

	var ingredient = {
		amount: amount,
		unit: unit,
		name: name,
		nutrition: {}
	};

	for(var i=0, l=wprmpn_admin.api_mapping.length; i<l; i++) {
		var mapping = wprmpn_admin.api_mapping[i],
			nutrient = mapping.name.toLowerCase().replace(' ', '-'),
			field = 'wprm-recipe-' + nutrient;
		
		ingredient.nutrition[nutrient] = jQuery('.wprm-nutrition-saved-ingredients-fields').find('.' + field).val();
	}

	var data = {
		action: 'wprmpn_save_ingredient',
		security: wprm_admin.nonce,
		ingredient: ingredient
	};

	jQuery.post(wprm_admin.ajax_url, data, function(out) {}, 'json');

	return true;
}

wprmpn_admin.show_nutrition = function(ingredients) {
	jQuery('.wprm-nutrition-calculation-results').html('');

	var ingredient_nutrition_values = [];

	for(var i=0, l=ingredients.length; i<l; i++) {
		var ingredient = ingredients[i];

		var name = '';
		if(ingredient.amount) name += ingredient.amount + ' ';
		if(ingredient.unit) name += ingredient.unit + ' ';
		if(ingredient.name) name += ingredient.name;
		if(ingredient.notes) name += ' (' + ingredient.notes + ')';

		var result = '<div id="wprm-nutrition-calculation-result-' + i + '-container" class="wprm-nutrition-calculation-result" data-index="' + i + '">';
		result += '<input type="checkbox" id="wprm-nutrition-calculation-result-' + i + '" checked="checked"> <label for="wprm-nutrition-calculation-result-' + i + '" class="wprm-nutrition-calculation-result-name">' + name + '</label>';

		var nutrition_summary = '',
			nutrition_full = '',
			dailies = [];

		// Loop over fields we use.
		ingredient_nutrition_values[i] = {};

		for(var j=0, m=wprmpn_admin.api_mapping.length; j<m; j++) {
			var mapping = wprmpn_admin.api_mapping[j],
				name = mapping.name.toLowerCase(),
				field = 'wprm-recipe-' + name.replace(' ', '-');

			nutrition_full += '<li><input type="text" class="wprm-nutrition-calculation-field ' + field + '" />' + mapping.unit + ' ' + name + '</li>';

			if(mapping.daily) {
				dailies.push(mapping.name);
			}

			ingredient_nutrition_values[i][field] = '';
		}

		if(ingredient.data && ingredient.data.nutrition) {
			var nutrients = ingredient.data.nutrition.nutrients;

			for(var j=0, m=nutrients.length; j<m; j++) {
				var nutrient = nutrients[j];

				// Update values.
				var value = jQuery.inArray(nutrient.title, dailies) !== -1 ? nutrient.percentOfDailyNeeds : nutrient.amount;
				var field = 'wprm-recipe-' + nutrient.title.replace(' ', '-').toLowerCase();

				ingredient_nutrition_values[i][field] = value;
			}

			nutrition_summary = ingredient.data.name;
		} else if (wprmpn_admin.custom_nutrition[i]) {
			var nutrients = wprmpn_admin.custom_nutrition[i].nutrients;

			for(var nutrient in nutrients) {
				// Update values.
				ingredient_nutrition_values[i]['wprm-recipe-' + nutrient] = nutrients[nutrient];
			}

			nutrition_summary = wprmpn_admin.custom_nutrition[i].name;
		} else {
			nutrition_summary = 'not found';
		}

		nutrition_summary += ' - <span class="wprm-nutrition-summary"></span>';

		result += ' <a href="#" class="wprm-nutrition-calculation-result-nutrition">' + nutrition_summary + '</a>';
		result += '<ul class="wprm-nutrition-calculation-result-nutrition-full">' + nutrition_full + '</ul>';

		result += '</div>'
		jQuery('.wprm-nutrition-calculation-results').append(result);
	}

	// Update values.
	for(var i=0, l=ingredient_nutrition_values.length; i<l; i++) {
		var values = ingredient_nutrition_values[i];

		for(var field in values) {
			var value = values[field];
			jQuery('#wprm-nutrition-calculation-result-' + i + '-container').find('.' + field).val(value);
		}

		// Update summary.
		wprmpn_admin.update_nutrition_summary(i);
	}
};

wprmpn_admin.update_nutrition_summary = function(ingredient) {
	var container = jQuery('#wprm-nutrition-calculation-result-' + ingredient + '-container'),
		calories = container.find('.wprm-recipe-calories').val(),
	    carbohydrates = container.find('.wprm-recipe-carbohydrates').val(),
	    fat = container.find('.wprm-recipe-fat').val(),
	    protein = container.find('.wprm-recipe-protein').val();
	
	calories = parseFloat(calories) || 0;
	carbohydrates = parseFloat(carbohydrates) || 0;
	fat = parseFloat(fat) || 0;
	protein = parseFloat(protein) || 0;

	var summary = '';
	summary += 'Calories: ' + calories + ' | ';
	summary += 'Carbohydrates: ' + carbohydrates + 'g | ';
	summary += 'Fat: ' + fat + 'g | ';
	summary += 'Protein: ' + protein + 'g';

	container.find('.wprm-nutrition-summary').text(summary);
}

// Mapping of nutrients from API to our fields.
wprmpn_admin.api_mapping = [
	{ name: 'Calories', 			unit: 'kcal', daily: false },
	{ name: 'Carbohydrates', 		unit: 'g', daily: false },
	{ name: 'Protein', 				unit: 'g', daily: false },
	{ name: 'Fat', 					unit: 'g', daily: false },
	{ name: 'Saturated Fat', 		unit: 'g', daily: false },
	{ name: 'Polyunsaturated Fat', 	unit: 'g', daily: false },
	{ name: 'Monounsaturated Fat', 	unit: 'g', daily: false },
	{ name: 'Trans Fat', 			unit: 'g', daily: false },
	{ name: 'Cholesterol', 			unit: 'mg', daily: false },
	{ name: 'Sodium', 				unit: 'mg', daily: false },
	{ name: 'Potassium',			unit: 'mg', daily: false },
	{ name: 'Fiber', 				unit: 'g', daily: false },
	{ name: 'Sugar', 				unit: 'g', daily: false },
	{ name: 'Vitamin A', 			unit: '%', daily: true },
	{ name: 'Vitamin C', 			unit: '%', daily: true },
	{ name: 'Calcium', 				unit: '%', daily: true },
	{ name: 'Iron', 				unit: '%', daily: true },
];

wprmpn_admin.set_recipe_nutrition = function(ingredients) {
	// Servings.
	var servings = parseQuantity(jQuery('#wprm-recipe-servings').val());
	servings = servings > 0 ? servings : 1;

	for(var i=0, l=wprmpn_admin.api_mapping.length; i<l; i++) {
		var mapping = wprmpn_admin.api_mapping[i],
			field = 'wprm-recipe-' + mapping.name.toLowerCase().replace(' ', '-'),
			nutrient_value = false;

		for(var j=0, m=ingredients.length; j<m; j++) {
			var ingredient = ingredients[j];

			var value = jQuery('#wprm-nutrition-calculation-result-' + ingredient + '-container').find('.' + field).val();
			value = value.replace(',','.').trim();
			value = parseFloat(value);

			if(!isNaN(value)) {
				nutrient_value = nutrient_value ? nutrient_value + value : value;
			}
		}

		if(nutrient_value !== false) {
			nutrient_value /= servings;

			// Format quantity
			if(mapping.daily) {
				nutrient_value = parseFloat(nutrient_value.toFixed(1));
			} else {
				nutrient_value = parseInt(nutrient_value);
			}
		} else {
			nutrient_value = '';
		}

		// Fix for total fat.
		if('wprm-recipe-fat' === field) {
			field = 'wprm-recipe-total-fat';
		}

		// Update value.
		jQuery('#' + field).val(nutrient_value);
	}
};

wprmpn_admin.reset_nutrition_tab = function() {
	jQuery('.wprm-nutrition-mapping-container').hide();
	jQuery('.wprm-nutrition-mapping-ingredient-container').hide();
	jQuery('.wprm-nutrition-calculation-container').hide();
	jQuery('.wprm-nutrition-facts-container').slideDown();
	jQuery('.wprm-button-action').show();
	jQuery('.wprm-button-action-save').show();
};

jQuery(document).ready(function() {
    jQuery('.wprm-button-nutrition-mapping').on('click', function() {
        jQuery('.wprm-nutrition-facts-container').slideUp();
        jQuery('.wprm-button-action').hide();
        jQuery('.wprm-button-action-save').hide();
        wprmpn_admin.init_calculation();
    });

	jQuery('.wprm-button-nutrition-cancel').on('click', function() {
        wprmpn_admin.reset_nutrition_tab();
    });

	jQuery('.wprm-nutrition-mapping-container').on('click', '.wprm-nutrition-mapping-ingredient-match', function(e) {
		e.preventDefault();
		var index = jQuery(this).parents('.wprm-nutrition-mapping-ingredient').data('index');
		wprmpn_admin.map_ingredient(index);
	});

	// Changing quantities and units.
	jQuery('.wprm-nutrition-mapping-container').on('keydown change', '.wprm-nutrition-mapping-ingredient-amount, .wprm-nutrition-mapping-ingredient-unit', function() {
		var ingredient = jQuery(this).parents('.wprm-nutrition-mapping-ingredient');
		var index = ingredient.data('index');
		var amount = ingredient.find('.wprm-nutrition-mapping-ingredient-amount').val();
		var unit = ingredient.find('.wprm-nutrition-mapping-ingredient-unit').val();

		wprmpn_admin.ingredients[index].amount = amount;
		wprmpn_admin.ingredients[index].unit = unit;
	});

	// Changing source.
	jQuery('.wprm-nutrition-mapping-container-table').on('change', '.wprm-nutrition-mapping-source', function() {
		var select = jQuery(this),
			source = select.val(),
			row = select.parents('.wprm-nutrition-mapping-ingredient'),
		  	index = row.data('index');

		wprmpn_admin.ingredients[index].source = source;

		if('api' == source) {
			select.parents('tr').find('.wprm-nutrition-mapping-ingredient-match').show();
		} else {
			select.parents('tr').find('.wprm-nutrition-mapping-ingredient-match').hide();
		}
	});

	// Search for ingredients.
	jQuery('.wprm-button-nutrition-mapping-ingredient-search').on('click', function() {
		var search_query = jQuery('#wprm-nutrition-mapping-ingredient-search').val();
		if(search_query.length > 0) {
			wprmpn_admin.search_ingredient(search_query);
		}
	});
	jQuery('#wprm-nutrition-mapping-ingredient-search').on('keydown', function(e) {
		if(e.which == 13) {
			var search_query = jQuery(this).val();
			if(search_query.length > 0) {
				wprmpn_admin.search_ingredient(search_query);
			}
		}
	});

	// Click on matching ingredient.
	jQuery('.wprm-nutrition-mapping-ingredient-container').on('click', '.wprm-nutrition-mapping-ingredient-search-result', function() {
		var id = jQuery(this).data('id');
		var name = jQuery(this).find('.wprm-nutrition-search-result-name').text();
		var index = jQuery(this).parents('.wprm-nutrition-mapping-ingredient-search-results').data('index');

		var match = jQuery('#wprm-nutrition-mapping-ingredient-' + index).find('.wprm-nutrition-mapping-ingredient-match');
		match.removeClass('wprm-nutrition-mapping-ingredient-no-match');
		match.text(name);

		wprmpn_admin.ingredients[index].match = id;
		wprmpn_admin.ingredients[index].match_name = name;

		jQuery('.wprm-nutrition-mapping-ingredient-container').slideUp(200, function() {
			jQuery('.wprm-nutrition-mapping-container').slideDown(200);
		});
	});

	// Confirm ingredients.
	jQuery('.wprm-button-nutrition-calculate').on('click', function() {
		wprmpn_admin.calculate_nutrition();
	});

	jQuery('.wprm-button-nutrition-back').on('click', function() {
		jQuery('.wprm-nutrition-mapping-ingredient-container, .wprm-nutrition-calculation-container').slideUp(200, function() {
			jQuery('.wprm-nutrition-mapping-container').slideDown(200);
		});
    });

	// Custom ingredients.
	jQuery('.wprm-saved-ingredients-dropdown').select2_wprm({
			width: '250px',
			ajax: {
				type: 'POST',
				url: wprm_admin.ajax_url,
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						action: 'wprmpn_search_saved_ingredients',
						security: wprm_admin.nonce,
						search: params.term
					};
				},
				processResults: function (out, params) {
					wprmpn_admin.saved_ingredient_search_results = out.data.saved_ingredients;
					return {
						results: out.data.saved_ingredients,
					};
				},
				cache: true
			},
			minimumInputLength: 1,
	});

	jQuery('#wprm-saved-ingredient-id').on('change', function() {
		var id = parseInt(jQuery(this).val());

		// Get nutrition values.
		var nutrition = false;

		for(var i=0, l=wprmpn_admin.saved_ingredient_search_results.length; i<l; i++) {
			var search_result = wprmpn_admin.saved_ingredient_search_results[i];

			if(search_result.id == id) {
				nutrition = search_result.nutrition;
				break;
			}
		}

		if(nutrition) {
			var ingredient = wprmpn_admin.ingredients[wprmpn_admin.custom_ingredient_todo];

			// Set values.
			jQuery('.wprm-nutrition-saved-ingredient-match-amount').text(ingredient.amount);
			jQuery('.wprm-nutrition-saved-ingredient-match-unit').text(ingredient.unit);
			jQuery('.wprm-nutrition-saved-ingredient-match-name').text(ingredient.name);

			var saved_amount = parseQuantity(nutrition.amount) || 0;
			jQuery('.wprm-nutrition-saved-ingredient-details-amount').val(nutrition.amount).data('amount', saved_amount);
			jQuery('.wprm-nutrition-saved-ingredient-details-unit').text(nutrition.unit);
			jQuery('.wprm-nutrition-saved-ingredient-details-name').text(nutrition.text);

			// Loop over fields we use.
			var fields = '';

			for(var i=0, l=wprmpn_admin.api_mapping.length; i<l; i++) {
				var mapping = wprmpn_admin.api_mapping[i],
					name = mapping.name.toLowerCase(),
					nutrient = name.replace(' ', '-'),
					value = nutrition.nutrients[nutrient] || '',
					amount = parseQuantity(value) || 0,
					field = 'wprm-recipe-' + nutrient;

				fields += '<li><span class="wprm-nutrition-calculation-field ' + field + '" data-amount="' + amount + '">' + value + '</span> ' + mapping.unit + ' ' + name + '</li>';
			}

			jQuery('.wprm-nutrition-saved-ingredient-details-fields').html(fields);

			// Match amounts if units are identical.
			if(ingredient.unit.trim() == nutrition.unit.trim()) {
				jQuery('.wprm-nutrition-saved-ingredient-details-amount').val(ingredient.amount).change();
			}

			jQuery('.wprm-nutrition-saved-ingredient-details').slideDown();
		} else {
			jQuery('.wprm-nutrition-saved-ingredient-details').slideUp();
		}
	});

	jQuery('.wprm-nutrition-saved-ingredient-details-amount').on('keyup change', function() {
		var input = jQuery(this),
			amount = parseQuantity(input.val()),
			original = parseFloat(input.data('amount'));
		
		jQuery('.wprm-nutrition-saved-ingredient-details-fields').find('.wprm-nutrition-calculation-field').each(function() {
			var field = jQuery(this),
				field_original = field.data('amount');

			if(!amount || amount === original) {
				field.text(field_original);
			} else {
				var field_amount = parseFloat( field_original ) / original * amount;
				field.text(parseFloat(field_amount.toFixed(1)));
			}
		});

	});

	jQuery('.wprm-button-saved-ingredients-save-confirm').on('click', function() {
		if(wprmpn_admin.save_custom_nutrition()) {
			var nutrition = {
				name: jQuery('.wprm-nutrition-saved-ingredient-name').val(),
				nutrients: {}
			};

			for(var i=0, l=wprmpn_admin.api_mapping.length; i<l; i++) {
				var mapping = wprmpn_admin.api_mapping[i],
					nutrient = mapping.name.toLowerCase().replace(' ', '-'),
					field = 'wprm-recipe-' + nutrient;
				
				nutrition.nutrients[nutrient] = jQuery('.wprm-nutrition-saved-ingredients-fields').find('.' + field).val();
			}

			wprmpn_admin.custom_nutrition[wprmpn_admin.custom_ingredient_todo] = nutrition;
			wprmpn_admin.calculate_custom_nutrition();
		}
	});
	jQuery('.wprm-button-saved-ingredients-nosave-confirm').on('click', function() {
		var nutrition = {
			name: jQuery('.wprm-nutrition-saved-ingredient-name').val(),
			nutrients: {}
		};

		for(var i=0, l=wprmpn_admin.api_mapping.length; i<l; i++) {
			var mapping = wprmpn_admin.api_mapping[i],
				nutrient = mapping.name.toLowerCase().replace(' ', '-'),
				field = 'wprm-recipe-' + nutrient;
			
			nutrition.nutrients[nutrient] = jQuery('.wprm-nutrition-saved-ingredients-fields').find('.' + field).val();
		}

		wprmpn_admin.custom_nutrition[wprmpn_admin.custom_ingredient_todo] = nutrition;
		wprmpn_admin.calculate_custom_nutrition();
	});

	jQuery('.wprm-button-saved-ingredients-confirm').on('click', function() {
		var nutrition = {
			name: jQuery('.wprm-nutrition-saved-ingredient-details-name').text(),
			nutrients: {}
		};

		for(var i=0, l=wprmpn_admin.api_mapping.length; i<l; i++) {
			var mapping = wprmpn_admin.api_mapping[i],
				nutrient = mapping.name.toLowerCase().replace(' ', '-'),
				field = 'wprm-recipe-' + nutrient;
			
			nutrition.nutrients[nutrient] = jQuery('.wprm-nutrition-saved-ingredient-details-fields').find('.' + field).text();
		}

		wprmpn_admin.custom_nutrition[wprmpn_admin.custom_ingredient_todo] = nutrition;
		wprmpn_admin.calculate_custom_nutrition();
	});

	// Show nutrition details.
	jQuery('.wprm-nutrition-calculation-results').on('click', '.wprm-nutrition-calculation-result-nutrition', function() {
		jQuery(this).siblings('.wprm-nutrition-calculation-result-nutrition-full').toggle();
	});

	// Update nutrition summary when updating details.
	jQuery('.wprm-nutrition-calculation-results').on('keyup change', '.wprm-recipe-calories, .wprm-recipe-carbohydrates, .wprm-recipe-fat, .wprm-recipe-protein', function() {
		var ingredient = jQuery(this).parents('.wprm-nutrition-calculation-result').data('index');
		wprmpn_admin.update_nutrition_summary(ingredient);
	});

	// Confirm nutrition details.
	jQuery('.wprm-button-nutrition-confirm').on('click', function() {
		var ingredients = [];

		jQuery('.wprm-nutrition-calculation-result input:checkbox:checked').each(function() {
			ingredients.push(jQuery(this).parents('.wprm-nutrition-calculation-result').data('index'));
		});

		wprmpn_admin.set_recipe_nutrition(ingredients);
		wprmpn_admin.reset_nutrition_tab();
	});
});
