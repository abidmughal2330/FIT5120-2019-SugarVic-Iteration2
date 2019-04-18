import React, { Fragment } from 'react';

import FieldContainer from '../../fields/FieldContainer';
import FieldText from '../../fields/FieldText';

import '../../../../css/admin/modal/fields/nutrition.scss';
 
const RecipeNutrition = (props) => {
    const serving_size = props.recipe.nutrition.hasOwnProperty('serving_size') ? props.recipe.nutrition['serving_size'] : '';
    const serving_unit = props.recipe.nutrition.hasOwnProperty('serving_unit') ? props.recipe.nutrition['serving_unit'] : '';

    return (
        <Fragment>
            <p>
                These should be the nutrition facts for <strong>1 serving of your recipe</strong>.<br/>
                {
                    props.recipe.servings
                    ?
                    <Fragment>Total servings for this recipe: { `${props.recipe.servings} ${props.recipe.servings_unit}`}</Fragment>
                    :
                    <Fragment>You don't have the servings field set for your recipe under "General".</Fragment>
                }
            </p>
            <div className="wprm-admin-modal-field-nutrition-container">
                <FieldContainer id="nutrition_serving_size" label="Serving Size" help="The weight of 1 serving. Does not affect the calculation.">
                    <FieldText
                        type="number"
                        value={ serving_size }
                        onChange={ (serving_size) => {
                            const nutrition = {
                                ...props.recipe.nutrition,
                                serving_size,
                            };

                            props.onRecipeChange( { nutrition } );
                        }}
                    />
                    <FieldText
                        placeholder="g"
                        value={ serving_unit }
                        onChange={ (serving_unit) => {
                            const nutrition = {
                                ...props.recipe.nutrition,
                                serving_unit,
                            };

                            props.onRecipeChange( { nutrition } );
                        }}
                    />
                </FieldContainer>
                {
                    Object.keys(wprm_admin_modal.nutrition).map((nutrient, index ) => {
                        const options = wprm_admin_modal.nutrition[nutrient];
                        const value = props.recipe.nutrition.hasOwnProperty(nutrient) ? props.recipe.nutrition[nutrient] : '';

                        if ( 'serving_size' === nutrient ) {
                            return null;
                        }

                        return (
                            <FieldContainer id={ `nutrition_${nutrient}` } label={ options.label } key={ index }>
                                <FieldText
                                    type="number"
                                    value={ value }
                                    onChange={ (value) => {
                                        const nutrition = {
                                            ...props.recipe.nutrition,
                                            [nutrient]: value,
                                        };

                                        props.onRecipeChange( { nutrition } );
                                    }}
                                /><span className="wprm-admin-modal-field-nutrition-unit">{ options.unit }</span>
                            </FieldContainer>
                        )
                    })
                }
            </div>
        </Fragment>
    );
}
export default RecipeNutrition;