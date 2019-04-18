import React from 'react';
import SVG from 'react-inlinesvg';

import IconCheckboxEmpty from '../../../../icons/checkbox-empty.svg';
import IconCheckboxChecked from '../../../../icons/checkbox-checked.svg';
import IconDelete from '../../../../icons/delete.svg';
import IconDrag from '../../../../icons/drag.svg';
import IconMinus from '../../../../icons/minus.svg';
import IconPlus from '../../../../icons/plus.svg';

import '../../../../css/public/icon.scss';
 
const icons = {
    checkboxEmpty: IconCheckboxEmpty,
    checkboxChecked: IconCheckboxChecked,
    delete: IconDelete,
    drag: IconDrag,
    minus: IconMinus,
    plus: IconPlus,
};

const Icon = (props) => {
    let icon = icons.hasOwnProperty(props.type) ? icons[props.type] : false;

    if ( !icon ) {
        return null;
    }

    return (
        <SVG
            src={icon}
            className='wprmprc-icon'
        />
    );
}
export default Icon;