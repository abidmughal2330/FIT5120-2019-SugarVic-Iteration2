const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const {
    Button,
    Disabled,
    ServerSideRender,
    PanelBody,
    Toolbar,
    TextControl,
    SelectControl,
} = wp.components;
const { Fragment } = wp.element;
const {
    InspectorControls,
    BlockControls,
} = wp.editor;

import Sidebar from './Sidebar';

import '../../../css/blocks/recipe.scss';
import '../../../css/blocks/modal.scss';

registerBlockType( 'wp-recipe-maker/recipe-roundup-item', {
    title: __( 'WPRM Recipe Roundup Item' ),
    description: __( 'Output your Recipe Roundup as ItemList metadata.' ),
    icon: 'media-document',
    keywords: [ 'wprm', 'wp recipe maker' ],
    category: 'wp-recipe-maker',
    supports: {
		html: false,
    },
    transforms: {
        from: [
            {
                type: 'shortcode',
                tag: 'wprm-recipe-roundup-item',
                attributes: {
                    id: {
                        type: 'number',
                        shortcode: ( { named: { id = '' } } ) => {
                            return parseInt( id.replace( 'id', '' ) );
                        },
                    },
                    template: {
                        type: 'string',
                        shortcode: ( { named: { template = '' } } ) => {
                            return template.replace( 'template', '' );
                        },
                    },
                },
            },
        ]
    },
    edit: (props) => {
        const { attributes, setAttributes, isSelected, className } = props;

        const modalCallback = ( id ) => {
            setAttributes({
                id,
            });
        };

        return (
            <div className={ className }>{
                attributes.id
                ?
                <Fragment>
                    <Sidebar {...props} />
                    <Disabled>    
                        <ServerSideRender
                            block="wp-recipe-maker/recipe-roundup-item"
                            attributes={ attributes }
                        />
                    </Disabled>
                </Fragment>
                :
                <Fragment>
                    <h2>WPRM { __( 'Recipe Roundup Item' ) }</h2>
                    <Button
                        isLarge
                        onClick={ () => {
                            WPRecipeMaker.admin.Modal.open(false, {
                                recipe_id: 0,
                                menu: 'insert-recipe',
                                gutenberg: true,
                                gutenbergCallback: modalCallback,
                            });
                            WPRecipeMaker.admin.Modal.disable_menu();
                        }}>
                        { __( 'Select Recipe' ) }
                    </Button>
                </Fragment>
            }</div>
        )
    },
    save: (props) => {
        const { attributes } = props;

        if ( attributes.id ) {
            let shortcode = `[wprm-recipe-roundup-item id="${attributes.id}"`;
            if ( attributes.template ) {
                shortcode += ` template="${attributes.template}"`;
            }
            shortcode += ']';
            return shortcode;
        } else {
            return null;
        }
    },
} );