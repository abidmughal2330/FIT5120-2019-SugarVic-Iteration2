import React, { Component, Fragment } from 'react';

import Ingredient from './Ingredient';

export default class Ingredients extends Component {
    render() {
        const ingredientNames = Object.keys(this.props.ingredients);

        return (
            <Fragment>
                {
                    0 < ingredientNames.length
                    ?
                    ingredientNames.map( ( name, index ) => (
                        <Ingredient
                            name={name}
                            ingredient={this.props.ingredients[ name ]}
                            key={index}
                        />
                    ))
                    :
                    <div className="wprmprc-shopping-list-list-ingredients-none">{ wprmprc_public.labels.shopping_list_empty }</div>
                }
            </Fragment>
        );
    }
}