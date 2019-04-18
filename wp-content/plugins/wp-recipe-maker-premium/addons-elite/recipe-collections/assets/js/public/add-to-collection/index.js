const AddToCollection = {
    checkInbox: (recipeId) => {
        const localCollections = localStorage.getItem( 'wprm-recipe-collection' );

        if ( localCollections ) {
            const collections = JSON.parse(localCollections);
            const matches = collections.inbox.items['0-0'].filter((item) => item.recipeId === recipeId );
            
            if ( matches.length > 0 ) {
                AddToCollection.updateButtons(recipeId);
            }
        }
    },
    updateButtons: (recipeId) => {
        jQuery('.wprm-recipe-add-to-collection').each(function() {
            const button = jQuery(this);
            const buttonRecipeId = button.data('recipe-id');

            if ( buttonRecipeId === recipeId ) {
                if ( button.hasClass( 'wprm-recipe-not-in-collection' ) ) {
                    button.hide();
                } else if ( button.hasClass( 'wprm-recipe-in-collection' ) ) {
                    button.show();
                }
            }
        });
    }
}

export default AddToCollection;

jQuery(document).ready(function($) {
    // Check local storage for recipe if user is not logged in.
    if ( 0 === parseInt( wprmp_public.user ) ) {
        jQuery('.wprm-recipe-add-to-collection.wprm-recipe-not-in-collection').each(function() {
            const recipeId = jQuery(this).data('recipe-id');
            AddToCollection.checkInbox(recipeId);
        });
    }

	jQuery(document).on('click', '.wprm-recipe-add-to-collection.wprm-recipe-not-in-collection', function(e) {
        e.preventDefault();
        const recipeId = jQuery(this).data('recipe-id');

        const servingsContainers = jQuery('.wprm-recipe-servings-' + recipeId);
        let servings = 0 < servingsContainers.length ? servingsContainers.first().data('servings') : false;
        if ( isNaN( servings ) ) {
            servings = false;
        }

        // Add to inbox.
        if ( 0 === parseInt( wprmp_public.user ) ) {
            const localCollections = localStorage.getItem( 'wprm-recipe-collection' );

            let collections;
            if ( localCollections ) {
                collections = JSON.parse(localCollections);
            } else {
                collections = wprmp_public.collections.default;
            }

            const recipe = jQuery(this).data('recipe');

            // Get unique ID for recipe.
            let maxId = Math.max.apply( Math, collections.inbox.items['0-0'].map( function(item) { return item.id; } ) );
            maxId = maxId < 0 ? -1 : maxId;
            recipe.id = maxId + 1;

            if ( false !== servings ) {
                recipe.servings = servings;
            }

            collections.inbox.nbrItems++;
            collections.inbox.items['0-0'].push(recipe)

            localStorage.setItem( 'wprm-recipe-collection', JSON.stringify( collections ) );
        } else {
            fetch(`${wprmp_public.endpoints.collections_helper}/inbox`, {
                method: 'POST',
                headers: {
                    'X-WP-Nonce': wprm_public.api_nonce,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    recipeId,
                    servings,
                }),
            });
        }
        
        // Update all buttons for this recipe.
        AddToCollection.updateButtons(recipeId);
	});
});
