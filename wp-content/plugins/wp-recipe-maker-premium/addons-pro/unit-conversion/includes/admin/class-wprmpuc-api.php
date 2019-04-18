<?php
/**
 * Handles interactions with the Nutrition API.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.0.0
 *
 * @package    WP_Recipe_Maker_Premium/addons-pro/unit-conversion
 * @subpackage WP_Recipe_Maker_Premium/addons-pro/unit-conversion/includes/admin
 */

/**
 * Handles interactions with the Nutrition API.
 *
 * @since      1.0.0
 * @package    WP_Recipe_Maker_Premium/addons-pro/unit-conversion
 * @subpackage WP_Recipe_Maker_Premium/addons-pro/unit-conversion/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMPUC_Api {

	/**
	 *  API Key.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $api_key API Key.
	 */
	private static $api_key = 'J8PGMek7P4mshIl43vXScCqfHtWzp19aybhjsncnXnlMhMF2E8';

	/**
	 * Convert a specific ingredient to a specific unit.
	 *
	 * @since    1.0.0
	 * @param    mixed $amount  Amount to convert.
	 * @param    mixed $unit    Unit to convert.
	 * @param    mixed $name    Name of the ingredient to convert.
	 * @param    mixed $unit_to Unit to convert to.
	 */
	public static function convert_ingredient( $amount, $unit, $name, $unit_to ) {
		// Remove special characters and numbers from name.
		$name = trim( preg_replace('/[^a-z\s]/i', '', $name ) );

		$args = array(
			'ingredientName' => $name,
			'sourceAmount' => $amount,
			'sourceUnit' => $unit,
			'targetUnit' => $unit_to,
		);

		return self::api_call( 'convert', $args );
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
			case 'convert':
				$url = 'https://spoonacular-recipe-food-nutrition-v1.p.mashape.com/recipes/convert';
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
