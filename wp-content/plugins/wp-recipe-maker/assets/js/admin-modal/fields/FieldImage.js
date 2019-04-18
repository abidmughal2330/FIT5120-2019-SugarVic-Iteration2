import React from 'react';

import Media from '../general/Media';
 
const FieldImage = (props) => {
    const hasImage = props.id > 0;

    const selectImage = () => {
        Media.selectImage((attachment) => {
            props.onChange( attachment.id, attachment.url );
        });
    }

    return (
        <div className="wprm-admin-modal-field-image">
            {
                hasImage
                ?
                <div className="wprm-admin-modal-field-image-preview">
                    <img
                        onClick={ selectImage }
                        src={ props.url }
                    />
                    <a
                        href="#"
                        tabIndex={ props.disableTab ? '-1' : null }
                        onClick={ (e) => {
                            e.preventDefault();
                            props.onChange( 0, '' );
                        } }
                    >Remove Image</a>
                </div>
                :
                <button
                    className="button"
                    tabIndex={ props.disableTab ? '-1' : null }
                    onClick={ selectImage }
                >Select Image</button>
            }
        </div>
    );
}
export default FieldImage;