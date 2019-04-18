import React, { Component, Fragment } from 'react';
import { withRouter } from 'react-router-dom';

import Api from '../general/Api';
import Loader from '../general/Loader';
import AddToCollection from '../../add-to-collection';

import '../../../../css/public/recipe.scss';
class Recipe extends Component {

    constructor(props) {
        super(props);

        if ( ! props.recipe.hasOwnProperty('html') || ! props.recipe.html ) {
            this.getRecipeHtml(props.recipe.id);
        }
    }

    componentDidMount() {
        AddToCollection.checkInbox(this.props.recipe.id);
    }

    componentDidUpdate() {
        AddToCollection.checkInbox(this.props.recipe.id);
    }

    getRecipeHtml(recipeId) {
        Api.getRecipe(recipeId).then((recipe) => {
            let recipes = {}
            recipes[recipeId] = recipe;

            this.props.onUpdateRecipes(recipes);
        });
    }

    render() {
        const { type, collection, recipe } = this.props;

        return (
            <Fragment>
                <div className="wprmprc-container-header">
                    <span className="wprmprc-header-link"
                        onClick={() => {
                            if ( 'inbox' === type ) {
                                this.props.history.push(`/collection/inbox/`);
                            } else {
                                this.props.history.push(`/collection/${type}/${collection.id}`);
                            }
                        }}
                    >{ collection.name }</span>
                    <span className="wprmprc-header-link-separator">&gt;</span>
                    Recipe
                </div>
                <div className="wprmprc-recipe">
                    {
                        recipe.html
                        ?
                        <div dangerouslySetInnerHTML={{__html: recipe.html}} />
                        :
                        <Loader />
                    }
                </div>
            </Fragment>
        );
    }
}

export default withRouter(Recipe);