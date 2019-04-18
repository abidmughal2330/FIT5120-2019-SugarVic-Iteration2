const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const {
    ServerSideRender,
} = wp.components;

registerBlockType( 'wp-recipe-maker/recipe-collections', {
    title: __( 'Recipe Collections' ),
    description: __( 'Display the Recipe Collections feature.' ),
    icon: 'book-alt',
    keywords: [ 'meal-planner' ],
    category: 'wp-recipe-maker',
    supports: {
		html: false,
    },
    transforms: {
        from: [
            {
                type: 'shortcode',
                tag: 'wprm-recipe-collections',
                attributes: {},
            },
        ]
    },
    edit: (props) => {
        const { attributes, className } = props;

        const style = {
            border: '1px dashed #444',
            borderRadius: '5px',
            padding: '10px',
        };

        return (
            <div className={ className } style={ style }>
                <ServerSideRender
                    block="wp-recipe-maker/recipe-collections"
                    attributes={ attributes }
                />
            </div>
        )
    },
    save: (props) => {
        return null;
    },
} );