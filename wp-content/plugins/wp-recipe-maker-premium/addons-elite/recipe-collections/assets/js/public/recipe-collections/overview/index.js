import React, { Component, Fragment } from 'react';
import { withRouter } from 'react-router-dom';

import EditList from '../general/EditList';

import '../../../../css/public/overview.scss';

class Overview extends Component {
    render() {
        const viewCollection = (type, collection) => (
            <div
                className={`wprmprc-overview-collection wprmprc-overview-collection-view wprmprc-overview-collection-${type}`}
                onClick={() => {
                    if ( 'inbox' === type ) {
                        this.props.history.push(`/collection/inbox/`);
                    } else {
                        this.props.history.push(`/collection/${type}/${collection.id}`);
                    }
                }}
            >
                <div className="wprmprc-overview-collection-name">{collection.name}</div>
                <div className="wprmprc-overview-collection-items">{collection.nbrItems}</div>
            </div>
        );

        const editCollection = (type, collection) => (
            <div className="wprmprc-overview-collection wprmprc-overview-collection-edit">
                <input
                    type="text"
                    value={collection.name}
                    onChange={(event) => {
                        this.props.onChangeCollection(type, collection.id, { name: event.target.value });
                    }}
                />
                <div className="wprmprc-overview-collection-items">{collection.nbrItems}</div>
            </div>
        );

        return (
            <Fragment>
                <div className="wprmprc-container-header">{ wprmprc_public.labels.overview_header }</div>
                <div className="wprmprc-overview">
                    <EditList
                        type='collection'
                        onAdd={() => this.props.onAddCollection('user')}
                        onDelete={(id) => this.props.onDeleteCollection('user', id)}
                        onReorder={(oldIndex, newIndex) => this.props.onReorderCollection('user', oldIndex, newIndex)}
                        header={(editing) => editing ? editCollection('inbox', this.props.collections.inbox) : viewCollection('inbox', this.props.collections.inbox) }
                        items={this.props.collections.user}
                        item={(editing, item) => editing ? editCollection('user', item) : viewCollection('user', item)}
                        labels={{
                            add: wprmprc_public.labels.overview_add_collection,
                            edit: wprmprc_public.labels.overview_edit_collections,
                        }}
                    />
                </div>
            </Fragment>
        );
    }
}

export default withRouter(Overview);