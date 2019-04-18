import React, { Component } from 'react';
import Modal from 'react-modal';
Modal.setAppElement( '#wprm-admin-modal' );

import '../../css/admin/modal/app.scss';

import Recipe from './recipe';

const contentBlocks = {
    recipe: Recipe,
};

export default class App extends Component {
    constructor() {
        super();
    
        this.state = {
            modalIsOpen: false,
            mode: 'recipe',
            args: {},
        };

        this.content = React.createRef();

        this.close = this.close.bind(this);
        this.closeIfAllowed = this.closeIfAllowed.bind(this);
    }

    open( mode, args ) {
        if ( ! this.state.modalIsOpen ) {
            this.setState({
                modalIsOpen: true,
                mode,
                args,
            });
        }
    }

    close(callback = false) { 
        this.setState({
            modalIsOpen: false,
        }, () => {
            if ( 'function' === typeof callback ) {
                callback();
            }
        });
    }

    closeIfAllowed(callback = false) {
        const checkFunction = this.content.current.hasOwnProperty( 'allowCloseModal' ) ? this.content.current.allowCloseModal : false;

        if ( ! checkFunction || checkFunction() ) {
            this.close(callback);
        }
    }

    render() {
        const Content = contentBlocks.hasOwnProperty(this.state.mode) ? contentBlocks[this.state.mode] : false;

        if ( ! Content ) {
            return null;
        }

        return (
            <Modal
                isOpen={ this.state.modalIsOpen }
                onRequestClose={ this.closeIfAllowed }
                overlayClassName="wprm-admin-modal-overlay"
                className={`wprm-admin-modal wprm-admin-modal-${this.state.mode}`}
            >
                <Content
                    ref={ this.content }
                    mode={ this.state.mode }
                    args={ this.state.args }
                    maybeCloseModal={ this.closeIfAllowed }
                />
            </Modal>
        );
    }
}
