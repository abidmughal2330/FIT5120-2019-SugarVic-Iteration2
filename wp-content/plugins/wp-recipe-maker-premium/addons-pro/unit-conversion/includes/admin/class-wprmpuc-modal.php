<?php
/**
 * Add Premium Nutrition features to the recipe modal.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.0.0
 *
 * @package    WP_Recipe_Maker_Premium/addons-pro/unit-conversion
 * @subpackage WP_Recipe_Maker_Premium/addons-pro/unit-conversion/includes/admin
 */

/**
 * Add Premium Nutrition features to the recipe modal.
 *
 * @since      1.0.0
 * @package    WP_Recipe_Maker_Premium/addons-pro/unit-conversion
 * @subpackage WP_Recipe_Maker_Premium/addons-pro/unit-conversion/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMPUC_Modal {

	/**
	 * Register actions and filters.
	 *
	 * @since    1.0.0
	 */
	public static function init() {
		if ( WPRM_Settings::get( 'unit_conversion_enabled' ) ) {
			add_filter( 'wprm_admin_modal_menu', array( __CLASS__, 'modal_menu' ), 15 );
		}
	}

	/**
	 * Add Premium features to the modal menu.
	 *
	 * @since    1.0.0
	 * @param    array $menu Modal menu to filter.
	 */
	public static function modal_menu( $menu ) {
		$menu['recipe']['tabs']['recipe-unit-conversion'] = array(
			'order' => 210,
			'label' => __( 'Unit Conversion', 'wp-recipe-maker-premium' ),
			'template' => WPRMPUC_DIR . 'templates/admin/modal/tabs/recipe-unit-conversion.php',
			'callback' => 'insert_update_recipe',
		);

		return $menu;
	}
}

WPRMPUC_Modal::init();
