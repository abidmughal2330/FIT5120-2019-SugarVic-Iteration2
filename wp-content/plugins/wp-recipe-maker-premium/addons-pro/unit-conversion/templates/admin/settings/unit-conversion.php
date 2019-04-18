<?php
/**
 * Template for the unit conversion settings sub page.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.0.0
 *
 * @package    WP_Recipe_Maker_Premium/addons-pro/unit-conversion
 * @subpackage WP_Recipe_Maker_Premium/addons-pro/unit-conversion/templates/admin/settings
 */

$default_units_data = array(
	'pound' => array(
		'label' => esc_html__( 'Pounds', 'wp-recipe-maker-premium' ),
		'aliases' => array( 'lb', 'lbs', 'pound', 'pounds' ),
		'singular' => esc_html( _x( 'lb', 'singular unit for pounds', 'wp-recipe-maker-premium' ) ),
		'plural' => esc_html( _x( 'lb', 'plural unit for pounds', 'wp-recipe-maker-premium' ) ),
	),
	'ounce' => array(
		'label' => esc_html__( 'Ounces', 'wp-recipe-maker-premium' ),
		'aliases' => array( 'oz', 'ounce', 'ounces' ),
		'singular' => esc_html( _x( 'oz', 'singular unit for ounces', 'wp-recipe-maker-premium' ) ),
		'plural' => esc_html( _x( 'oz', 'plural unit for ounces', 'wp-recipe-maker-premium' ) ),
	),
	'kilogram' => array(
		'label' => esc_html__( 'Kilograms', 'wp-recipe-maker-premium' ),
		'aliases' => array( 'kg', 'kilogram', 'kilograms' ),
		'singular' => esc_html( _x( 'kg', 'singular unit for kilograms', 'wp-recipe-maker-premium' ) ),
		'plural' => esc_html( _x( 'kg', 'plural unit for kilograms', 'wp-recipe-maker-premium' ) ),
	),
	'gram' => array(
		'label' => esc_html__( 'Grams', 'wp-recipe-maker-premium' ),
		'aliases' => array( 'g', 'gram', 'grams' ),
		'singular' => esc_html( _x( 'g', 'singular unit for grams', 'wp-recipe-maker-premium' ) ),
		'plural' => esc_html( _x( 'g', 'plural unit for grams', 'wp-recipe-maker-premium' ) ),
	),
	'milligram' => array(
		'label' => esc_html__( 'Milligrams', 'wp-recipe-maker-premium' ),
		'aliases' => array( 'mg', 'milligram', 'milligrams' ),
		'singular' => esc_html( _x( 'mg', 'singular unit for milligrams', 'wp-recipe-maker-premium' ) ),
		'plural' => esc_html( _x( 'mg', 'plural unit for milligrams', 'wp-recipe-maker-premium' ) ),
	),
	'cup' => array(
		'label' => esc_html__( 'Cups', 'wp-recipe-maker-premium' ),
		'aliases' => array( 'cup', 'cups', 'cu', 'c' ),
		'singular' => esc_html( _x( 'cup', 'singular unit for cups', 'wp-recipe-maker-premium' ) ),
		'plural' => esc_html( _x( 'cups', 'plural unit for cups', 'wp-recipe-maker-premium' ) ),
	),
	'gallon' => array(
		'label' => esc_html__( 'Gallons', 'wp-recipe-maker-premium' ),
		'aliases' => array( 'gal', 'gallon', 'gallons' ),
		'singular' => esc_html( _x( 'gal', 'singular unit for gallons', 'wp-recipe-maker-premium' ) ),
		'plural' => esc_html( _x( 'gal', 'plural unit for gallons', 'wp-recipe-maker-premium' ) ),
	),
	'pint' => array(
		'label' => esc_html__( 'Pints', 'wp-recipe-maker-premium' ),
		'aliases' => array( 'pt', 'pint', 'pints' ),
		'singular' => esc_html( _x( 'pt', 'singular unit for pints', 'wp-recipe-maker-premium' ) ),
		'plural' => esc_html( _x( 'pt', 'plural unit for pints', 'wp-recipe-maker-premium' ) ),
	),
	'fluid_ounce' => array(
		'label' => esc_html__( 'Fluid Ounces', 'wp-recipe-maker-premium' ),
		'aliases' => array( 'floz', 'fluid ounce', 'fluid ounces', 'fl ounce', 'fl ounces' ),
		'singular' => esc_html( _x( 'floz', 'singular unit for fluid ounces', 'wp-recipe-maker-premium' ) ),
		'plural' => esc_html( _x( 'floz', 'plural unit for fluid ounces', 'wp-recipe-maker-premium' ) ),
	),
	'liter' => array(
		'label' => esc_html__( 'Liters', 'wp-recipe-maker-premium' ),
		'aliases' => array( 'l', 'liter', 'liters' ),
		'singular' => esc_html( _x( 'l', 'singular unit for liters', 'wp-recipe-maker-premium' ) ),
		'plural' => esc_html( _x( 'l', 'plural unit for liters', 'wp-recipe-maker-premium' ) ),
	),
	'deciliter' => array(
		'label' => esc_html__( 'Deciliters', 'wp-recipe-maker-premium' ),
		'aliases' => array( 'dl', 'deciliter', 'deciliters' ),
		'singular' => esc_html( _x( 'dl', 'singular unit for deciliters', 'wp-recipe-maker-premium' ) ),
		'plural' => esc_html( _x( 'dl', 'plural unit for deciliters', 'wp-recipe-maker-premium' ) ),
	),
	'centiliter' => array(
		'label' => esc_html__( 'Centiliters', 'wp-recipe-maker-premium' ),
		'aliases' => array( 'cl', 'centiliter', 'centiliters' ),
		'singular' => esc_html( _x( 'cl', 'singular unit for centiliters', 'wp-recipe-maker-premium' ) ),
		'plural' => esc_html( _x( 'cl', 'plural unit for centiliters', 'wp-recipe-maker-premium' ) ),
	),
	'milliliter' => array(
		'label' => esc_html__( 'Milliliters', 'wp-recipe-maker-premium' ),
		'aliases' => array( 'ml', 'milliliter', 'milliliters' ),
		'singular' => esc_html( _x( 'ml', 'singular unit for milliliters', 'wp-recipe-maker-premium' ) ),
		'plural' => esc_html( _x( 'ml', 'plural unit for milliliters', 'wp-recipe-maker-premium' ) ),
	),
	'tablespoon' => array(
		'label' => esc_html__( 'Tablespoons', 'wp-recipe-maker-premium' ),
		'aliases' => array( 'tablespoon', 'tablespoons', 'tbsp', 'tbsps', 'tbls', 'tb', 'tbs', 'T' ),
		'singular' => esc_html( _x( 'tbsp', 'singular unit for tablespoons', 'wp-recipe-maker-premium' ) ),
		'plural' => esc_html( _x( 'tbsp', 'plural unit for tablespoons', 'wp-recipe-maker-premium' ) ),
	),
	'teaspoon' => array(
		'label' => esc_html__( 'Teaspoons', 'wp-recipe-maker-premium' ),
		'aliases' => array( 'teaspoon', 'teaspoons', 'tsp', 'tsps', 't' ),
		'singular' => esc_html( _x( 'tsp', 'singular unit for teaspoons', 'wp-recipe-maker-premium' ) ),
		'plural' => esc_html( _x( 'tsp', 'plural unit for teaspoons', 'wp-recipe-maker-premium' ) ),
	),
);

$unit_conversion = array(
	'id' => 'unitConversion',
	'name' => __( 'Unit Conversion', 'wp-recipe-maker' ),
	'required' => 'pro',
	'description' => __( 'Reach an international audience by providing your recipes in both Metric and US Customary units.', 'wp-recipe-maker' ),
	'documentation' => 'https://help.bootstrapped.ventures/article/18-unit-conversion',
	'settings' => array(
		array(
			'id' => 'unit_conversion_enabled',
			'name' => __( 'Enable Unit Conversion', 'wp-recipe-maker-premium' ),
			'type' => 'toggle',
			'default' => false,
		),
	),
	'subGroups' => array(
		array(
			'name' => __( 'Unit Systems', 'wp-recipe-maker-premium' ),
			'dependency' => array(
				'id' => 'unit_conversion_enabled',
				'value' => true,
			),
			'settings' => array(
				array(
					'id' => 'unit_conversion_system_1',
					'name' => __( 'Default Unit System', 'wp-recipe-maker-premium' ),
					'description' => __( 'Name of the unit system you write your recipes in.', 'wp-recipe-maker-premium' ),
					'type' => 'text',
					'default' => 'US Customary',
				),
				array(
					'id' => 'unit_conversion_system_2',
					'name' => __( 'Converted Unit System', 'wp-recipe-maker-premium' ),
					'description' => __( 'Name of the unit system you want to convert to.', 'wp-recipe-maker-premium' ),
					'type' => 'text',
					'default' => 'Metric',
				),
			),
		),
		array(
			'name' => __( 'Converted Unit System', 'wp-recipe-maker-premium' ),
			'description' => __( 'Units you want the ingredients to use after converting', 'wp-recipe-maker-premium' ),
			'dependency' => array(
				'id' => 'unit_conversion_enabled',
				'value' => true,
			),
			'settings' => array(
				array(
					'id' => 'unit_conversion_system_2_weight_units',
					'name' => __( 'Weight Units', 'wp-recipe-maker-premium' ),
					'type' => 'dropdownMultiselect',
					'options' => array(
						'cup' => $default_units_data['cup']['label'],
						'pound' => $default_units_data['pound']['label'],
						'ounce' => $default_units_data['ounce']['label'],
						'kilogram' => $default_units_data['kilogram']['label'],
						'gram' => $default_units_data['gram']['label'],
						'milligram' => $default_units_data['milligram']['label'],
					),
					'default' => array( 'kilogram', 'gram', 'milligram' ),
				),
				array(
					'id' => 'unit_conversion_system_2_volume_units',
					'name' => __( 'Volume Units', 'wp-recipe-maker-premium' ),
					'type' => 'dropdownMultiselect',
					'options' => array(
						'cup' => $default_units_data['cup']['label'],
						'gallon' => $default_units_data['gallon']['label'],
						'pint' => $default_units_data['pint']['label'],
						'fluid_ounce' => $default_units_data['fluid_ounce']['label'],
						'liter' => $default_units_data['liter']['label'],
						'deciliter' => $default_units_data['deciliter']['label'],
						'centiliter' => $default_units_data['centiliter']['label'],
						'milliliter' => $default_units_data['milliliter']['label'],
						'tablespoon' => $default_units_data['tablespoon']['label'],
						'teaspoon' => $default_units_data['teaspoon']['label'],
					),
					'default' => array( 'liter', 'milliliter', 'tablespoon', 'teaspoon' ),
				),
				array(
					'id' => 'unit_conversion_round_to_decimals',
					'name' => __( 'Round quantity to', 'wp-recipe-maker' ),
					'description' => __( 'Number of decimals to round a quantity to when calculating converted values.', 'wp-recipe-maker' ),
					'type' => 'number',
					'suffix' => 'decimals',
					'default' => '2',
				),
			),
		),
		array(
			'name' => __( 'Units', 'wp-recipe-maker-premium' ),
			'description' => __( 'How to recognize and display specific units. Use ; to separate aliases.', 'wp-recipe-maker-premium' ),
			'dependency' => array(
				'id' => 'unit_conversion_enabled',
				'value' => true,
			),
			'settings' => array(
				array(
					'id' => 'unit_conversion_units',
					'type' => 'unitConversionUnits',
					'default' => $default_units_data,
					'sanitize' => function( $value ) use ( $default_units_data ) {
						$new_units_data = array();

						foreach ( $default_units_data as $unit => $unit_data ) {
							if ( isset( $value[ $unit] ) ) {
								$label = $default_units_data[ $unit ]['label'];
								$aliases = isset( $value[ $unit ]['aliases'] ) ? $value[ $unit ]['aliases'] : array();
								$singular = isset( $value[ $unit ]['singular'] ) ? $value[ $unit ]['singular'] : '';
								$plural = isset( $value[ $unit ]['plural'] ) ? $value[ $unit ]['plural'] : '';

								// Make sure singular and plural are an alias as well.
								$aliases[] = $singular;
								$aliases[] = $plural;

								// Clean up array.
								$aliases = array_values( array_filter( array_unique( $aliases ) ) );

								// If values not set, use default.
								$aliases = ! empty( $aliases ) ? $aliases : $default_units_data[ $unit ]['aliases'];
								$singular = $singular ? $singular : $default_units_data[ $unit ]['singular'];
								$plural = $plural ? $plural : $default_units_data[ $unit ]['plural'];

								$new_units_data[ $unit ] = array(
									'label' => $label,
									'aliases' => $aliases,
									'singular' => $singular,
									'plural' => $plural,
								);
							} else {
								$new_units_data[ $unit ] = $default_units_data[ $unit ];
							}
						}

						return $new_units_data;
					},
				),
			),
		),
	),
);
