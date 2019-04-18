<?php
/**
 * Handle the Nutrition Calculation API.
 *
 * @link       http://bootstrapped.ventures
 * @since      5.0.0
 *
 * @package    WP_Recipe_Maker_Premium/addons-pro/advanced-nutrition
 * @subpackage WP_Recipe_Maker_Premium/addons-pro/advanced-nutrition/includes/public
 */

/**
 * Handle the Nutrition Calculation API.
 *
 * @since      5.0.0
 * @package    WP_Recipe_Maker_Premium/addons-pro/advanced-nutrition
 * @subpackage WP_Recipe_Maker_Premium/addons-pro/advanced-nutrition/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMPN_Api {

	/**
	 * Register actions and filters.
	 *
	 * @since    5.0.0
	 */
	public static function init() {
		add_action( 'rest_api_init', array( __CLASS__, 'api_register_data' ) );
	}

	/**
	 * Register data for the REST API.
	 *
	 * @since    5.0.0
	 */
	public static function api_register_data() {
		if ( function_exists( 'register_rest_field' ) ) { // Prevent issue with Jetpack.
			register_rest_route( 'wp-recipe-maker/v1', '/nutrition/matches', array(
				'callback' => array( __CLASS__, 'api_get_matches' ),
				'methods' => 'POST',
				'permission_callback' => array( __CLASS__, 'api_required_permissions' ),
			));
			register_rest_route( 'wp-recipe-maker/v1', '/nutrition/matches/options', array(
				'callback' => array( __CLASS__, 'api_get_matches_options' ),
				'methods' => 'POST',
				'permission_callback' => array( __CLASS__, 'api_required_permissions' ),
			));
		}
	}

	/**
	 * Required permissions for the API.
	 *
	 * @since 5.0.0
	 */
	public static function api_required_permissions() {
		return current_user_can( 'edit_posts' );
	}

	/**
	 * Handle get matches call to the REST API.
	 *
	 * @since 5.0.0
	 * @param WP_REST_Request $request Current request.
	 */
	public static function api_get_matches( $request ) {
		// Required classes.
		require_once( WPRMPN_DIR . 'includes/admin/class-wprmpn-nutrition-api.php' );

		// Parameters.
		$params = $request->get_params();

		$ingredients = isset( $params['ingredients'] ) ? $params['ingredients'] : array();

		foreach ( $ingredients as $index => $ingredient ) {
			// Check for previous match.
			$match = false;
			if ( isset( $ingredient['id'] ) && $ingredient['id'] ) {
				$match = get_term_meta( $ingredient['id'], 'wprmpn_previous_match', true );
			}

			// Find potential matches if there is no previous match.
			$match_options = false;
			$match_search = '';
			if ( ! $match && isset( $ingredient['name'] ) && $ingredient['name'] ) {
				$match_options = WPRMPN_Nutrition_API::search_ingredient( $ingredient['name'] );
				$match_search = $ingredient['name'];

				if ( $match_options && isset( $match_options[0] ) ) {
					$match = (array) $match_options[0];
				}
			}

			// Make sure array exists.
			if ( ! isset( $ingredients[ $index ]['nutrition'] ) ) {
				$ingredients[ $index ]['nutrition'] = array();
			}

			// Set matches.
			$ingredients[ $index ]['nutrition']['source'] = isset( $match['source'] ) ? $match['source'] : 'api';
			$ingredients[ $index ]['nutrition']['match'] = $match;
			$ingredients[ $index ]['nutrition']['matchOptions'] = $match_options;
			$ingredients[ $index ]['nutrition']['matchSearch'] = $match_search;

			// Clean up values.
			$ingredients[ $index ]['amount'] = wp_strip_all_tags( strip_shortcodes( $ingredient['amount'] ) );
			$ingredients[ $index ]['unit'] = wp_strip_all_tags( strip_shortcodes( $ingredient['unit'] ) );
			$ingredients[ $index ]['name'] = wp_strip_all_tags( strip_shortcodes( $ingredient['name'] ) );
			$ingredients[ $index ]['notes'] = wp_strip_all_tags( strip_shortcodes( $ingredient['notes'] ) );
		}

		return array(
			'ingredients' => $ingredients,
		);
	}

	/**
	 * Handle get matches options call to the REST API.
	 *
	 * @since 5.0.0
	 * @param WP_REST_Request $request Current request.
	 */
	public static function api_get_matches_options( $request ) {
		// Required classes.
		require_once( WPRMPN_DIR . 'includes/admin/class-wprmpn-nutrition-api.php' );

		// Parameters.
		$params = $request->get_params();
		$search = isset( $params['search'] ) ? $params['search'] : array();

		return array(
			'matchOptions' => WPRMPN_Nutrition_API::search_ingredient( $search ),
		);
	}
}

WPRMPN_Api::init();
