import React, { Component } from 'react';
import { withRouter } from 'react-router-dom';
import { Draggable } from 'react-beautiful-dnd';

import Icon from '../general/Icon';

import '../../../../css/public/item.scss';

class Item extends Component {
    render() {
        const { item, index } = this.props;

        const clickSetting = 'admin' === this.props.type ? 'parent' : wprmprc_public.settings.recipe_collections_recipe_click;
        let allowClick = this.props.allowClick && 'disabled' !== clickSetting;

        switch ( clickSetting ) {
            case 'disabled':
                allowClick = false;
                break;
            case 'parent':
                if ( ! item.parent_url ) {
                    allowClick = false;
                }
                break;
        }

        return (
            <Draggable
                draggableId={`${item.id}`}
                index={index}
                type='RECIPE'
                isDragDisabled={'saved' === this.props.type}
            >
                {(provided, snapshot) => (
                    <div
                        className={`wprmprc-collection-item wprmprc-collection-item-${wprmprc_public.settings.recipe_collections_recipe_style}`}
                        ref={provided.innerRef}
                        {...provided.draggableProps}
                    >
                        {
                            'saved' !== this.props.type
                            &&
                            <div className="wprmprc-collection-item-actions">
                                <div
                                    className="wprmprc-collection-item-action wprmprc-collection-item-action-order"
                                    style={ this.props.onDeleteItem ? { display: 'none' } : {}}
                                    {...provided.dragHandleProps}
                                ><Icon type="drag" /></div>
                                {
                                    this.props.onDeleteItem
                                    &&
                                    <div
                                        className="wprmprc-collection-item-action wprmprc-collection-item-action-delete"
                                        onClick={() => this.props.onDeleteItem(item.id, index)}
                                    ><Icon type="delete" /></div>
                                }
                            </div>
                        }
                        <div
                            className={`wprmprc-collection-item-details${ allowClick ? ' wprmprc-collection-item-details-allow-click' : ''}`}
                            onClick={(e) => {
                                if ( allowClick ) {
                                    switch ( clickSetting ) {
                                        case 'recipe':
                                            if ( 'inbox' === this.props.type ) {
                                                this.props.history.push(`/collection/inbox/${item.recipeId}`);
                                            } else {
                                                this.props.history.push(`/collection/${this.props.type}/${this.props.collection.id}/${item.recipeId}`);
                                            }
                                            break;
                                        case 'parent':
                                            if ( 'admin' === this.props.type || e.metaKey ) {
                                                window.open( item.parent_url );
                                            } else {
                                                location.href = item.parent_url;
                                            }
                                            break;
                                    }
                                }
                            }}
                        >
                            <div className="wprmprc-collection-item-name">{item.name}</div>
                            {
                                item.image
                                &&
                                <div className="wprmprc-collection-item-image">
                                    <img src={item.image} />
                                </div>
                            }
                        </div>
                    </div>
                )}
            </Draggable>
        );
    }
}

export default withRouter(Item);