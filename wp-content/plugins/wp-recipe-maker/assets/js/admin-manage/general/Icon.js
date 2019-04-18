import React from 'react';
import SVG from 'react-inlinesvg';

import IconEdit from '../../../icons/manage/edit.svg';
import IconDelete from '../../../icons/manage/delete.svg';
 
const icons = {
    edit: IconEdit,
    delete: IconDelete,
};

const Icon = (props) => {
    let icon = icons.hasOwnProperty(props.type) ? icons[props.type] : false;

    if ( !icon ) {
        return null;
    }

    return (
        <span
            className='wprm-manage-icon'
            onClick={props.onClick}
        >
            <SVG
                src={icon}
                
            />
        </span>
    );
}
export default Icon;