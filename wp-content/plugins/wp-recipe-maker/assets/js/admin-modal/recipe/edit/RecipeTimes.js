import React, { Fragment } from 'react';

import '../../../../css/admin/modal/fields/times.scss';

import FieldContainer from '../../fields/FieldContainer';
import FieldText from '../../fields/FieldText';
import FieldTime from '../../fields/FieldTime';
 
const RecipeTimes = (props) => {
    const calculatedTotal = parseInt( props.recipe.prep_time ) + parseInt( props.recipe.cook_time ) + parseInt( props.recipe.custom_time );

    return (
        <Fragment>
            <FieldContainer id="prep-time" label="Prep Time">
                <FieldTime
                    value={ props.recipe.prep_time }
                    onChange={ (prep_time) => {
                        props.onRecipeChange( { prep_time } );
                    }}
                />
            </FieldContainer>
            <FieldContainer id="cook-time" label="Cook Time">
                <FieldTime
                    value={ props.recipe.cook_time }
                    onChange={ (cook_time) => {
                        props.onRecipeChange( { cook_time } );
                    }}
                />
            </FieldContainer>
            <FieldContainer id="custom-time-label" label="Custom Time Label">
                <FieldText
                    placeholder="Resting Time"
                    value={ props.recipe.custom_time_label }
                    onChange={ (custom_time_label) => {
                        props.onRecipeChange( { custom_time_label } );
                    }}
                />
            </FieldContainer>
            <FieldContainer id="custom-time" label="Custom Time">
                <FieldTime
                    value={ props.recipe.custom_time }
                    onChange={ (custom_time) => {
                        props.onRecipeChange( { custom_time } );
                    }}
                />
            </FieldContainer>
            <FieldContainer id="total-time" label="Total Time">
                <FieldTime
                    value={ props.recipe.total_time }
                    onChange={ (total_time) => {
                        props.onRecipeChange( { total_time } );
                    }}
                />
                {
                    calculatedTotal !== parseInt( props.recipe.total_time )
                    &&
                    <div>
                        <a
                            href="#"
                            onClick={(e) => {
                                e.preventDefault();
                                props.onRecipeChange({
                                    total_time: calculatedTotal,
                                });
                            }}
                        >Recalculate Total Time</a>
                    </div>
                }
            </FieldContainer>
        </Fragment>
    );
}
export default RecipeTimes;