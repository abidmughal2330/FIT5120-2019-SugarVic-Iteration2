<?php
/**
 * Handles the search for nutrition facts.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.0.0
 *
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/addons-pro/advanced-nutrition/includes/admin
 */

/**
 * Handles the search for nutrition facts.
 *
 * @since      1.0.0
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/addons-pro/advanced-nutrition/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMPN_Nutrition_Manager {

	/**
	 * Register actions and filters.
	 *
	 * @since    1.0.0
	 */
	public static function init() {
		add_action( 'wp_ajax_wprm_search_ingredients', array( __CLASS__, 'ajax_search_ingredients' ) );
		add_action( 'wp_ajax_wprm_search_ingredient', array( __CLASS__, 'ajax_search_ingredient' ) );
		add_action( 'wp_ajax_wprm_get_nutrition_facts', array( __CLASS__, 'ajax_get_nutrition_facts' ) );
	}

	/**
	 * Search for a set of ingredients through AJAX.
	 *
	 * @since    1.0.0
	 */
	public static function ajax_search_ingredients() {
		if ( check_ajax_referer( 'wprm', 'security', false ) ) {
			$recipe = isset( $_POST['recipe'] ) ? WPRM_Recipe_Sanitizer::sanitize( wp_unslash( $_POST['recipe'] ) ) : array(); // Input var okay.

			$ingredients_without_groups = array();

			foreach ( $recipe['ingredients'] as $ingredient_group ) {
				$ingredients_without_groups = array_merge( $ingredients_without_groups, $ingredient_group['ingredients'] );
			}

			wp_send_json_success( array(
				'ingredients' => $ingredients_without_groups,
				'mapping' => self::search_ingredients( $ingredients_without_groups ),
			) );
		}

		wp_die();
	}

	/**
	 * Search for a speciifc ingredients through AJAX.
	 *
	 * @since    1.0.0
	 */
	public static function ajax_search_ingredient() {
		if ( check_ajax_referer( 'wprm', 'security', false ) ) {
			$ingredient = isset( $_POST['ingredient'] ) ? sanitize_text_field( wp_unslash( $_POST['ingredient'] ) ) : ''; // Input var okay.

			wp_send_json_success( array(
				'matches' => self::search_ingredient( $ingredient ),
			) );
		}

		wp_die();
	}

	/**
	 * Get the nutrition facts for a set of ingredients through AJAX.
	 *
	 * @since    1.0.0
	 */
	public static function ajax_get_nutrition_facts() {
		if ( check_ajax_referer( 'wprm', 'security', false ) ) {
			$ingredients = isset( $_POST['ingredients'] ) ? wp_unslash( $_POST['ingredients'] ) : array(); // Input var okay.

			$ingredients_with_nutrition = array();

			foreach ( $ingredients as $ingredient ) {
				$source = isset( $ingredient['source'] ) ? sanitize_key( $ingredient['source'] ) : 'api';
				$match = isset( $ingredient['match'] ) ? intval( $ingredient['match'] ) : 0;
				$match_name = isset( $ingredient['match_name'] ) ? sanitize_text_field( $ingredient['match_name'] ) : '';

				if ( $match || 'custom' === $source ) {
					self::save_match( $ingredient['id'], array( 'id' => $match, 'name' => $match_name, 'source' => $source ) );
				}

				if ( $match && 'api' === $source ) {
					$amount = isset( $ingredient['amount'] ) ? trim( $ingredient['amount'] ) : '';
					$unit = isset( $ingredient['unit'] ) ? trim( $ingredient['unit'] ) : '';

					$ingredient['data'] = self::get_nutrition( $amount, $unit, $match );
				} else {
					$ingredient['data'] = false;
				}
				$ingredients_with_nutrition[] = $ingredient;
			}

			wp_send_json_success( array(
				'ingredients' => $ingredients_with_nutrition,
			) );
		}

		wp_die();
	}

	/**
	 * Search for a set of ingredients.
	 *
	 * @since    1.0.0
	 * @param    array $ingredients Ingredients to search for.
	 */
	public static function search_ingredients( $ingredients ) {
		$mapping = array();

		foreach ( $ingredients as $ingredient ) {
			// Check for previous match.
			$prev_match = false;
			
			if ( isset( $ingredient['id'] ) && $ingredient['id'] ) {
				$prev_match = get_term_meta( $ingredient['id'], 'wprmpn_previous_match', true );
			}

			if ( $prev_match ) {
				$mapping[] = array(
					'ingredient' => $ingredient,
					'prev_match' => $prev_match,
				);
			} else {
				$matches = self::search_ingredient( $ingredient['name'] );

				$mapping[] = array(
					'ingredient' => $ingredient,
					'matches' => $matches,
				);
			}
		}

		return $mapping;
	}

	/**
	 * Search for a specific ingredient.
	 *
	 * @since    1.0.0
	 * @param    array $name Ingredient name to search for.
	 */
	public static function search_ingredient( $name ) {
		return WPRMPN_Nutrition_API::search_ingredient( $name );
	}

	/**
	 * Get nutrition facts for a specific ingredient.
	 *
	 * @since    1.0.0
	 * @param    mixed   $amount Amount to get the nutrition facts for.
	 * @param    mixed   $unit   Unit to get the nutrition facts for.
	 * @param    integer $ingredient Ingredient ID to get the nutrition facts for.
	 */
	public static function get_nutrition( $amount, $unit, $ingredient ) {
		return WPRMPN_Nutrition_API::get_nutrition_for( $amount, $unit, $ingredient );
	}

	/**
	 * Save the match the user decided to use for an ingredient.
	 *
	 * @since    1.1.0
	 * @param    mixed $ingredient Ingredient ID to save the match for.
	 * @param    mixed $match  	   Match to save for the ingredient.
	 */
	public static function save_match( $ingredient, $match ) {
		update_term_meta( $ingredient, 'wprmpn_previous_match', $match );
	}
}

WPRMPN_Nutrition_Manager::init();
