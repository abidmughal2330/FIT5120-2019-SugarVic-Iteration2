import React, { Component, Fragment } from 'react';
import { DragDropContext, Droppable, Draggable } from 'react-beautiful-dnd';

import Icon from '../general/Icon';

import '../../../../css/public/edit-list.scss';

export default class EditList extends Component {
    constructor(props) {
        super(props);

        this.lastItem = React.createRef();

        this.state = {
            editing: props.editing ? props.editing : false,
        }
    }

    componentDidUpdate( prevProps ) {
        if ( this.state.editing ) {
            if ( this.props.items.length > prevProps.items.length ) {
                const inputs = this.lastItem.current.getElementsByTagName('input');

                if ( inputs.length ) {
                    inputs[0].focus();
                }
            }
        }
    }

    onDragEnd(result) {
        if ( this.props.type.toUpperCase() === result.type && result.destination ) {
            this.props.onReorder( result.source.index, result.destination.index );
        }
    }

    render() {
        return (
            <DragDropContext
                onDragEnd={this.onDragEnd.bind(this)}
            >
                <div
                    className="wprmprc-edit-list"
                    ref={this.listContainer}
                >
                    {
                        undefined !== this.props.header
                        &&
                        <div className='wprmprc-edit-list-item-container wprmprc-edit-list-item-header'>
                            <div className={`wprmprc-edit-list-item${ this.state.editing ? ' wprmprc-edit-list-item-edit': ''}`}>
                                { this.props.header(this.state.editing) }
                            </div>
                        </div>
                    }
                    <Droppable
                        droppableId={`edit-list-${this.props.type}`}
                        type={this.props.type.toUpperCase()}
                    >
                        {(provided, snapshot) => (
                            <div
                                className={`wprmprc-edit-list-items${ snapshot.isDraggingOver ? ' wprmprc-edit-list-items-draggingover' : ''}`}
                                ref={provided.innerRef}
                                {...provided.droppableProps}
                            >
                                {
                                    this.props.items.map((item, index) =>
                                        <Draggable
                                            draggableId={`${item.id}`}
                                            index={index}
                                            key={index}
                                            type={this.props.type.toUpperCase()}
                                            isDragDisabled={this.state.editing}
                                        >
                                            {(provided, snapshot) => (
                                                <div
                                                    className="wprmprc-edit-list-item-container"
                                                    ref={provided.innerRef}
                                                    {...provided.draggableProps}
                                                >
                                                    {
                                                        this.state.editing
                                                        ?
                                                        <div
                                                            className="wprmprc-edit-list-item-delete"
                                                            onClick={() => {
                                                                if(confirm(`${wprmprc_public.labels.confirm_delete} "${item.name}"?`)) {
                                                                    this.props.onDelete(item.id, index);
                                                                }
                                                            }}
                                                        ><Icon type="delete" /></div>
                                                        :
                                                        <div
                                                            className="wprmprc-edit-list-item-handle"
                                                            {...provided.dragHandleProps}
                                                        ><Icon type="drag" /></div>
                                                    }
                                                    <div
                                                        className={`wprmprc-edit-list-item${ this.state.editing ? ' wprmprc-edit-list-item-edit': ''}`}
                                                        ref={index === this.props.items.length - 1 ? this.lastItem : null }
                                                    >
                                                        { this.props.item(this.state.editing, item, index) }
                                                    </div>
                                                </div>
                                            )}
                                        </Draggable>
                                    )
                                }
                                {provided.placeholder}
                            </div>
                        )}
                    </Droppable>
                    <div className="wprmprc-edit-list-actions">
                        {
                            this.state.editing
                            ?
                            <Fragment>
                                <span
                                    className="wprmprc-edit-list-action"
                                    onClick={() => this.setState({ editing: false }) }
                                >{ wprmprc_public.labels.confirm_stop }</span> - <span
                                    className="wprmprc-edit-list-action"
                                    onClick={() => this.props.onAdd()}
                                >{this.props.labels.add}</span>
                            </Fragment>
                            :
                            <span
                                className="wprmprc-edit-list-action"
                                onClick={() => this.setState({ editing: true }) }
                            >{this.props.labels.edit}</span>
                        }
                    </div>
                </div>
            </DragDropContext>
        );
    }
}
