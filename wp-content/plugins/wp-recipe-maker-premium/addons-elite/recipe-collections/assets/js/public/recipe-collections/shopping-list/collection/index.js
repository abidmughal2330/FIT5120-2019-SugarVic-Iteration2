import React, { Component, Fragment } from 'react';

import Item from './Item';

export default class Collection extends Component {
    onChangeServings( columnId, groupId, index, servings ) {
        let items = JSON.parse(JSON.stringify(this.props.collection.items));

        if ( items[`${columnId}-${groupId}`] && items[`${columnId}-${groupId}`][index] && 0 <= servings ) {
            items[`${columnId}-${groupId}`][index].servings = servings;

            this.props.onChangeCollection( this.props.type, this.props.collection.id, { items } );
        }
    }

    render() {
        const { type, collection } = this.props;

        if ( 0 === collection.nbrItems ) {
            return null;
        }

        return (
            <div className="wprmprc-shopping-list-collection">
                <div className="wprmprc-shopping-list-collection-header">
                    <div className="wprmprc-shopping-list-collection-name">
                        { wprmprc_public.labels.shopping_list_collection }
                    </div>
                </div>
                {
                    collection.columns.map( (column, columnIndex) => 
                        <div className="wprmprc-shopping-list-column" key={columnIndex}>
                            {
                                '' !== column.name
                                &&
                                <div className="wprmprc-shopping-list-column-header">
                                    <div className="wprmprc-shopping-list-column-name">
                                        {column.name}
                                    </div>
                                </div>
                            }
                            <div className="wprmprc-shopping-list-column-items">
                                {
                                    collection.groups.map( (group, groupIndex) => {
                                        const groupItems = collection.items[`${column.id}-${group.id}`] ? collection.items[`${column.id}-${group.id}`] : [];

                                        return (
                                            <Fragment key={groupIndex}>
                                                {
                                                    groupItems.map( (item, itemIndex) =>
                                                        <Item
                                                            item={item}
                                                            onChangeServings={(servings) => this.onChangeServings( column.id, group.id, itemIndex, servings )}
                                                            key={itemIndex}
                                                        />
                                                    )
                                                }
                                            </Fragment>
                                        )
                                    })
                                }
                            </div>
                        </div>
                    )
                }
            </div>
        );
    }
}