<?php
/**
 * Handle the add to collection shortcode.
 *
 * @link       http://bootstrapped.ventures
 * @since      4.1.0
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/public/shortcodes/recipe
 */

/**
 * Handle the add to collection shortcode.
 *
 * @since      4.1.0
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/public/shortcodes/recipe
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRM_SC_Add_To_Collection extends WPRM_Template_Shortcode {
	public static $shortcode = 'wprm-recipe-add-to-collection';

	public static function init() {
		self::$attributes = array(
			'id' => array(
				'default' => '0',
			),
			'style' => array(
				'default' => 'text',
				'type' => 'dropdown',
				'options' => array(
					'text' => 'Text',
					'button' => 'Button',
					'inline-button' => 'Inline Button',
					'wide-button' => 'Full Width Button',
				),
			),
			'icon' => array(
				'default' => '',
				'type' => 'icon',
			),
			'text' => array(
				'default' => __( 'Add to Collection', 'wp-recipe-maker' ),
				'type' => 'text',
			),
			'icon_added' => array(
				'default' => '',
				'type' => 'icon',
			),
			'text_added' => array(
				'default' => __( 'Go to Collections', 'wp-recipe-maker' ),
				'type' => 'text',
			),
			'text_style' => array(
				'default' => 'normal',
				'type' => 'dropdown',
				'options' => 'text_styles',
			),
			'icon_color' => array(
				'default' => '#333333',
				'type' => 'color',
				'dependency' => array(
					'id' => 'icon',
					'value' => '',
					'type' => 'inverse',
				),
			),
			'text_color' => array(
				'default' => '#333333',
				'type' => 'color',
				'dependency' => array(
					'id' => 'text',
					'value' => '',
					'type' => 'inverse',
				),
			),
			'horizontal_padding' => array(
				'default' => '5px',
				'type' => 'size',
				'dependency' => array(
					'id' => 'style',
					'value' => 'text',
					'type' => 'inverse',
				),
			),
			'vertical_padding' => array(
				'default' => '5px',
				'type' => 'size',
				'dependency' => array(
					'id' => 'style',
					'value' => 'text',
					'type' => 'inverse',
				),
			),
			'button_color' => array(
				'default' => '#ffffff',
				'type' => 'color',
				'dependency' => array(
					'id' => 'style',
					'value' => 'text',
					'type' => 'inverse',
				),
			),
			'border_color' => array(
				'default' => '#333333',
				'type' => 'color',
				'dependency' => array(
					'id' => 'style',
					'value' => 'text',
					'type' => 'inverse',
				),
			),
			'border_radius' => array(
				'default' => '0px',
				'type' => 'size',
				'dependency' => array(
					'id' => 'style',
					'value' => 'text',
					'type' => 'inverse',
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

		// Check if user has access and link has been set.
		$recipe_collections_link = WPRM_Settings::get( 'recipe_collections_link' );
		if ( ! WPRM_Addons::is_active( 'recipe-collections' ) || ! $recipe_collections_link || ( 'logged_in' === WPRM_Settings::get( 'recipe_collections_access' ) && ! is_user_logged_in() ) ) {
			return '';
		}

		$recipe = WPRM_Template_Shortcodes::get_recipe( $atts['id'] );
		if ( ! $recipe ) {
			return '';
		}

		$in_collection = $recipe->in_collection( 'inbox' );

		// Get optional icon.
		$icon = '';
		if ( $atts['icon'] ) {
			$icon = WPRM_Icon::get( $atts['icon'], $atts['icon_color'] );

			if ( $icon ) {
				$icon = '<span class="wprm-recipe-icon wprm-recipe-add-to-collection-icon wprm-recipe-not-in-collection">' . $icon . '</span> ';
			}
		}
		$icon_added = '';
		if ( $atts['icon_added'] ) {
			$icon_added = WPRM_Icon::get( $atts['icon_added'], $atts['icon_color'] );

			if ( $icon_added ) {
				$icon_added = '<span class="wprm-recipe-icon wprm-recipe-add-to-collection-icon wprm-recipe-in-collection">' . $icon_added . '</span> ';
			}
		}

		// Output.
		$classes = array(
			'wprm-recipe-add-to-collection',
			'wprm-recipe-link',
			'wprm-block-text-' . $atts['text_style'],
		);

		$style = 'color: ' . $atts['text_color'] . ';';
		if ( 'text' !== $atts['style'] ) {
			$classes[] = 'wprm-recipe-add-to-collection-' . $atts['style'];
			$classes[] = 'wprm-recipe-link-' . $atts['style'];
			$classes[] = 'wprm-color-accent';

			$style .= 'background-color: ' . $atts['button_color'] . ';';
			$style .= 'border-color: ' . $atts['border_color'] . ';';
			$style .= 'border-radius: ' . $atts['border_radius'] . ';';
			$style .= 'padding: ' . $atts['vertical_padding'] . ' ' . $atts['horizontal_padding'] . ';';
		}

		// Backwards compatibility.
		if ( 'legacy' === WPRM_Settings::get( 'recipe_template_mode' ) ) {
			$style = '';
		}

		$output = '';
		if ( ! $in_collection ) {
			$collections_data = json_encode( WPRMPRC_Manager::get_collections_data_for_recipe( $recipe ) );

			$output .= '<a href="' . esc_url( $recipe_collections_link ) . '" style="' . $style . '" class="wprm-recipe-not-in-collection ' . implode( ' ', $classes ) . '" data-recipe-id="' . esc_attr( $recipe->id() ) . '" data-recipe="' . esc_attr( $collections_data ) . '">' . $icon . __( $atts['text'], 'wp-recipe-maker' ) . '</a>';
			$style .= 'display: none;';
		}
		$output .= '<a href="' . esc_url( $recipe_collections_link ) . '" style="' . $style . '" class="wprm-recipe-in-collection ' . implode( ' ', $classes ) . '" data-recipe-id="' . esc_attr( $recipe->id() ) . '" data-text-added="">' . $icon_added . __( $atts['text_added'], 'wp-recipe-maker' ) . '</a>';

		return apply_filters( parent::get_hook(), $output, $atts, $recipe );
	}
}

WPRM_SC_Add_To_Collection::init();