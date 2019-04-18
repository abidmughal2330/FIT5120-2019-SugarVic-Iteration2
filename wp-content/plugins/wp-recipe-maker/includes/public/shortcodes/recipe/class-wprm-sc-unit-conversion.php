<?php
/**
 * Handle the recipe unit conversion shortcode.
 *
 * @link       http://bootstrapped.ventures
 * @since      3.3.0
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/public/shortcodes/recipe
 */

/**
 * Handle the recipe unit conversion shortcode.
 *
 * @since      3.3.0
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/public/shortcodes/recipe
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRM_SC_Unit_Conversion extends WPRM_Template_Shortcode {
	public static $shortcode = 'wprm-recipe-unit-conversion';

	public static function init() {
		self::$attributes = array(
			'id' => array(
				'default' => '0',
			),
			'style' => array (
				'default' => 'links',
				'type' => 'dropdown',
				'options' => array(
					'links' => 'Links',
					'dropdown' => 'Dropdown',
				),
			),
			'text_style' => array(
				'default' => 'normal',
				'type' => 'dropdown',
				'options' => 'text_styles',
				'dependency' => array(
					'id' => 'style',
					'value' => 'links',
				),
			),
		);
		parent::init();
	}

	/**
	 * Output for the shortcode.
	 *
	 * @since	3.2.0
	 * @param	array $atts Options passed along with the shortcode.
	 */
	public static function shortcode( $atts ) {
		$atts = parent::get_attributes( $atts );

		$recipe = WPRM_Template_Shortcodes::get_recipe( $atts['id'] );
		if ( ! $recipe || ! $recipe->ingredients() || ! WPRM_Addons::is_active( 'unit-conversion' ) || ! WPRM_Settings::get( 'unit_conversion_enabled' ) ) {
			return '';
		}

		$output = '';
		$ingredients = $recipe->ingredients_without_groups();
		$unit_systems = array(
			1 => true, // Default unit system.
		);

		// Check if there are values for any other unit system.
		foreach ( $ingredients as $ingredient ) {
			if ( isset( $ingredient['converted'] ) ) {
				foreach ( $ingredient['converted'] as $system => $values ) {
					if ( $values['amount'] || $values['unit'] ) {
						$unit_systems[ $system ] = true;
					}
				}
			}
		}

		if ( count( $unit_systems ) > 1 ) {
			$classes = array(
				'wprm-unit-conversion-container',
				'wprm-unit-conversion-container-' . $atts['style'],
			);

			$unit_conversion_output = '';

			if ( 'links' === $atts['style'] ) {
				$unit_system_links = array();
				foreach ( $unit_systems as $unit_system => $value ) {
					$active = 1 === $unit_system ? ' wprmpuc-active' : '';
					$unit_system_links[] = '<a href="#" class="wprm-unit-conversion' . esc_attr( $active ) . '" data-system="' . esc_attr( $unit_system ) . '" data-recipe="' . esc_attr( $recipe->id() ) . '">' . WPRM_Settings::get( 'unit_conversion_system_' . $unit_system ) . '</a>';
				}

				$classes[] = 'wprm-block-text-' . $atts['text_style'];
				$unit_conversion_output = implode( ' - ', $unit_system_links );
			} else {
				$unit_system_options = '';
				foreach ( $unit_systems as $unit_system => $value ) {
					$selected = 1 === $unit_system ? ' selected="selected"' : '';
					$unit_system_options .= '<option value="' . esc_attr( $unit_system ) . '"' . $selected . '>' . WPRM_Settings::get( 'unit_conversion_system_' . $unit_system ) . '</option>';
				}

				$unit_conversion_output = '<select class="wprm-unit-conversion-dropdown" data-recipe="' . esc_attr( $recipe->id() ) . '">' . $unit_system_options . '</select>';
			}
			

			// Output.
			$output = '<div class="' . implode( ' ', $classes ) . '">' . $unit_conversion_output . '</div>';

			wp_localize_script( 'wprm-public', 'wprmpuc_recipe_' . $recipe->id(), array(
				'ingredients' => $ingredients,
			));
		}

		return apply_filters( parent::get_hook(), $output, $atts, $recipe );
	}
}

WPRM_SC_Unit_Conversion::init();