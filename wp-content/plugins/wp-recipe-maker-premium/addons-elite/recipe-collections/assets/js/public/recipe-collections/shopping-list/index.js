import React, { Component, Fragment } from 'react';
import { withRouter } from 'react-router-dom';

import Collection from './collection';
import List from './list';

import Print from '../general/Print';

import '../../../../css/public/shopping-list.scss';

class ShoppingList extends Component {

    constructor(props) {
        super(props);

        this.printCollectionShoppingList = React.createRef();
        this.printCollection = React.createRef();
        this.printShoppingList = React.createRef();

        this.state = {
            print: false,
        }
    }

    render() {
        const { type, collection } = this.props;

        return (
            <Fragment>
                <div className="wprmprc-container-header">
                    <span className="wprmprc-header-link"
                        onClick={() => {
                            if ( 'inbox' === type ) {
                                this.props.history.push(`/collection/inbox/`);
                            } else {
                                this.props.history.push(`/collection/${type}/${collection.id}`);
                            }
                        }}
                    >{ collection.name }</span>
                    <span className="wprmprc-header-link-separator">&gt;</span>
                    { wprmprc_public.labels.shopping_list_header }
                </div>
                <div className="wprmprc-shopping-list">
                    <div ref={this.printCollectionShoppingList}>
                        <div ref={this.printCollection}>
                            <Collection
                                type={type}
                                collection={collection}
                                onChangeCollection={this.props.onChangeCollection}
                            />
                        </div>
                        <div ref={this.printShoppingList}>
                            <List
                                collection={collection}
                            />
                        </div>
                    </div>
                    {
                        wprmprc_public.settings.recipe_collections_shopping_list_print
                        &&
                        <Fragment>
                            <div className="wprmprc-shopping-list-actions">
                                <div className="wprmprc-shopping-list-action" onClick={ () => this.setState({ print: 'shopping-list' }) }>{ wprmprc_public.labels.shopping_list_print_list }</div>
                                <div className="wprmprc-shopping-list-action" onClick={ () => this.setState({ print: 'collection' }) }>{ wprmprc_public.labels.shopping_list_print_collection }</div>
                                <div className="wprmprc-shopping-list-action" onClick={ () => this.setState({ print: 'collection-shopping-list' }) }>{ wprmprc_public.labels.shopping_list_print_both }</div>
                            </div>
                            {
                                'collection-shopping-list' === this.state.print
                                && <Print onFinished={() => this.setState({ print: false }) } print={this.printCollectionShoppingList} className="wprmprc-shopping-list"><List collection={collection} /></Print>
                            }
                            {
                                'collection' === this.state.print
                                && <Print onFinished={() => this.setState({ print: false }) } print={this.printCollection} className="wprmprc-shopping-list"><List collection={collection} /></Print>
                            }
                            {
                                'shopping-list' === this.state.print
                                && <Print onFinished={() => this.setState({ print: false }) } print={this.printShoppingList} className="wprmprc-shopping-list"><List collection={collection} /></Print>
                            }
                        </Fragment>
                    }
                </div>
            </Fragment>
        );
    }
}

export default withRouter(ShoppingList);