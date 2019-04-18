import React, { Component } from 'react';
import { Droppable } from 'react-beautiful-dnd';

import Item from '../../Item';

export default class AddItems extends Component {
    render() {
        return (
            <Droppable
                droppableId={`select-items`}
                type='RECIPE'
                isDropDisabled={true}
            >
                {(provided, snapshot) => (
                    <div
                        className='wprmprc-collection-action-select-items'
                        ref={provided.innerRef}
                        {...provided.droppableProps}
                    >
                        {
                            0 < this.props.addItems.length
                            && <div style={{padding: '0 5px 5px 5px', fontStyle: 'italic', fontSize: '0.8em'}}>{ wprmprc_public.labels.collection_add_item_drag_drop }</div>
                        }
                        {
                            this.props.addItems.map( (item, index) => {
                                return (
                                    <Item
                                        type={this.props.type}
                                        collection={this.props.collection}
                                        item={{
                                            ...item,
                                            id: `select-${item.id}`,
                                        }}
                                        index={index}
                                        key={index}
                                    />
                                )
                            })
                        }
                    </div>
                )}
            </Droppable>
        );
    }
}
