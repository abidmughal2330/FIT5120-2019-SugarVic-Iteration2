import React, { Component, Fragment } from 'react';

import Icon from '../../general/Icon';

import  { formatQuantity } from '../../../../../../../../assets/js/shared/quantities';

export default class Ingredient extends Component {
    constructor(props) {
        super(props); 

        this.state = {
            checked: false,
        }
    }

    render() {
        const { name, ingredient } = this.props;

        return (
            <div className={`wprmprc-shopping-list-list-ingredient${ this.state.checked ? ' wprmprc-shopping-list-list-ingredient-checked' : ''}`}>
                <div
                    className="wprmprc-shopping-list-list-ingredient-checkbox"
                    onClick={() => this.setState({
                        checked: ! this.state.checked,
                    })}
                ><Icon type={ this.state.checked ? 'checkboxChecked' : 'checkboxEmpty' } /></div>
                <div className="wprmprc-shopping-list-list-ingredient-name">
                    {
                        ingredient.link && ingredient.link.url
                        ?
                        <a href={ingredient.link.url} target="_blank" rel="nofollow">{ name }</a>
                        :
                        name
                    }
                </div>
                <div className="wprmprc-shopping-list-list-ingredient-variations">
                    {
                        ingredient.variations.map((variation, index) => (
                            <div className="wprmprc-shopping-list-list-ingredient-variation" key={index}>
                                { `${ variation.amount ? formatQuantity( variation.amount, wprmprc_public.settings.recipe_collections_shopping_list_round_to_decimals ) + ' ' : '' }` }{ variation.unit }
                            </div>
                        ))
                    }
                </div>
            </div>
        );
    }
}