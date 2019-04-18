import React, { Component, Fragment } from 'react';
import { withRouter } from 'react-router-dom';

import AddItem from './AddItem';
import ColumnsGroups from './ColumnsGroups';
import SaveCollection from './SaveCollection';

import '../../../../../css/public/actions.scss';

class Actions extends Component {
    render() {        
        return (
            <div className={`wprmprc-collection-actions wprmprc-collection-actions-${this.props.mode}`}>
                {
                    'overview' === this.props.mode
                    &&
                    <Fragment>
                        {
                            'saved' === this.props.type
                            ?
                            <SaveCollection
                                collection={this.props.collection}
                            />
                            :
                            <Fragment>
                                <div className="wprmprc-collection-action" onClick={ () => this.props.onChangeMode('add-item' ) }>{ wprmprc_public.labels.collection_add_item }</div>
                                <div className="wprmprc-collection-action" onClick={ () => this.props.onChangeMode('remove-items' ) }>{ wprmprc_public.labels.collection_remove_items }</div>
                                {
                                    'inbox' !== this.props.type
                                    && <div className="wprmprc-collection-action" onClick={ () => this.props.onChangeMode('columns-groups' ) }>{ wprmprc_public.labels.collection_columns_groups }</div>
                                }
                            </Fragment>
                        }
                        {
                            wprmprc_public.settings.recipe_collections_nutrition_facts && 0 < wprmprc_public.settings.recipe_collections_nutrition_facts_fields.length
                            &&
                            <div
                                className="wprmprc-collection-action"
                                onClick={() => {
                                    this.props.onChangeShowNutrition( ! this.props.showNutrition );
                                }}
                            >{ this.props.showNutrition ? wprmprc_public.labels.nutrition_hide_button : wprmprc_public.labels.nutrition_show_button }</div>
                        }
                        {
                            wprmprc_public.settings.recipe_collections_shopping_list
                            &&
                            'admin' !== this.props.type
                            &&
                            <div
                                className="wprmprc-collection-action"
                                onClick={() => {
                                    if ( 'inbox' === this.props.type ) {
                                        this.props.history.push(`/shopping-list/inbox/`);
                                    } else {
                                        this.props.history.push(`/shopping-list/${this.props.type}/${this.props.collection.id}`);
                                    }
                                }}
                            >{ wprmprc_public.labels.shopping_list_header }</div>
                        }
                    </Fragment>
                }
                {
                    'remove-items' === this.props.mode
                    &&
                    <div className="wprmprc-collection-action" onClick={ () => this.props.onChangeMode('overview' ) }>{ wprmprc_public.labels.collection_remove_items_stop }</div>
                }
                {
                    'add-item' === this.props.mode
                    &&
                    <Fragment>
                        <div className="wprmprc-collection-action-header">
                            <span className="wprmprc-header-link" onClick={ () => this.props.onChangeMode('overview') }>{ wprmprc_public.labels.collection_actions }</span>
                            <span className="wprmprc-header-link-separator">&gt;</span>
                            { wprmprc_public.labels.collection_add_item }
                        </div>
                        <AddItem
                            collections={this.props.collections}
                            type={this.props.type}
                            collection={this.props.collection}
                            addItems={this.props.addItems}
                            onChangeAddItems={this.props.onChangeAddItems}
                        />
                    </Fragment>
                }
                {
                    'inbox' !== this.props.type && 'columns-groups' === this.props.mode
                    &&
                    <Fragment>
                        <div className="wprmprc-collection-action-header">
                            <span className="wprmprc-header-link" onClick={ () => this.props.onChangeMode('overview') }>{ wprmprc_public.labels.collection_actions }</span>
                            <span className="wprmprc-header-link-separator">&gt;</span>
                            { wprmprc_public.labels.collection_columns_groups }
                        </div>
                        <ColumnsGroups
                            columns={this.props.columns}
                            onChangeColumns={this.props.onChangeColumns}
                            groups={this.props.groups}
                            onChangeGroups={this.props.onChangeGroups}
                        />
                    </Fragment>
                }
            </div>
        );
    }
}

export default withRouter( Actions );