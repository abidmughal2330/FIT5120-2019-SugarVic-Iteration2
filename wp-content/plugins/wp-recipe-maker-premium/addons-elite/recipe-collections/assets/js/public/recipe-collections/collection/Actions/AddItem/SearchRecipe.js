import React, { Component } from 'react';

import Api from '../../../general/Api';
import Loader from '../../../general/Loader';

export default class SearchRecipe extends Component {
    constructor(props) {
        super(props);

        this.searchInput = React.createRef();

        this.state = {
            search: '',
            searching: false,
        }
    }

    componentDidMount() {
        this.searchInput.current.focus();
    }

    onSearch(search) {
        // Clear current results.
        this.props.onChangeAddItems([]);

        // Update state.
        this.setState({
            search,
            searching: true,
        });

        // Search via API.
        Api.searchRecipes(search).then((recipes) => {
            if ( false !== recipes ) {
                this.props.onChangeAddItems(recipes);

                this.setState({
                    searching: false,
                });
            }
        });
    }
    
    render() {
        return (
            <div className='wprmprc-collection-action-search-recipe'>
                <input
                    ref={this.searchInput}
                    type="text"
                    value={this.state.search}
                    placeholder={ wprmprc_public.labels.collection_add_item_search_placeholder }
                    onChange={(event) => { this.onSearch(event.target.value) }}
                />
                {
                    this.state.searching
                    && <Loader/>
                }
            </div>
        );
    }
}
