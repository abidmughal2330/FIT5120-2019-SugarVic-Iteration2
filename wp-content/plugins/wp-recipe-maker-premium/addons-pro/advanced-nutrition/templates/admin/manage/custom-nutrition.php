<?php
/**
 * Template for the custom nutrition ingredients manage page.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.3.0
 *
 * @package    WP_Recipe_Maker_Premium/addons-pro/advanced-nutrition
 * @subpackage WP_Recipe_Maker_Premium/addons-pro/advanced-nutrition/templates/admin/manage
 */

?>
<div class="wprm-manage-header">
	<button type="button" class="button button-primary wprm-manage-custom-nutrition-create" title="<?php esc_attr_e( 'Create Custom Nutrition Ingredient', 'wp-recipe-maker-premium' ); ?>"><?php esc_html_e( 'Create Custom Nutrition Ingredient', 'wp-recipe-maker' ); ?></button>
</div>
<table id="wprm-manage-custom-nutrition" class="wprm-manage-datatable" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th data-width="50px">ID</th>
			<th data-width="200px">Name</th>
			<th data-sortable="false" data-width="50px">Amount</th>
			<th>Nutrition Facts</th>
			<th data-sortable="false" data-width="40px">&nbsp;</th>
		</tr>
	</thead>
</table>
<div class="wprm-manage-modal-container wprm-manage-custom-nutrition-modal">
	<div class="wprm-manage-modal-backdrop"></div>
	<div class="wprm-manage-modal">
		<div class="wprm-manage-modal-title">
			<?php esc_html_e( 'Custom Nutrition Ingredient', 'wp-recipe-maker' ); ?>
		</div>
		<div class="wprm-manage-modal-content">
			<input type="hidden" id="wprm-nutrition-saved-ingredient-id">
			<label for="wprm-nutrition-saved-ingredient-amount"><?php esc_html_e( 'Amount, Unit & Name (required)', 'wp-recipe-maker-premium' ); ?></label>
			<input type="text" id="wprm-nutrition-saved-ingredient-amount">
			<input type="text" id="wprm-nutrition-saved-ingredient-unit">
			<input type="text" id="wprm-nutrition-saved-ingredient-name">
			<label for="wprm-nutrition-saved-ingredient-calories"><?php esc_html_e( 'Nutrients', 'wp-recipe-maker-premium' ); ?></label>
			<ol class="wprm-manage-custom-nutrition-modal-nutrients">
				<li><input type="text" id="wprm-nutrition-saved-ingredient-calories">kcal calories</li>
				<li><input type="text" id="wprm-nutrition-saved-ingredient-carbohydrates">g carbohydrates</li>
				<li><input type="text" id="wprm-nutrition-saved-ingredient-protein">g protein</li>
				<li><input type="text" id="wprm-nutrition-saved-ingredient-fat">g fat</li>
				<li><input type="text" id="wprm-nutrition-saved-ingredient-saturated-fat">g saturated fat</li>
				<li><input type="text" id="wprm-nutrition-saved-ingredient-polyunsaturated-fat">g polyunsaturated fat</li>
				<li><input type="text" id="wprm-nutrition-saved-ingredient-monounsaturated-fat">g monounsaturated fat</li>
				<li><input type="text" id="wprm-nutrition-saved-ingredient-trans-fat">g trans fat</li>
				<li><input type="text" id="wprm-nutrition-saved-ingredient-cholesterol">mg cholesterol</li>
				<li><input type="text" id="wprm-nutrition-saved-ingredient-sodium">mg sodium</li>
				<li><input type="text" id="wprm-nutrition-saved-ingredient-potassium">mg potassium</li>
				<li><input type="text" id="wprm-nutrition-saved-ingredient-fiber">g fiber</li>
				<li><input type="text" id="wprm-nutrition-saved-ingredient-sugar">g sugar</li>
				<li><input type="text" id="wprm-nutrition-saved-ingredient-vitamin-a">% vitamin a</li>
				<li><input type="text" id="wprm-nutrition-saved-ingredient-vitamin-c">% vitamin c</li>
				<li><input type="text" id="wprm-nutrition-saved-ingredient-calcium">% calcium</li>
				<li><input type="text" id="wprm-nutrition-saved-ingredient-iron">% iron</li>
			</ol>
		</div>
		<div class="wprm-manage-modal-buttons">
			<button type="button" class="button button-primary wprm-manage-modal-save" title="<?php esc_attr_e( 'Save', 'wp-recipe-maker-premium' ); ?>"><?php esc_html_e( 'Save', 'wp-recipe-maker' ); ?></button>
			<button type="button" class="button wprm-manage-modal-cancel" title="<?php esc_attr_e( 'Cancel', 'wp-recipe-maker-premium' ); ?>"><?php esc_html_e( 'Cancel', 'wp-recipe-maker' ); ?></button>
		</div>
	</div>
</div>
