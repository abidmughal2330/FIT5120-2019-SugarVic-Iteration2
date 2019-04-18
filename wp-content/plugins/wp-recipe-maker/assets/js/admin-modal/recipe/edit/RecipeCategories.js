import React, { Fragment } from 'react';

import FieldContainer from '../../fields/FieldContainer';
import FieldCategory from '../../fields/FieldCategory';
 
const RecipeCategories = (props) => {
    const categories = Object.keys( wprm_admin_modal.categories );

    return (
        <Fragment>
            {
                categories.map((category, index) => {
                    const options = wprm_admin_modal.categories[ category ];
                    const value = props.recipe.tags.hasOwnProperty( category ) ? props.recipe.tags[ category ] : [];

                    return (
                        <FieldContainer id={ category } label={ options.label } key={ index }>
                            <FieldCategory
                                id={ category }
                                value={ value }
                                onChange={ (value) => {
                                    const tags = {
                                        ...props.recipe.tags,
                                    };

                                    tags[ category ] = value;

                                    props.onRecipeChange( { tags } );
                                }}
                                width="450px"
                            />
                        </FieldContainer>
                    )
                })
            }
        </Fragment>
    );
}
export default RecipeCategories;