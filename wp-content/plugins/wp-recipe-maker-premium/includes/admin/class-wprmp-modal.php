<?php
/**
 * Add Premium features to the recipe modal.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.0.0
 *
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/includes/admin
 */

/**
 * Add Premium features to the recipe modal.
 *
 * @since      1.0.0
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMP_Modal {

	/**
	 * Register actions and filters.
	 *
	 * @since    1.0.0
	 */
	public static function init() {
		add_filter( 'wprm_admin_modal_menu', array( __CLASS__, 'modal_menu' ) );
	}

	/**
	 * Add Premium features to the modal menu.
	 *
	 * @since    1.0.0
	 * @param    array $menu Modal menu to filter.
	 */
	public static function modal_menu( $menu ) {
		// Add new recipe from other.
		$menu['clone-recipe'] = array(
			'order' => 102,
			'label' => __( 'New Recipe from...', 'wp-recipe-maker' ),
			'tabs' => array(
				'clone-recipe-select' => array(
					'order' => 100,
					'label' => __( 'New Recipe from...', 'wp-recipe-maker' ),
					'template' => WPRMP_DIR . 'templates/admin/modal/tabs/clone-recipe.php',
					'callback' => 'clone_recipe',
					'button' => __( 'Clone Recipe', 'wp-recipe-maker' ),
				),
			),
			'default_tab' => 'clone-recipe-select',
		);

		// Ingredient Links.
		if ( current_user_can( WPRM_Settings::get( 'features_manage_access' ) ) ) {
			$menu['recipe']['tabs']['recipe-ingredient-links'] = array(
				'order' => 225,
				'label' => __( 'Ingredient Links', 'wp-recipe-maker-premium' ),
				'template' => WPRMP_DIR . 'templates/admin/modal/tabs/recipe-ingredient-links.php',
				'callback' => 'insert_update_recipe',
			);
		}

		// Nutrition Facts.
		$menu['recipe']['tabs']['recipe-nutrition-facts'] = array(
			'order' => 250,
			'label' => __( 'Nutrition Facts', 'wp-recipe-maker-premium' ),
			'template' => WPRMP_DIR . 'templates/admin/modal/tabs/recipe-nutrition-facts.php',
			'callback' => 'insert_update_recipe',
		);

		// Nutrition Label.
		$menu['nutrition-label'] = array(
			'order' => 300,
			'label' => __( 'Nutrition Label', 'wp-recipe-maker-premium' ),
			'tabs' => array(
				'nutrition-label-shortcode' => array(
					'order' => 100,
					'label' => __( 'Label Shortcode', 'wp-recipe-maker-premium' ),
					'template' => WPRMP_DIR . 'templates/admin/modal/tabs/nutrition-label-shortcode.php',
					'callback' => 'insert_nutrition_label',
					'init' => 'reset_nutrition_label',
				),
			),
			'default_tab' => 'nutrition-label-shortcode',
		);

		return $menu;
	}
}

WPRMP_Modal::init();
