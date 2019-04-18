import React, { Component, Fragment } from 'react';
import  { formatQuantity } from '../../../../../../../assets/js/shared/quantities';

import '../../../../css/public/nutrition.scss';
import Api from '../general/Api';
import Loader from '../general/Loader';

export default class Nutrition extends Component {
    constructor(props) {
        super(props);

        this.state = {
            loadingNutrition: true,
            nutrition: [],
        }
    }

    componentDidMount() {
        this.checkRecipes();
    }

    componentDidUpdate( prevProps ) {
        if ( JSON.stringify(this.props.items) !== JSON.stringify(prevProps.items) ) {
            this.checkRecipes();
        }
    }

    checkRecipes() {
        let allRecipes = [];
        let recipesWithoutNutrition = [];

        for ( let item of this.props.items ) {
            if ( 'recipe' === item.type ) {
                let recipe = this.props.recipes.hasOwnProperty(item.recipeId) ? this.props.recipes[item.recipeId] : false;

                if ( ! recipe || ! recipe.hasOwnProperty('nutrition') && ! recipesWithoutNutrition.includes( item.recipeId ) ) {
                    recipesWithoutNutrition.push(item.recipeId);
                }

                allRecipes.push(item.recipeId);
            }
        }

        if ( 0 < recipesWithoutNutrition.length ) {
            this.setState({
                loadingNutrition: true,
            }, () => {
                Api.getNutrition(recipesWithoutNutrition).then((recipes) => {
                    if ( recipes ) {
                        this.props.onUpdateRecipes( recipes );
                        this.updateNutrition( allRecipes );
                    }
                });
            });
        } else {
            this.updateNutrition( allRecipes );
        }
    }

    updateNutrition(recipes) {
        let nutritionFields = {};

        for ( let nutritionField of wprmprc_public.settings.recipe_collections_nutrition_facts_fields ) {
            // Default to 0.
            if ( ! nutritionFields.hasOwnProperty( nutritionField ) ) {
                nutritionFields[ nutritionField ] = 0.0;
            }

            // Add values for all recipes.
            for ( let recipeId of recipes ) {
                const recipe = this.props.recipes.hasOwnProperty( recipeId ) ? this.props.recipes[recipeId] : {};
                const recipeNutrition = recipe.hasOwnProperty( 'nutrition' ) ? recipe['nutrition'] : {};

                if ( recipeNutrition.hasOwnProperty( nutritionField ) && recipeNutrition[ nutritionField ] ) {
                    nutritionFields[ nutritionField ] += recipeNutrition[ nutritionField ];
                }
            }
        }

        // Round total values.
        for ( let nutritionField in nutritionFields ) {
            if ( nutritionFields.hasOwnProperty( nutritionField ) ) {
                nutritionFields[nutritionField] = formatQuantity( nutritionFields[nutritionField], wprmprc_public.settings.recipe_collections_nutrition_facts_round_to_decimals );
            }
        }

        this.setState({
            loadingNutrition: false,
            nutrition: nutritionFields,
        })
    }

    render() {
        if ( ! wprmprc_public.settings.recipe_collections_nutrition_facts || 0 === wprmprc_public.settings.recipe_collections_nutrition_facts_fields.length ) {
            return null;
        }

        return (
            <div className="wprmprc-collection-column-nutrition">
                <div className="wprmprc-collection-column-nutrition-header">{ wprmprc_public.labels.nutrition_header }</div>
                <div className="wprmprc-collection-column-nutrition-fields">
                    {
                        this.state.loadingNutrition
                        ?
                        <Loader />
                        :
                        <Fragment>
                            {
                                Object.keys(this.state.nutrition).map((nutritionField) => {
                                    const label = wprmprc_public.labels.nutrition_fields.hasOwnProperty( nutritionField ) ? wprmprc_public.labels.nutrition_fields[ nutritionField ].label : nutritionField;
                                    const value = this.state.nutrition[ nutritionField ];
                                    const unit = wprmprc_public.labels.nutrition_fields.hasOwnProperty( nutritionField ) ? wprmprc_public.labels.nutrition_fields[ nutritionField ].unit : '';

                                    return (
                                        <div className="wprmprc-collection-column-nutrition-field" key={nutritionField}>
                                            <div className="wprmprc-collection-column-nutrition-field-label">{ label }</div>
                                            <div className="wprmprc-collection-column-nutrition-field-value-container">
                                                <span className="wprmprc-collection-column-nutrition-field-value">{ value }</span>
                                                <span className="wprmprc-collection-column-nutrition-field-unit">{ unit }</span>
                                            </div>
                                        </div>
                                    )
                                })
                            }
                        </Fragment>
                    }
                </div>
            </div>
        );
    }
}