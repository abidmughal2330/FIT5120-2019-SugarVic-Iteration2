import React, { Component, Fragment } from 'react';

import '../../../css/admin/modal/recipe.scss';

import Api from '../../shared/Api';

import EditRecipe from './edit';
import NutritionCalculation from './nutrition-calculation';

export default class Recipe extends Component {
    constructor(props) {
        super(props);

        let recipe = JSON.parse( JSON.stringify( wprm_admin_modal.recipe ) );
        let loadingRecipe = false;

        if ( props.args.hasOwnProperty( 'recipe' ) ) {
            recipe = JSON.parse( JSON.stringify( props.args.recipe ) );
        } else if ( props.args.hasOwnProperty( 'recipeId' ) ) {
            loadingRecipe = true;
            Api.getRecipe(props.args.recipeId).then((data) => {
                if ( data ) {
                    const recipe = JSON.parse( JSON.stringify( data.recipe ) );
                    this.setState({
                        recipe,
                        originalRecipe: JSON.parse( JSON.stringify( recipe ) ),
                        loadingRecipe: false,
                        mode: 'nutrition-calculation', // TODO for testing only.
                    });
                }
            });
        }

        this.state = {
            recipe,
            originalRecipe: JSON.parse( JSON.stringify( recipe ) ),
            saveCallback: props.args.hasOwnProperty( 'saveCallback' ) ? props.args.saveCallback : false,
            savingChanges: false,
            loadingRecipe,
            forceRerender: 0,
            mode: 'edit',
        };

        // Bind functions.
        this.onModeChange = this.onModeChange.bind(this);
        this.onRecipeChange = this.onRecipeChange.bind(this);
        this.resetRecipe = this.resetRecipe.bind(this);
        this.saveRecipe = this.saveRecipe.bind(this);
        this.allowCloseModal = this.allowCloseModal.bind(this);
        this.changesMade = this.changesMade.bind(this);
    }

    onModeChange(mode) {
        this.setState({
            mode,
        });
    }

    onRecipeChange(fields) {
        let newRecipe = {
            ...JSON.parse( JSON.stringify( this.state.recipe ) ),
            ...fields,
        }

        this.setState({
            recipe: newRecipe,
        });
    }

    resetRecipe() {
        if ( this.changesMade() ) {
            this.setState({
                recipe: JSON.parse( JSON.stringify( this.state.originalRecipe ) ),
                forceRerender: this.state.forceRerender + 1,
            });
        }
    }

    saveRecipe() {

    }

    allowCloseModal() {
        return ! this.state.savingChanges && ( ! this.changesMade() || confirm( 'Are you sure you want to close without saving changes?' ) );
    }

    changesMade() {
        return JSON.stringify( this.state.recipe ) !== JSON.stringify( this.state.originalRecipe );
    }

    render() {
        // console.log(this.state.recipe);

        switch ( this.state.mode ) {
            case 'nutrition-calculation':
                return (
                    <NutritionCalculation
                        onCloseModal={ this.props.maybeCloseModal }
                        changesMade={ this.changesMade() }
                        savingChanges={ this.state.savingChanges }
                        loadingRecipe={ this.state.loadingRecipe }
                        recipe={ this.state.recipe }
                        onNutritionChange={ (nutrition) => {
                            this.onRecipeChange({
                                nutrition,
                            });
                        }}
                    />
                );
            default:
                return (
                    <EditRecipe
                        onCloseModal={ this.props.maybeCloseModal }
                        changesMade={ this.changesMade() }
                        savingChanges={ this.state.savingChanges }
                        loadingRecipe={ this.state.loadingRecipe }
                        recipe={ this.state.recipe }
                        onRecipeChange={ this.onRecipeChange }
                        resetRecipe={ this.resetRecipe }
                        saveRecipe={ this.saveRecipe }
                        forceRerender={ this.state.forceRerender }
                    />
                );
        }
    }
}