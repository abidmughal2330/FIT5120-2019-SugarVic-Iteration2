const recipeEndpoint = wprm_admin.endpoints.recipe;
const manageEndpoint = wprm_admin.endpoints.manage;

export default {
    getRecipes(args) {
        console.log('getRecipes', args);
        return fetch(`${manageEndpoint}/recipes`, {
            method: 'POST',
            headers: {
                'X-WP-Nonce': wprm_admin.api_nonce,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin',
            body: JSON.stringify(args),
        }).then(response => {
            return response.json().then(json => {
                return response.ok ? json : Promise.reject(json);
            });
        });
    },
    deleteRecipe(id) {
        return fetch(`${recipeEndpoint}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-WP-Nonce': wprm_admin.api_nonce,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin',
        });
    }
};
