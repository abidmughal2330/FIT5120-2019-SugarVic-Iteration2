import React, { Component, Fragment } from 'react';

import Api from './Api';
import Loader from '../../../shared/Loader';

export default class StepMatch extends Component {
    constructor(props) {
        super(props);

        console.log(props.ingredient);

        this.searchInput = React.createRef();

        let search = props.ingredient.nutrition.matchSearch;
        let options = props.ingredient.nutrition.matchOptions;
        let isSearching = false;

        if ( false === options ) {
            search = props.ingredient.name;
            isSearching = true;
            this.searchApi( search );
        }

        this.state = {
            search,
            prevSearch: search,
            options,
            isSearching,
        }

        this.onSearch = this.onSearch.bind(this);
        this.searchApi = this.searchApi.bind(this);
    }

    componentDidMount() {
        this.searchInput.current.focus();
    }

    onSearch() {
        if ( ! this.state.isSearching && '' !== this.state.search ) {
            this.setState({
                isSearching: true,
            }, () => {
                this.searchApi( this.state.search );
            });
        }
    }

    searchApi(search) {
        Api.searchMatchOptions(search).then((data) => {
            if ( data ) {
                console.log('api matches', data);
                this.setState({
                    options: data.matchOptions,
                    prevSearch: search,
                    isSearching: false,
                });
            }
        });
    }

    render() {
        const { ingredient } = this.props;
        console.log( this.state.options );

        let fullIngredientText = `${ingredient.amount} ${ingredient.unit} ${ingredient.name}`;

        if ( ingredient.notes ) {
            fullIngredientText += ` (${ingredient.notes})`;
        }

        return (
            <div className="wprm-admin-modal-recipe-nutrition-calculation-match">
                <p><strong>Find a match for:</strong> { fullIngredientText }</p>
                <div className="wprm-admin-modal-recipe-nutrition-calculation-match-search">
                    <input
                        ref={ this.searchInput }
                        type="text"
                        value={ this.state.search }
                        onChange={(e) => {
                            this.setState({
                                search: e.target.value,
                            });
                        }}
                        onKeyDown={(e) => {
                            if (e.which === 13 || e.keyCode === 13) {
                                this.onSearch();
                            }
                        }}
                        disabled={ this.state.isSearching }
                    />
                    <button
                        className="button button-primary"
                        onClick={this.onSearch}
                        disabled={ this.state.isSearching || '' === this.state.search || this.state.prevSearch === this.state.search }
                    >Search</button>
                </div>
                {
                    this.state.isSearching
                    ?
                    <Loader />
                    :
                    <Fragment>
                        {
                            ! this.state.options
                            || 0 === this.state.options.length
                            ?
                            <Fragment>
                                {
                                    this.state.prevSearch
                                    ?
                                    <p>No ingredients found for "{ this.state.prevSearch }".</p>
                                    :
                                    <p>No ingredients found.</p>
                                }
                            </Fragment>
                            :
                            <Fragment>
                                {
                                    false !== this.state.prevSearch
                                    &&
                                    <p>Results for "{ this.state.prevSearch }":</p>
                                }
                                <div className="wprm-admin-modal-recipe-nutrition-calculation-matches">
                                    {
                                        this.state.options.map((match, index) => (
                                            <div
                                                className="wprm-admin-modal-recipe-nutrition-calculation-matches-option"
                                                onClick={() => {
                                                    this.props.onMatchChange({
                                                        match,
                                                        matchSearch: this.state.search,
                                                        matchOptions: this.state.options,
                                                    });
                                                }}
                                                key={index}
                                            >
                                                {
                                                    match.image
                                                    ?
                                                    <img
                                                        className="wprm-admin-modal-recipe-nutrition-calculation-matches-option-image"
                                                        src={ `https://spoonacular.com/cdn/ingredients_100x100/${match.image}` }
                                                    />
                                                    :
                                                    null
                                                }
                                                <div
                                                    className="wprm-admin-modal-recipe-nutrition-calculation-matches-option-name"
                                                >{ match.name }{ match.aisle ? ` (${ match.aisle.toLowerCase() })` : ''}</div>
                                            </div>
                                        ))
                                    }
                                </div>
                            </Fragment>
                        }
                    </Fragment>
                }
            </div>
        );
    }
}