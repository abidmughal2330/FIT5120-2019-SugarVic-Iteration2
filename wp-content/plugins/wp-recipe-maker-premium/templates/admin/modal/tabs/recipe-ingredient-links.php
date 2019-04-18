<?php
/**
 * Template for the Recipe Ingredient Links tab in the modal.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.0.0
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/templates/admin/modal/tabs
 */

?>

<div class="wprm-recipe-ingredient-links-form-container">
	<div class="wprm-recipe-form wprm-recipe-ingredient-links-form">
		<div class="wprm-recipe-form-container">
			<select id="wprm-ingredient-links-type">
				<?php
				$options = array(
					'global' => __( 'Use global links as defined on the WP Recipe Maker > Manage > Ingredients page', 'wp-recipe-maker' ),
					'custom' => __( 'Use custom ingredient links defined for this recipe only', 'wp-recipe-maker' ),
				);

				foreach ( $options as $option => $label ) {
					echo '<option value="' . esc_attr( $option ) . '">' . esc_html( $label ) . '</option>';
				}
				?>
			</select>
		</div>
		<div class="wprm-ingredient-links-warning">
			<strong><?php esc_html_e( 'Warning', 'wp-recipe-maker' ); ?></strong>
			<p><?php esc_html_e( 'Changing a link will change it for all recipes using that ingredient.', 'wp-recipe-maker' ); ?></p>
		</div>
	</div>
	<table class="wprm-ingredient-links-container"></table>
</div>
<div class="wprm-loader wprm-ingredient-links-loader">Loading...</div>
