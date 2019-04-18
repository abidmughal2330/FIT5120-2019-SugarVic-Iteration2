<?php
/**
 * Responsible for displaying the Nutrition Label for recipes.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.0.0
 *
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/includes/public
 */

/**
 * Responsible for displaying the Nutrition Label for recipes.
 *
 * @since      1.0.0
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMP_Nutrition_Label {

	/**
	 * Default nutrition fields.
	 *
	 * @since	4.0.0
	 * @var		array $nutrition_fields Default nutrition fields.
	 */
	public static $nutrition_fields = array();

	/**
	 * Units for the nutrition fields.
	 *
	 * @deprecated Use $nutrition_fields instead.
	 * @since	1.0.0
	 * @var	 	array $nutrition_units Units for the nutrition fields.
	 */
	public static $nutrition_units = array(
		'serving_size' => 'g',
		'calories' => 'kcal',
		'carbohydrates' => 'g',
		'protein' => 'g',
		'fat' => 'g',
		'saturated_fat' => 'g',
		'polyunsaturated_fat' => 'g',
		'monounsaturated_fat' => 'g',
		'trans_fat' => 'g',
		'cholesterol' => 'mg',
		'sodium' => 'mg',
		'potassium' => 'mg',
		'fiber' => 'g',
		'sugar' => 'g',
		'vitamin_a' => '%',
		'vitamin_c' => '%',
		'calcium' => '%',
		'iron' => '%',
	);

	/**
	 * Daily values for the nutrition fields.
	 *
	 * @since    1.0.0
	 * @var      array     $daily_values    Daily values for the nutrition fields.
	 */
	public static $daily_values = array();

	/**
	 * Register actions and filters.
	 *
	 * @since    1.0.0
	 */
	public static function init() {
		// Optionally get custom daily values from settings.
		$daily_values = array(
			'carbohydrates' => 300,
			'protein' 		=> 50,
			'fat' 		    => 65,
			'saturated_fat' => 20,
			'cholesterol' 	=> 300,
			'sodium' 		=> 2400,
			'potassium' 	=> 3500,
			'fiber' 		=> 25,
		);

		if ( WPRM_Settings::get( 'nutrition_label_custom_daily_values' ) ) {
			foreach ( $daily_values as $nutrient => $default ) {
				$value = intval( WPRM_Settings::get( 'nutrition_label_custom_daily_values_' . $nutrient ) );

				if ( $value ) {
					$daily_values[ $nutrient ] = $value;
				}
			}
		}
		self::$daily_values = $daily_values;

		// Set nutrition fields.
		self::$nutrition_fields = array(
			'serving_size' => array(
				'label' => __( 'Serving', 'wp-recipe-maker-premium' ),
				'unit' => 'g',
			),
			'calories' => array(
				'label' => __( 'Calories', 'wp-recipe-maker-premium' ),
				'unit' => 'kcal',
			),
			'carbohydrates' => array(
				'label' => __( 'Carbohydrates', 'wp-recipe-maker-premium' ),
				'unit' => 'g',
			),
			'protein' => array(
				'label' => __( 'Protein', 'wp-recipe-maker-premium' ),
				'unit' => 'g',
			),
			'fat' => array(
				'label' => __( 'Fat', 'wp-recipe-maker-premium' ),
				'unit' => 'g',
			),
			'saturated_fat' => array(
				'label' => __( 'Saturated Fat', 'wp-recipe-maker-premium' ),
				'unit' => 'g',
			),
			'polyunsaturated_fat' => array(
				'label' => __( 'Polyunsaturated Fat', 'wp-recipe-maker-premium' ),
				'unit' => 'g',
			),
			'monounsaturated_fat' => array(
				'label' => __( 'Monounsaturated Fat', 'wp-recipe-maker-premium' ),
				'unit' => 'g',
			),
			'trans_fat' => array(
				'label' => __( 'Trans Fat', 'wp-recipe-maker-premium' ),
				'unit' => 'g',
			),
			'cholesterol' => array(
				'label' => __( 'Cholesterol', 'wp-recipe-maker-premium' ),
				'unit' => 'mg',
			),
			'sodium' => array(
				'label' => __( 'Sodium', 'wp-recipe-maker-premium' ),
				'unit' => 'mg',
			),
			'potassium' => array(
				'label' => __( 'Potassium', 'wp-recipe-maker-premium' ),
				'unit' => 'mg',
			),
			'fiber' => array(
				'label' => __( 'Fiber', 'wp-recipe-maker-premium' ),
				'unit' => 'g',
			),
			'sugar' => array(
				'label' => __( 'Sugar', 'wp-recipe-maker-premium' ),
				'unit' => 'g',
			),
			'vitamin_a' => array(
				'label' => __( 'Vitamin A', 'wp-recipe-maker-premium' ),
				'unit' => '%',
			),
			'vitamin_c' => array(
				'label' => __( 'Vitamin C', 'wp-recipe-maker-premium' ),
				'unit' => '%',
			),
			'calcium' => array(
				'label' => __( 'Calcium', 'wp-recipe-maker-premium' ),
				'unit' => '%',
			),
			'iron' => array(
				'label' => __( 'Iron', 'wp-recipe-maker-premium' ),
				'unit' => '%',
			),
		);

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
	}

	/**
	 * Enqueue stylesheets and scripts.
	 *
	 * @since    1.0.0
	 */
	public static function enqueue() {
	}

	/**
	 * Get nutrition label for a recipe.
	 *
	 * @since    1.0.0
	 * @param    object $recipe Recipe to show the nutrition label for.
	 */
	public static function nutrition_label( $recipe ) {
		ob_start();
		require( WPRMP_DIR . 'templates/public/nutrition-label.php' );
		$label = ob_get_contents();
		ob_end_clean();

		return $label;
	}
}

WPRMP_Nutrition_Label::init();
