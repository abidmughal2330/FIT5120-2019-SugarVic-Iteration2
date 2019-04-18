import React, { Component } from 'react';

import HeaderBlock from './blocks/HeaderBlock';
import InputBlock from './blocks/InputBlock';
import ParagraphBlock from './blocks/ParagraphBlock';
import RecipeImageBlock from './blocks/RecipeImageBlock';
import SubmitBlock from './blocks/SubmitBlock';
import TextareaBlock from './blocks/TextareaBlock';

const blockComponents = {
    header: HeaderBlock,
    paragraph: ParagraphBlock,
    submit: SubmitBlock,
    recipe_name: InputBlock,
    recipe_summary: TextareaBlock,
    recipe_image: RecipeImageBlock,
    recipe_servings: InputBlock,
    recipe_prep_time: InputBlock,
    recipe_cook_time: InputBlock,
    recipe_total_time: InputBlock,
    recipe_courses: InputBlock,
    recipe_cuisines: InputBlock,
    recipe_ingredients: TextareaBlock,
    recipe_instructions: TextareaBlock,
    recipe_notes: TextareaBlock,
    user_name: InputBlock,
    user_email: InputBlock,
};

export default blockComponents;