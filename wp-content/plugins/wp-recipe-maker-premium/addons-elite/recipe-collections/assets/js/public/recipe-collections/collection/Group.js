import React, { Component } from 'react';
import { Droppable } from 'react-beautiful-dnd';

import Item from './Item';

import '../../../../css/public/group.scss';

export default class Group extends Component {
    render() {
        const { index, group, items } = this.props;

        return (
            <div className="wprmprc-collection-group">
                {
                    '' !== group.name
                    &&
                    <div className="wprmprc-collection-group-header">
                        <div className="wprmprc-collection-group-name">
                            {group.name}
                        </div>
                    </div>
                }
                <Droppable
                    droppableId={index}
                    type='RECIPE'
                >
                    {(provided, snapshot) => (
                        <div
                            className={`wprmprc-collection-group-items${ snapshot.isDraggingOver ? ' wprmprc-collection-group-items-draggingover' : ''}`}
                            ref={provided.innerRef}
                            {...provided.droppableProps}
                        >
                            {
                                items.map( (item, itemIndex) =>
                                    <Item
                                        type={this.props.type}
                                        collection={this.props.collection}
                                        item={item}
                                        onDeleteItem={this.props.onDeleteItem}
                                        index={itemIndex}
                                        key={itemIndex}
                                        allowClick={true}
                                    />
                                )
                            }
                            {provided.placeholder}
                        </div>
                    )}
                </Droppable>
            </div>
        );
    }
}
