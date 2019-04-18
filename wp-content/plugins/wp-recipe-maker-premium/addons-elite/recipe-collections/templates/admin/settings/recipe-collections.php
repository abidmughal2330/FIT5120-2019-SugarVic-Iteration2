<?php
/**
 * Template for the Recipe Collections settings sub page.
 *
 * @link       http://bootstrapped.ventures
 * @since      4.1.0
 *
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-collections
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-collections/templates/admin/settings
 */

$recipe_collections = array(
	'id' => 'recipeCollections',
	'name' => __( 'Recipe Collections', 'wp-recipe-maker' ),
	'required' => 'elite',
	'subGroups' => array(
		array(
			'name' => '',
			'description' => __( 'Add the Recipe Collections block or [wprm-recipe-collections] shortcode to a regular WordPress page to display the Recipe Collections feature.', 'wp-recipe-maker-premium' ),
			'documentation' => 'https://help.bootstrapped.ventures/article/148-recipe-collections',
			'settings' => array(
				array(
					'id' => 'recipe_collections_link',
					'name' => __( 'Link to Collections feature', 'wp-recipe-maker' ),
					'description' => __( "Full URL of the page where you've added the Recipe Collections shortcode.", 'wp-recipe-maker' ),
					'type' => 'text',
					'default' => '',
				),
				array(
					'id' => 'recipe_collections_access',
					'name' => __( 'Access to Recipe Collections', 'wp-recipe-maker' ),
					'type' => 'dropdown',
					'options' => array(
						'everyone' => __( 'Everyone', 'wp-recipe-maker' ),
						'logged_in' => __( 'Logged In Users', 'wp-recipe-maker' ),
					),
					'default' => 'everyone',
				),
				array(
					'id' => 'recipe_collections_no_access_message',
					'name' => __( 'No Access Message', 'wp-recipe-maker' ),
					'description' => __( 'Optional text to show instead of the Recipe Collections feature for visitors with no access.', 'wp-recipe-maker' ),
					'type' => 'richTextarea',
					'default' => '',
					'dependency' => array(
						'id' => 'recipe_collections_access',
						'value' => 'logged_in',
					),
				),
			),
		),
		array(
			'name' => __( 'Collections', 'wp-recipe-maker-premium' ),
			'settings' => array(
				array(
					'id' => 'recipe_collections_inbox_name',
					'name' => __( 'Default Inbox Name', 'wp-recipe-maker' ),
					'description' => __( 'Name of the inbox collection that exists for everyone.', 'wp-recipe-maker' ),
					'type' => 'text',
					'default' => __( 'Inbox', 'wp-recipe-maker-premium' ),
				),
				array(
					'id' => 'recipe_collections_recipe_click',
					'name' => __( 'Click on recipe', 'wp-recipe-maker' ),
					'description' => __( 'What happens when clicking on a recipe in the collection.', 'wp-recipe-maker' ),
					'type' => 'dropdown',
					'options' => array(
						'disabled' => __( 'Does nothing', 'wp-recipe-maker' ),
						'recipe' => __( 'Shows the recipe box', 'wp-recipe-maker' ),
						'parent' => __( 'Opens the parent post', 'wp-recipe-maker' ),
					),
					'default' => 'recipe',
				),
				array(
					'id' => 'recipe_collections_template_modern',
					'name' => __( 'Recipe template to show', 'wp-recipe-maker' ),
					'type' => 'dropdownTemplateModern',
					'default' => 'compact',
					'dependency' => array(
						array(
							'id' => 'recipe_template_mode',
							'value' => 'modern',
						),
						array(
							'id' => 'recipe_collections_recipe_click',
							'value' => 'recipe',
						),
					),
				),
				array(
					'id' => 'recipe_collections_template_legacy',
					'name' => __( 'Recipe template to show', 'wp-recipe-maker' ),
					'type' => 'dropdownTemplateLegacy',
					'default' => 'simple',
					'dependency' => array(
						array(
							'id' => 'recipe_template_mode',
							'value' => 'legacy',
						),
						array(
							'id' => 'recipe_collections_recipe_click',
							'value' => 'recipe',
						),
					),
				),
			),
		),
		array(
			'name' => __( 'Appearance', 'wp-recipe-maker-premium' ),
			'settings' => array(
				array(
					'id' => 'recipe_collections_appearance_font_size',
					'name' => __( 'Base Font Size', 'wp-recipe-maker' ),
					'type' => 'number',
					'suffix' => 'px',
					'default' => '12',
				),
				array(
					'id' => 'recipe_collections_appearance_column_size',
					'name' => __( 'Minimum Column Width', 'wp-recipe-maker' ),
					'type' => 'number',
					'suffix' => 'px',
					'default' => '200',
				),
				array(
					'id' => 'recipe_collections_recipe_style',
					'name' => __( 'Recipe style', 'wp-recipe-maker' ),
					'type' => 'dropdown',
					'options' => array(
						'compact' => __( 'Compact', 'wp-recipe-maker' ),
						'large' => __( 'Large Image', 'wp-recipe-maker' ),
						'overlay' => __( 'Overlay', 'wp-recipe-maker' ),
					),
					'default' => 'compact',
				),
			),
		),
		array(
			'name' => __( 'Nutrition Facts', 'wp-recipe-maker-premium' ),
			'description' => __( 'In each column, show the added totals per serving for the nutrition facts.', 'wp-recipe-maker-premium' ),
			'settings' => array(
				array(
					'id' => 'recipe_collections_nutrition_facts',
					'name' => __( 'Enable button to show nutrition facts', 'wp-recipe-maker-premium' ),
					'type' => 'toggle',
					'default' => false,
				),
				array(
					'id' => 'recipe_collections_nutrition_facts_hidden_default',
					'name' => __( 'Button is enabled by default', 'wp-recipe-maker-premium' ),
					'description' => __( 'When disabled an extra click is required to show the nutrition facts.', 'wp-recipe-maker-premium' ),
					'type' => 'toggle',
					'default' => false,
					'dependency' => array(
						'id' => 'recipe_collections_nutrition_facts',
						'value' => true,
					),
				),
				array(
					'id' => 'recipe_collections_nutrition_facts_fields',
					'name' => __( 'Nutrition fields to show', 'wp-recipe-maker-premium' ),
					'type' => 'dropdownMultiselect',
					'options' => array(
						'calories' => __( 'Calories', 'wp-recipe-maker-premium' ),
						'carbohydrates' => __( 'Carbohydrates', 'wp-recipe-maker-premium' ),
						'protein' => __( 'Protein', 'wp-recipe-maker-premium' ),
						'fat' => __( 'Fat', 'wp-recipe-maker-premium' ),
						'saturated_fat' => __( 'Saturated Fat', 'wp-recipe-maker-premium' ),
						'polyunsaturated_fat' => __( 'Polyunsaturated Fat', 'wp-recipe-maker-premium' ),
						'monounsaturated_fat' => __( 'Monounsaturated Fat', 'wp-recipe-maker-premium' ),
						'trans_fat' => __( 'Trans Fat', 'wp-recipe-maker-premium' ),
						'cholesterol' => __( 'Cholesterol', 'wp-recipe-maker-premium' ),
						'sodium' => __( 'Sodium', 'wp-recipe-maker-premium' ),
						'potassium' => __( 'Potassium', 'wp-recipe-maker-premium' ),
						'fiber' => __( 'Fiber', 'wp-recipe-maker-premium' ),
						'sugar' => __( 'Sugar', 'wp-recipe-maker-premium' ),
						'vitamin_a' => __( 'Vitamin A', 'wp-recipe-maker-premium' ),
						'vitamin_c' => __( 'Vitamin C', 'wp-recipe-maker-premium' ),
						'calcium' => __( 'Calcium', 'wp-recipe-maker-premium' ),
						'iron' => __( 'Iron', 'wp-recipe-maker-premium' ),
					),
					'default' => array(
						'calories',
						'carbohydrates',
						'protein',
						'fat',
					),
					'dependency' => array(
						'id' => 'recipe_collections_nutrition_facts',
						'value' => true,
					),
				),
				array(
					'id' => 'recipe_collections_nutrition_facts_round_to_decimals',
					'name' => __( 'Round quantity to', 'wp-recipe-maker' ),
					'description' => __( 'Number of decimals to round a quantity to when adding up nutrition facts.', 'wp-recipe-maker' ),
					'type' => 'number',
					'suffix' => 'decimals',
					'default' => '1',
				),
			),
		),
		array(
			'name' => __( 'Shopping List', 'wp-recipe-maker-premium' ),
			'settings' => array(
				array(
					'id' => 'recipe_collections_shopping_list',
					'name' => __( 'Allow shopping list generation', 'wp-recipe-maker' ),
					'type' => 'toggle',
					'default' => true,
				),
				array(
					'id' => 'recipe_collections_shopping_list_links',
					'name' => __( 'Ingredient Links', 'wp-recipe-maker' ),
					'type' => 'toggle',
					'default' => true,
					'dependency' => array(
						'id' => 'recipe_collections_shopping_list',
						'value' => true,
					),
				),
				array(
					'id' => 'recipe_collections_shopping_list_round_to_decimals',
					'name' => __( 'Round quantity to', 'wp-recipe-maker' ),
					'description' => __( 'Number of decimals to round a quantity to in the shopping list.', 'wp-recipe-maker' ),
					'type' => 'number',
					'suffix' => 'decimals',
					'default' => '2',
				),
				array(
					'id' => 'recipe_collections_shopping_list_print',
					'name' => __( 'Show print buttons', 'wp-recipe-maker' ),
					'type' => 'toggle',
					'default' => true,
					'dependency' => array(
						'id' => 'recipe_collections_shopping_list',
						'value' => true,
					),
				),
			),
		),
		array(
			'name' => __( 'Saved Collections', 'wp-recipe-maker-premium' ),
			'description' => __( 'Create your own collections to display to your visitors.', 'wp-recipe-maker-premium' ),
			'documentation' => 'https://help.bootstrapped.ventures/article/149-saved-recipe-collection',
			'settings' => array(
				array(
					'id' => 'recipe_collections_save_button',
					'name' => __( 'Allow save to own collections', 'wp-recipe-maker' ),
					'type' => 'toggle',
					'default' => true,
				),
			),
		),
	),
);
