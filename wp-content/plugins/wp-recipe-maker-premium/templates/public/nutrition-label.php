<?php
/**
 * Template for the Nutrition Label.
 *
 * @link   http://bootstrapped.ventures
 * @since  1.0.0
 *
 * @package WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/templates/public
 */

$nutrition = $recipe->nutrition();

$has_nutritional_information = false;
$main_info = false;
$sub_info = false;

foreach ( self::$nutrition_units as $field => $options ) {
	if ( isset( $nutrition[ $field ] ) && false !== $nutrition[ $field ] ) {
		if ( false !== $nutrition[ $field ] && WPRM_Settings::get( 'nutrition_label_zero_values' ) || $nutrition[ $field ] ) {
			$$field = $nutrition[ $field ];

			if ( isset( self::$daily_values[ $field ] ) ) {
				$perc_field = $field . '_perc';
				$$perc_field = round( floatval( $$field ) / self::$daily_values[ $field ] * 100 );
			}

			// Flags to know what to output.
			$has_nutritional_information = true;
			if ( in_array( $field, array( 'fat', 'saturated_fat', 'trans_fat', 'polyunsaturated_fat', 'monounsaturated_fat', 'cholesterol', 'sodium', 'potassium', 'carbohydrates', 'fiber', 'sugar', 'protein' ), true ) ) {
				$main_info = true;
			} elseif ( in_array( $field, array( 'vitamin_a', 'vitamin_c', 'calcium', 'iron' ), true ) ) {
				$sub_info = true;
			}
		}
	}
}

if ( $has_nutritional_information ) :

	// Calculate calories if not set.
	$fat_calories = isset( $fat ) ? round( floatval( $fat ) * 9 ) : 0;

	if ( ! isset( $calories ) ) {
		$proteins = isset( $protein ) ? $protein : 0;
		$carbs = isset( $carbohydrates ) ? $carbohydrates : 0;

		$calories = ( ( $proteins + $carbs ) * 4 ) + $fat_calories;
	}
?>

<div class="wprm-nutrition-label">
	<div class="nutrition-title"><?php esc_html_e( 'Nutrition Facts', 'wp-recipe-maker-premium' ); ?></div>
	<div class="nutrition-recipe"><?php echo esc_html( $recipe->name() ); ?></div>
	<div class="nutrition-line nutrition-line-big"></div>
	<div class="nutrition-serving">
		<?php esc_html_e( 'Amount Per Serving', 'wp-recipe-maker-premium' ); ?>
		<?php
		if ( isset( $serving_size ) ) {
			$unit = isset( $nutrition['serving_unit'] ) && $nutrition['serving_unit'] ? $nutrition['serving_unit'] : 'g';
			echo ' (' . esc_html( $serving_size ) . ' ' . esc_html( $unit ) . ')';
		}
		?>
	</div>
	<div class="nutrition-item">
		<span class="nutrition-main"><strong><?php esc_html_e( 'Calories', 'wp-recipe-maker-premium' ); ?></strong> <?php echo esc_html( $calories ); ?></span>
		<?php if ( $fat_calories ) : ?>
		<span class="nutrition-percentage"><?php esc_html_e( 'Calories from Fat', 'wp-recipe-maker-premium' ); ?> <?php echo esc_html( $fat_calories ); ?></span>
		<?php endif; // Fat calories. ?>
	</div>
	<?php if ( $main_info ) : ?>
	<div class="nutrition-line"></div>
	<div class="nutrition-item">
		<span class="nutrition-percentage"><strong><?php esc_html_e( '% Daily Value', 'wp-recipe-maker-premium' ); ?>*</strong></span>
	</div>
	<?php if ( isset( $fat ) ) : ?>
	<div class="nutrition-item">
		<span class="nutrition-main"><strong><?php esc_html_e( 'Total Fat', 'wp-recipe-maker-premium' ); ?></strong> <?php echo esc_html( $fat ); ?>g</span>
		<span class="nutrition-percentage"><strong><?php echo esc_html( $fat_perc ); ?>%</strong></span>
	</div>
	<?php if ( isset( $saturated_fat ) ) : ?>
	<div class="nutrition-sub-item">
		<span class="nutrition-sub"><?php esc_html_e( 'Saturated Fat', 'wp-recipe-maker-premium' ); ?> <?php echo esc_html( $saturated_fat ); ?>g</span>
		<span class="nutrition-percentage"><strong><?php echo esc_html( $saturated_fat_perc ); ?>%</strong></span>
	</div>
	<?php endif; // Saturated Fat. ?>
	<?php if ( isset( $trans_fat ) ) : ?>
	<div class="nutrition-sub-item">
		<span class="nutrition-sub"><?php esc_html_e( 'Trans Fat', 'wp-recipe-maker-premium' ); ?> <?php echo esc_html( $trans_fat ); ?>g</span>
	</div>
	<?php endif; // Trans Fat. ?>
	<?php if ( isset( $polyunsaturated_fat ) ) : ?>
	<div class="nutrition-sub-item">
		<span class="nutrition-sub"><?php esc_html_e( 'Polyunsaturated Fat', 'wp-recipe-maker-premium' ); ?> <?php echo esc_html( $polyunsaturated_fat ); ?>g</span>
	</div>
	<?php endif; // Polyunsaturated Fat. ?>
	<?php if ( isset( $monounsaturated_fat ) ) : ?>
	<div class="nutrition-sub-item">
		<span class="nutrition-sub"><?php esc_html_e( 'Monounsaturated Fat', 'wp-recipe-maker-premium' ); ?> <?php echo esc_html( $monounsaturated_fat ); ?>g</span>
	</div>
	<?php endif; // Monounsaturated Fat. ?>
	<?php endif; // Fat. ?>
	<?php if ( isset( $cholesterol ) ) : ?>
	<div class="nutrition-item">
		<span class="nutrition-main"><strong><?php esc_html_e( 'Cholesterol', 'wp-recipe-maker-premium' ); ?></strong> <?php echo esc_html( $cholesterol ); ?>mg</span>
		<span class="nutrition-percentage"><strong><?php echo esc_html( $cholesterol_perc ); ?>%</strong></span>
	</div>
	<?php endif; // Cholesterol. ?>
	<?php if ( isset( $sodium ) ) : ?>
	<div class="nutrition-item">
		<span class="nutrition-main"><strong><?php esc_html_e( 'Sodium', 'wp-recipe-maker-premium' ); ?></strong> <?php echo esc_html( $sodium ); ?>mg</span>
		<span class="nutrition-percentage"><strong><?php echo esc_html( $sodium_perc ); ?>%</strong></span>
	</div>
	<?php endif; // Sodium. ?>
	<?php if ( isset( $potassium ) ) : ?>
	<div class="nutrition-item">
		<span class="nutrition-main"><strong><?php esc_html_e( 'Potassium', 'wp-recipe-maker-premium' ); ?></strong> <?php echo esc_html( $potassium ); ?>mg</span>
		<span class="nutrition-percentage"><strong><?php echo esc_html( $potassium_perc ); ?>%</strong></span>
	</div>
	<?php endif; // Potassium. ?>
	<?php if ( isset( $carbohydrates ) ) : ?>
	<div class="nutrition-item">
		<span class="nutrition-main"><strong><?php esc_html_e( 'Total Carbohydrates', 'wp-recipe-maker-premium' ); ?></strong> <?php echo esc_html( $carbohydrates ); ?>g</span>
		<span class="nutrition-percentage"><strong><?php echo esc_html( $carbohydrates_perc ); ?>%</strong></span>
	</div>
	<?php endif; // Carbohydrates. ?>
	<?php if ( isset( $fiber ) ) : ?>
	<div class="nutrition-sub-item">
		<span class="nutrition-sub"><?php esc_html_e( 'Dietary Fiber', 'wp-recipe-maker-premium' ); ?> <?php echo esc_html( $fiber ); ?>g</span>
		<span class="nutrition-percentage"><strong><?php echo esc_html( $fiber_perc ); ?>%</strong></span>
	</div>
	<?php endif; // Fiber. ?>
	<?php if ( isset( $sugar ) ) : ?>
	<div class="nutrition-sub-item">
		<span class="nutrition-sub"><?php esc_html_e( 'Sugars', 'wp-recipe-maker-premium' ); ?> <?php echo esc_html( $sugar ); ?>g</span>
	</div>
	<?php endif; // Sugar. ?>
	<?php if ( isset( $protein ) ) : ?>
	<div class="nutrition-item">
		<span class="nutrition-main"><strong><?php esc_html_e( 'Protein', 'wp-recipe-maker-premium' ); ?></strong> <?php echo esc_html( $protein ); ?>g</span>
		<span class="nutrition-percentage"><strong><?php echo esc_html( $protein_perc ); ?>%</strong></span>
	</div>
	<?php endif; // Protein. ?>
	<?php endif; // Main info. ?>
	<?php if ( $sub_info ) : ?>
	<div class="nutrition-line nutrition-line-big"></div>
	<?php if ( isset( $vitamin_a ) ) : ?>
	<div class="nutrition-item">
		<span class="nutrition-main"><?php esc_html_e( 'Vitamin A', 'wp-recipe-maker-premium' ); ?></span>
		<span class="nutrition-percentage"><?php echo esc_html( $vitamin_a ); ?>%</span>
	</div>
	<?php endif; // Vitamin A. ?>
	<?php if ( isset( $vitamin_c ) ) : ?>
	<div class="nutrition-item">
		<span class="nutrition-main"><?php esc_html_e( 'Vitamin C', 'wp-recipe-maker-premium' ); ?></span>
		<span class="nutrition-percentage"><?php echo esc_html( $vitamin_c ); ?>%</span>
	</div>
	<?php endif; // Vitamin C. ?>
	<?php if ( isset( $calcium ) ) : ?>
	<div class="nutrition-item">
		<span class="nutrition-main"><?php esc_html_e( 'Calcium', 'wp-recipe-maker-premium' ); ?></span>
		<span class="nutrition-percentage"><?php echo esc_html( $calcium ); ?>%</span>
	</div>
	<?php endif; // Calcium. ?>
	<?php if ( isset( $iron ) ) : ?>
	<div class="nutrition-item">
		<span class="nutrition-main"><?php esc_html_e( 'Iron', 'wp-recipe-maker-premium' ); ?></span>
		<span class="nutrition-percentage"><?php echo esc_html( $iron ); ?>%</span>
	</div>
	<?php endif; // Iron. ?>
	<?php endif; // Sub info. ?>
	<div class="nutrition-warning">* <?php echo esc_html( WPRM_Settings::get( 'nutrition_label_custom_daily_values_disclaimer' ) ); ?></div>
</div>
<?php endif; // Has nutritional information. ?>
