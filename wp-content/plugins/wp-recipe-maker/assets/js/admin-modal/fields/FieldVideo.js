import React, { Fragment } from 'react';

import Media from '../general/Media';
import FieldTextarea from './FieldTextarea';
 
const FieldVideo = (props) => {
    const hasUpload = props.id > 0;
    const hasEmbed = ! hasUpload && ( -1 == props.id || props.embed );
    const hasVideo = hasUpload || hasEmbed;

    const selectVideo = () => {
        Media.selectVideo((attachment) => {
            props.onChange( attachment.attributes.id, attachment.attributes.thumb.src );
        });
    }

    return (
        <div className="wprm-admin-modal-field-video">
            {
                hasVideo
                ?
                <Fragment>
                    {
                        hasUpload
                        ?
                        <div className="wprm-admin-modal-field-video-preview">
                            <img
                                onClick={ selectVideo }
                                src={ props.thumb }
                            />
                            <a
                                href="#"
                                onClick={ (e) => {
                                    e.preventDefault();
                                    props.onChange( 0, '' );
                                } }
                            >Remove Video</a>
                        </div>
                        :
                        <Fragment>
                            <FieldTextarea
                                value={ props.embed }
                                onChange={(embed) => {
                                    props.onChange( -1, '', embed );
                                }}
                            />
                            <a
                                href="#"
                                onClick={ (e) => {
                                    e.preventDefault();
                                    props.onChange( 0, '', '' );
                                } }
                            >Remove Video</a>
                        </Fragment>
                    }
                </Fragment>
                :
                <Fragment>
                    <button
                        className="button"
                        onClick={ selectVideo }
                    >Upload Video</button>
                    <button
                        className="button"
                        onClick={ () => {
                            props.onChange( -1, '' );
                        } }
                    >Embed Video</button>
                </Fragment>
            }
        </div>
    );
}
export default FieldVideo;