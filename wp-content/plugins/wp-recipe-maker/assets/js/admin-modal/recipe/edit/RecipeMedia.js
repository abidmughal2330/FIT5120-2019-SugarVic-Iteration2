import React, { Fragment } from 'react';

import '../../../../css/admin/modal/fields/media.scss';

import FieldContainer from '../../fields/FieldContainer';
import FieldImage from '../../fields/FieldImage';
import FieldVideo from '../../fields/FieldVideo';
 
const RecipeMedia = (props) => {
    return (
        <Fragment>
            <FieldContainer label="Image">
                <FieldImage
                    id={ props.recipe.image_id }
                    url={ props.recipe.image_url }
                    onChange={ ( image_id, image_url ) => {
                        props.onRecipeChange( {
                            image_id,
                            image_url,
                        } );
                    }}
                />
            </FieldContainer>
            <FieldContainer label="Pin Image">
                <FieldImage
                    id={ props.recipe.pin_image_id }
                    url={ props.recipe.pin_image_url }
                    onChange={ ( pin_image_id, pin_image_url ) => {
                        props.onRecipeChange( {
                            pin_image_id,
                            pin_image_url,
                        } );
                    }}
                />
            </FieldContainer>
            <FieldContainer label="Video">
                <FieldVideo
                    id={ props.recipe.video_id }
                    thumb={ props.recipe.video_thumb_url }
                    embed={ props.recipe.video_embed }
                    onChange={ ( video_id, video_thumb_url, video_embed = false ) => {
                        let video = {
                            video_id,
                            video_thumb_url,
                        }

                        if ( video_embed !== false ) {
                            video.video_embed = video_embed;
                        }

                        props.onRecipeChange( video );
                    }}
                />
            </FieldContainer>
        </Fragment>
    );
}
export default RecipeMedia;