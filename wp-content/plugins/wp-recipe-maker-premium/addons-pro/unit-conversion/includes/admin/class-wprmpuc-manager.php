<?php
/**
 * Handles the unit conversion.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.0.0
 *
 * @package    WP_Recipe_Maker_Premium/addons-pro/unit-conversion
 * @subpackage WP_Recipe_Maker_Premium/addons-pro/unit-conversion/includes/admin
 */

/**
 * Handles the unit conversion.
 *
 * @since      1.0.0
 * @package    WP_Recipe_Maker_Premium/addons-pro/unit-conversion
 * @subpackage WP_Recipe_Maker_Premium/addons-pro/unit-conversion/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMPUC_Manager {

	/**
	 * Register actions and filters.
	 *
	 * @since    1.0.0
	 */
	public static function init() {
		add_action( 'wp_ajax_wprm_calculate_unit_conversion', array( __CLASS__, 'ajax_calculate_unit_conversion' ) );
	}

	/**
	 * Calculate unit conversion.
	 *
	 * @since    1.0.0
	 */
	public static function ajax_calculate_unit_conversion() {
		if ( check_ajax_referer( 'wprm', 'security', false ) ) {
			$ingredients = isset( $_POST['ingredients'] ) ? wp_unslash( $_POST['ingredients'] ) : array(); // Input var okay.
			$system = isset( $_POST['system'] ) ? intval( $_POST['system'] ) : 2; // Input var okay.

			$conversion = array();

			foreach ( $ingredients as $uid => $ingredient ) {
				$unit_from = self::get_unit_from_alias( $ingredient['unit'] );
				$units_to = isset( $ingredient['units_to'] ) ? $ingredient['units_to'] : false;

				if ( ! $units_to ) {
					$units_to = self::calculate_units( $unit_from, $system );
				}

				if ( $units_to ) {
					$conversion[ $uid ] = self::calculate_best_conversion( $ingredient, $unit_from, $units_to );
				}

				if ( ! isset( $conversion[ $uid ] ) || ! $conversion[ $uid ] ) {
					$conversion[ $uid ] = array(
						'amount' => $ingredient['amount'],
						'unit' => $unit_from,
						'alias' => $ingredient['unit'],
						'type' => 'none',
					);
				} else {
					// Conversion type.	
					$conversion[ $uid ]['type'] = $conversion[ $uid ]['unit'] === $unit_from ? 'none' : 'automatic';
				}
			}

			wp_send_json_success( array(
				'conversion' => $conversion,
			) );
		}

		wp_die();
	}

	/**
	 * Calculate possible units to convert to for a specific unit.
	 *
	 * @since    1.0.0
	 * @param 	 mixed   $unit_from Unit to convert from.
	 * @param 	 integer $system 	System to convert to.
	 */
	public static function calculate_units( $unit_from, $system ) {
		$units_to = false;

		if ( $unit_from ) {
			$weight_units = array( 'cup', 'pound', 'ounce', 'kilogram', 'gram', 'milligram' );

			$unit_type = in_array( $unit_from, $weight_units, true ) ? 'weight' : 'volume';
			$units_to = WPRM_Settings::get( 'unit_conversion_system_' . $system . '_' . $unit_type . '_units' );
		}

		return $units_to;
	}

	/**
	 * Calculate best unit conversion for a specific ingredient.
	 *
	 * @since    1.0.0
	 * @param 	 array $ingredient Ingredient to calculate the unit conversion for.
	 * @param 	 mixed $unit_from  Unit to convert from.
	 * @param 	 array $units_to   Possible units to convert to.
	 */
	public static function calculate_best_conversion( $ingredient, $unit_from, $units_to ) {
		$best_conversion = false;

		// Check if we can just keep the same unit.
		if ( in_array( $unit_from, $units_to, true ) ) {
			$best_conversion = array(
				'amount' => $ingredient['amount'],
				'unit' => $unit_from,
				'alias' => $ingredient['unit'],
			);
		} else {
			// Find best match from possible units.
			foreach ( $units_to as $unit_to ) {
				$conversion = self::calculate_conversion( $ingredient, $unit_from, $unit_to );

				if ( $conversion ) {
					if ( ! $best_conversion ) {
						$best_conversion = $conversion;
					} else {
						// Check if this new conversion is better than the other.
						$upper_limit = in_array( $conversion['unit'], array( 'teaspoon', 'tablespoon' ), true ) ? 10 : 999;

						if ( 1 <= $conversion['amount'] && $conversion['amount'] < $best_conversion['amount'] ) {
							$best_conversion = $conversion;
						} elseif ( $conversion['amount'] < $upper_limit && $conversion['amount'] > $best_conversion['amount'] ) {
							$best_conversion = $conversion;
						}
					}

					// Check if this conversion is good enough.
					$upper_limit = $best_conversion && in_array( $best_conversion['unit'], array( 'teaspoon', 'tablespoon' ), true ) ? 10 : 999;
					if ( 1 <= $best_conversion['amount'] && $best_conversion['amount'] <= $upper_limit ) {
						return $best_conversion;
					}
				}
			}
		}

		return $best_conversion;
	}

	/**
	 * Calculate unit conversion for a specific ingredient.
	 *
	 * @since    1.0.0
	 * @param 	 array $ingredient Ingredient to calculate the unit conversion for.
	 * @param 	 mixed $unit_from  Unit to convert from.
	 * @param 	 mixed $unit_to    Unit to convert to.
	 */
	public static function calculate_conversion( $ingredient, $unit_from, $unit_to ) {
		$converted = false;

		$amount = floatval( $ingredient['amount'] );

		if ( $unit_from && $unit_to && 'cup' !== $unit_from && 'cup' !== $unit_to ) {
			$unit_conversion = array(
				// Base unit for weights is grams (1g = 1ml).
				'pound' => 453.592,
				'ounce' => 28.3496,
				'kilogram' => 1000.0,
				'gram' => 1.0,
				'milligram' => 0.001,
				// Base unit for volume is milliliters (1g = 1ml).
				'gallon' => 3785.41,
				'pint' => 473.176,
				'fluid_ounce' => 29.5735,
				'liter' => 1000.0,
				'deciliter' => 100.0,
				'centiliter' => 10.0,
				'milliliter' => 1.0,
				'tablespoon' => 14.7868,
				'teaspoon' => 4.92892,
			);

			$converted_amount = $amount * $unit_conversion[ $unit_from ] / $unit_conversion[ $unit_to ];

			$converted = array(
				'amount' => $converted_amount,
				'unit' => $unit_to,
				'alias' => self::get_alias_for( $converted_amount, $unit_to ),
			);
		} else {
			$result = WPRMPUC_Api::convert_ingredient( $ingredient['amount'], $ingredient['unit'], $ingredient['name'], str_replace( '_', ' ', $unit_to ) );

			if ( !isset( $result->status ) || 'failure' !== $result->status ) {
				$converted = array(
					'amount' => $result->targetAmount,
					'unit' => $unit_to,
					'alias' => self::get_alias_for( 2, $unit_to ),
				);
			}
		}
		return $converted;
	}

	/**
	 * Get unit from unit alias.
	 *
	 * @since    1.0.0
	 * @param 	 mixed $alias Alias to get the unit for.
	 */
	public static function get_unit_from_alias( $alias ) {
		// Clean up alias.
		$alias = trim( $alias );
		$alias = preg_replace( '/[^[:alnum:]]/u', '', $alias );

		// Check all units for exact alias match.
		$units_data = WPRM_Settings::get( 'unit_conversion_units' );
		foreach ( $units_data as $unit => $data ) {
			if ( in_array( $alias, $data['aliases'], true ) ) {
				return $unit;
			}
		}

		// Nothing found? Check again, all lowercase.
		$alias = strtolower( $alias );

		foreach ( $units_data as $unit => $data ) {
			$aliases = array_map( 'strtolower', $data['aliases'] );
			if ( in_array( $alias, $aliases, true ) ) {
				return $unit;
			}
		}

		return false;
	}

	/**
	 * Get alias for a specific amount and unit.
	 *
	 * @since    1.0.0
	 * @param 	 float $amount Amount of the unit that we have.
	 * @param 	 mixed $unit   Unit to get the alias for.
	 */
	public static function get_alias_for( $amount, $unit ) {
		$units_data = WPRM_Settings::get( 'unit_conversion_units' );

		$type = 0 < $amount && $amount <= 1 ? 'singular' : 'plural';
		return $units_data[ $unit ][ $type ];
	}
}

WPRMPUC_Manager::init();
