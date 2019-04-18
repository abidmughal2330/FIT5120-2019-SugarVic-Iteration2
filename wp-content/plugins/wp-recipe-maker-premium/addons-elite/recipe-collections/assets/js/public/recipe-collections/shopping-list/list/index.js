import React, { Component, Fragment } from 'react';

import Ingredients from './Ingredients';
import Api from '../../general/Api';
import Loader from '../../general/Loader';

import  { parseQuantity } from '../../../../../../../../assets/js/shared/quantities';

export default class List extends Component {

    constructor(props) {
        super(props);

        this.state = {
            recipes: {},
            loading: this.loadRecipes(),
        }
    }

    loadRecipes() {
        let recipesMissing = [];

        if ( this.props.collection.items ) {
            const items = Object.values(this.props.collection.items).reduce( (allItems, groupItems) => allItems.concat(groupItems), [] );

            for ( let item of items ) {
                if ( 'recipe' === item.type && item.recipeId && ! recipesMissing.includes( item.recipeId ) ) {
                    recipesMissing.push( item.recipeId );
                }
            }
        }

        if ( 0 < recipesMissing.length ) {
            Api.getIngredients(recipesMissing).then((recipes) => {
                if ( recipes ) {
                    this.setState({
                        recipes,
                        loading: false,
                    });
                }
            });
        }

        return 0 < recipesMissing.length;
    }

    addIngredientsFromRecipe( ingredients, recipe, servings ) {
        for ( let ingredient of recipe.ingredients ) {
            let amount = parseQuantity( ingredient.amount );

            // Recalculate based on servings.
            if ( amount && servings !== recipe.servings ) {
                amount = ( amount / recipe.servings ) * servings;
            }

            // Make sure ingredient exists.
            if ( ! ingredients.hasOwnProperty( ingredient.name ) ) {
                ingredients[ ingredient.name ] = {
                    link: ingredient.hasOwnProperty( 'link' ) ? ingredient.link : false,
                    variations: [],
                };
            }

            // Check if this unit already exists.
            const existingIndex = ingredients[ ingredient.name ].variations.findIndex( (existingIngredient) => existingIngredient.unit === ingredient.unit.trim() );

            // Add to ingredients array.
            if ( -1 < existingIndex && false !== amount && false !== ingredients[ ingredient.name ].variations[ existingIndex ].amount ) {
                ingredients[ ingredient.name ].variations[ existingIndex ].amount += amount;
            } else {
                ingredients[ ingredient.name ].variations.push({
                    amount,
                    unit: ingredient.unit.trim(),
                });
            }
        }

        return ingredients;
    }

    getIngredients() {
        let ingredients = {};
        const items = Object.values(this.props.collection.items).reduce( (allItems, groupItems) => allItems.concat(groupItems), [] );

        for ( let item of items ) {
            if ( item.servings > 0 ) {
                if ( 'recipe' === item.type && this.state.recipes.hasOwnProperty( item.recipeId ) ) {
                    const recipe = this.state.recipes[ item.recipeId ];
                    ingredients = this.addIngredientsFromRecipe( ingredients, recipe, item.servings );
                }
            }
        }

        return ingredients;
    }

    render() {
        return (
            <div className="wprmprc-shopping-list-list">
                <div className="wprmprc-shopping-list-list-header">
                    <div className="wprmprc-shopping-list-list-name">
                        { wprmprc_public.labels.shopping_list_header }
                    </div>
                </div>
                <div className="wprmprc-shopping-list-list-ingredients">
                    {
                        this.state.loading
                        ?
                        <Loader />
                        :
                        <Ingredients
                            ingredients={this.getIngredients()}
                        />
                    }
                </div>
            </div>
        );
    }
}