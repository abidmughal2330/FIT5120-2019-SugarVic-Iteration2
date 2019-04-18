import React, { Component } from 'react';
import PropTypes from 'prop-types';

const AvailableBlocks = (props) => {
    const usedBlockTypes = props.usedBlocks.map(block => block.type);

    const buttonMany = (type) => (
        <button onClick={(e) => props.onAddBlock(type)}>{window.wprmprs_layout.defaults[type].name}</button>
    );
    const buttonOne = (type) => (
        <button onClick={(e) => props.onAddBlock(type)} disabled={usedBlockTypes.includes(type)}>{window.wprmprs_layout.defaults[type].name}</button>
    );

    return (
        <div id="wprmprs-layout-available-blocks">
            <h4>General Blocks</h4>
            {buttonMany('header')}
            {buttonMany('paragraph')}
            {buttonOne('submit')}
            <h4>Recipe Fields</h4>
            {buttonOne('recipe_name')}
            {buttonOne('recipe_summary')}
            {buttonOne('recipe_image')}
            {buttonOne('recipe_servings')}
            {buttonOne('recipe_prep_time')}
            {buttonOne('recipe_cook_time')}
            {buttonOne('recipe_total_time')}
            {buttonOne('recipe_courses')}
            {buttonOne('recipe_cuisines')}
            {buttonOne('recipe_ingredients')}
            {buttonOne('recipe_instructions')}
            {buttonOne('recipe_notes')}
            <h4>User Fields</h4>
            {buttonOne('user_name')}
            {buttonOne('user_email')}
        </div>
    );
}

AvailableBlocks.propTypes = {
    usedBlocks: PropTypes.array.isRequired,
    onAddBlock: PropTypes.func.isRequired,
}

export default AvailableBlocks;