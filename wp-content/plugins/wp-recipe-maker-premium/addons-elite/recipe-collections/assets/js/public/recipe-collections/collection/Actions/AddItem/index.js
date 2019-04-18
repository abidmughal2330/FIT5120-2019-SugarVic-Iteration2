import React, { Component, Fragment } from 'react';
import Select from 'react-select';

import SearchRecipe from './SearchRecipe';
import SelectCollection from './SelectCollection';
import AddItems from './AddItems';

export default class AddItem extends Component {
    constructor(props) {
        super(props);

        this.state = {
            mode: 'admin' === this.props.type || 'inbox' === this.props.type ? 'search' : 'collection',
        }
    }

    render() {
        const addItemModes = [
            { value: 'collection', label: wprmprc_public.labels.collection_add_item_collection},
            { value: 'search', label: wprmprc_public.labels.collection_add_item_search},
        ];

        return (
            <Fragment>
                {
                    'admin' !== this.props.type
                    &&
                    <Select
                        className="wprmprc-collection-action-add-item-mode"
                        value={addItemModes.filter(({value}) => value === this.state.mode)}
                        onChange={(option) => this.setState({ mode: option.value })}
                        options={addItemModes}
                        clearable={false}
                        styles={{
                            control: styles => ({ ...styles, borderRadius: 5 }),
                        }}
                    />
                }
                {
                    'search' === this.state.mode
                    &&
                    <SearchRecipe
                        onChangeAddItems={this.props.onChangeAddItems}
                    />
                }
                {
                    'collection' === this.state.mode
                    &&
                    <SelectCollection
                        collections={this.props.collections}
                        onChangeAddItems={this.props.onChangeAddItems}
                    />
                }
                <AddItems
                    type={this.props.type}
                    collection={this.props.collection}
                    addItems={this.props.addItems}
                />
            </Fragment>
        );
    }
}
