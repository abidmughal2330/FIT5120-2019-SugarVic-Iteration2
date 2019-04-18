export function get_active_system(recipe) {
    var system = 1,
        link = recipe.find('.wprm-unit-conversion.wprmpuc-active'),
        dropdown = recipe.find('.wprm-unit-conversion-dropdown');

    if ( link.length > 0 ) {
        system = parseInt(link.data('system'));
    } else if ( dropdown.length > 0 ) {
        system = parseInt(dropdown.val());
    }

    return system;
};