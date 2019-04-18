<?php
/**
 * Template for the Clone Recipe tab in the modal.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.5.0
 *
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/templates/admin/modal/tabs
 */

?>

<p>
	<?php esc_html_e( 'Select the recipe you would like to use as a starting point for your new recipe.', 'wp-recipe-maker' ); ?>
</p>
<div class="wprm-shortcode-builder">
	<div class="wprm-shortcode-builder-container">
		<label for="wprm-clone-recipe-id"><?php esc_html_e( 'Recipe', 'wp-recipe-maker' ); ?></label>
		<select id="wprm-clone-recipe-id" class="wprm-recipes-dropdown">
			<option value="0"><?php esc_html_e( 'Select a recipe', 'wp-recipe-maker' ); ?></option>
		</select>
	</div>
</div>
