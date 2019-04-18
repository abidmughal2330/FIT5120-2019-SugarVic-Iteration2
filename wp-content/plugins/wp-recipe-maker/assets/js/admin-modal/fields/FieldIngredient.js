import React from 'react';
import { Draggable } from 'react-beautiful-dnd';
import { isKeyHotkey } from 'is-hotkey';

const isTabHotkey = isKeyHotkey('tab');

import Icon from '../../shared/Icon';

import FieldRichText from './FieldRichText';
 
const FieldIngredient = (props) => {
    const handle = (provided) => (
        <div
            className="wprm-admin-modal-field-ingredient-handle"
            {...provided.dragHandleProps}
            tabIndex="-1"
        ><Icon type="drag" /></div>
    );

    const group = (provided) => (
        <div
            className="wprm-admin-modal-field-ingredient-group"
            ref={provided.innerRef}
            {...provided.draggableProps}
        >
            { handle(provided) }
            <div className="wprm-admin-modal-field-ingredient-group-name-container">
                <FieldRichText
                    singleLine
                    className="wprm-admin-modal-field-ingredient-group-name"
                    toolbar="no-styling"
                    value={ props.name }
                    placeholder="Ingredient Group Header"
                    onChange={(value) => props.onChangeName(value)}
                    onKeyDown={(event) => {
                        if ( isTabHotkey(event) ) {
                            props.onTab();
                            event.preventDefault();
                        }
                    }}
                />
            </div>
            <div className="wprm-admin-modal-field-ingredient-after-container">
                <Icon
                    type="trash"
                    onClick={ props.onDelete }
                />
            </div>
        </div>
    );

    const ingredient = (provided) => (
        <div
            className="wprm-admin-modal-field-ingredient"
            ref={provided.innerRef}
            {...provided.draggableProps}
        >
            { handle(provided) }
            <div className="wprm-admin-modal-field-ingredient-text-container">
                <FieldRichText
                    singleLine
                    className="wprm-admin-modal-field-ingredient-amount"
                    value={ props.amount }
                    placeholder="1"
                    onChange={(amount) => props.onChangeIngredient({amount})}
                />
                <FieldRichText
                    singleLine
                    value={ props.unit }
                    placeholder="tbsp"
                    onChange={(unit) => props.onChangeIngredient({unit})}
                />
                <FieldRichText
                    singleLine
                    toolbar="ingredient"
                    value={ props.name }
                    placeholder="olive oil"
                    onChange={(name) => props.onChangeIngredient({name})}
                />
                <FieldRichText
                    singleLine
                    value={ props.notes }
                    placeholder="extra virgin"
                    onChange={(notes) => props.onChangeIngredient({notes})}
                    onKeyDown={(event) => {
                        if ( isTabHotkey(event) ) {
                            props.onTab();
                            event.preventDefault();
                        }
                    }}
                />
            </div>
            <div className="wprm-admin-modal-field-ingredient-after-container">
                <Icon
                    type="trash"
                    onClick={ props.onDelete }
                />
            </div>
        </div>
    );

    return (
        <Draggable
            draggableId={ `ingredient-${props.uid}` }
            index={ props.index }
        >
            {(provided, snapshot) => {
                if ( 'group' === props.type ) {
                    return group(provided);
                } else {
                    return ingredient(provided);
                }
            }}
        </Draggable>
    );
}
export default FieldIngredient;