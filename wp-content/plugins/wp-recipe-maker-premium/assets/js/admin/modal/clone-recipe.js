WPRecipeMaker.admin.Modal.actions.clone_recipe = function(button) {
	var id = parseInt(jQuery('#wprm-clone-recipe-id').val());
	if(id != 0) {
		var editor = WPRecipeMaker.admin.Modal.active_editor_id;
		let args = {
			clone_recipe_id: id
		};

		if (WPRecipeMaker.admin.Modal.gutenberg ) {
			args.gutenberg = true;
			args.gutenbergCallback = WPRecipeMaker.admin.Modal.args.gutenbergCallback;
		}

		WPRecipeMaker.admin.Modal.close();
		WPRecipeMaker.admin.Modal.open(editor, args);
	}
};