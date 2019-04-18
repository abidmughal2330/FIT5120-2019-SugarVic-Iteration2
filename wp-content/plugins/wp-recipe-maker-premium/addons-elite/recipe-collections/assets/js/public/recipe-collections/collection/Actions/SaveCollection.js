import React, { Component } from 'react';

import Api from '../../general/Api';
import Loader from '../../general/Loader';

export default class SaveCollection extends Component {
    constructor(props) {
        super(props);

        this.state = {
            saving: false,
            saved: false,
        }
    }

    componentDidUpdate() {
        if ( this.state.saved ) {
            // Redirect to collections if link is set.
            const collections_url = wprmprc_public.settings.recipe_collections_link;
            if ( collections_url ) {
                window.location = collections_url;
            }
        }
    }

    saveCollection() {
        if ( 0 === parseInt( wprmp_public.user ) ) {
            const localCollections = localStorage.getItem( 'wprm-recipe-collection' );

            let collections;
            if ( localCollections ) {
                collections = JSON.parse(localCollections);
            } else {
                collections = wprmp_public.collections.default;
            }

            let collection = JSON.parse(JSON.stringify(this.props.collection));

            // Get unique ID for collection.
            let maxId = Math.max.apply( Math, collections.user.map( function(collection) { return collection.id; } ) );
            maxId = maxId < 0 ? -1 : maxId;
            collection.id = maxId + 1;

            collections.user.push(collection);
            localStorage.setItem( 'wprm-recipe-collection', JSON.stringify( collections ) );

            this.setState({
                saved: true,
            });
        } else {
            this.setState({
                saving: true,
            });
    
            Api.saveCollectionToCollections(this.props.collection.id).then(() => {
                this.setState({
                    saving: false,
                    saved: true,
                });
            });
        }
    }

    render() {
        if ( this.state.saved || ! wprmprc_public.settings.recipe_collections_save_button ) {
            return null;
        }
        
        return (
            <div className="wprmprc-collection-action" onClick={this.saveCollection.bind(this)}>
                { this.state.saving ? <Loader /> : wprmprc_public.labels.collection_save }
            </div>
        );
    }
}
