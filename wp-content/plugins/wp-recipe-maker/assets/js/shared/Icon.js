import React from 'react';
import SVG from 'react-inlinesvg';

import '../../css/admin/shared/icon.scss';
import Tooltip from './Tooltip';

import IconAdjustable from '../../icons/admin/adjustable.svg';
import IconBold from '../../icons/admin/bold.svg';
import IconClock from '../../icons/admin/clock.svg';
import IconClose from '../../icons/admin/close.svg';
import IconCheckboxChecked from '../../icons/admin/checkbox-checked.svg';
import IconCheckboxEmpty from '../../icons/admin/checkbox-empty.svg';
import IconDrag from '../../icons/admin/drag.svg';
import IconItalic from '../../icons/admin/italic.svg';
import IconLink from '../../icons/admin/link.svg';
import IconQuestion from '../../icons/admin/question.svg';
import IconSubscript from '../../icons/admin/subscript.svg';
import IconSuperscript from '../../icons/admin/superscript.svg';
import IconTrash from '../../icons/admin/trash.svg';
import IconUnderline from '../../icons/admin/underline.svg';
import IconUnlink from '../../icons/admin/unlink.svg';
 
const icons = {
    adjustable: IconAdjustable,
    bold: IconBold,
    clock: IconClock,
    close: IconClose,
    "checkbox-checked": IconCheckboxChecked,
    "checkbox-empty": IconCheckboxEmpty,
    drag: IconDrag,
    italic: IconItalic,
    link: IconLink,
    question: IconQuestion,
    subscript: IconSubscript,
    superscript: IconSuperscript,
    trash: IconTrash,
    underline: IconUnderline,
    unlink: IconUnlink,
};

const Icon = (props) => {
    let icon = icons.hasOwnProperty(props.type) ? icons[props.type] : false;

    if ( !icon ) {
        return null;
    }

    const className = props.className ? `wprm-admin-icon ${props.className}` : 'wprm-admin-icon';

    return (
        <Tooltip content={props.title}>
            <span
                className={ className }
                onClick={props.onClick}
                title={props.title}
            >
                <SVG
                    src={icon}
                />
            </span>
        </Tooltip>
    );
}
export default Icon;