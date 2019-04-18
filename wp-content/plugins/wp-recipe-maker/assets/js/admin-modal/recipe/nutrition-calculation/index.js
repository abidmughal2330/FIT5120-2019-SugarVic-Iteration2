import React, { Component, Fragment } from 'react';
import { Element, Link } from 'react-scroll';

import '../../../../css/admin/modal/nutrition-calculation.scss';

import Header from '../../general/Header';
import Footer from '../../general/Footer';

import Api from './Api';
import StepMatch from './StepMatch';
import StepSource from './StepSource';

import  { parseQuantity } from '../../../../../../wp-recipe-maker-premium/assets/js/shared/quantities';
import Loader from '../../../shared/Loader';

export default class NutritionCalculation extends Component {
    constructor(props) {
        super(props);

        // Remove ingredient groups.
        let ingredients = props.recipe.ingredients_flat.filter( ( ingredient ) => ingredient.type === 'ingredient' );

        // Parse quantities.
        ingredients = ingredients.map( ( ingredient ) => {
            ingredient.nutrition = {
                amount: parseQuantity( ingredient.amount ),
                unit: ingredient.unit,
            }

            return ingredient;
        });

        Api.getMatches(ingredients).then((data) => {
            if ( data ) {
                this.setState({
                    ingredients: data.ingredients,
                    calculating: false,
                });
            }
        });

        this.state = {
            step: 'source',
            stepArgs: {},
            ingredients: [],
            calculating: true,
        };

        // Bind functions.
        this.onStepChange = this.onStepChange.bind(this);
        this.onIngredientChange = this.onIngredientChange.bind(this);
        this.nextStep = this.nextStep.bind(this);
    }

    onStepChange(step, stepArgs = {} ) {
        this.setState({
            step,
            stepArgs,
        });
    }

    onIngredientChange(index, nutrition) {
        let ingredients = JSON.parse( JSON.stringify( this.state.ingredients ) );

        ingredients[index].nutrition = {
            ...ingredients[index].nutrition,
            ...nutrition,
        }

        this.setState({
            ingredients,
        });
    }

    nextStep() {

    }

    render() {
        let step = null;
        switch ( this.state.step ) {
            case 'source':
                step = (
                    <StepSource
                        ingredients={ this.state.ingredients }
                        onIngredientChange={ this.onIngredientChange }
                        onStepChange={ this.onStepChange }
                    />
                );
                break;
            case 'match':
                const index = this.state.stepArgs.index;

                step = (
                    <StepMatch
                        ingredient={ this.state.ingredients[ index ] }
                        onMatchChange={ (match) => {
                            this.onIngredientChange( index, {
                                ...match,
                            });
                            this.onStepChange('source');
                        }}
                    />
                );
                break;
        }

        let buttons = null;

        const cancelCalculationButton = (
            <button
                className="button"
                onClick={() => {}}
            >
                Cancel
            </button>
        );

        const previousStepButton = (step) => (
            <button
                className="button"
                onClick={() => {
                    this.onStepChange(step);
                }}
            >
                Cancel
            </button>
        );

        const nextStepButton = (step) => (
            <button
                className="button button-primary"
                onClick={() => {
                    this.onStepChange(step);
                }}
            >
                Next
            </button>
        );

        switch ( this.state.step ) {
            case 'source':
                buttons = (
                    <Fragment>
                        { cancelCalculationButton }
                        { nextStepButton( '') }
                    </Fragment>
                );
                break;
            case 'match':
                buttons = (
                    <Fragment>
                        { previousStepButton( 'source' ) }
                    </Fragment>
                );
                break;
        }

        return (
            <Fragment>
                <Header
                    onCloseModal={ this.props.onCloseModal }
                >
                    Recipe > Nutrition Calculation
                </Header>
                <div className="wprm-admin-modal-recipe-nutrition-calculation">
                    {
                        this.state.calculating
                        ?
                        <Loader />
                        :
                        step
                    }
                </div>
                <Footer
                    savingChanges={ this.state.calculating }
                >
                    { buttons }
                </Footer>
            </Fragment>
        );
    }
}