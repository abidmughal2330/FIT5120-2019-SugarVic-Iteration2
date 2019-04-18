import 'tooltipster';
import 'tooltipster/dist/css/tooltipster.bundle.css';

import  { parseQuantity, formatQuantity } from '../shared/quantities';

import '../../css/public/servings-changer.scss';

export function analyze_quantities(recipe) {
	var servings = parseInt(recipe.find('.wprm-recipe-servings').data('original_servings'));
	if(servings > 0) {
		recipe.find('.wprm-recipe-ingredient-amount, .wprm-dynamic-quantity').each(function() {
			// Only do this once.
			if(0 === jQuery(this).find('.wprm-adjustable').length) {
				// Surround all the number blocks
				var quantity = jQuery(this),
					quantity_text = quantity.text();

				// Special case: .5
				if ( /^\.\d+\s*$/.test(quantity_text) ) {
					quantity.html('<span class="wprm-adjustable">' + quantity_text + '</span>');
				} else {
					var fractions = '\u00BC\u00BD\u00BE\u2150\u2151\u2152\u2153\u2154\u2155\u2156\u2157\u2158\u2159\u215A\u215B\u215C\u215D\u215E';
					var number_regex = '[\\d'+fractions+']([\\d'+fractions+'.,\\/\\s]*[\\d'+fractions+'])?';
					var substitution = '<span class="wprm-adjustable">$&</span>';

					quantity_text = quantity_text.replace(new RegExp(number_regex, 'g'), substitution);
					quantity.html(quantity_text);
				}
			}
		});

		recipe.find('.wprm-adjustable').each(function() {
			// Only do this once.
			if('undefined' == typeof jQuery(this).data('original_quantity')) {
				var quantity = parseQuantity(jQuery(this).text());
				quantity /= servings;

				jQuery(this)
					.data('original_quantity', jQuery(this).text())
					.data('unit_quantity', quantity);
			}
		});
	}
};

export function update_serving_size(recipe) {
	var servings_element = recipe.find('.wprm-recipe-servings'),
		servings = parseInt(servings_element.data('servings')),
		original_servings = servings_element.data('original_servings');

	var adjustable_quantities = recipe.find('.wprm-adjustable');
	
	if(adjustable_quantities.length == 0) {
		analyze_quantities(recipe);
		adjustable_quantities = recipe.find('.wprm-adjustable');
	}

	adjustable_quantities.each(function() {
		var quantity_element = jQuery(this);

		if(servings == original_servings) {
			quantity_element.text(quantity_element.data('original_quantity'));
		} else {
			var quantity = parseFloat(quantity_element.data('unit_quantity')) * servings;

			if(!isNaN(quantity)) {
				quantity_element.text(formatQuantity(quantity, wprmp_public.settings.adjustable_servings_round_to_decimals));
			}
		}
	});
};

export function set_print_servings(servings) {
	if(servings > 0) {
		var recipe = jQuery('body');

		jQuery('.wprm-recipe-servings').each(function() {
			jQuery(this).text(servings);
			jQuery(this).data('servings', servings);
		});

		update_serving_size(recipe);
	}
};

function init_tooltip_slider(servings_element) {
	// Get the recipe ID.
	var recipe_id = servings_element.data('recipe');

	// Backwards compatibility.
	if (!recipe_id) {
		recipe_id = servings_element.parents('.wprm-recipe-container').data('recipe-id');
		servings_element.data('recipe', recipe_id);
	}

	// Make the servings a link
	servings_element.wrap('<a href="#" class="wprm-recipe-servings-link"></a>');

	// Add tooltip
	servings_element.tooltipster({
		content: '<input type="range" min="1" max="1" value="1" class="wprm-recipe-servings-slider">',
		contentAsHTML: true,
		functionBefore: function() {
			var instances = jQuery.tooltipster.instances();
			jQuery.each(instances, function(i, instance){
				instance.close();
			});
		},
		functionReady: function(instance, helper) {
			var max = 20,
				value = parseInt(jQuery(helper.origin).text());

			if( max < 2*value ) {
				max = 2*value;
			}

			// Set reference to correct servings changer.
			var uid = Date.now();
			jQuery(helper.origin).attr('id', 'wprm-tooltip-' + uid);

			jQuery(helper.tooltip)
				.find('.wprm-recipe-servings-slider').attr('max', max)
				.data('origin', 'wprm-tooltip-' + uid)
				.val(value);
		},
		interactive: true,
		delay: 0,
		trigger: 'custom',
		triggerOpen: {
			mouseenter: true,
			touchstart: true
		},
		triggerClose: {
			click: true,
			tap: true
		},
	});

	jQuery(document).on('input change', '.wprm-recipe-servings-slider', function() {
		var servings = jQuery(this).val(),
			origin = jQuery(this).data('origin'),
			servings_element = jQuery('#' + origin),
			recipe_id = servings_element.data('recipe'),
			recipe = jQuery('#wprm-recipe-container-' + recipe_id);

		// Update this servings element.
		servings_element.text(servings);
		servings_element.data('servings', servings);

		// Make sure all serving elements for this recipe are changed
		jQuery('.wprm-recipe-servings-' + recipe_id).each(function() {
			jQuery(this).text(servings);
			jQuery(this).data('servings', servings);
		});
		update_serving_size(recipe);
	});

	jQuery(document).on('click', '.wprm-recipe-servings-link', function(e) {
		e.preventDefault();
		e.stopPropagation();
	});
};

function init_text_field(servings_element) {
	var servings = servings_element.data('servings'),
		recipe_id = servings_element.data('recipe');
	
	// Backwards compatibility.
	if (!recipe_id) {
		recipe_id = servings_element.parents('.wprm-recipe-container').data('recipe-id');
	}

	var input = '<input type="number" class="wprm-recipe-servings wprm-recipe-servings-' + recipe_id + '" min="1" value="' + servings + '" data-servings="' + servings + '" data-original_servings="' + servings + '" data-recipe="' + recipe_id + '" />';

	servings_element.replaceWith(input);

	jQuery(document).on('input change', 'input.wprm-recipe-servings', function() {
		var servings_element = jQuery(this),
			servings = servings_element.val(),
			recipe_id = servings_element.data('recipe'),
			recipe = jQuery('#wprm-recipe-container-' + recipe_id);

		// Make sure all serving elements for this recipe are changed.
		jQuery('.wprm-recipe-servings-' + recipe_id).each(function() {
			jQuery(this).val(servings);
			jQuery(this).data('servings', servings);
		});
		update_serving_size(recipe);
	});
};


jQuery(document).ready(function($) {
	jQuery('.wprm-recipe-servings').each(function() {
		var servings_element = jQuery(this),
			servings = parseInt(servings_element.text());

		if( servings > 0 ) {
			// Save original servings
			servings_element.data('servings', servings);
			servings_element.data('original_servings', servings);

			if( !jQuery('body').hasClass('wprm-print')) {
				if ( 'modern' === wprmp_public.settings.recipe_template_mode ) {
					if ( servings_element.hasClass('wprm-recipe-servings-adjustable-tooltip') ) {
						init_tooltip_slider(servings_element);
					} else if ( servings_element.hasClass('wprm-recipe-servings-adjustable-text') ) {
						init_text_field(servings_element);
					}
				} else if ( wprmp_public.settings.features_adjustable_servings) {
					if ( wprmp_public.settings.servings_changer_display == 'text_field' ) {
						init_text_field(servings_element);
					} else { // Default = Tooltip Slider
						init_tooltip_slider(servings_element);
					}
				}
			}
		}
	});
});
