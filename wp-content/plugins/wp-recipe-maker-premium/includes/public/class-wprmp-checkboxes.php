<?php
/**
 * Use checkboxes for ingredients and instructions.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.6.0
 *
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/includes/public
 */

/**
 * Use checkboxes for ingredients and instructions.
 *
 * @since      1.6.0
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMP_Checkboxes {

	/**
	 * Register actions and filters.
	 *
	 * @since    1.6.0
	 */
	public static function init() {
		add_filter( 'wprm_custom_css', array( __CLASS__, 'custom_css' ), 10, 3 );
	}

	/**
	 * Add custom CSS required for the checkboxes.
	 *
	 * @since    1.6.0
	 */
	public static function custom_css( $output, $type, $selector ) {
		if ( 'legacy' === WPRM_Settings::get( 'recipe_template_mode' ) ) {
			$checkboxes = false;
			$checkbox_style = 'content: \'\'; display: inline-block; height: 1.1em; width: 1em; background-image: url(\'' . WPRM_URL . 'assets/icons/checkbox-empty.svg\'); background-size: 0.9em; background-position: left bottom; background-repeat:no-repeat; padding-left: 1.5em; float: left; margin-left: -1.5em;';

			if ( 'checkbox' === WPRM_Settings::get( 'template_ingredient_list_style' ) ) {
				$checkboxes = true;
				$output .= $selector . ' li.wprm-recipe-ingredient:before { ' . $checkbox_style . ' }';
				$output .= $selector . ' li.wprm-recipe-ingredient { margin-left: 1.5em !important; }';
				$output .= $selector . ' ul.wprm-recipe-ingredients { margin-left: 0; }';
			}

			if ( 'checkbox' === WPRM_Settings::get( 'template_instruction_list_style' ) ) {
				$checkboxes = true;
				$output .= $selector . ' li.wprm-recipe-instruction:before { ' . $checkbox_style . ' }';
				$output .= $selector . ' li.wprm-recipe-instruction { margin-left: 1.5em !important; }';
				$output .= $selector . ' ol.wprm-recipe-instructions { margin-left: 0; }';
			}

			if ( $checkboxes ) {
				$output .= $selector . ' .wprm-list-checkbox { ' . $checkbox_style . ' }';
				$output .= $selector . ' .wprm-list-checkbox.wprm-list-checkbox-checked { background-image: url(\'' . WPRM_URL . 'assets/icons/checkbox-checked.svg\'); }';
			}
		}

		return $output;
	}

	/**
	 * Get checkbox.
	 *
	 * @since	4.0.0
	 */
	public static function checkbox() {
		$output = '<span class="wprm-checkbox-container">';
		$output .= '<span class="wprm-checkbox wprm-checkbox-empty">' . WPRM_Icon::get( 'checkbox-empty' ) . '</span>';
		$output .= '<span class="wprm-checkbox wprm-checkbox-checked">' . WPRM_Icon::get( 'checkbox-checked' ) . '</span>';
		$output .= '</span>';

		return $output;
	}
}

WPRMP_Checkboxes::init();
