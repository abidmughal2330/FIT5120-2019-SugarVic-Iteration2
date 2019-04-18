import React from 'react';
 
const SelectColumns = (props) => {
    return (
        <div className="wprm-manage-recipes-select-columns-container">
            <h4>Show/Hide Columns:</h4>
            <div className="wprm-manage-recipes-select-columns">
            {
                props.columns.map( (column, index) => {
                    if ( 'actions' === column.id ) {
                        return null;
                    }

                    return (
                        <span className="wprm-manage-recipes-select-columns-column" key={index}>
                            <input
                                id={`wprm-manage-recipes-select-columns-column-${column.id}`}
                                type="checkbox"
                                checked={ props.selectedColumns.includes(column.id) }
                                onChange={(event) => props.onColumnsChange(column.id, event.target.checked)}
                            />
                            <label htmlFor={`wprm-manage-recipes-select-columns-column-${column.id}`}>{ column.Header }</label>
                        </span>
                    );
                })
            }
            </div>
        </div>
    );
}
export default SelectColumns;