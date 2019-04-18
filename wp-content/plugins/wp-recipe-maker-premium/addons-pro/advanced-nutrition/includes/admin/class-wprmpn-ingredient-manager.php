<?php
/**
 * Handles saved nutrition ingredients.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.1.0
 *
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/addons-pro/advanced-nutrition/includes/admin
 */

/**
 * Handles saved nutrition ingredients.
 *
 * @since      1.1.0
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/addons-pro/advanced-nutrition/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMPN_Ingredient_Manager {

	/**
	 * Register actions and filters.
	 *
	 * @since    1.1.0
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'ingredient_taxonomy' ), 2 );

		add_action( 'wp_ajax_wprmpn_save_ingredient', array( __CLASS__, 'ajax_save_ingredient' ) );
		add_action( 'wp_ajax_wprmpn_rename_ingredient', array( __CLASS__, 'ajax_rename_ingredient' ) );
		add_action( 'wp_ajax_wprmpn_search_saved_ingredients', array( __CLASS__, 'ajax_search_saved_ingredients' ) );
	}

	/**
	 * Register the ingredient taxonomy.
	 *
	 * @since    1.1.0
	 */
	public static function ingredient_taxonomy() {
		$args = apply_filters( 'wprm_nutrition_ingredient_taxonomy', array(
			'labels'            => array(
				'name'               => _x( 'Courses', 'taxonomy general name', 'wp-recipe-maker' ),
				'singular_name'      => _x( 'Course', 'taxonomy singular name', 'wp-recipe-maker' ),
			),
			'hierarchical'      => false,
			'public'            => false,
			'show_ui' 			=> false,
			'query_var'         => false,
			'rewrite'           => false,
			'show_in_rest'      => true,
		) );

		register_taxonomy( 'wprm_nutrition_ingredient', WPRM_POST_TYPE, $args );
		register_taxonomy_for_object_type( 'wprm_nutrition_ingredient', WPRM_POST_TYPE );
	}

	/**
	 * Save an ingredient.
	 *
	 * @since    1.1.0
	 */
	public static function ajax_save_ingredient() {
		if ( check_ajax_referer( 'wprm', 'security', false ) ) {
			$id = isset( $_POST['custom_nutrition_id'] ) ? intval( $_POST['custom_nutrition_id'] ) : 0; // Input var okay.
			$ingredient = isset( $_POST['ingredient'] ) ? wp_unslash( $_POST['ingredient'] ) : array(); // Input var okay.

			$name = sanitize_text_field( $ingredient['name'] );
			$unique_name = $name;
			$i = 2;

			if ( 0 === $id ) {
				do {
					$term = wp_insert_term( $unique_name, 'wprm_nutrition_ingredient' );
					$unique_name = $name . ' (' . $i . ')';
					$i++;
				} while ( is_wp_error( $term ) );

				$id = $term['term_id'];
			} else {
				$existing_id = term_exists( $unique_name, 'wprm_nutrition_ingredient' );
				while ( $existing_id && $id !== intval( $existing_id['term_id'] ) ) {
					$unique_name = $name . ' (' . $i . ')';
					$i++;
					$existing_id = term_exists( $unique_name, 'wprm_nutrition_ingredient' );
				}

				wp_update_term( $id, 'wprm_nutrition_ingredient', array(
					'name' => $unique_name,
				) );
			}

			$nutrition = array(
				'amount' => sanitize_text_field( $ingredient['amount'] ),
				'unit' => sanitize_text_field( $ingredient['unit'] ),
				'nutrients' => $ingredient['nutrition'],
			);

			$meta = update_term_meta( $id, 'wprpn_nutrition', $nutrition );

			wp_send_json_success();
		}

		wp_die();
	}

	/**
	 * Rename an ingredient.
	 *
	 * @since    2.3.0
	 */
	public static function ajax_rename_ingredient() {
		if ( check_ajax_referer( 'wprm', 'security', false ) ) {
			$custom_nutrition_id = isset( $_POST['custom_nutrition_id'] ) ? intval( $_POST['custom_nutrition_id'] ) : 0; // Input var okay.
			$new_name = isset( $_POST['new_name'] ) ? sanitize_text_field( wp_unslash( $_POST['new_name'] ) ) : ''; // Input var okay.

			if ( $custom_nutrition_id ) {
				$term = get_term( $custom_nutrition_id, 'wprm_nutrition_ingredient' );

				if ( $term && ! is_wp_error( $term ) ) {
					$term_update = wp_update_term(
						$custom_nutrition_id,
						'wprm_nutrition_ingredient',
						array(
							'name' => $new_name,
							'slug' => sanitize_title( $new_name ),
						)
					);
				}
			}
			wp_send_json_success();
		}

		wp_die();
	}

	/**
	 * Search for saved ingredients by keyword.
	 *
	 * @since    1.1.0
	 */
	public static function ajax_search_saved_ingredients() {
		if ( check_ajax_referer( 'wprm', 'security', false ) ) {
			$search = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : ''; // Input var okay.

			$ingredients = array();

			$args = array(
				'taxonomy' => 'wprm_nutrition_ingredient',
				'hide_empty' => false,
				'fields' => 'id=>name',
				'name__like' => $search,
			);

			$terms = get_terms( $args );

			foreach ( $terms as $id => $name ) {
				$ingredients[] = array(
					'id' => $id,
					'text' => $name,
					'nutrition' => get_term_meta( $id, 'wprpn_nutrition', true ),
				);
			}

			wp_send_json_success( array(
				'saved_ingredients' => $ingredients,
			) );
		}

		wp_die();
	}
}

WPRMPN_Ingredient_Manager::init();
