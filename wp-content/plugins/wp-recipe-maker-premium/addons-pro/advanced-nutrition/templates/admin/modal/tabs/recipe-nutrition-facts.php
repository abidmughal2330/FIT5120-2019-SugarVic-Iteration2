<?php
/**
 * Template for the Recipe Nutrition Facts tab in the modal.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.0.0
 *
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/addons-pro/advanced-nutrition/templates/admin/modal/tabs
 */

?>
<div class="wprm-nutrition-facts-container">
	<button type="button" class="button wprm-button button-primary button-large wprm-button-calculate-nutrition wprm-button-nutrition-mapping"><?php esc_html_e( 'Calculate Nutrition Facts', 'wp-recipe-maker-premium-nutrition' ); ?></button>
	<?php include( WPRMP_DIR . 'templates/admin/modal/tabs/recipe-nutrition-facts.php' ); ?>
</div>
<div class="wprm-nutrition-mapping-container">
	<button type="button" class="button wprm-button button-primary button-large wprm-button-calculate-nutrition wprm-button-nutrition-calculate"><?php esc_html_e( 'Calculate Nutrition Facts', 'wp-recipe-maker-premium-nutrition' ); ?></button>
	<button type="button" class="button wprm-button button-large wprm-button-nutrition-cancel"><?php esc_html_e( 'Cancel', 'wp-recipe-maker-premium-nutrition' ); ?></button>
	<select class="wprm-nutrition-mapping-source-placeholder">
		<option value="api"><?php esc_html_e( 'API', 'wp-ultimate-recipe-premium' ); ?></option>
		<option value="custom"><?php esc_html_e( 'Saved/Custom', 'wp-ultimate-recipe-premium' ); ?></option>
	</select>
	<table class="wprm-nutrition-mapping-container-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Your Ingredients', 'wp-recipe-maker-premium-nutrition' ); ?></th>
				<th><?php esc_html_e( 'Source', 'wp-recipe-maker-premium-nutrition' ); ?></th>
				<th><?php esc_html_e( 'Matched Ingredients', 'wp-recipe-maker-premium-nutrition' ); ?></th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
	<p><?php esc_html_e( 'Click on a matched ingredient to choose another one.', 'wp-recipe-maker-premium-nutrition' ); ?></p>
</div>
<div class="wprm-nutrition-mapping-ingredient-container">
	<strong><?php esc_html_e( 'Match this ingredient', 'wp-recipe-maker-premium-nutrition' ); ?>: </strong><span class="wprm-nutrition-mapping-ingredient-to-search"></span>
	<div>
		<input type="text" id="wprm-nutrition-mapping-ingredient-search">
		<button type="button" class="button wprm-button button-primary button-large wprm-button-calculate-nutrition wprm-button-nutrition-mapping-ingredient-search"><?php esc_html_e( 'Search', 'wp-recipe-maker-premium-nutrition' ); ?></button>
		<button type="button" class="button wprm-button button-large wprm-button-nutrition-back"><?php esc_html_e( 'Back', 'wp-recipe-maker-premium-nutrition' ); ?></button>
	</div>
	<div class="wprm-nutrition-mapping-ingredient-search-results"></div>
</div>
<div class="wprm-nutrition-saved-ingredients-container">
	<h3><?php esc_html_e( 'Save a new custom ingredient', 'wp-recipe-maker-premium-nutrition' ); ?></h3>
	<div>
		<input type="text" class="wprm-nutrition-saved-ingredient-amount"> <input type="text" class="wprm-nutrition-saved-ingredient-unit"> <input type="text" class="wprm-nutrition-saved-ingredient-name">
	</div>
	<ol class="wprm-nutrition-saved-ingredients-fields"></ol>
	<button type="button" class="button wprm-button button-primary button-large wprm-button-calculate-nutrition wprm-button-saved-ingredients-save-confirm"><?php esc_html_e( 'Save & Use', 'wp-recipe-maker-premium-nutrition' ); ?></button>
	<button type="button" class="button wprm-button button-primary button-large wprm-button-calculate-nutrition wprm-button-saved-ingredients-nosave-confirm"><?php esc_html_e( 'Use', 'wp-recipe-maker-premium-nutrition' ); ?></button>
	<h3><?php esc_html_e( 'Select a saved ingredient', 'wp-recipe-maker-premium-nutrition' ); ?></h3>
	<select id="wprm-saved-ingredient-id" class="wprm-saved-ingredients-dropdown">
		<option value="0"><?php esc_html_e( 'Select a saved ingredient', 'wp-recipe-maker-premium-nutrition' ); ?></option>
	</select>
	<div class="wprm-nutrition-saved-ingredient-details">
		<div>
			<span class="wprm-nutrition-saved-ingredient-match-amount"></span> <span class="wprm-nutrition-saved-ingredient-match-unit"></span> <span class="wprm-nutrition-saved-ingredient-match-name"></span> = <input type="text" class="wprm-nutrition-saved-ingredient-details-amount"> <span class="wprm-nutrition-saved-ingredient-details-unit"></span> <span class="wprm-nutrition-saved-ingredient-details-name"></span> (<?php esc_html_e( 'Match the equation to use the correct amounts', 'wp-recipe-maker-premium-nutrition' ); ?>)
		</div>
		<ol class="wprm-nutrition-saved-ingredient-details-fields"></ol>
		<button type="button" class="button wprm-button button-primary button-large wprm-button-calculate-nutrition wprm-button-saved-ingredients-confirm"><?php esc_html_e( 'Use', 'wp-recipe-maker-premium-nutrition' ); ?></button>
	</div>
</div>
<div class="wprm-nutrition-calculation-container">
	<button type="button" class="button wprm-button button-primary button-large wprm-button-calculate-nutrition wprm-button-nutrition-confirm"><?php esc_html_e( 'Use these values', 'wp-recipe-maker-premium-nutrition' ); ?></button>
	<button type="button" class="button wprm-button button-large wprm-button-nutrition-back"><?php esc_html_e( 'Back', 'wp-recipe-maker-premium-nutrition' ); ?></button>
	<div class="wprm-nutrition-calculation-results"></div>
	<p><?php esc_html_e( 'Values of all the checked ingredients will be added together and divided by the number of servings.', 'wp-recipe-maker-premium-nutrition' ); ?></p>
</div>
<div class="wprm-loader wprm-nutrition-loader">Loading...</div>
