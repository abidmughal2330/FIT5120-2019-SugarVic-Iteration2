<?php
/**
 * Template for the Recipe Collections manage page.
 *
 * @link       http://bootstrapped.ventures
 * @since      4.1.0
 *
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-collections
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-collections/templates/admin/manage
 */
?>
<br/><a href="<?php echo admin_url( 'admin.php?page=wprm_recipe_collections' ); ?>" class="button button-primary"><?php esc_html_e( 'Create collection', 'wp-recipe-maker' ); ?></a><br/>

<table id="wprm-manage-recipe-collections" class="wprm-manage-datatable" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th data-width="50px">ID</th>
			<th>Date</th>
			<th>Name</th>
			<th data-width="75px"># Items</th>
			<th data-sortable="false">Actions</th>
		</tr>
	</thead>
</table>