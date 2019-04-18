import React, { Component } from 'react';
import { Switch, Route, Redirect } from 'react-router-dom';

import Api from './general/Api';
import Collection from './collection';
import Overview from './overview';
import Recipe from './recipe';
import ShoppingList from './shopping-list';

import '../../../css/public/app.scss';

export default class App extends Component {

    constructor(props) {
        super(props);

        this.state = {
            collections: Api.getCollections(),
            recipes: {},
        } 
    }

    componentDidUpdate( prevProps, prevState ) {
        if ( JSON.stringify(this.state.collections) !== JSON.stringify(prevState.collections) ) {
            Api.saveCollections(this.state.collections);
        }
    }

    cleanUpCollection( collection ) {
        let columnsGroups = [];
        let nbrItems = 0;

        // Find all existing column-group combinations.
        for ( let column of collection.columns ) {
            for ( let group of collection.groups ) {
                columnsGroups.push(`${column.id}-${group.id}`);
            }
        }

        for ( let columnGroup of Object.keys( collection.items ) ) {            
            if ( ! columnsGroups.includes( columnGroup ) ) {
                delete collection.items[ columnGroup ];
            } else {
                nbrItems += collection.items[ columnGroup ].length;
            }
        }

        collection.nbrItems = nbrItems;

        // Make sure 1 group always exists.
        if ( 0 === collection.groups.length ) {
            collection.groups = [{
                id: 0,
                name: '',
            }]
        }

        return { ...collection };
    }

    onChangeCollection( type, id, newCollection ) {
        if ( 'inbox' === type ) {
            const inbox = this.cleanUpCollection({ ...this.state.collections.inbox, ...newCollection });

            this.setState({
                collections: {
                    ...this.state.collections,
                    inbox,
                }
            });
        } else if ( 'user' === type ) {
            const index = this.state.collections[type].findIndex((collection) => id === collection.id);

            if ( -1 !== index ) {
                let userCollections = [ ...this.state.collections.user ];
                userCollections[index] = this.cleanUpCollection({
                    ...userCollections[index],
                    ...newCollection,
                });

                this.setState({
                    collections: {
                        ...this.state.collections,
                        user: userCollections,
                    }
                });
            }
        }
    }

    onAddCollection( type ) {
        if ( 'user' === type ) {
            let userCollections = [ ...this.state.collections.user ];
            let maxId = Math.max.apply( Math, userCollections.map( function(collection) { return collection.id; } ) );
            maxId = maxId < 0 ? -1 : maxId;

            userCollections.push({
                id: maxId + 1,
                name: '',
                nbrItems: 0,
                columns: [ { id: 0, name: wprmprc_public.labels.collection_default_column } ],
                groups: [ { id: 0, name: '' } ],
                items: {},
            });

            this.setState({
                collections: {
                    ...this.state.collections,
                    user: userCollections,
                }
            });
        }
    }

    onDeleteCollection( type, id ) {
        if ( 'user' === type ) {
            const index = this.state.collections[type].findIndex((collection) => id === collection.id);

            if ( -1 !== index ) {
                let userCollections = [ ...this.state.collections.user ];
                userCollections.splice(index, 1);

                this.setState({
                    collections: {
                        ...this.state.collections,
                        user: userCollections,
                    }
                });
            }
        }
    }

    onReorderCollection( type, oldIndex, newIndex ) {
        if ( 'user' === type ) {
            let userCollections = [ ...this.state.collections.user ];

            const collection = userCollections.splice(oldIndex, 1)[0];
            userCollections.splice(newIndex, 0, collection);

            this.setState({
                collections: {
                    ...this.state.collections,
                    user: userCollections,
                }
            });
        }
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
        const getCollection = ( type, collectionId ) => {
            let collection = false;
            if ( this.state.collections.hasOwnProperty(type) ) {
                if ( 'inbox' === type ) {
                    collection = this.state.collections.inbox;
                } else {
                    collection = this.state.collections[type].find((collection) => collectionId === collection.id);
                }
            }

            return collection;
        }

        const collectionRoute = ( type, collectionId ) => {
            const collection = getCollection( type, collectionId );

            if ( collection ) {
                return (
                    <Collection
                        collections={this.state.collections}
                        type={type}
                        collection={collection}
                        onChangeCollection={this.onChangeCollection.bind(this)}
                        recipes={this.state.recipes}
                        onUpdateRecipes={this.onUpdateRecipes.bind(this)}
                    />
                )
            } else {
                return (
                    <Redirect to='/' />
                )
            }
        }

        const recipeRoute = ( type, collectionId, recipeId ) => {
            const collection = getCollection( type, collectionId );

            if ( collection && 'recipe' === wprmprc_public.settings.recipe_collections_recipe_click ) {
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
                            collection={collection}
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
        }

        const shoppingListRoute = ( type, collectionId ) => {
            const collection = getCollection( type, collectionId );

            if ( collection && wprmprc_public.settings.recipe_collections_shopping_list ) {
                return (
                    <ShoppingList
                        collections={this.state.collections}
                        type={type}
                        collection={collection}
                        onChangeCollection={this.onChangeCollection.bind(this)}
                    />
                )
            } else {
                return (
                    <Redirect to='/' />
                )
            }
        }

        return (
            <Switch>
                <Route path="/collection/inbox/:recipeId" render={(props) => {
                    const { recipeId } = props.match.params;
                    return recipeRoute( 'inbox', null, parseInt( recipeId ) );
                }} />
                <Route path="/collection/inbox" render={() => {
                    return collectionRoute( 'inbox', null );
                }} />
                <Route path="/collection/:type/:collectionId/:recipeId" render={(props) => {
                    const { type, collectionId, recipeId } = props.match.params;
                    return recipeRoute( type, parseInt( collectionId ), parseInt( recipeId ) );
                }} />
                <Route path="/collection/:type/:collectionId" render={(props) => {
                    const { type, collectionId } = props.match.params;
                    return collectionRoute( type, parseInt( collectionId ) );
                }} />
                <Route path="/shopping-list/inbox" render={() => {
                    return shoppingListRoute( 'inbox', null );
                }} />
                <Route path="/shopping-list/:type/:collectionId" render={(props) => {
                    const { type, collectionId } = props.match.params;
                    return shoppingListRoute( type, parseInt( collectionId ) );
                }} />
                <Route render={() =>
                    <Overview
                        collections={this.state.collections}
                        onChangeCollection={this.onChangeCollection.bind(this)}
                        onDeleteCollection={this.onDeleteCollection.bind(this)}
                        onAddCollection={this.onAddCollection.bind(this)}
                        onReorderCollection={this.onReorderCollection.bind(this)}
                    />
                } />
            </Switch>
        );
    }
}