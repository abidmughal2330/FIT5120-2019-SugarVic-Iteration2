<?php
/**
 * Save recipe from the Recipe Submission form data.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.1.0
 *
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-submission
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-submission/includes/public
 */

/**
 * Save recipe from the Recipe Submission form data.
 *
 * @since      2.1.0
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-submission
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-submission/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMPRS_Saver {
	/**
	 * Save recipe submission.
	 *
	 * @since	2.1.0
	 * @param	array $user User that submitted the form.
	 * @param	array $recipe Recipe that was submitted through the form.
	 */
	public static function save_recipe( $user, $recipe ) {
		$recipe = WPRM_Recipe_Sanitizer::sanitize( $recipe );

		// Create recipe as pending.
		$post = array(
			'post_type' => WPRM_POST_TYPE,
			'post_status' => 'pending',
		);

		$recipe_id = wp_insert_post( $post );

		// Save recipe data.
		WPRM_Recipe_Saver::update_recipe( $recipe_id, $recipe );

		// Save user data.
		update_post_meta( $recipe_id, 'wprm_submission_user', $user );
	}
}
