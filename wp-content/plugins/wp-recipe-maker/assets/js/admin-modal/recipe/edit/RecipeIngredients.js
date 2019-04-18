import React, { Component } from 'react';
import { DragDropContext, Droppable } from 'react-beautiful-dnd';

import '../../../../css/admin/modal/fields/ingredients.scss';

import FieldIngredient from '../../fields/FieldIngredient';

export default class RecipeIngredients extends Component {
    constructor(props) {
        super(props);

        this.container = React.createRef();
    }

    componentDidUpdate( prevProps ) {
        if ( this.props.recipe.ingredients_flat.length > prevProps.recipe.ingredients_flat.length ) {
            const inputs = this.container.current.querySelectorAll('.wprm-admin-modal-field-ingredient-group-name, .wprm-admin-modal-field-ingredient-amount');

            if ( inputs.length ) {
                inputs[ inputs.length - 1 ].focus();
            }
        }
    }

    onDragEnd(result) {
        if ( result.destination ) {
            let newFields = JSON.parse( JSON.stringify( this.props.recipe.ingredients_flat ) );
            const sourceIndex = result.source.index;
            const destinationIndex = result.destination.index;

            const field = newFields.splice(sourceIndex, 1)[0];
            newFields.splice(destinationIndex, 0, field);

            this.props.onRecipeChange({
                ingredients_flat: newFields,
            });
        }
    }

    addField(type) {
        let newFields = JSON.parse( JSON.stringify( this.props.recipe.ingredients_flat ) );
        let newField;

        if ( 'group' === type ) {
            newField = {
                type: 'group',
                name: '',
            };
        } else {
            newField = {
                type: 'ingredient',
                amount: '',
                unit: '',
                name: '',
                notes: '',
            }
        }

        // Give unique UID.
        let maxUid = Math.max.apply( Math, newFields.map( function(field) { return field.uid; } ) );
        maxUid = maxUid < 0 ? -1 : maxUid;
        newField.uid = maxUid + 1;

        newFields.push(newField);

        this.props.onRecipeChange({
            ingredients_flat: newFields,
        });
    }
  
    render() {
        return (
            <div
                className="wprm-admin-modal-field-ingredient-container"
                ref={ this.container }
            >
                <DragDropContext
                    onDragEnd={this.onDragEnd.bind(this)}
                >
                    <Droppable
                        droppableId="wprm-ingredients"
                    >
                        {(provided, snapshot) => (
                            <div
                                className={`${ snapshot.isDraggingOver ? ' wprm-admin-modal-field-ingredient-container-draggingover' : ''}`}
                                ref={provided.innerRef}
                                {...provided.droppableProps}
                            >
                                {
                                    this.props.recipe.ingredients_flat.map((field, index) => (
                                        <FieldIngredient
                                            { ...field }
                                            index={ index }
                                            key={ index }
                                            onTab={() => {
                                                // Create new ingredient if we're tabbing in the last one.
                                                if ( index === this.props.recipe.ingredients_flat.length - 1) {
                                                    // Use timeout to fix focus problem (because of preventDefault?).
                                                    setTimeout(() => {
                                                        this.addField( 'ingredient' );
                                                    });
                                                }
                                            }}
                                            onChangeName={ ( name ) => {
                                                let newFields = JSON.parse( JSON.stringify( this.props.recipe.ingredients_flat ) );
                                                newFields[index].name = name;

                                                this.props.onRecipeChange({
                                                    ingredients_flat: newFields,
                                                });
                                            }}
                                            onChangeIngredient={ ( ingredient ) => {
                                                let newFields = JSON.parse( JSON.stringify( this.props.recipe.ingredients_flat ) );

                                                newFields[index] = {
                                                    ...newFields[index],
                                                    ...ingredient,
                                                }
                                                
                                                this.props.onRecipeChange({
                                                    ingredients_flat: newFields,
                                                });
                                            }}
                                            onDelete={() => {
                                                let newFields = JSON.parse( JSON.stringify( this.props.recipe.ingredients_flat ) );
                                                newFields.splice(index, 1);

                                                this.props.onRecipeChange({
                                                    ingredients_flat: newFields,
                                                });
                                            }}
                                        />
                                    ))
                                }
                                {provided.placeholder}
                            </div>
                        )}
                    </Droppable>
                </DragDropContext>
                <div
                    className="wprm-admin-modal-field-ingredient-actions"
                >
                    <button
                        className="button"
                        onClick={() => { this.addField( 'ingredient' ) } }
                    >Add Ingredient</button>
                    <button
                        className="button"
                        onClick={() => { this.addField( 'group' ) } }
                    >Add Ingredient Group</button>
                    <p>Tip: use the TAB key to move from field to field and easily add ingredients.</p>
                </div>
            </div>
        );
    }
}
