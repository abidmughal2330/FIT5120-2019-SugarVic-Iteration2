<?php
/**
 * Settings for Recipe Collections.
 *
 * @link       http://bootstrapped.ventures
 * @since      4.1.0
 *
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-collections
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-collections/includes/public
 */

/**
 * Settings for Recipe Collections.
 *
 * @since      4.1.0
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-collections
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-collections/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMPRC_Settings {

	/**
	 * Register actions and filters.
	 *
	 * @since    2.1.0
	 */
	public static function init() {
		add_filter( 'wprm_settings_structure', array( __CLASS__, 'settings_structure' ) );
	}

	/**
	 * Add recipe submission settings.
	 *
	 * @since    3.0.0
	 * @param    array $structure Settings structure.
	 */
	public static function settings_structure( $structure ) {
		require( WPRMPRC_DIR . 'templates/admin/settings/recipe-collections.php' );
		$structure['recipeCollections'] = $recipe_collections;

		return $structure;
	}
}

WPRMPRC_Settings::init();
