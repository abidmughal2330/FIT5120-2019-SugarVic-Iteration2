import React from 'react';

import '../../../../css/admin/modal/fields/notes.scss';

import FieldContainer from '../../fields/FieldContainer';
import FieldTinymce from '../../fields/FieldTinymce';
 
const RecipeNotes = (props) => {
    return (
        <FieldContainer label="Recipe Notes">
            <FieldTinymce
                id="recipe-notes"
                value={ props.recipe.notes }
                onChange={ ( notes ) => {
                    props.onRecipeChange( { notes } );
                }}
            />
        </FieldContainer>
    );
}
export default RecipeNotes;