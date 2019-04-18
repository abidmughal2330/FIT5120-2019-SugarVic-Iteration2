<?php
/**
 * Handle the recipe ingredients shortcode.
 *
 * @link       http://bootstrapped.ventures
 * @since      3.3.0
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/public/shortcodes/recipe
 */

/**
 * Handle the recipe ingredients shortcode.
 *
 * @since      3.3.0
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/public/shortcodes/recipe
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRM_SC_Ingredients extends WPRM_Template_Shortcode {
	public static $shortcode = 'wprm-recipe-ingredients';

	public static function init() {
		self::$attributes = array(
			'id' => array(
				'default' => '0',
			),
			'text_style' => array(
				'default' => 'normal',
				'type' => 'dropdown',
				'options' => 'text_styles',
			),
			'header' => array(
				'default' => '',
				'type' => 'text',
			),
			'header_tag' => array(
				'default' => 'h3',
				'type' => 'dropdown',
				'options' => 'header_tags',
				'dependency' => array(
					'id' => 'header',
					'value' => '',
					'type' => 'inverse',
				),
			),
			'header_style' => array(
				'default' => 'bold',
				'type' => 'dropdown',
				'options' => 'text_styles',
				'dependency' => array(
					'id' => 'header',
					'value' => '',
					'type' => 'inverse',
				),
			),
			'group_tag' => array(
				'default' => 'h4',
				'type' => 'dropdown',
				'options' => 'header_tags',
			),
			'group_style' => array(
				'default' => 'bold',
				'type' => 'dropdown',
				'options' => 'text_styles',
			),
			'list_style' => array(
				'default' => 'disc',
				'type' => 'dropdown',
				'options' => 'list_style_types',
			),
			'ingredient_notes_separator' => array(
				'default' => 'none',
				'type' => 'dropdown',
				'options' => array(
					'none' => 'None',
					'comma' => 'Comma',
					'dash' => 'Dash',
					'parentheses' => 'Parentheses',
				),
			),
			'notes_style' => array(
				'default' => 'normal',
				'type' => 'dropdown',
				'options' => array(
					'normal' => 'Normal',
					'faded' => 'Faded',
					'smaller' => 'Smaller',
					'smaller-faded' => 'Smaller & Faded',
				),
			),
			'unit_conversion' => array(
				'default' => 'after',
				'type' => 'dropdown',
				'options' => array(
					'' => "Don't show",
					'before' => 'Show selector before the ingredients',
					'after' => 'Show selector after the ingredients',
					'both' => 'Show both systems at once',
				),
			),
			'unit_conversion_style' => array(
				'default' => 'links',
				'type' => 'dropdown',
				'options' => array(
					'links' => 'Links',
					'dropdown' => 'Dropdown',
				),
				'dependency' => array(
					array(
						'id' => 'unit_conversion',
						'value' => '',
						'type' => 'inverse',
					),
					array(
						'id' => 'unit_conversion',
						'value' => 'both',
						'type' => 'inverse'
					),
				),
			),
			'unit_conversion_both_style' => array(
				'default' => 'parentheses',
				'type' => 'dropdown',
				'options' => array(
					'none' => 'None',
					'parentheses' => 'Parentheses',
				),
				'dependency' => array(
					'id' => 'unit_conversion',
					'value' => 'both',
				),
			),
			'unit_conversion_show_identical' => array(
				'default' => '1',
				'type' => 'toggle',
				'dependency' => array(
					'id' => 'unit_conversion',
					'value' => 'both',
				),
			),
		);
		parent::init();
	}

	/**
	 * Output for the shortcode.
	 *
	 * @since	3.3.0
	 * @param	array $atts Options passed along with the shortcode.
	 */
	public static function shortcode( $atts ) {
		$atts = parent::get_attributes( $atts );

		$recipe = WPRM_Template_Shortcodes::get_recipe( $atts['id'] );
		if ( ! $recipe || ! $recipe->ingredients() ) {
			return '';
		}

		// Output.
		$classes = array(
			'wprm-recipe-ingredients-container',
			'wprm-block-text-' . $atts['text_style'],
		);

		$output = '<div class="' . implode( ' ', $classes ) . '">';

		if ( $atts['header'] ) {
			$classes = array(
				'wprm-recipe-header',
				'wprm-recipe-ingredients-header',
				'wprm-block-text-' . $atts['header_style'],
			);

			$tag = trim( $atts['header_tag'] );
			$output .= '<' . $tag . ' class="' . implode( ' ', $classes ) . '">' . __( $atts['header'], 'wp-recipe-maker' ) . '</' . $tag . '>';
		}

		if ( 'before' === $atts['unit_conversion'] ) {
			$unit_conversion_atts = array(
				'id' => $atts['id'],
				'style' => $atts['unit_conversion_style'],
			);
			$output .= WPRM_SC_Unit_Conversion::shortcode( $unit_conversion_atts );
		}

		$ingredients = $recipe->ingredients();
		foreach ( $ingredients as $ingredient_group ) {
			$output .= '<div class="wprm-recipe-ingredient-group">';

			if ( $ingredient_group['name'] ) {
				$classes = array(
					'wprm-recipe-group-name',
					'wprm-recipe-ingredient-group-name',
					'wprm-block-text-' . $atts['group_style'],
				);

				$tag = trim( $atts['group_tag'] );
				$output .= '<' . $tag . ' class="' . implode( ' ', $classes ) . '">' . $ingredient_group['name'] . '</' . $tag . '>';
			}

			$output .= '<ul class="wprm-recipe-ingredients">';

			foreach ( $ingredient_group['ingredients'] as $ingredient ) {
				$list_style_type = 'checkbox' === $atts['list_style'] ? 'none' : $atts['list_style'];
				$style = 'list-style-type: ' . $list_style_type . ';';
				$output .= '<li class="wprm-recipe-ingredient" style="' . $style . '">';

				// Output checkbox.
				if ( 'checkbox' === $atts['list_style'] && WPRM_Addons::is_active( 'premium' ) ) {
					$output .= WPRMP_Checkboxes::checkbox();
				}
				
				// Amount & Unit.
				$amount_unit = '';

				if ( $ingredient['amount'] || ( isset( $ingredient['converted'] ) && isset( $ingredient['converted'][2] ) && $ingredient['converted'][2]['amount'] ) ) {
					$amount_unit .= '<span class="wprm-recipe-ingredient-amount">' . $ingredient['amount'] . '</span> ';
				}
				if ( $ingredient['unit'] || ( isset( $ingredient['converted'] ) && isset( $ingredient['converted'][2] ) && $ingredient['converted'][2]['unit'] ) ) {
					$amount_unit .= '<span class="wprm-recipe-ingredient-unit">' . $ingredient['unit'] . '</span> ';
				}

				if ( 'both' === $atts['unit_conversion'] && WPRM_Addons::is_active( 'unit-conversion' ) && WPRM_Settings::get( 'unit_conversion_enabled' ) ) {
					// Surround first unit system with span.
					$amount_unit = '<span class="wprm-recipe-ingredient-unit-system wprm-recipe-ingredient-unit-system-1">' . trim( $amount_unit ) . '</span>';

					// Add second unit system.
					$second_system = '';
					if ( isset( $ingredient['converted'] ) && isset( $ingredient['converted'][2] ) ) {

						// Check if identical if we're not showing them.
						$skip_second_system = false;
						if ( ! $atts['unit_conversion_show_identical'] ) {
							if ( $ingredient['amount'] === $ingredient['converted'][2]['amount'] && $ingredient['unit'] === $ingredient['converted'][2]['unit'] ) {
								$skip_second_system = true;
							}
						}

						// Add second unit system to output.
						if ( ! $skip_second_system ) {
							if ( $ingredient['converted'][2]['amount'] ) {
								$second_system .= '<span class="wprm-recipe-ingredient-amount">' . $ingredient['converted'][2]['amount'] . '</span> ';
							}
							if ( $ingredient['converted'][2]['unit'] ) {
								$second_system .= '<span class="wprm-recipe-ingredient-unit">' . $ingredient['converted'][2]['unit'] . '</span>';
							}
						}
					}

					if ( $second_system && 'parentheses' === $atts['unit_conversion_both_style'] ) {
						$second_system = '(' . $second_system . ')';
					}

					$amount_unit .= ' <span class="wprm-recipe-ingredient-unit-system wprm-recipe-ingredient-unit-system-2">' . $second_system . '</span> ';
				}
				$output .= $amount_unit;

				// Ingredient name.
				if ( $ingredient['name'] ) {
					$separator = '';
					if ( $ingredient['notes'] ) {
						switch ( $atts['ingredient_notes_separator'] ) {
							case 'comma':
								$separator = ', ';
								break;
							case 'dash':
								$separator = ' - ';
								break;
							default:
								$separator = ' ';
						}	
					}

					$output .= '<span class="wprm-recipe-ingredient-name">' . self::ingredient_name( $recipe, $ingredient ) . '</span>'  . $separator;
				}
				if ( $ingredient['notes'] ) {
					if ( 'parentheses' === $atts['ingredient_notes_separator'] ) {
						$output .= '<span class="wprm-recipe-ingredient-notes wprm-recipe-ingredient-notes-' . $atts['notes_style'] . '">(' . $ingredient['notes'] . ')</span>';
					} else {
						$output .= '<span class="wprm-recipe-ingredient-notes wprm-recipe-ingredient-notes-' . $atts['notes_style'] . '">' . $ingredient['notes'] . '</span>';
					}
				}

				$output .= '</li>';
			}

			$output .= '</ul>';
			$output .= '</div>';
		}

	 	if ( 'after' === $atts['unit_conversion'] ) {
			$unit_conversion_atts = array(
				'id' => $atts['id'],
				'style' => $atts['unit_conversion_style'],
			);
			$output .= WPRM_SC_Unit_Conversion::shortcode( $unit_conversion_atts );
		}

		$output .= '</div>';

		return apply_filters( parent::get_hook(), $output, $atts, $recipe );
	}

	/**
	 * Display the ingredient name.
	 *
	 * @since	3.3.0
	 * @param	mixed   $recipe Recipe we're displaying the ingredient from.
	 * @param	array   $ingredient Ingredient to display.
	 */
	private static function ingredient_name( $recipe, $ingredient ) {
		$name = $ingredient['name'];
		$show_link = WPRM_Addons::is_active( 'premium' ) ? true : false;

		$link = array();
		if ( $show_link ) {
			if ( 'global' === $recipe->ingredient_links_type() ) {
				$link = WPRMP_Ingredient_Links::get_ingredient_link( $ingredient['id'] );
			} elseif ( isset( $ingredient['link'] ) ) {
				$link = $ingredient['link'];
			}
		}

		if ( isset( $link['url'] ) && $link['url'] ) {
			$target = WPRM_Settings::get( 'ingredient_links_open_in_new_tab' ) ? ' target="_blank"' : '';

			// Nofollow.
			switch ( $link['nofollow'] ) {
				case 'follow':
					$nofollow = '';
					break;
				case 'nofollow':
					$nofollow = ' rel="nofollow"';
					break;
				default:
					$nofollow = WPRM_Settings::get( 'ingredient_links_use_nofollow' ) ? ' rel="nofollow"' : '';
			}

			return '<a href="' . $link['url'] . '"' . $target . $nofollow . '>' . $name . '</a>';
		} else {
			return $name;
		}
	}
}

WPRM_SC_Ingredients::init();