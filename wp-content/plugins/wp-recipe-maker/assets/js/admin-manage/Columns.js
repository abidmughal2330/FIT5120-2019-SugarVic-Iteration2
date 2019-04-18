import React from 'react';
import Icon from './general/Icon';
import Api from './general/Api';

export default {
    getColumns(refreshData) {
        let columns = [{
            Header: 'ID',
            id: 'id',
            accessor: 'id',
            style: { textAlign: 'center' },
            maxWidth: 65,
        },{
            Header: 'Name',
            id: 'name',
            accessor: 'name',
        },{
            Header: 'Parent Post',
            id: 'parent_post_id',
            accessor: 'parent_post_id',
            sortable: false,
            Filter: ({ filter, onChange }) => (
                <select
                    onChange={event => onChange(event.target.value)}
                    style={{ width: '100%', fontSize: '1em' }}
                    value={filter ? filter.value : 'all'}
                >
                    <option value="all">Show All</option>
                    <option value="yes">Has Parent Post</option>
                    <option value="no">Does not have Parent Post</option>
                </select>
            ),
            Cell: row => {
                let parent_post = row.original.parent_post;
        
                if ( ! parent_post ) {
                    return (<div></div>);
                } else {
                    return (
                        <div>{row.original.parent_post.ID} - {row.original.parent_post.post_title}</div>
                    )
                }
            },
        }];
        
        // Taxonomies.
        // TODO Prefilter when coming from taxonomy manage tag.
        for (let key in wprm_admin_manage.taxonomies) {
            const name = wprm_admin_manage.taxonomies[key].name;
            const tag = key.substr(5);
        
            let terms = Object.entries(wprm_admin_manage.terms[key]).map(e => { return {id: e[0], label: e[1] } } );
            terms.sort((a,b) => a.label.localeCompare(b.label));
        
            columns.push({
                Header: name,
                id: tag,
                accessor: d => d.tags[tag],
                sortable: false,
                Filter: ({ filter, onChange }) => (
                    <select
                        onChange={event => onChange(event.target.value)}
                        style={{ width: '100%', fontSize: '1em' }}
                        value={filter ? filter.value : 'all'}
                    >
                        <option value="all">All {name}</option>
                        <option value="none">No {name}</option>
                        {
                            terms.map((term, index) => (
                                <option value={term.id} key={index}>{ term.label }</option>
                            ))
                        }
                    </select>
                ),
                Cell: row => {
                    const names = row.value.map(t => t.name);
        
                    if ( 'ingredient' === tag ) {
                        return (
                            <div>
                                {
                                    row.original.ingredients.map( (group, index) => (
                                        <div key={index}>
                                            { group.name && <div style={{ fontWeight: 'bold' }}>{ group.name }</div> }
                                            {
                                                group.ingredients.map( (ingredient, ingredient_index) => {
                                                    let fields = [];
                                                    
                                                    if ( ingredient.amount ) { fields.push( ingredient.amount ); }
                                                    if ( ingredient.unit ) { fields.push( ingredient.unit ); }
                                                    if ( ingredient.name ) { fields.push( ingredient.name ); }
                                                    if ( ingredient.notes ) { fields.push( ingredient.notes ); }
                                                    
                                                    if ( fields.length ) {
                                                        return (
                                                            <div key={ingredient_index}>{ fields.join( ' ' ) }</div>
                                                        )
                                                    }
                                                })
                                            }
                                        </div>
                                    ))
                                }
                            </div>
                        )
                    } else {
                        return (
                            <div>{ names.join(', ') }</div>
                        )
                    }
                },
            });
        }
        
        columns.push({
            Header: 'Nutrition',
            id: 'nutrition',
            accessor: 'nutrition',
            sortable: false,
            filterable: false,
            Cell: row => (
                <div>
                    {
                        Object.keys(row.value).map((nutrition, index) => {
                            const value = row.value[nutrition];
        
                            if ( false !== value ) {
                                return (
                                    <div
                                        className="wprm-manage-recipes-recipe-nutrition"
                                        key={index}
                                    >{`${nutrition}: ${value}`}</div>
                                )
                            }
                        })
                    }
                </div>
            ),
        },{
            Header: '',
            id: 'actions',
            sortable: false,
            filterable: false,
            maxWidth: 50,
            Cell: row => (
                <div className="wprm-manage-recipes-recipe-actions">
                    <Icon
                        type="edit"
                        onClick={() => {
                            WPRecipeMaker.admin.Modal.open(false, {
                                recipe_id: row.original.id,
                                callback: () => refreshData(),
                            });
                        }}
                    />
                    <Icon
                        type="delete"
                        onClick={() => {
                            if(confirm(`Are you sure you want to delete the "${row.original.name}" recipe?`)) {
                                Api.deleteRecipe(row.original.id).then(() => refreshData());
                            }
                        }}
                    />
                </div>
            ),
        });

        return columns;
    }
};