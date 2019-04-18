<?php
/**
 * Handle the ingredient links in the recipe modal.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.3.0
 *
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/includes/public
 */

/**
 * Handle the ingredient links in the recipe modal.
 *
 * @since      1.3.0
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMP_Ingredient_Links {

	/**
	 * Register actions and filters.
	 *
	 * @since    1.3.0
	 */
	public static function init() {
		add_action( 'wp_ajax_wprm_get_ingredient_links', array( __CLASS__, 'ajax_get_ingredient_links' ) );
	}

	/**
	 * Get ingredient links through AJAX.
	 *
	 * @since    1.0.0
	 */
	public static function ajax_get_ingredient_links() {
		if ( check_ajax_referer( 'wprm', 'security', false ) ) {
			$ingredients = isset( $_POST['ingredients'] ) ? wp_unslash( $_POST['ingredients'] ) : array(); // Input var okay.

			$links = array();
			foreach ( $ingredients as $ingredient ) {
				$links[ $ingredient ] = self::get_ingredient_link( $ingredient );
			}

			wp_send_json_success( array(
				'links' => $links,
			) );
		}

		wp_die();
	}

	/**
	 * Get the ingredient link for a specific ingredient.
	 *
	 * @since    1.3.0
	 * @param    mixed $ingredient_name_or_id Name or ID of the ingredient to get the link for.
	 */
	public static function get_ingredient_link( $ingredient_name_or_id ) {
		if ( is_int( $ingredient_name_or_id ) ) {
			$ingredient_id = $ingredient_name_or_id;
		} else {
			$term = term_exists( $ingredient_name_or_id, 'wprm_ingredient' );
			$ingredient_id = isset( $term['term_id'] ) ? $term['term_id'] : false;
		}

		$link = array(
			'url' => '',
			'nofollow' => '',
		);

		if ( $ingredient_id ) {
			$link['url'] = get_term_meta( $ingredient_id, 'wprmp_ingredient_link', true );

			$link_nofollow = get_term_meta( $ingredient_id, 'wprmp_ingredient_link_nofollow', true );
			$link['nofollow'] = in_array( $link_nofollow, array( 'default', 'nofollow', 'follow' ), true ) ? $link_nofollow : 'default';
		}

		return $link;
	}

	/**
	 * Update links for a set of ingredients.
	 *
	 * @since    1.3.0
	 * @param    mixed $ingredients Ingredients with links to update to.
	 */
	public static function update_ingredient_links( $ingredients ) {
		foreach ( $ingredients as $name => $link ) {
			$term = term_exists( $name, 'wprm_ingredient' );
			$id = isset( $term['term_id'] ) ? $term['term_id'] : false;

			if ( $id && isset( $link['url'] ) && isset( $link['nofollow'] ) ) {
				update_term_meta( $id, 'wprmp_ingredient_link', $link['url'] );
				update_term_meta( $id, 'wprmp_ingredient_link_nofollow', $link['nofollow'] );
			}
		}
	}
}

WPRMP_Ingredient_Links::init();
