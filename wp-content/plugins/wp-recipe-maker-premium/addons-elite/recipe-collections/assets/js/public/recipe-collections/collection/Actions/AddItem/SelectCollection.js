
import React, { Component } from 'react';
import Select from 'react-select';

export default class SelectCollection extends Component {
    constructor(props) {
        super(props);

        this.state = {
            collection: 'inbox',
        }
    }

    componentDidMount() {
        this.updateAddItems();
    }

    componentDidUpdate(prevProps, prevState) {
        if ( prevState.collection !== this.state.collection ) {
            this.updateAddItems();
        }
    }

    updateAddItems() {
        let collection;
        if ( 'inbox' === this.state.collection ) {
            collection = this.props.collections.inbox;
        } else {
            const collectionId = parseInt( this.state.collection );
            collection = this.props.collections.user.find((c) => collectionId === c.id);
        }
        
        if ( collection && collection.items ) {
            const items = Object.values(collection.items).reduce( (allItems, groupItems) => allItems.concat(groupItems), [] );
            this.props.onChangeAddItems(items);
        } else {
            this.props.onChangeAddItems([]);
        }
    }

    render() {
        let collectionOptions = [
            { value: 'inbox', label: this.props.collections.inbox.name},
        ];

        for ( let collection of this.props.collections.user ) {
            collectionOptions.push({
                value: collection.id,
                label: collection.name,
            });
        }

        return (
            <Select
                className="wprmprc-collection-action-select-collection"
                value={collectionOptions.filter(({value}) => value === this.state.collection)}
                onChange={(option) => this.setState({ collection: option.value })}
                options={collectionOptions}
                clearable={false}
                styles={{
                    control: styles => ({ ...styles, borderRadius: 5 }),
                }}
            />
        );
    }
}
