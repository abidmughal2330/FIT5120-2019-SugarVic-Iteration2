import React, { Component, Fragment } from 'react';
import { withRouter } from 'react-router-dom';
import { DragDropContext } from 'react-beautiful-dnd';

import Loader from '../general/Loader';
import Actions from './Actions';
import Group from './Group';
import Nutrition from './Nutrition';
import '../../../../css/public/collection.scss';

class Collection extends Component {
    constructor(props) {
        super(props);

        const showNutrition = this.props.collection.showNutrition ? this.props.collection.showNutrition : wprmprc_public.settings.recipe_collections_nutrition_facts_hidden_default;
        this.state = {
            addItems: [],
            mode: 'overview',
            showNutrition, // Only used when displaying saved nutrition.
        }
    }
    
    onDragEnd(result) {
        if ( result.destination && 'RECIPE' === result.type ) {
            let items = JSON.parse(JSON.stringify(this.props.collection.items));
            const [ destinationColumn, destinationGroup ] = result.destination.droppableId.split('-');
            const destination = `${this.props.collection.columns[destinationColumn].id}-${this.props.collection.groups[destinationGroup].id}`;
            const destinationIndex = result.destination.index;

            // Make sure items is an object and destination exists.
            if ( Object !== items.constructor ) {
                items = {};
            }
            if ( ! items.hasOwnProperty(destination) ) {
                items[destination] = [];
            }

            let item;

            if ( 'select-items' === result.source.droppableId ) {
                // Adding item.
                // Get maxId in use.
                const allItems = Object.values(items).reduce( (allItems, groupItems) => allItems.concat(groupItems), [] );
                let maxId = Math.max.apply( Math, allItems.map( function(item) { return item.id; } ) );
                maxId = maxId < 0 ? -1 : maxId;

                const itemId = parseInt(result.draggableId.substr(7));
                item = { ...this.state.addItems.find((item) => itemId === item.id) };

                // Give item a unique ID.
                item.id = maxId + 1;
            } else {
                const [ sourceColumn, sourceGroup ] = result.source.droppableId.split('-');
                const source = `${this.props.collection.columns[sourceColumn].id}-${this.props.collection.groups[sourceGroup].id}`;
                const sourceIndex = result.source.index;

                // Remove item from source.
                item = items[source].splice(sourceIndex, 1)[0];
            }

            // Add item to destination.
            items[destination].splice(destinationIndex, 0, item);

            // Update collection.
            this.props.onChangeCollection(this.props.type, this.props.collection.id, { items } );
        }
    }

    onChangeAddItems(items) {
        // Clean up items and add index.
        items = items.map( (item, index) => {
            item.id = index;
            item.servings = 0 < parseInt( item.servings ) ? parseInt( item.servings ) : 1;

            if ( 'recipe' === item.type ) {
                item.recipeId = parseInt( item.recipeId );
            }

            return item;
        } );

        this.setState({
            addItems: items,
        })
    }

    onDeleteItem(columnId, groupId, index) {
        let items = { ...this.props.collection.items };
        items[`${columnId}-${groupId}`].splice(index, 1);

        this.props.onChangeCollection(this.props.type, this.props.collection.id, { items } );
    }

    render() {
        const { collection } = this.props;
        const isLoading = ! collection.columns || ! collection.groups;

        // Use value from collection unless displaying a saved collection in frontend.
        let showNutrition = collection.hasOwnProperty( 'showNutrition' ) ? collection.showNutrition : wprmprc_public.settings.recipe_collections_nutrition_facts_hidden_default;
        if ( 'saved' === this.props.type ) {
            showNutrition = this.state.showNutrition;
        }

        return (
            <Fragment>
                {
                    'admin' !== this.props.type
                    &&
                    <div className="wprmprc-container-header">
                        {
                            'saved' !== this.props.type
                            &&
                            <Fragment>
                                <span className="wprmprc-header-link"
                                    onClick={() => {
                                        this.props.history.push(`/`);
                                    }}
                                >{ wprmprc_public.labels.overview_header }</span>
                                <span className="wprmprc-header-link-separator">&gt;</span>
                            </Fragment>
                        }
                        {collection.name}
                    </div>
                }
                {
                    isLoading
                    ?
                    <Loader />
                    :
                    <div className="wprmprc-collection">
                        <DragDropContext
                            onDragEnd={this.onDragEnd.bind(this)}
                        >
                            {
                                collection.columns.map( (column, columnIndex) => {
                                    let itemsInColumn = [];

                                    return (
                                        <div className="wprmprc-collection-column" key={columnIndex}>
                                            {
                                                '' !== column.name
                                                &&
                                                <div className="wprmprc-collection-column-header">
                                                    <div className="wprmprc-collection-column-name">
                                                        {column.name}
                                                    </div>
                                                </div>
                                            }
                                            <div className="wprmprc-collection-column-groups">
                                                {
                                                    collection.groups.map( (group, groupIndex) => {
                                                        const groupItems = collection.items[`${column.id}-${group.id}`] ? collection.items[`${column.id}-${group.id}`] : [];
                                                        itemsInColumn = [
                                                            ...itemsInColumn,
                                                            ...groupItems,
                                                        ];

                                                        const onDeleteItem = 'remove-items' === this.state.mode ? (id, index) => {
                                                            this.onDeleteItem(column.id, group.id, index);
                                                        } : false;

                                                        return (
                                                            <Group
                                                                type={this.props.type}
                                                                collection={collection}
                                                                group={group}
                                                                items={groupItems}
                                                                onDeleteItem={onDeleteItem}
                                                                index={`${columnIndex}-${groupIndex}`}
                                                                key={`${columnIndex}-${groupIndex}`}
                                                            />
                                                        )
                                                    })
                                                }
                                            </div>
                                            {
                                                showNutrition
                                                &&
                                                <Nutrition
                                                    items={itemsInColumn}
                                                    recipes={this.props.recipes}
                                                    onUpdateRecipes={this.props.onUpdateRecipes}
                                                />
                                            }
                                        </div>
                                    )
                                } )
                            }
                            <Actions
                                collections={this.props.collections}
                                collection={collection}
                                type={this.props.type}
                                mode={this.state.mode}
                                columns={collection.columns}
                                groups={collection.groups}
                                addItems={this.state.addItems}
                                onChangeColumns={(columns) => this.props.onChangeCollection(this.props.type, collection.id, { columns })}
                                onChangeGroups={(groups) => this.props.onChangeCollection(this.props.type, collection.id, { groups })}
                                onChangeAddItems={this.onChangeAddItems.bind(this)}
                                onChangeMode={(mode) => {
                                    this.setState({
                                        mode
                                    });
                                }}
                                showNutrition={showNutrition}
                                onChangeShowNutrition={(showNutrition) => {
                                    // Store value in collection unless displaying a saved collection in frontend.
                                    if ( 'saved' === this.props.type ) {
                                        this.setState({
                                            showNutrition,
                                        });
                                    } else {
                                        this.props.onChangeCollection(this.props.type, this.props.collection.id, { showNutrition } );
                                    }
                                }}
                            />
                        </DragDropContext>
                        <div className="wprmprc-collection-column-balancer"></div>
                        <div className="wprmprc-collection-column-balancer"></div>
                        <div className="wprmprc-collection-column-balancer"></div>
                        <div className="wprmprc-collection-column-balancer"></div>
                        <div className="wprmprc-collection-column-balancer"></div>
                        <div className="wprmprc-collection-column-balancer"></div>
                        <div className="wprmprc-collection-column-balancer"></div>
                    </div>
                }
            </Fragment>
        );
    }
}

export default withRouter(Collection);