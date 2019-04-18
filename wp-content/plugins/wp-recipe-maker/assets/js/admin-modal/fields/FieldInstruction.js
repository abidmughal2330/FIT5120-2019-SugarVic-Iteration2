import React from 'react';
import { Draggable } from 'react-beautiful-dnd';
import { isKeyHotkey } from 'is-hotkey';

const isTabHotkey = isKeyHotkey('tab');

import Icon from '../../shared/Icon';

import FieldRichText from './FieldRichText';
import FieldImage from './FieldImage';
 
const FieldInstruction = (props) => {
    const handle = (provided) => (
        <div
            className="wprm-admin-modal-field-instruction-handle"
            {...provided.dragHandleProps}
            tabIndex="-1"
        ><Icon type="drag" /></div>
    );

    const group = (provided) => (
        <div
            className="wprm-admin-modal-field-instruction-group"
            ref={provided.innerRef}
            {...provided.draggableProps}
        >
            <div className="wprm-admin-modal-field-instruction-main-container">
                { handle(provided) }
                <div className="wprm-admin-modal-field-instruction-group-name-container">
                    <FieldRichText
                        singleLine
                        toolbar="no-styling"
                        value={ props.name }
                        placeholder="Instruction Group Header"
                        onChange={(value) => props.onChangeText(value)}
                        onKeyDown={(event) => {
                            if ( isTabHotkey(event) ) {
                                props.onTab();
                                event.preventDefault();
                            }
                        }}
                    />
                </div>
            </div>
            <div className="wprm-admin-modal-field-instruction-after-container">
                <Icon
                    type="trash"
                    onClick={ props.onDelete }
                />
            </div>
        </div>
    );

    const instruction = (provided) => (
        <div
            className="wprm-admin-modal-field-instruction"
            ref={provided.innerRef}
            {...provided.draggableProps}
        >
            <div className="wprm-admin-modal-field-instruction-main-container">
                { handle(provided) }
                <div className="wprm-admin-modal-field-instruction-text-container">
                    <FieldRichText
                        value={ props.text }
                        placeholder="This is one instruction step."
                        onChange={(value) => props.onChangeText(value)}
                        onKeyDown={(event) => {
                            if ( isTabHotkey(event) ) {
                                props.onTab();
                                event.preventDefault();
                            }
                        }}
                    />
                </div>
            </div>
            <div className="wprm-admin-modal-field-instruction-after-container">
                <Icon
                    type="trash"
                    onClick={ props.onDelete }
                />
                <FieldImage
                    id={ props.image }
                    url={ props.image_url }
                    onChange={(id, url) => props.onChangeImage(id, url)}
                    disableTab={ true }
                />
            </div>
        </div>
    );

    return (
        <Draggable
            draggableId={ `instruction-${props.uid}` }
            index={ props.index }
        >
            {(provided, snapshot) => {
                if ( 'group' === props.type ) {
                    return group(provided);
                } else {
                    return instruction(provided);
                }
            }}
        </Draggable>
    );
}
export default FieldInstruction;