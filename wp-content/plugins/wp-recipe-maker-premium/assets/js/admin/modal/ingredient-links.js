let ingredient_links = {};

function set_ingredient_links_from_ingredients() {
    var ingredients = [];
    jQuery('.wprm-recipe-ingredients').find('tr.wprm-recipe-ingredient').each(function() {
        var row = jQuery(this),
            name = row.find('.wprm-recipe-ingredient-name').val();

        if(name.length > 0 && ingredients.indexOf(name) === -1) {
            ingredients.push(name);
        }
    });

    set_ingredient_links_form(ingredients);
};

function set_ingredient_links_form(ingredients) {
    var container = jQuery('.wprm-ingredient-links-container');
    var type = jQuery('#wprm-ingredient-links-type').val();

    var unknown_ingredients = [];
    var unknown_ingredients_names = [];

    // Check for existing values.
    jQuery('.wprm-ingredient-link').each(function() {
        var row = jQuery(this),
            name = row.find('.wprm-ingredient-link-name').text(),
            url = row.find('.wprm-ingredient-link-url').val(),
            nofollow = row.find('.wprm-ingredient-link-nofollow').val();
        
        ingredient_links[name] = {
            url: url,
            nofollow: nofollow
        };
    });

    // Empty and refill form from current ingredients.
    container.html('');

    for(var i=0,l=ingredients.length; i<l; i++) {
        var name = ingredients[i],
            known_ingredient = ingredient_links.hasOwnProperty(name),
            url = known_ingredient ? ingredient_links[name].url : '';
            nofollow = known_ingredient ? ingredient_links[name].nofollow : 'default';

        if(!known_ingredient) {
            unknown_ingredients.push(i);
            unknown_ingredients_names.push(name);
        }

        var nofollow_select = '<select id="wprm-ingredient-link-nofollow-' + i + '" class="wprm-ingredient-link-nofollow"><option value="default">Default</option><option value="follow">Don\'t use Nofollow</option><option value="nofollow">Use Nofollow</option></select>';

        var row = '<tr class="wprm-ingredient-link" data-id="' + i + '">';
        row += '<td class="wprm-ingredient-link-name">' + name + '</td>';
        row += '<td><input type="text" id="wprm-ingredient-link-url-' + i + '" class="wprm-ingredient-link-url" value="' + url + '"></td>';
        row += '<td>' + nofollow_select + '</td>';
        row += '</tr>';

        container.append(row);
        jQuery('#wprm-ingredient-link-nofollow-' + i).val(nofollow);
    }

    jQuery('.wprm-ingredient-link-nofollow').select2_wprm({
        width: '100%'
    });

    // Get unknown ingredients.
    if(type == 'global' && unknown_ingredients.length > 0) {
        var data = {
            action: 'wprm_get_ingredient_links',
            security: wprm_admin.nonce,
            ingredients: unknown_ingredients_names
        };

        jQuery('.wprm-recipe-ingredient-links-form-container').hide();
        jQuery('.wprm-ingredient-links-loader').show();
        jQuery.post(wprm_admin.ajax_url, data, function(out) {
            if (out.success) {
                var links = out.data.links;

                for(var i=0,l=unknown_ingredients.length; i<l; i++) {
                    var index = unknown_ingredients[i],
                        name = unknown_ingredients_names[i];

                    if(links.hasOwnProperty(name)) {
                        jQuery('#wprm-ingredient-link-url-' + index).val(links[name].url);
                        jQuery('#wprm-ingredient-link-nofollow-' + index).val(links[name].nofollow).change();
                    }
                }

                jQuery('.wprm-recipe-ingredient-links-form-container').show();
                jQuery('.wprm-ingredient-links-loader').hide();
            }
        }, 'json');
    }
};

const recipeClear = WPRecipeMaker.admin.Recipe.clear;
WPRecipeMaker.admin.Recipe.clear = function() {
    recipeClear.call(WPRecipeMaker.admin.Recipe);

    jQuery('#wprm-ingredient-links-type').val('global').change();
    jQuery('.wprm-ingredient-links-container').html('');
};

const recipeSet = WPRecipeMaker.admin.Recipe.set;
WPRecipeMaker.admin.Recipe.set = function(recipe) {
    recipeSet.call(WPRecipeMaker.admin.Recipe, recipe);

    var links_type = recipe.ingredient_links_type ? recipe.ingredient_links_type : 'global';
    jQuery('#wprm-ingredient-links-type').val(links_type).change();

    // Custom ingredient links.
    if(links_type == 'custom') {
        var ingredients = recipe.ingredients;

        var i, l, group, j, m;
        for (i = 0, l = ingredients.length; i < l; i++) {
            group = ingredients[i];

            for (j = 0, m = group.ingredients.length; j < m; j++) {
                var ingredient = group.ingredients[j];

                if(ingredient.hasOwnProperty('link')) {
                    ingredient_links[ingredient.name] = ingredient.link;
                }
            }
        }
    }
}

const recipeGet = WPRecipeMaker.admin.Recipe.get;
WPRecipeMaker.admin.Recipe.get = function() {
    let recipe = recipeGet.call(WPRecipeMaker.admin.Recipe);

    let link_type = jQuery('#wprm-ingredient-links-type').val();
    recipe.ingredient_links_type = link_type;

    if(link_type == 'global') {
        var links = {};

        jQuery('.wprm-ingredient-link').each(function() {
            var row = jQuery(this),
                name = row.find('.wprm-ingredient-link-name').text(),
                url = row.find('.wprm-ingredient-link-url').val(),
                nofollow = row.find('.wprm-ingredient-link-nofollow').val();
            
            links[name] = {
                url: url,
                nofollow: nofollow
            };
        });

        recipe.global_ingredient_links = links;
    } else if(link_type == 'custom') {
        // Make sure the ingredient links array is up to date.
        set_ingredient_links_from_ingredients();

        // Update ingredients.
        var i, l, j, m;
        for (i = 0, l = recipe.ingredients.length; i < l; i++) {
            for (j = 0, m = recipe.ingredients[i].ingredients.length; j < m; j++) {
                var name = recipe.ingredients[i].ingredients[j].name;

                if(ingredient_links.hasOwnProperty(name)) {
                    recipe.ingredients[i].ingredients[j].link = ingredient_links[name];
                }
            }
        }
    }

    return recipe;
}

jQuery(document).ready(function() {
    jQuery('#wprm-ingredient-links-type').select2_wprm({
        width: '100%'
    });

    jQuery('#wprm-ingredient-links-type').on('change', function() {
        if(jQuery(this).val() == 'global') {
            jQuery('.wprm-ingredient-links-warning').show();
            
            // Reset links when going back to global.
            ingredient_links = {};
            jQuery('.wprm-ingredient-links-container').html('');
            set_ingredient_links_from_ingredients();
        } else {
            jQuery('.wprm-ingredient-links-warning').hide();
        }
    });

    jQuery('.wprm-menu-item[data-tab="recipe-recipe-ingredient-links"]').on('click', function() {
        set_ingredient_links_from_ingredients();
    });
});