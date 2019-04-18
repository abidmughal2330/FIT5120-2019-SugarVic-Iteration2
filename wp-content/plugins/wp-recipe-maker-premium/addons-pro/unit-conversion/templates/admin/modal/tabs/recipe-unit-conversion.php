<?php
/**
 * Template for the Recipe Unit Conversion tab in the modal.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.0.0
 *
 * @package    WP_Recipe_Maker_Premium/addons-pro/unit-conversion
 * @subpackage WP_Recipe_Maker_Premium/addons-pro/unit-conversion/templates/admin/modal/tabs
 */

$units_data = WPRM_Settings::get( 'unit_conversion_units' );
?>
<div class="wprm-unit-conversion-container">
	<button type="button" class="button wprm-button button-primary button-large wprm-button-calculate-unit-conversion"><?php esc_html_e( 'Calculate Unit Conversion', 'wp-recipe-maker-premium' ); ?></button>
	<table class="wprm-unit-conversion-ingredients-container">
		<thead>
			<tr>
				<th><?php echo esc_html( WPRM_Settings::get( 'unit_conversion_system_1' ) ); ?></th>
				<th><?php echo esc_html( WPRM_Settings::get( 'unit_conversion_system_2' ) ); ?></th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
<div class="wprm-unit-conversion-calculation-container">
	<button type="button" class="button wprm-button button-primary button-large wprm-button-use-unit-conversion"><?php esc_html_e( 'Use These Values', 'wp-recipe-maker-premium' ); ?></button>
	<button type="button" class="button wprm-button button button-large wprm-button-cancel-unit-conversion"><?php esc_html_e( 'Cancel', 'wp-recipe-maker-premium' ); ?></button>
	<select class="wprm-ingredient-conversion-calculation-type-placeholder">
		<option value="none"><?php esc_html_e( 'Keep unit', 'wp-ultimate-recipe-premium' ); ?></option>
		<option value="custom"><?php esc_html_e( 'Manual', 'wp-ultimate-recipe-premium' ); ?></option>
		<option value="automatic"><?php esc_html_e( 'Automatic', 'wp-ultimate-recipe-premium' ); ?></option>
		<optgroup label="<?php esc_attr_e( 'Weight Units', 'wp-recipe-maker-premium' ); ?>">
			<?php
			$units_in_system = WPRM_Settings::get( 'unit_conversion_system_2_weight_units' );

			foreach ( $units_in_system as $unit ) {
				$label = $units_data[ $unit ]['label'];

				echo '<option value="' . esc_attr( $unit ) . '">' . esc_html( $label ) . '</option>';
			}
			?>
		</optgroup>
		<optgroup label="<?php esc_attr_e( 'Volume Units', 'wp-recipe-maker-premium' ); ?>">
			<?php
			$units_in_system = WPRM_Settings::get( 'unit_conversion_system_2_volume_units' );

			foreach ( $units_in_system as $unit ) {
				$label = $units_data[ $unit ]['label'];

				echo '<option value="' . esc_attr( $unit ) . '">' . esc_html( $label ) . '</option>';
			}
			?>
		</optgroup>
	</select>
	<table class="wprm-unit-conversion-calculation-table">
		<thead>
			<tr>
				<th><?php echo esc_html( WPRM_Settings::get( 'unit_conversion_system_1' ) ); ?></th>
				<th><?php esc_html_e( 'Conversion', 'wp-ultimate-recipe-premium' ); ?></th>
				<th><?php echo esc_html( WPRM_Settings::get( 'unit_conversion_system_2' ) ); ?></th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
<div class="wprm-loader wprm-unit-conversion-loader">Loading...</div>
