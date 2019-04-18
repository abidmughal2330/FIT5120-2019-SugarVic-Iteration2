import React, { Fragment } from 'react';
import { Element, Link } from 'react-scroll';

import Header from '../../general/Header';
import Footer from '../../general/Footer';

import Loader from '../../../shared/Loader';

import FieldGroup from '../../fields/FieldGroup';

import RecipeMedia from './RecipeMedia';
import RecipeGeneral from './RecipeGeneral';
import RecipeTimes from './RecipeTimes';
import RecipeCategories from './RecipeCategories';
import RecipeIngredients from './RecipeIngredients';
import RecipeInstructions from './RecipeInstructions';
import RecipeNutrition from './RecipeNutrition';
import RecipeNotes from './RecipeNotes';
 
const EditRecipe = (props) => {
    const structure = [
        { id: 'media', elem: RecipeMedia, name: 'Media' },
        { id: 'general', elem: RecipeGeneral, name: 'General' },
        { id: 'times', elem: RecipeTimes, name: 'Times' },
        { id: 'categories', elem: RecipeCategories, name: 'Categories' },
        { id: 'ingredients', elem: RecipeIngredients, name: 'Ingredients' },
        { id: 'instructions', elem: RecipeInstructions, name: 'Instructions' },
        { id: 'nutrition', elem: RecipeNutrition, name: 'Nutrition' },
        { id: 'notes', elem: RecipeNotes, name: 'Notes' },
    ];

    return (
        <Fragment>
            <Header
                onCloseModal={ props.onCloseModal }
            >
                Recipe
            </Header>
            <div className="wprm-admin-modal-recipe-quicklinks">
                {
                    structure.map((group, index) => (
                        <Link
                            to={ `wprm-admin-modal-fields-group-${ group.id }` }
                            containerId="wprm-admin-modal-recipe-content"
                            className="wprm-admin-modal-recipe-quicklink"
                            activeClass="active"
                            spy={true}
                            offset={-10}
                            smooth={true}
                            duration={400}
                            key={index}
                        >
                            { group.name }
                        </Link>
                    ))
                }
            </div>
            <Element className="wprm-admin-modal-content" id="wprm-admin-modal-recipe-content">
                {
                    props.loadingRecipe
                    ?
                    <Loader/>
                    :
                    <div className="wprm-admin-modal-recipe-fields">
                        {
                            structure.map((group, index) => (
                                <FieldGroup
                                    header={ group.name }
                                    id={ group.id }
                                    key={ 100 * props.forceRerender + index }
                                >
                                    <group.elem
                                        recipe={ props.recipe }
                                        onRecipeChange={ props.onRecipeChange }
                                        onRecipeChange={ props.onRecipeChange }
                                    />
                                </FieldGroup>
                            ))
                        }
                    </div>
                }
            </Element>
            <div id="wprm-admin-modal-toolbar-container"></div>
            <Footer
                savingChanges={ props.savingChanges }
            >
                <button
                    className="button"
                    onClick={ props.resetRecipe }
                    disabled={ ! props.changesMade }
                >
                    Cancel
                </button>
                <button
                    className="button button-primary"
                    onClick={ props.saveRecipe }
                    disabled={ ! props.changesMade }
                >
                    Save
                </button>
            </Footer>
        </Fragment>
    );
}
export default EditRecipe;