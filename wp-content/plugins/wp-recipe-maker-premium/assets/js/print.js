if (!global._babelPolyfill) { require('babel-polyfill'); }
import { set_print_servings } from './public/servings-changer';
import { set_print_system } from '../../addons-pro/unit-conversion/assets/js/public/unit-conversion';

// Remove ingredient links.
jQuery(document).ready(function() {
    jQuery('.wprm-recipe-ingredient-name a').contents().unwrap();
});

export { set_print_servings, set_print_system };
