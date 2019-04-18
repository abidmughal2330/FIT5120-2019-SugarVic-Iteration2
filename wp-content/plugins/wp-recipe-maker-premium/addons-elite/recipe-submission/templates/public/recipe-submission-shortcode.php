<?php
/**
 * Template for the Recipe Submission form.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.1.0
 *
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-submission
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-submission/templates/public
 */

?>
<form id="wprm-recipe-submission" method="post" action="" enctype="multipart/form-data">
	<?php echo wp_nonce_field( 'wprmprs', 'wprmprs' ); ?>
	<?php
	$dir = WPRMPRS_DIR . 'templates/public/blocks/';

	foreach ( $blocks as $block ) {
		$type = isset( $block['type'] ) ? sanitize_key( $block['type'] ) : false;

		switch ( $type ) {
			case 'submit':
				if ( WPRM_Settings::get( 'recipe_submission_recaptcha' ) ) {
					include( $dir . 'submit-captcha.php' );
				} else {
					include( $dir . 'submit.php' );
				}
				break;
			case 'header':
			case 'paragraph':
			case 'recipe_image':
				include( $dir . $type . '.php' );
				break;
			case 'recipe_name':
			case 'recipe_servings':
			case 'recipe_prep_time':
			case 'recipe_cook_time':
			case 'recipe_total_time':
			case 'recipe_courses':
			case 'recipe_cuisines':
			case 'user_name':
			case 'user_email':
				include( $dir . 'input.php' );
				break;
			case 'recipe_summary':
			case 'recipe_ingredients':
			case 'recipe_instructions':
			case 'recipe_notes':
				include( $dir . 'textarea.php' );
				break;
		}
	}
	?>
</form>
