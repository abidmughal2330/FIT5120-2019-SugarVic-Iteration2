const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const {
    ServerSideRender,
    PanelBody,
    TextControl,
} = wp.components;
const {
    InspectorControls,
} = wp.editor;

import PostSelect from '../../../../../../wp-recipe-maker/assets/js/blocks/shared/PostSelect';

registerBlockType( 'wp-recipe-maker/saved-collection', {
    title: __( 'Saved Collection' ),
    description: __( 'Display a Saved Recipe Collection.' ),
    icon: 'book-alt',
    keywords: [],
    category: 'wp-recipe-maker',
    supports: {
		html: false,
    },
    transforms: {
        from: [
            {
                type: 'shortcode',
                tag: 'wprm-saved-collection',
                attributes: {
                    id: {
                        type: 'number',
                        shortcode: ( { named: { id = '' } } ) => {
                            return parseInt( id.replace( 'id', '' ) );
                        },
                    },
                },
            },
        ]
    },
    edit: (props) => {
        const { attributes, setAttributes, className } = props;

        const style = {
            border: '1px dashed #444',
            borderRadius: '5px',
            padding: '10px',
        };

        return (
            <div className={ className } style={ style }>
                <InspectorControls>
                    <PanelBody title={ __( 'Saved Collection Details' ) }>
                        <TextControl
                            label={ __( 'Saved Collection ID' ) }
                            help={ __( 'Find the ID on the WP Recipe Maker > Manage > Saved Collections page.' ) }
                            value={ attributes.id }
                            onChange={(id) => {
                                id = parseInt(id);
                                if ( isNaN( id ) ) {
                                    id = '';
                                }

                                setAttributes({
                                    id,
                                });
                            }}
                        />
                    </PanelBody>
                </InspectorControls>
                <ServerSideRender
                    block="wp-recipe-maker/saved-collection"
                    attributes={ attributes }
                />
            </div>
        )
    },
    save: (props) => {
        return null;
    },
} );
