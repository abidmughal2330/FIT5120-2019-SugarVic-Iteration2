<?php
/**
 * Template for the custom taxonomies manage page.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.2.0
 *
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/templates/admin/manage
 */

$taxonomies = WPRMP_Custom_Taxonomies::get_custom_taxonomies();

if ( 0 === count( $taxonomies ) ) :
	echo '<p>' . esc_html__( 'No custom taxonomies have been created.', 'wp-recipe-maker' ) . '</p>';
else :
?>
<table class="wprmp-custom-taxonomies widefat" cellspacing="0">
	<thead>
		<tr>
			<th>Key</th>
			<th>Singular Name</th>
			<th>Plural Name</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ( $taxonomies as $taxonomy => $options ) : ?>
		<tr>
			<td><?php echo esc_html( $taxonomy ); ?></td>
			<td><?php echo esc_html( $options['singular_name'] ); ?></td>
			<td><?php echo esc_html( $options['name'] ); ?></td>
			<td><span class="dashicons dashicons-admin-tools wprm-icon wprm-manage-custom-taxonomies-actions" data-key="<?php echo esc_attr( $taxonomy ); ?>"></span></td>
		</tr>
	<?php endforeach; // Taxonomies. ?>
	</tbody>
</table>
<?php endif; // Taxonomies count. ?>

<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
	<input type="hidden" name="action" value="wprmp_add_custom_taxonomy">
	<?php wp_nonce_field( 'wprmp_custom_taxonomy', 'wprmp_custom_taxonomy', false ); ?>
	<h2 class="title"><?php esc_html_e( 'New Custom Taxonomy', 'wp-recipe-maker' ); ?></h2>
	<p>
		<?php esc_html_e( 'Create a new custom taxonomy to categorize your recipes.', 'wp-recipe-maker-premium' ); ?>
	</p>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="taxonomy_key"><?php esc_html_e( 'Key', 'wp-recipe-maker-premium' ); ?></label>
				</th>
				<td>
					<input name="taxonomy_key" type="text" id="taxonomy_key" class="regular-text">
					<p class="description">
						<?php esc_html_e( 'Unique key for the taxonomy.', 'wp-recipe-maker-premium' ); ?> <?php esc_html_e( 'For example', 'wp-recipe-maker-premium' ); ?>: course
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="taxonomy_singular"><?php esc_html_e( 'Singular Name', 'wp-recipe-maker-premium' ); ?></label>
				</th>
				<td>
					<input name="taxonomy_singular" type="text" id="taxonomy_singular" class="regular-text">
					<p class="description">
						<?php esc_html_e( 'Singular name for the taxonomy.', 'wp-recipe-maker-premium' ); ?> <?php esc_html_e( 'For example', 'wp-recipe-maker-premium' ); ?>: Course
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="taxonomy_plural"><?php esc_html_e( 'Plural Name', 'wp-recipe-maker-premium' ); ?></label>
				</th>
				<td>
					<input name="taxonomy_plural" type="text" id="taxonomy_plural" class="regular-text">
					<p class="description">
						<?php esc_html_e( 'Plural name for the taxonomy.', 'wp-recipe-maker-premium' ); ?> <?php esc_html_e( 'For example', 'wp-recipe-maker-premium' ); ?>: Courses
					</p>
				</td>
			</tr>
		</tbody>
	</table>
	<?php submit_button( __( 'Create Custom Taxonomy', 'wp-recipe-maker-premium' ) ); ?>
</form>
