import '../../css/public/checkboxes.scss';

// Legacy template functionality.
if ('legacy' === wprmp_public.settings.recipe_template_mode && ( wprmp_public.settings.template_ingredient_list_style == 'checkbox' || wprmp_public.settings.template_instruction_list_style == 'checkbox') ) {

    jQuery(document).ready(function($) {
        var list_items = '';
        if (wprmp_public.settings.template_ingredient_list_style == 'checkbox' && wprmp_public.settings.template_instruction_list_style == 'checkbox') {
            list_items = 'li.wprm-recipe-ingredient, li.wprm-recipe-instruction';
        } else {
            list_items = wprmp_public.settings.template_ingredient_list_style == 'checkbox' ? 'li.wprm-recipe-ingredient' : 'li.wprm-recipe-instruction';
        }
        
        // Ingredient checkboxes
        jQuery(list_items).each(function() {
            jQuery(this).addClass('wprm-list-checkbox-container').prepend('<span class="wprm-list-checkbox"></span>')
        });

        jQuery(document).on('click', '.wprm-list-checkbox', function() {
            var checkbox = jQuery(this),
                container = checkbox.parents('.wprm-list-checkbox-container');

            checkbox.toggleClass('wprm-list-checkbox-checked');
            container.toggleClass('wprm-list-checkbox-checked');
        });
    });
}

// Modern template functionality.
if ('modern' === wprmp_public.settings.recipe_template_mode ) {
    jQuery(document).ready(function($) {
        jQuery(document).on('click', '.wprm-checkbox', function() {
            var checkbox = jQuery(this),
                container = checkbox.parents('li');

            container.toggleClass('wprm-checkbox-is-checked');
        });
    });
}