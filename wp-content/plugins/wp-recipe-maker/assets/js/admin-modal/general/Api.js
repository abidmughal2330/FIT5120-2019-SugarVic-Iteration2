const modalEndpoint = wprm_admin.endpoints.modal;

let gettingSuggestions = false;
let gettingSuggestionsNextArgs = false;

export default {
    getSuggestions(args) {
        if ( ! gettingSuggestions ) {
            return this.getSuggestionsDebounced(args);
        } else {
            gettingSuggestionsNextArgs = args;
            return new Promise(r => r(false));
        }
    },
    getSuggestionsDebounced(args) {
        gettingSuggestions = true;

        return fetch(`${modalEndpoint}/ingredient/suggest`, {
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
                // Check if another request is queued.
                if ( gettingSuggestionsNextArgs ) {
                    const newArgs = gettingSuggestionsNextArgs;
                    gettingSuggestionsNextArgs = false;

                    return this.getSuggestionsDebounced(newArgs);
                } else {
                    // Return this request.
                    gettingSuggestions = false;
                    return response.ok ? json : false;
                }
            });
        });
    },
};
