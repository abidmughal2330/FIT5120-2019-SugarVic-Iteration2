<?php
/**
 * Handle the Recipe Collections blocks.
 *
 * @link       http://bootstrapped.ventures
 * @since      4.1.0
 *
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-collections
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-collections/includes/public
 */

/**
 * Handle the Recipe Collections blocks.
 *
 * @since      4.1.0
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-collections
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-collections/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMPRC_Blocks {

	/**
	 * Register actions and filters.
	 *
	 * @since	4.1.0
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_blocks' ) );
	}

	/**
	 * Register blocks.
	 *
	 * @since	4.1.0
	 */
	public static function register_blocks() {
		if ( function_exists( 'register_block_type' ) ) {
			$block_settings = array(
				'attributes' => array(),
				'render_callback' => array( __CLASS__, 'render_recipe_collections_block' ),
			);
			register_block_type( 'wp-recipe-maker/recipe-collections', $block_settings );

			$block_settings = array(
				'attributes' => array(
                    'id' => array(
                        'type' => 'number',
                        'default' => 0,
                    ),
				),
				'render_callback' => array( __CLASS__, 'render_saved_collection_block' ),
			);
			register_block_type( 'wp-recipe-maker/saved-collection', $block_settings );
		}
	}

	/**
	 * Render the recipe collections block.
	 *
	 * @since	4.1.0
	 * @param	mixed $atts Block attributes.
	 */
	public static function render_recipe_collections_block( $atts ) {
		if ( isset( $GLOBALS['wp']->query_vars['rest_route'] ) && '/wp/v2/block-renderer/wp-recipe-maker/recipe-collections' === $GLOBALS['wp']->query_vars['rest_route'] ) {
			$output = '<h4 style="margin: 0;">WP Recipe Maker</h4>';
			$output .= '<p style="margin: 10px 0;">Placeholder for the Recipe Collections feature.</p>';
			$output .= '<a href="https://help.bootstrapped.ventures/article/148-recipe-collections" target="_blank">Learn More</a>';

			return $output;
		} else {
			return WPRMPRC_Shortcode::recipe_collections_shortcode( $atts );
		}
	}

	/**
	 * Render the saved collection block.
	 *
	 * @since	4.1.0
	 * @param	mixed $atts Block attributes.
	 */
	public static function render_saved_collection_block( $atts ) {
		if ( isset( $GLOBALS['wp']->query_vars['rest_route'] ) && '/wp/v2/block-renderer/wp-recipe-maker/saved-collection' === $GLOBALS['wp']->query_vars['rest_route'] ) {
			$collection = false;
			$collection_id = intval( $atts['id'] );
			
			if ( $collection_id ) { 
				$collection = WPRMPRC_Manager::get_collection( $collection_id );
			}

			if ( $collection ) {
				$output = '<h4 style="margin: 0;">' . $collection->name() . '</h4>';
				$output .= '<p style="margin: 10px 0;">Placeholder for a saved recipe collection.</p>';
				$output .= '<a href="' . admin_url( 'admin.php?page=wprm_recipe_collections&id=' . $collection->id() ) . '" target="_blank">Edit saved collection</a>';
			} else {
				$output = '<h4>Saved Recipe Collection</h4>';
				$output .= '<p style="margin: 10px 0; color: darkred;">Set the saved recipe collection ID in the sidebar.</p>';
				$output .= '<a href="' . admin_url( 'admin.php?page=wprm_recipe_collections' ) . '" target="_blank">Create new saved collection</a>';
			}

			return $output;
		} else {
			return WPRMPRC_Shortcode::saved_collection_shortcode( $atts );
		}
	}
}

WPRMPRC_Blocks::init();
