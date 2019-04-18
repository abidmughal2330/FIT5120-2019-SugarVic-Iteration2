<?php
/**
 * Template for the plugin settings structure.
 *
 * @link       http://bootstrapped.ventures
 * @since      4.3.0
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/templates/settings
 */

$recipe_roundup = array(
	'id' => 'recipeRoundup',
	'name' => __( 'Recipe Roundup', 'wp-recipe-maker' ),
	'description' => __( "Use this feature for your recipe roundup posts and we'll automatically output ItemList metadata allowing you to show up as a carousel in Google.", 'wp-recipe-maker' ),
	'documentation' => 'https://help.bootstrapped.ventures/article/182-itemlist-metadata-for-recipe-roundup-posts',
	'settings' => array(
		array(
			'id' => 'recipe_roundup_template',
			'name' => __( 'Default Roundup Template', 'wp-recipe-maker' ),
			'type' => 'dropdownTemplateModern',
			'default' => 'roundup-button',
		),
		array(
			'name' => __( 'Template Editor', 'wp-recipe-maker' ),
			'documentation' => 'https://help.bootstrapped.ventures/article/53-template-editor',
			'type' => 'button',
			'button' => __( 'Open the Template Editor', 'wp-recipe-maker' ),
			'link' => admin_url( 'admin.php?page=wprm_template_editor' ),
			'dependency' => array(
				'id' => 'recipe_template_mode',
				'value' => 'modern',
			),
		),
	),
);
