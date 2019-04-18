import React from 'react';

import FieldText from '../../fields/FieldText';
import FieldDropdown from '../../fields/FieldDropdown';

const StepSource = (props) => {
    return (
        <table className="wprm-admin-modal-recipe-nutrition-calculation-source">
            <thead>
            <tr>
                <th>Used in Recipe</th>
                <th>Used for Calculation</th>
                <th>Nutrition Source</th>
                <th>API Match</th>
            </tr>
            </thead>
            <tbody>
            {
                props.ingredients.map( ( ingredient, index ) => {
                    let apiMatchFound = false;

                    console.log(ingredient);
                    if ( ingredient.nutrition.match && 'api' === ingredient.nutrition.source ) {
                        apiMatchFound = true;
                    }

                    return (
                        <tr key={ index }>
                            <td>{ `${ ingredient.amount} ${ ingredient.unit }` }</td>
                            <td>
                                <FieldText
                                    type="number"
                                    value={ ingredient.nutrition.amount }
                                    onChange={ (amount) => {
                                        props.onIngredientChange( index, { amount } );
                                    }}
                                />
                                <FieldText
                                    value={ ingredient.nutrition.unit }
                                    onChange={ (unit) => {
                                        props.onIngredientChange( index, { unit } );
                                    }}
                                />
                                { ingredient.name } { ingredient.notes ? ` (${ingredient.notes})` : '' }
                            </td>
                            <td>
                                <FieldDropdown
                                    options={[
                                        {
                                            value: 'api',
                                            label: 'API',
                                        },
                                        {
                                            value: 'custom',
                                            label: 'Saved/Custom',
                                        }
                                    ]}
                                    value={ ingredient.nutrition.source }
                                    onChange={ (source) => {
                                        props.onIngredientChange( index, { source } );
                                    }}
                                    width={ 150 }
                                />
                            </td>
                            <td>
                                {
                                    'api' === ingredient.nutrition.source
                                    &&
                                    <a
                                        href="#"
                                        onClick={(e) => {
                                            e.preventDefault();

                                            props.onStepChange( 'match', {
                                                index,
                                            } );
                                        }}
                                        className={ apiMatchFound ? '' : 'wprm-admin-modal-recipe-nutrition-calculation-source-no-match' }
                                    >
                                    {
                                        apiMatchFound
                                        ?
                                        `${ingredient.nutrition.match.name}${ ingredient.nutrition.match.aisle ? ` (${ ingredient.nutrition.match.aisle.toLowerCase() })` : ''}`
                                        :
                                        "no match found"
                                    }
                                    </a>
                                }
                            </td>
                        </tr>
                    )
                })
            }
            </tbody>
        </table>
    );
}
export default StepSource;