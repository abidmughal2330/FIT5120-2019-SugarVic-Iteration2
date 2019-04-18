WPRecipeMaker.admin.Modal.actions.insert_recipe_submission = function(button) {
    var shortcode = '[wprm-recipe-submission]';

    WPRecipeMaker.admin.utils.add_text_to_editor(shortcode);
    WPRecipeMaker.admin.Modal.close();
};
