<?php
/**
 * Handle the custom nutrition ingredients manage page.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.3.0
 *
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/addons-pro/advanced-nutrition/includes/admin
 */

/**
 * Handle the custom nutrition ingredients manage page.
 *
 * @since      2.3.0
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/addons-pro/advanced-nutrition/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMPN_Manage {

	/**
	 * Register actions and filters.
	 *
	 * @since	2.3.0
	 */
	public static function init() {
		add_filter( 'wprm_manage_tabs', array( __CLASS__, 'manage_tabs' ), 20 );
		add_filter( 'wprm_manage_datatable_data', array( __CLASS__, 'manage_datatable_data' ), 10, 3 );

		add_action( 'wprm_manage_page', array( __CLASS__, 'manage_page' ) );

		add_action( 'wp_ajax_wprm_delete_custom_nutrition', array( __CLASS__, 'ajax_delete_custom_nutrition' ) );
	}

	/**
	 * Add custom nutrition ingredients to the manage tabs.
	 *
	 * @since	2.3.0
	 * @param	array $tabs Manage tabs.
	 */
	public static function manage_tabs( $tabs ) {
		$tabs['custom_nutrition'] = __( 'Custom Nutrition', 'wp-recipe-maker-premium' );
		return $tabs;
	}

	/**
	 * Manage page to output.
	 *
	 * @since	2.3.0
	 * @param	mixed $sub Sub manage page to display.
	 */
	public static function manage_page( $sub ) {
		if ( 'custom_nutrition' === $sub ) {
			require_once( WPRMPN_DIR . 'templates/admin/manage/custom-nutrition.php' );
		}
	}

	/**
	 * Datatable for custom nutrition ingredients.
	 *
	 * @since	2.3.0
	 * @param	mixed $data Data for the datatable.
	 * @param	mixed $table Table we are filtering the data for.
	 * @param	mixed $datatable Datatable request values.
	 */
	public static function manage_datatable_data( $data, $table, $datatable ) {
		if ( 'wprm-manage-custom-nutrition' === $table ) {
			$data = self::get_datatable( $datatable );
		}

		return $data;
	}

	/**
	 * Get the data to display in the datatable.
	 *
	 * @since	2.3.0
	 * @param	array $datatable Datatable request values.
	 */
	public static function get_datatable( $datatable ) {
		$data = array();

		$orderby_options = array(
			0 => 'id',
			1 => 'name',
		);
		$orderby = isset( $orderby_options[ $datatable['orderby'] ] ) ? $orderby_options[ $datatable['orderby'] ] : $orderby_options[0];

		$args = array(
				'taxonomy' => 'wprm_nutrition_ingredient',
				'hide_empty' => false,
				'orderby' => $orderby,
				'order' => $datatable['order'],
				'number' => $datatable['length'],
				'offset' => $datatable['start'],
				'search' => $datatable['search'],
		);

		$terms = get_terms( $args );

		foreach ( $terms as $term ) {
			$nutrition = get_term_meta( $term->term_id, 'wprpn_nutrition', true );

			$amount_unit = '<span id="wprm-manage-custom-nutrition-amount-' . esc_attr( $term->term_id ) . '">' . $nutrition['amount'] . '</span> <span id="wprm-manage-custom-nutrition-unit-' . esc_attr( $term->term_id ) . '">' . $nutrition['unit'] . '</span>';

			$nutrients = array();
			foreach ( $nutrition['nutrients'] as $label => $value ) {
				if ( $value ) {
					$nutrients[] = $label . ': ' . '<span id="wprm-manage-custom-nutrition-nutrient-' . esc_attr( $label ) . '-' . esc_attr( $term->term_id ) . '">' . $value . '</span>';
				}
			}

			$nutrition_summary = count( $nutrients ) ? implode( ' | ', $nutrients ) : __( 'No values set', 'wp-recipe-maker-premium' );

			$icon_rename = '<span class="wprm-manage-custom-nutrition-edit wprm-manage-action-icon wprm-manage-tooltip" title="' . __( 'Edit', 'wp-recipe-maker' ) . '">' . file_get_contents( WPRM_DIR . 'assets/icons/pencil.svg' ) . '</span>';
			$icon_delete = '<span class="wprm-manage-custom-nutrition-delete wprm-manage-action-icon wprm-manage-tooltip" title="' . __( 'Delete', 'wp-recipe-maker' ) . '">' . file_get_contents( WPRM_DIR . 'assets/icons/trash.svg' ) . '</span>';

			$data[] = array(
				$term->term_id,
				'<span id="wprm-manage-custom-nutrition-name-' . esc_attr( $term->term_id ) . '">' . $term->name . '</span>',
				$amount_unit,
				$nutrition_summary,
				'<span class="wprm-manage-custom-nutrition-actions" data-id="' . esc_attr( $term->term_id ) . '">' . $icon_rename . $icon_delete . '</span>',
			);
		}

		unset( $args['offset'] );
		unset( $args['number'] );
		$total = wp_count_terms( 'wprm_nutrition_ingredient', $args );

		return array(
			'draw' => $datatable['draw'],
			'recordsTotal' => $total,
			'recordsFiltered' => $total,
			'data' => $data,
		);
	}

	/**
	 * Delete custom nutrition ingredient through AJAX.
	 *
	 * @since	2.3.0
	 */
	public static function ajax_delete_custom_nutrition() {
		if ( check_ajax_referer( 'wprm', 'security', false ) ) {
			$recipe_id = isset( $_POST['recipe_id'] ) ? intval( $_POST['recipe_id'] ) : 0; // Input var okay.

			if ( $recipe_id ) {
				$recipe = WPRM_Recipe_Manager::get_recipe( $recipe_id );

				if ( $recipe ) {
					wp_trash_post( $recipe_id );
				}
			}
		}

		wp_die();
	}
}

WPRMPN_Manage::init();
