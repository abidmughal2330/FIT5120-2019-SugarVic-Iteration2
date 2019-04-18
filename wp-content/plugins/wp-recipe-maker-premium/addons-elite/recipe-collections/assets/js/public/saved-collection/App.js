import React, { Component } from 'react';
import { Switch, Route, Redirect } from 'react-router-dom';

import '../../../css/public/app.scss';

import Collection from '../recipe-collections/collection';
import Recipe from '../recipe-collections/recipe';
import ShoppingList from '../recipe-collections/shopping-list';

export default class App extends Component {

    constructor(props) {
        super(props);

        this.state = {
            collection: JSON.parse(JSON.stringify(wprmprc_public.collection)),
            recipes: {},
        }
    }

    onChangeCollection( type, id, newCollection ) {
        this.setState({
            collection: {
                ...this.state.collection,
                ...newCollection
            }
        });
    }

    onUpdateRecipes( recipes ) {
        let newRecipes = JSON.parse(JSON.stringify(this.state.recipes));

        for ( let recipeId in recipes ) {
            if ( recipes.hasOwnProperty( recipeId ) ) {
                const oldRecipe = newRecipes.hasOwnProperty(recipeId) ? newRecipes[recipeId] : {};
                newRecipes[recipeId] = {
                    ...oldRecipe,
                    ...recipes[recipeId],
                }
            }
        }

        this.setState({
            recipes: newRecipes,
        });
    }

    render() {
        
        return (
            <Switch>
                <Route path="/collection/:type/:collectionId/:recipeId" render={(props) => {
                    const { type, collectionId, recipeId } = props.match.params;

                    if ( 'recipe' === wprmprc_public.settings.recipe_collections_recipe_click ) {
                        let recipe = this.state.recipes.hasOwnProperty(recipeId) ? this.state.recipes[ recipeId ] : {};
                
                        // Make sure ID is passed along.
                        recipe.id = recipeId;
        
                        if ( false === recipe.html ) {
                            return (
                                <Redirect to={`/collection/${type}/${collectionId}`} />
                            );
                        } else {
                            return (
                                <Recipe
                                    type={type}
                                    collection={this.state.collection}
                                    recipe={recipe}
                                    onUpdateRecipes={this.onUpdateRecipes.bind(this)}
                                />
                            );
                        }
                    } else {
                        return (
                            <Redirect to='/' />
                        );
                    }
                }} />
                <Route path="/shopping-list/:type/:collectionId" render={(props) => {
                    const { type, collectionId } = props.match.params;

                    if ( wprmprc_public.settings.recipe_collections_shopping_list ) {
                        return (
                            <ShoppingList
                                collections={false}
                                type={type}
                                collection={this.state.collection}
                                onChangeCollection={this.onChangeCollection.bind(this)}
                            />
                        )
                    } else {
                        return (
                            <Redirect to='/' />
                        )
                    }
                }} />
                <Route render={() =>
                    <Collection
                        collections={false}
                        type={'saved'}
                        collection={this.state.collection}
                        onChangeCollection={this.onChangeCollection.bind(this)}
                        recipes={this.state.recipes}
                        onUpdateRecipes={this.onUpdateRecipes.bind(this)}
                    />
                } />
            </Switch>
        );
    }
}