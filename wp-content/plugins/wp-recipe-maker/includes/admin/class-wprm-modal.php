<?php
/**
 * Handle the recipe modal.
 *
 * @link       http://bootstrapped.ventures
 * @since      5.0.0
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/admin/modal
 */

/**
 * Handle the recipe modal.
 *
 * @since      5.0.0
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/admin/modal
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRM_Modal_New {

	/**
	 * Register actions and filters.
	 *
	 * @since    5.0.0
	 */
	public static function init() {
		add_action( 'admin_footer', array( __CLASS__, 'add_modal_content' ) );

		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
	}

	/**
	 * Add modal template to edit screen.
	 *
	 * @since    5.0.0
	 */
	public static function add_modal_content() {
		echo '<div id="wprm-admin-modal"></div>';
		echo '<div id="wprm-admin-modal-notes-placeholder">';
		wp_editor( '', 'wprm-admin-modal-notes-editor' );
		echo '</div>';
	}


	/**
	 * Enqueue stylesheets and scripts.
	 *
	 * @since    5.0.0
	 */
	public static function enqueue() {
		wp_enqueue_media();
		wp_enqueue_editor();
		
		wp_enqueue_style( 'wprm-admin-modal', WPRM_URL . 'dist/admin-modal.css', array(), WPRM_VERSION, 'all' );
		wp_enqueue_script( 'wprm-admin-modal', WPRM_URL . 'dist/admin-modal.js', array(), WPRM_VERSION, true );

		// Nutrition.
		if ( WPRM_Addons::is_active( 'premium' ) ) {
			$nutrition_fields = WPRMP_Nutrition_Label::$nutrition_fields;
		} else {
			$nutrition_fields = array(
				'calories' => array(
					'label' => __( 'Calories', 'wp-recipe-maker-premium' ),
					'unit' => 'kcal',
				),
			);
		}

		wp_localize_script( 'wprm-admin-modal', 'wprm_admin_modal', array(
			'recipe' => self::get_new_recipe(),
			'editor_uid' => 0,
			'text' => array(
				'modal' => array(
					'close' => __( 'Close', 'wp-recipe-maker' ),
				),
			),
			'options' => array(
				'author' => self::get_author_options(),
			),
			'categories' => self::get_categories(),
			'nutrition' => $nutrition_fields,
		) );
	}
	
	/**
	 * Get new recipe.
	 *
	 * @since	5.0.0
	 */
	public static function get_new_recipe() {
		return array(
			'type' => 'food',
			'image_id' => 0,
			'image_url' => '',
			'pin_image_id' => 0,
			'pin_image_url' => '',
			'video_id' => 0,
			'video_embed' => '',
			'video_thumb_url' => '',
			'name' => '',
			'summary' => '',
			'author_display' => 'default',
			'author_name' => 'custom' === WPRM_Settings::get( 'recipe_author_display_default' ) ? WPRM_Settings::get( 'recipe_author_custom_default' ) : '',
			'author_link' => '',
			'servings' => 0,
			'servings_unit' => '',
			'prep_time' => 0,
			'cook_time' => 0,
			'total_time' => 0,
			'custom_time' => 0,
			'custom_time_label' => '',
			'tags' => array(),
			'ingredients' => array(),
			'ingredients_flat' => array(
				array(
					'uid' => 0,
					'type' => 'ingredient',
					'amount' => '',
					'unit' => '',
					'name' => '',
					'notes' => '',
				),
			),
			'ingredient_links_type' => 'global',
			'instructions' => array(),
			'instructions_flat' => array(
				array(
					'uid' => 0,
					'type' => 'instruction',
					'text' => '',
					'image' => 0,
					'image_url' => '',
				),
			),
			'notes' => '',
			'nutrition' => array(),
		);
	}

	/**
	 * Get all category options.
	 *
	 * @since    5.0.0
	 */
	public static function get_categories() {
		$categories = array();
		$wprm_taxonomies = WPRM_Taxonomies::get_taxonomies();

		foreach ( $wprm_taxonomies as $wprm_taxonomy => $options ) {
			$wprm_key = substr( $wprm_taxonomy, 5 );

			$terms = get_terms( array(
				'taxonomy' => $wprm_taxonomy,
				'hide_empty' => false,
				'count' => true,
			) );

			$categories[ $wprm_key ] = array(
				'label' => $options['name'],
				'terms' => array_values( $terms ),
			);
		}

		return $categories;
	}

	/**
	 * Get all author options.
	 *
	 * @since    5.0.0
	 */
	public static function get_author_options() {
		$labels = array(
			'disabled' => __( "Don't show", 'wp-recipe-maker' ),
			'post_author' => __( 'Name of post author', 'wp-recipe-maker' ),
			'custom' => __( 'Custom author name', 'wp-recipe-maker' ),
		);

		$default = WPRM_Settings::get( 'recipe_author_display_default' );

		$options = array(
			array(
				'value' => 'default',
				'label' => __( 'Use Default', 'easy-affiliate-links' ) . ' (' . $labels[ $default ] . ')',
				'actual' => $default,
			),
		);

		foreach ( $labels as $value => $label ) {
			$options[] = array(
				'value' => $value,
				'label' => $label,
				'actual' => $value,
			);
		}

		return $options;
	}
}

WPRM_Modal_New::init();
