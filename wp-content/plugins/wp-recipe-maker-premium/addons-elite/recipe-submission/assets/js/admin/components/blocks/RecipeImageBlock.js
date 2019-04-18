import React, { Component } from 'react';
import shared from './_shared';

const RecipeImageBlock = (props) => {
    return (
        <div className="wprmprs-layout-block-recipe_image">
            { props.block.label && (
                <label className="wprmprs-form-label">{ props.block.label }{ props.block.required && shared.requiredSpan }</label>
            ) }
            { props.block.help && (
                <div className="wprmprs-form-help">{props.block.help}</div>
            ) }
            <div className="ezdz-dropzone">
                <div>{props.block.placeholder}</div>
            </div>
            { props.isEditing && (
            <div className={shared.editClassName} onClick={(e) => shared.onEditClick(e)}>
                <label htmlFor={`edit-block-${props.block.key}-label`}>Label</label>
                <input type="text" id={`edit-block-${props.block.key}-label`} value={props.block.label} onChange={(e) => props.onEdit('label', e.target.value)} />
                <label htmlFor={`edit-block-${props.block.key}-help`}>Help Text</label>
                <input type="text" id={`edit-block-${props.block.key}-help`} value={props.block.help} onChange={(e) => props.onEdit('help', e.target.value)} />
                <label htmlFor={`edit-block-${props.block.key}-placeholder`}>Placeholder</label>
                <input type="text" id={`edit-block-${props.block.key}-placeholder`} value={props.block.placeholder} onChange={(e) => props.onEdit('placeholder', e.target.value)} />
            </div>
            ) }
        </div>
    );
}

export default RecipeImageBlock;