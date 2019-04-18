import 'ezdz';
import 'ezdz/src/jquery.ezdz.css';

import '../../css/public/form.scss';
import '../../css/public/blocks.scss';

jQuery('.wprmprs-layout-block-recipe_image').each(function() {
    var input = jQuery(this).find('input');
    var placeholder = input.data('placeholder');

    input.ezdz({
        text: placeholder,
        validators: {
            maxNumber: 1
        }
    });
});