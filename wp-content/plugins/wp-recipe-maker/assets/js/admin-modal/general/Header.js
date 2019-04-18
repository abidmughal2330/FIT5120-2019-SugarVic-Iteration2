import React from 'react';

import Icon from '../../shared/Icon';
 
const Header = (props) => {
    return (
        <div className="wprm-admin-modal-header">
            <h2>{ props.children }</h2>
            <div
                className="wprm-admin-modal-close"
                onClick={props.onCloseModal}
            >
                <Icon
                    type="close"
                    title={ wprm_admin_modal.text.modal.close }
                />
            </div>
        </div>
    );
}
export default Header;