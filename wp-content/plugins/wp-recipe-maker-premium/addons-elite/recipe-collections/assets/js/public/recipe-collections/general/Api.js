const collectionsHelperEndpoint = window.wprmp_public ? wprmp_public.endpoints.collections_helper : '';
const debounceTime = 500;

let searchRequest = '';
let searchRequestTimer = null;
let searchPromise = false;

let saveRequest = false;
let saveRequestTimer = null;

export default {
    getCollections() {
        let collections = false;

        if ( 0 === parseInt( wprmp_public.user ) ) {
            // Not logged in, check for local storage.
            const localCollections = localStorage.getItem( 'wprm-recipe-collection' );

            if ( localCollections ) {
                collections = JSON.parse(localCollections);
            }
        }

        if ( ! collections ) {
            collections = wprmprc_public.collections;
        }

        // Decouple and return.
        return JSON.parse(JSON.stringify(collections));
    },
    saveCollections(collections) {
        if ( 0 === parseInt( wprmp_public.user ) ) {
            // Not logged in, save in local storage.
            localStorage.setItem( 'wprm-recipe-collection', JSON.stringify( collections ) );
        } else {
            saveRequest = collections;

            clearTimeout(saveRequestTimer);
            saveRequestTimer = setTimeout(() => {
                this.saveCollectionsDebounced();
            }, debounceTime);
        }

        return collections;
    },
    saveCollectionsDebounced() {
        const collections = saveRequest;
        saveRequest = false;

        if ( collections ) {
            return fetch(`${collectionsHelperEndpoint}/user/${wprmp_public.user}`, {
                method: 'POST',
                headers: {
                    'X-WP-Nonce': wprm_public.api_nonce,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    collections,
                }),
            });
        }
    },
    searchRecipes(search) {
        searchRequest = search;

        clearTimeout(searchRequestTimer);
        searchRequestTimer = setTimeout(() => {
            this.searchRecipesDebounced();
        }, debounceTime);

        if ( searchPromise ) {
            searchPromise(false)
        }

        return new Promise( r => searchPromise = r );
    },
    searchRecipesDebounced() {
        const promise = searchPromise;
        const search = searchRequest;
        searchPromise = false;
        searchRequest = '';

        return fetch(`${collectionsHelperEndpoint}/recipes`, {
            method: 'POST',
            headers: {
                'X-WP-Nonce': wprm_public.api_nonce,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                search,
            }),
        }).then(response => {
            return response.json().then(json => {
                let result = response.ok ? json : false;
                promise( result );
            });
        });
    },
    getRecipe(id) {
        return fetch(`${collectionsHelperEndpoint}/recipe/${id}`, {
            method: 'GET',
            headers: {
                'X-WP-Nonce': wprm_public.api_nonce,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin',
        }).then(response => {
            return response.json().then(json => {
                return response.ok ? json : false;
            });
        });
    },
    getIngredients(recipes) {
        return fetch(`${collectionsHelperEndpoint}/ingredients`, {
            method: 'POST',
            headers: {
                'X-WP-Nonce': wprm_public.api_nonce,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                recipes,
            }),
        }).then(response => {
            return response.json().then(json => {
                return response.ok ? json : false;
            });
        });
    },
    getNutrition(recipes) {
        return fetch(`${collectionsHelperEndpoint}/nutrition`, {
            method: 'POST',
            headers: {
                'X-WP-Nonce': wprm_public.api_nonce,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                recipes,
            }),
        }).then(response => {
            return response.json().then(json => {
                return response.ok ? json : false;
            });
        });
    },
    saveCollectionToCollections(collectionId) {
        return fetch(`${collectionsHelperEndpoint}/save`, {
            method: 'POST',
            headers: {
                'X-WP-Nonce': wprm_public.api_nonce,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                collectionId,
            }),
        }).then(response => {
            return response.ok;
        });
    },
};
