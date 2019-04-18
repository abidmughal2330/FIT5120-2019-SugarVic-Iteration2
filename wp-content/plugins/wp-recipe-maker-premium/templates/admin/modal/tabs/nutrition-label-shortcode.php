<?php
/**
 * Template for the Nutrition Label Shortcode tab in the modal.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.0.0
 *
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/templates/admin/modal/tabs
 */

?>

<p>
	<?php printf( esc_html__( 'The %s shortcode can be used to add the nutrition label shortcode.', 'wp-recipe-maker-premium' ), esc_html( '[wprm-nutrition-label]' ) ); ?>
</p>
<h3><?php esc_html_e( 'Shortcode Examples' ); ?></h3>
<p>
	[wprm-nutrition-label]<br />
	<em><?php esc_html_e( 'Show the nutrition label for the first recipe found on the page.', 'wp-recipe-maker-premium' ); ?></em>
</p>
<p>
	[wprm-nutrition-label id="123"]<br />
	<em><?php esc_html_e( 'Show the nutrition label for the recipe with ID 123.', 'wp-recipe-maker-premium' ); ?></em>
</p>
<p>
	[wprm-nutrition-label id="123" align="right"]<br />
	<em><?php esc_html_e( 'Show the nutrition label for the recipe with ID 123 and align it to the right.', 'wp-recipe-maker-premium' ); ?></em>
</p>
<h3><?php esc_html_e( 'Shortcode Builder' ); ?></h3>
<div class="wprm-shortcode-builder">
	<div class="wprm-shortcode-builder-container">
		<label for="wprm-nutrition-label-id"><?php esc_html_e( 'Recipe', 'wp-recipe-maker-premium' ); ?></label>
		<select id="wprm-nutrition-label-id" class="wprm-recipes-dropdown-with-first">
			<option value="0"><?php esc_html_e( 'First recipe on page', 'wp-recipe-maker-premium' ); ?></option>
		</select>
	</div>
	<div class="wprm-shortcode-builder-container">
		<label for="wprm-nutrition-label-align"><?php esc_html_e( 'Align', 'wp-recipe-maker-premium' ); ?></label>
		<select id="wprm-nutrition-label-align">
			<option value="left"><?php esc_html_e( 'Left', 'wp-recipe-maker-premium' ); ?></option>
			<option value="center"><?php esc_html_e( 'Center', 'wp-recipe-maker-premium' ); ?></option>
			<option value="right"><?php esc_html_e( 'Right', 'wp-recipe-maker-premium' ); ?></option>
		</select>
	</div>
</div>
