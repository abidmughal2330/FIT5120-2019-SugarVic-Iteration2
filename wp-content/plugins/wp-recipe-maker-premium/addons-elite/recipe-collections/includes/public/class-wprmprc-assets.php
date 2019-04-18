<?php
/**
 * Handle the Recipe Collections assets.
 *
 * @link       http://bootstrapped.ventures
 * @since      4.1.0
 *
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-collections
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-collections/includes/public
 */

/**
 * Handle the Recipe Collections assets.
 *
 * @since      4.1.0
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-collections
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-collections/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMPRC_Assets {

	/**
	 * Register actions and filters.
	 *
	 * @since	4.1.0
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
		add_action( 'wp_head', array( __CLASS__, 'custom_css' ) );
		add_action( 'admin_head', array( __CLASS__, 'custom_css_admin' ) );

		add_filter( 'wprmp_localize_public', array( __CLASS__, 'localize_public_data' )  );
	}

	/**
	 * Enqueue the public assets.
	 *
	 * @since	4.2.0
	 */
	public static function enqueue() {
		wp_enqueue_style( 'wprmprc-public', WPRMP_URL . 'dist/public-recipe-collections.css', array(), WPRMP_VERSION, 'all' );
		wp_register_script( 'wprmprc-public', WPRMP_URL . 'dist/public-recipe-collections.js', array( 'wprmp-public' ), WPRMP_VERSION, true );
	}

	/**
	 * Localize the public JS file.
	 *
	 * @since	4.1.0
	 */
	public static function localize_public_data( $data ) {
		$data['endpoints']['collections'] = get_rest_url( null, 'wp/v2/' . WPRMPRC_POST_TYPE );
		$data['endpoints']['collections_helper'] = get_rest_url( null, 'wp-recipe-maker/v1/recipe-collections' );
		$data['collections'] = array(
			'default' => WPRMPRC_Manager::get_default_collections(),
		);
		$data['user'] = get_current_user_id();

		return $data;
	}

	/**
	 * Data for localizing the shortcode.
	 *
	 * @since	4.1.0
	 */
	public static function localize_shortcode_data( $collection = false ) {
		$data = array(
			'user' => get_current_user_id(),
			'settings' => array(
				'recipe_collections_link' => WPRM_Settings::get( 'recipe_collections_link' ),
				'recipe_collections_recipe_style' => WPRM_Settings::get( 'recipe_collections_recipe_style' ),
				'recipe_collections_recipe_click' => WPRM_Settings::get( 'recipe_collections_recipe_click' ),
				'recipe_collections_nutrition_facts' => WPRM_Settings::get( 'recipe_collections_nutrition_facts' ),
				'recipe_collections_nutrition_facts_hidden_default' => WPRM_Settings::get( 'recipe_collections_nutrition_facts_hidden_default' ),
				'recipe_collections_nutrition_facts_fields' => WPRM_Settings::get( 'recipe_collections_nutrition_facts_fields' ),
				'recipe_collections_nutrition_facts_round_to_decimals' => WPRM_Settings::get( 'recipe_collections_nutrition_facts_round_to_decimals' ),
				'recipe_collections_shopping_list' => WPRM_Settings::get( 'recipe_collections_shopping_list' ),
				'recipe_collections_shopping_list_print' => WPRM_Settings::get( 'recipe_collections_shopping_list_print' ),
				'recipe_collections_shopping_list_round_to_decimals' => WPRM_Settings::get( 'recipe_collections_shopping_list_round_to_decimals' ),
				'recipe_collections_save_button' => WPRM_Settings::get( 'recipe_collections_save_button' ),
			),
			'labels' => array(
				'overview_header' => __( 'Your Recipe Collection', 'wp-recipe-maker-premium' ),
				'overview_add_collection' => __( 'Add Collection', 'wp-recipe-maker-premium' ),
				'overview_edit_collections' => __( 'Edit Collections', 'wp-recipe-maker-premium' ),
				'confirm_delete' => __( 'Are you sure you want to delete all items in', 'wp-recipe-maker-premium' ),
				'confirm_stop' => __( 'Stop Editing', 'wp-recipe-maker-premium' ),
				'collection_default_column' => __( 'Recipes', 'wp-recipe-maker-premium' ),
				'collection_actions' => __( 'Actions', 'wp-recipe-maker-premium' ),
				'collection_save' => __( 'Save to my Collections', 'wp-recipe-maker-premium' ),
				'collection_remove_items' => __( 'Remove Recipe', 'wp-recipe-maker-premium' ),
				'collection_remove_items_stop' => __( 'Stop Removing Items', 'wp-recipe-maker-premium' ),
				'collection_add_item' => __( 'Add Recipe', 'wp-recipe-maker-premium' ),
				'collection_add_item_collection' => __( 'Add from Collection', 'wp-recipe-maker-premium' ),
				'collection_add_item_search' => __( 'Search Recipes', 'wp-recipe-maker-premium' ),
				'collection_add_item_search_placeholder' => __( 'Start typing to search...', 'wp-recipe-maker-premium' ),
				'collection_add_item_drag_drop' => __( 'Drag and drop to add:', 'wp-recipe-maker-premium' ),
				'collection_columns_groups' => __( 'Columns & Groups', 'wp-recipe-maker-premium' ),
				'collection_add_column' => __( 'Add Column', 'wp-recipe-maker-premium' ),
				'collection_edit_columns' => __( 'Edit Columns', 'wp-recipe-maker-premium' ),
				'collection_add_group' => __( 'Add Group', 'wp-recipe-maker-premium' ),
				'collection_edit_groups' => __( 'Edit Groups', 'wp-recipe-maker-premium' ),
				'nutrition_show_button' => __( 'Show Nutrition Facts', 'wp-recipe-maker-premium' ),
				'nutrition_hide_button' => __( 'Hide Nutrition Facts', 'wp-recipe-maker-premium' ),
				'nutrition_header' => __( 'Nutrition Facts (per serving)', 'wp-recipe-maker-premium' ),
				'nutrition_fields' => WPRMP_Nutrition_Label::$nutrition_fields,
				'shopping_list_header' => __( 'Shopping List', 'wp-recipe-maker-premium' ),
				'shopping_list_collection' => __( 'Collection', 'wp-recipe-maker-premium' ),
				'shopping_list_empty' => __( 'Your shopping list is empty.', 'wp-recipe-maker-premium' ),
				'shopping_list_print_list' => __( 'Print Shopping List', 'wp-recipe-maker-premium' ),
				'shopping_list_print_collection' => __( 'Print Collection', 'wp-recipe-maker-premium' ),
				'shopping_list_print_both' => __( 'Print Both', 'wp-recipe-maker-premium' ),
			),
		);

		if ( false === $collection ) {
			$data['collections'] = WPRMPRC_Manager::get_user_collections();
		} else {
			$data['collection'] = $collection->get_data();
		}

		return $data;
	}

	/**
	 * Localize the shortcode.
	 *
	 * @since	4.1.0
	 */
	public static function localize_shortcode( $collection = false ) {
		wp_localize_script( 'wprmp-public', 'wprmprc_public', self::localize_shortcode_data( $collection ) );
	}

	/**
	 * Custom CSS from settings.
	 *
	 * @since	4.1.0
	 */
	public static function custom_css() {
		$css = '';

		$css .= '#wprm-recipe-collections-app, #wprm-recipe-saved-collections-app { font-size: ' . WPRM_Settings::get( 'recipe_collections_appearance_font_size' ) . 'px; }';
		$css .= '.wprmprc-collection-column-balancer, .wprmprc-collection-column, .wprmprc-collection-actions { flex: 1; flex-basis: ' . WPRM_Settings::get( 'recipe_collections_appearance_column_size' ) . 'px; }';

		echo '<style type="text/css">' . $css . '</style>';
	}

	/**
	 * Custom CSS from settings on admin page.
	 *
	 * @since	4.1.0
	 */
	public static function custom_css_admin() {
		$screen = get_current_screen();
		
		if ( 'admin_page_wprm_recipe_collections' === $screen->id ) {
			echo self::custom_css();
		}
	}
}

WPRMPRC_Assets::init();