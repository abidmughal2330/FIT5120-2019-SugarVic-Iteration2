<?php
/**
 * Add Recipe Submission to the recipe modal.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.1.0
 *
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-submission
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-submission/includes/admin
 */

/**
 * Add Recipe Submission to the recipe modal.
 *
 * @since      2.1.0
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-submission
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-submission/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMPRS_Modal {

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
		// Nutrition Label.
		$menu['recipe-submission'] = array(
			'order' => 350,
			'label' => __( 'Recipe Submission', 'wp-recipe-maker-premium' ),
			'tabs' => array(
				'recipe-submission-shortcode' => array(
					'order' => 100,
					'label' => __( 'Recipe Submission Shortcode', 'wp-recipe-maker-premium' ),
					'template' => WPRMPRS_DIR . 'templates/admin/modal/recipe-submission-shortcode.php',
					'callback' => 'insert_recipe_submission',
				),
			),
			'default_tab' => 'recipe-submission-shortcode',
		);

		return $menu;
	}
}

WPRMPRS_Modal::init();
