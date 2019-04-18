<?php
/**
 * Template for the Recipe Nutrition Facts tab in the modal.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.0.0
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/templates/admin/modal/tabs
 */

?>

<div class="wprm-recipe-form wprm-recipe-nutrition-facts-form">
	<div class="wprm-recipe-form-container wprm-recipe-form-container-thirds">
		<label for="wprm-recipe-nutrition-serving"><?php esc_html_e( 'Serving Size', 'wp-recipe-maker-premium' ); ?></label>
		<input type="number" min="0" id="wprm-recipe-nutrition-serving" placeholder="340" />
		<input type="text" id="wprm-recipe-nutrition-serving-unit" placeholder="g"/>
	</div><div class="wprm-recipe-form-container wprm-recipe-form-container-thirds">
		<label for="wprm-recipe-calories"><?php esc_html_e( 'Calories', 'wp-recipe-maker-premium' ); ?></label>
		<input type="number" min="0" id="wprm-recipe-calories" placeholder="280" /> <?php esc_html_e( 'kcal', 'wp-recipe-maker-premium' ); ?>
	</div><div class="wprm-recipe-form-container wprm-recipe-form-container-thirds">
		<label for="wprm-recipe-carbohydrates"><?php esc_html_e( 'Total Carbohydrates', 'wp-recipe-maker-premium' ); ?></label>
		<input type="number" min="0" id="wprm-recipe-carbohydrates" placeholder="71" /> <?php esc_html_e( 'g', 'wp-recipe-maker-premium' ); ?>
	</div>

	<div class="wprm-recipe-form-container wprm-recipe-form-container-thirds">
		<label for="wprm-recipe-protein"><?php esc_html_e( 'Protein', 'wp-recipe-maker-premium' ); ?></label>
		<input type="number" min="0" id="wprm-recipe-protein" placeholder="57" /> <?php esc_html_e( 'g', 'wp-recipe-maker-premium' ); ?>
	</div><div class="wprm-recipe-form-container wprm-recipe-form-container-thirds">
		<label for="wprm-recipe-total-fat"><?php esc_html_e( 'Total Fat', 'wp-recipe-maker-premium' ); ?></label>
		<input type="number" min="0" id="wprm-recipe-total-fat" placeholder="85" /> <?php esc_html_e( 'g', 'wp-recipe-maker-premium' ); ?>
	</div><div class="wprm-recipe-form-container wprm-recipe-form-container-thirds">
		<label for="wprm-recipe-saturated-fat"><?php esc_html_e( 'Saturated Fat', 'wp-recipe-maker-premium' ); ?></label>
		<input type="number" min="0" id="wprm-recipe-saturated-fat" placeholder="22" /> <?php esc_html_e( 'g', 'wp-recipe-maker-premium' ); ?>
	</div>

	<div class="wprm-recipe-form-container wprm-recipe-form-container-thirds">
		<label for="wprm-recipe-polyunsaturated-fat"><?php esc_html_e( 'Polyunsaturated Fat', 'wp-recipe-maker-premium' ); ?></label>
		<input type="number" min="0" id="wprm-recipe-polyunsaturated-fat" placeholder="10" /> <?php esc_html_e( 'g', 'wp-recipe-maker-premium' ); ?>
	</div><div class="wprm-recipe-form-container wprm-recipe-form-container-thirds">
		<label for="wprm-recipe-monounsaturated-fat"><?php esc_html_e( 'Monounsaturated Fat', 'wp-recipe-maker-premium' ); ?></label>
		<input type="number" min="0" id="wprm-recipe-monounsaturated-fat" placeholder="44" /> <?php esc_html_e( 'g', 'wp-recipe-maker-premium' ); ?>
	</div><div class="wprm-recipe-form-container wprm-recipe-form-container-thirds">
		<label for="wprm-recipe-trans-fat"><?php esc_html_e( 'Trans Fat', 'wp-recipe-maker-premium' ); ?></label>
		<input type="number" min="0" id="wprm-recipe-trans-fat" placeholder="2" /> <?php esc_html_e( 'g', 'wp-recipe-maker-premium' ); ?>
	</div>

	<div class="wprm-recipe-form-container wprm-recipe-form-container-thirds">
		<label for="wprm-recipe-cholesterol"><?php esc_html_e( 'Cholesterol', 'wp-recipe-maker-premium' ); ?></label>
		<input type="number" min="0" id="wprm-recipe-cholesterol" placeholder="238" /> <?php esc_html_e( 'mg', 'wp-recipe-maker-premium' ); ?>
	</div><div class="wprm-recipe-form-container wprm-recipe-form-container-thirds">
		<label for="wprm-recipe-sodium"><?php esc_html_e( 'Sodium', 'wp-recipe-maker-premium' ); ?></label>
		<input type="number" min="0" id="wprm-recipe-sodium" placeholder="254" /> <?php esc_html_e( 'mg', 'wp-recipe-maker-premium' ); ?>
	</div><div class="wprm-recipe-form-container wprm-recipe-form-container-thirds">
		<label for="wprm-recipe-potassium"><?php esc_html_e( 'Potassium', 'wp-recipe-maker-premium' ); ?></label>
		<input type="number" min="0" id="wprm-recipe-potassium" placeholder="620" /> <?php esc_html_e( 'mg', 'wp-recipe-maker-premium' ); ?>
	</div>

	<div class="wprm-recipe-form-container wprm-recipe-form-container-thirds">
		<label for="wprm-recipe-fiber"><?php esc_html_e( 'Dietary Fiber', 'wp-recipe-maker-premium' ); ?></label>
		<input type="number" min="0" id="wprm-recipe-fiber" placeholder="4" /> <?php esc_html_e( 'g', 'wp-recipe-maker-premium' ); ?>
	</div><div class="wprm-recipe-form-container wprm-recipe-form-container-thirds">
		<label for="wprm-recipe-sugar"><?php esc_html_e( 'Sugar', 'wp-recipe-maker-premium' ); ?></label>
		<input type="number" min="0" id="wprm-recipe-sugar" placeholder="4" /> <?php esc_html_e( 'g', 'wp-recipe-maker-premium' ); ?>
	</div><div class="wprm-recipe-form-container wprm-recipe-form-container-thirds">
		<label for="wprm-recipe-vitamin-a"><?php esc_html_e( 'Vitamin A', 'wp-recipe-maker-premium' ); ?></label>
		<input type="number" min="0" id="wprm-recipe-vitamin-a" placeholder="2" /> <?php esc_html_e( '% Daily Value', 'wp-recipe-maker-premium' ); ?>
	</div>

	<div class="wprm-recipe-form-container wprm-recipe-form-container-thirds">
		<label for="wprm-recipe-vitamin-c"><?php esc_html_e( 'Vitamin C', 'wp-recipe-maker-premium' ); ?></label>
		<input type="number" min="0" id="wprm-recipe-vitamin-c" placeholder="0.1" /> <?php esc_html_e( '% Daily Value', 'wp-recipe-maker-premium' ); ?>
	</div><div class="wprm-recipe-form-container wprm-recipe-form-container-thirds">
		<label for="wprm-recipe-calcium"><?php esc_html_e( 'Calcium', 'wp-recipe-maker-premium' ); ?></label>
		<input type="number" min="0" id="wprm-recipe-calcium" placeholder="16" /> <?php esc_html_e( '% Daily Value', 'wp-recipe-maker-premium' ); ?>
	</div><div class="wprm-recipe-form-container wprm-recipe-form-container-thirds">
		<label for="wprm-recipe-iron"><?php esc_html_e( 'Iron', 'wp-recipe-maker-premium' ); ?></label>
		<input type="number" min="0" id="wprm-recipe-iron" placeholder="12" /> <?php esc_html_e( '% Daily Value', 'wp-recipe-maker-premium' ); ?>
	</div>


	<div class='wprm-modal-hint'>
		<span class="wprm-modal-hint-header"><?php esc_html_e( 'Important', 'wp-recipe-maker-premium' ); ?></span>
		<span class="wprm-modal-hint-text"><?php esc_html_e( 'Nutrition facts should be for 1 serving of the recipe.', 'wp-recipe-maker-premium' ); ?></span>
		<span class="wprm-modal-hint-text"><?php esc_html_e( 'Use the nutrition label shortcode to display these values.', 'wp-recipe-maker-premium' ); ?></span><br/>
		<span class="wprm-modal-hint-text"><a href="https://help.bootstrapped.ventures/article/22-nutrition-label" target="_blank"><?php esc_html_e( 'Learn more', 'wp-recipe-maker' ); ?></a>.</span>
	</div>
</div>
