<?php
/**
 * Add Premium Nutrition features to the recipe modal.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.0.0
 *
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/addons-pro/advanced-nutrition/includes/admin
 */

/**
 * Add Premium Nutrition features to the recipe modal.
 *
 * @since      1.0.0
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/addons-pro/advanced-nutrition/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMPN_Modal {

	/**
	 * Register actions and filters.
	 *
	 * @since    1.0.0
	 */
	public static function init() {
		add_filter( 'wprm_admin_modal_menu', array( __CLASS__, 'modal_menu' ), 15 );
	}

	/**
	 * Add Premium features to the modal menu.
	 *
	 * @since    1.0.0
	 * @param    array $menu Modal menu to filter.
	 */
	public static function modal_menu( $menu ) {
		// Override Nutrition Facts template.
		$menu['recipe']['tabs']['recipe-nutrition-facts']['template'] = WPRMPN_DIR . 'templates/admin/modal/tabs/recipe-nutrition-facts.php';

		return $menu;
	}
}

WPRMPN_Modal::init();
