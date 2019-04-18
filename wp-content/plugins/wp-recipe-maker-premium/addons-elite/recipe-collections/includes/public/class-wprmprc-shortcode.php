<?php
/**
 * Handle the Recipe Collections shortcode.
 *
 * @link       http://bootstrapped.ventures
 * @since      4.1.0
 *
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-collections
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-collections/includes/public
 */

/**
 * Handle the Recipe Collections shortcode.
 *
 * @since      4.1.0
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-collections
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-collections/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMPRC_Shortcode {

	/**
	 * Register actions and filters.
	 *
	 * @since    4.1.0
	 */
	public static function init() {
		add_shortcode( 'wprm-recipe-collections', array( __CLASS__, 'recipe_collections_shortcode' ) );
		add_shortcode( 'wprm-saved-collection', array( __CLASS__, 'saved_collection_shortcode' ) );
	}

	/**
	 * Output for the Recipe Collections shortcode.
	 *
	 * @since	4.1.0
	 * @param	array $atts Options passed along with the shortcode.
	 */
	public static function recipe_collections_shortcode( $atts ) {
		$atts = shortcode_atts( array(), $atts, 'wprm_recipe_collections' );

		// Display no access message if user is not logged in.
		if ( 'logged_in' === WPRM_Settings::get( 'recipe_collections_access' ) && ! is_user_logged_in() ) {
			$message = WPRM_Settings::get( 'recipe_collections_no_access_message' );

			if ( $message ) {
				$message = '<div class="wprm-recipe-collections-no-access">' . $message . '</div>';
			}

			return $message;
		}

		wp_enqueue_script( 'wprmprc-public' );
		WPRMPRC_Assets::localize_shortcode();
		return '<div id="wprm-recipe-collections-app"></div>';
	}

	/**
	 * Output for the saved collection shortcode.
	 *
	 * @since	4.1.0
	 * @param	array $atts Options passed along with the shortcode.
	 */
	public static function saved_collection_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'id' => 0,
		), $atts, 'wprm_saved_collection' );

		$id = intval( $atts['id'] );

		if ( $id ) {
			$collection = WPRMPRC_Manager::get_collection( $id );

			if ( $collection ) {
				wp_enqueue_script( 'wprmprc-public' );
				WPRMPRC_Assets::localize_shortcode( $collection );
				return '<div id="wprm-recipe-saved-collections-app"></div>';
			}
		}

		return '';
	}
}

WPRMPRC_Shortcode::init();
