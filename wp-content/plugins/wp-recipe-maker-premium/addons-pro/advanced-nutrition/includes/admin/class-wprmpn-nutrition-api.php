<?php
/**
 * Handles interactions with the Nutrition API.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.0.0
 *
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/addons-pro/advanced-nutrition/includes/admin
 */

/**
 * Handles interactions with the Nutrition API.
 *
 * @since      1.0.0
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/addons-pro/advanced-nutrition/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMPN_Nutrition_Api {

	/**
	 *  API Key.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $api_key API Key.
	 */
	private static $api_key = 'J8PGMek7P4mshIl43vXScCqfHtWzp19aybhjsncnXnlMhMF2E8';

	/**
	 * Search for an ingredient by name.
	 *
	 * @since    1.0.0
	 * @param    mixed   $name    Name of the ingredient to search for.
	 * @param    integer $results Number of results to show. Defaults to 20.
	 */
	public static function search_ingredient( $name, $results = 20 ) {
		$args = array(
			'query' => wp_strip_all_tags( strip_shortcodes( $name ) ),
			'number' => $results,
			'metaInformation' => 'true',
		);

		return self::api_call( 'search', $args );
	}

	/**
	 * Get the nutrition data for a specific ingredient.
	 *
	 * @since    1.0.0
	 * @param    mixed   $amount Amount to get the nutrition facts for.
	 * @param    mixed   $unit   Unit to get the nutrition facts for.
	 * @param    integer $ingredient Ingredient ID to get the nutrition facts for.
	 */
	public static function get_nutrition_for( $amount, $unit, $ingredient ) {
		$args = array(
			'id' => $ingredient,
		);

		if ( $amount ) {
			$args['amount'] = wp_strip_all_tags( strip_shortcodes( $amount ) );
		}

		if ( $unit ) {
			$args['unit'] = wp_strip_all_tags( strip_shortcodes( $unit ) );
		}

		return self::api_call( 'nutrition', $args );
	}

	/**
	 * Perform an API call.
	 *
	 * @since    1.0.0
	 * @param 	 mixed $function API function to call.
	 * @param 	 array $args     Arguments for the API call.
	 */
	private static function api_call( $function, $args ) {
		switch ( $function ) {
			case 'search':
				$url = 'https://spoonacular-recipe-food-nutrition-v1.p.mashape.com/food/ingredients/autocomplete';
				break;
			case 'nutrition':
				$url = 'https://spoonacular-recipe-food-nutrition-v1.p.mashape.com/food/ingredients/' . $args['id'] . '/information';
				unset( $args['id'] );
				break;
			default:
				return false;
		}

		// Construct parameter string.
		$params = array();
		foreach ( $args as $key => $val ) {
			$params[] = $key . '=' . rawurlencode( $val );
		}

		$url .= '?' . implode( '&', $params );

		$response = wp_remote_get( $url, array(
				'timeout' => 45,
				'headers' => array(
					'X-Mashape-Key' => self::$api_key,
					'Accept' => 'application/json',
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			return array();
		} else {
			return json_decode( $response['body'] );
		}
	}
}
