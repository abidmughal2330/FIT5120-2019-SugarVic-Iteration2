import React, { Fragment } from 'react';

import '../../../../css/admin/modal/fields/general.scss';

import FieldContainer from '../../fields/FieldContainer';
import FieldDropdown from '../../fields/FieldDropdown';
import FieldText from '../../fields/FieldText';
import FieldRadio from '../../fields/FieldRadio';
import FieldRichText from '../../fields/FieldRichText';
 
const RecipeGeneral = (props) => {
    const author = wprm_admin_modal.options.author.find((option) => option.value === props.recipe.author_display );

    return (
        <Fragment>
            <FieldContainer id="type" label="Recipe Type">
                <FieldRadio
                    id="type"
                    options={[
                        { value: 'food', label: 'Food Recipe' },
                        { value: 'other', label: 'Other (no metadata)' },
                    ]}
                    value={ props.recipe.type }
                    onChange={ (type) => {
                        props.onRecipeChange( { type } );
                    }}
                />
            </FieldContainer>
            <FieldContainer id="name" label="Name">
                <FieldText
                    placeholder="Recipe Name"
                    value={ props.recipe.name }
                    onChange={ (name) => {
                        props.onRecipeChange( { name } );
                    }}
                />
            </FieldContainer>
            <FieldContainer id="summary" label="Summary">
                <FieldRichText
                    placeholder="Short description of this recipe..."
                    value={ props.recipe.summary }
                    onChange={ (summary) => {
                        props.onRecipeChange( { summary } );
                    }}
                />
            </FieldContainer>
            <FieldContainer id="author" label="Author">
                <FieldDropdown
                    options={wprm_admin_modal.options.author}
                    value={ props.recipe.author_display }
                    onChange={ (author_display) => {
                        props.onRecipeChange( { author_display } );
                    }}
                    width={ 300 }
                />
            </FieldContainer>
            {
                author && 'custom' === author.actual
                &&
                <Fragment>
                    <FieldContainer id="author-name" label="Name">
                        <FieldText
                            placeholder="Author Name"
                            value={ props.recipe.author_name }
                            onChange={ (author_name) => {
                                props.onRecipeChange( { author_name } );
                            }}
                        />
                    </FieldContainer>
                    <FieldContainer id="author-link" label="Link">
                        <FieldText
                            placeholder="https://bootstrapped.ventures"
                            type="url"
                            value={ props.recipe.author_link }
                            onChange={ (author_link) => {
                                props.onRecipeChange( { author_link } );
                            }}
                        />
                    </FieldContainer>
                </Fragment>
            }
            <FieldContainer id="servings" label="Servings">
                <FieldText
                    placeholder="4"
                    type="number"
                    value={ props.recipe.servings }
                    onChange={ (servings) => {
                        props.onRecipeChange( { servings } );
                    }}
                />
                <FieldText
                    placeholder="people"
                    value={ props.recipe.servings_unit }
                    onChange={ (servings_unit) => {
                        props.onRecipeChange( { servings_unit } );
                    }}
                />
            </FieldContainer>
        </Fragment>
    );
}
export default RecipeGeneral;