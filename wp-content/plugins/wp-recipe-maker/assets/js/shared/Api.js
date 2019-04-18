const recipeEndpoint = wprm_admin.endpoints.recipe;

export default {
    getRecipe(id) {
        return fetch(`${recipeEndpoint}/${id}`, {
            method: 'GET',
            headers: {
                'X-WP-Nonce': wprm_admin.api_nonce,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin',
        }).then(function (response) {
            if ( response.ok ) {
                return response.json();
            } else {
                console.log(response);
                alert( 'Something went wrong. Please contact support.' );
                return false;
            }
        });
    },
};
