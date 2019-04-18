const nutritionEndpoint = wprm_admin.endpoints.nutrition;

export default {
    getMatches(ingredients) {
        const data = {
            ingredients,
        };

        return fetch(`${nutritionEndpoint}/matches`, {
            method: 'POST',
            headers: {
                'X-WP-Nonce': wprm_admin.api_nonce,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin',
            body: JSON.stringify(data),
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
    searchMatchOptions(search) {
        const data = {
            search,
        };

        return fetch(`${nutritionEndpoint}/matches/options`, {
            method: 'POST',
            headers: {
                'X-WP-Nonce': wprm_admin.api_nonce,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin',
            body: JSON.stringify(data),
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
