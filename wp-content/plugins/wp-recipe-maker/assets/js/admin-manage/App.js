import React, { Component } from 'react';
import ReactTable from 'react-table'
import 'react-table/react-table.css'

import Columns from './Columns';
import SelectColumns from './SelectColumns';
import Api from './general/Api';
import '../../css/admin/manage.scss';

export default class App extends Component {

    constructor(props) {
        super(props);

        this.state = {
            data: [],
            pages: null,
            loading: true,
            columns: Columns.getColumns( this.refreshData.bind(this) ),
            selectedColumns: ['id', 'name', 'parent_post_id', 'actions'],
        }
    }

    refreshData() {
        this.refReactTable.fireFetchData();
    }

    fetchData(state, instance) {
        this.setState({ loading: true });

        Api.getRecipes({
            pageSize: state.pageSize,
            page: state.page,
            sorted: state.sorted,
            filtered: state.filtered,
        }).then(data => {
            console.log(data.recipes);
            this.setState({
                data: data.recipes,
                pages: data.pages,
                loading: false
            });
        });
    }

    onColumnsChange(id, checked) {
        let selectedColumns = this.state.selectedColumns;

        if (checked) {
            selectedColumns.push(id);
        } else {
            selectedColumns = selectedColumns.filter(c => c !== id);
        }

        this.setState({
            selectedColumns
        });
    }

    render() {
        const { data, pages, loading } = this.state;
        const selectedColumns = this.state.columns.filter(column => 'actions' === column.id || this.state.selectedColumns.includes(column.id));

        return (
            <div className="wprm-manage-recipes-container">
                <SelectColumns
                    onColumnsChange={this.onColumnsChange.bind(this)}
                    columns={this.state.columns}
                    selectedColumns={this.state.selectedColumns}
                />
                <ReactTable
                    ref={(refReactTable) => {this.refReactTable = refReactTable;}}
                    manual
                    columns={selectedColumns}
                    data={data}
                    pages={pages}
                    loading={loading}
                    onFetchData={this.fetchData.bind(this)}
                    defaultPageSize={25}
                    defaultSorted={[{
                        id: "id",
                        desc: true
                    }]}
                    filterable
                    className="-striped"
                />
            </div>
        );
    }
}
