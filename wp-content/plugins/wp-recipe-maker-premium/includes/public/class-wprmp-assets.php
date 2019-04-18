<?php
/**
 * Responsible for loading the plugin assets.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.6.0
 *
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/includes/public
 */

/**
 * Responsible for loading the plugin assets.
 *
 * @since      1.6.0
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMP_Assets {

	/**
	 * Register actions and filters.
	 *
	 * @since    1.6.0
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin' ) );
		add_action( 'amp_post_template_css', array( __CLASS__, 'amp_style' ) );
		add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'block_assets' ) );
	}

	/**
	 * Enqueue stylesheets and scripts.
	 *
	 * @since    1.6.0
	 */
	public static function enqueue() {
		$filename = 'public-' . strtolower( WPRMP_BUNDLE );
		wp_enqueue_style( 'wprmp-public', WPRMP_URL . 'dist/' . $filename . '.css', array(), WPRMP_VERSION, 'all' );
		wp_enqueue_script( 'wprmp-public', WPRMP_URL . 'dist/' . $filename . '.js', array( 'jquery' ), WPRMP_VERSION, true );

		// Get Timer Icons.
		ob_start();
		include( WPRM_DIR . 'assets/icons/timer/pause.svg' );
		$pause = ob_get_contents();
		ob_end_clean();

		ob_start();
		include( WPRM_DIR . 'assets/icons/timer/play.svg' );
		$play = ob_get_contents();
		ob_end_clean();

		ob_start();
		include( WPRM_DIR . 'assets/icons/timer/close.svg' );
		$close = ob_get_contents();
		ob_end_clean();

		wp_localize_script( 'wprmp-public', 'wprmp_public', apply_filters( 'wprmp_localize_public', array(
			'endpoints' => array(),
			'settings' => array(
				'recipe_template_mode' => WPRM_Settings::get( 'recipe_template_mode' ),
				'features_adjustable_servings' => WPRM_Settings::get( 'features_adjustable_servings' ),
				'adjustable_servings_round_to_decimals' => WPRM_Settings::get( 'adjustable_servings_round_to_decimals' ),
				'features_user_ratings' => WPRM_Settings::get( 'features_user_ratings' ),
				'servings_changer_display' => WPRM_Settings::get( 'servings_changer_display' ),
				'template_ingredient_list_style' => WPRM_Settings::get( 'template_ingredient_list_style' ),
				'template_instruction_list_style' => WPRM_Settings::get( 'template_instruction_list_style' ),
				'template_color_icon' => WPRM_Settings::get( 'template_color_icon' ),
			),
			'timer' => array(
				'sound_dir' => WPRMP_URL . 'dist/',
				'text' => array(
					'start_timer' => __( 'Click to Start Timer', 'wp-recipe-maker-premium' ),
				),
				'icons' => array(
					'pause' => $pause,
					'play' => $play,
					'close' => $close,
				),
			),
			'recipe_submission' => array(
				'text' => array(
					'drop_image' => __( 'Drop an image', 'wp-recipe-maker-premium' ),
				),
			),
		) ) );
	}

	/**
	 * Enqueue Gutenberg block assets.
	 *
	 * @since    4.0.0
	 */
	public static function block_assets() {
		$filename = 'public-' . strtolower( WPRMP_BUNDLE );
		wp_enqueue_style( 'wprmp-public', WPRMP_URL . 'dist/' . $filename . '.css', array(), WPRMP_VERSION, 'all' );

		$filename = 'blocks-' . strtolower( WPRMP_BUNDLE );
		wp_enqueue_script( 'wprmp-blocks', WPRMP_URL . 'dist/' . $filename . '.js', array(), WPRMP_VERSION, true );
	}

	/**
	 * Enqueue admin stylesheets and scripts.
	 *
	 * @since    2.0.0
	 */
	public static function enqueue_admin() {
		$filename = 'admin-' . strtolower( WPRMP_BUNDLE );
		wp_enqueue_style( 'wprmp-admin', WPRMP_URL . 'dist/' . $filename . '.css', array(), WPRMP_VERSION, 'all' );
		wp_enqueue_script( 'wprmp-admin', WPRMP_URL . 'dist/' . $filename . '.js', array( 'jquery', 'jquery-ui-sortable' ), WPRMP_VERSION, true );

		wp_localize_script( 'wprmp-admin', 'wprmp_admin', apply_filters( 'wprmp_localize_admin', array(
			'settings' => array(
				'unit_conversion_round_to_decimals' => WPRM_Settings::get( 'unit_conversion_round_to_decimals' ),
			),
		) ) );
	}

	/**
	 * Enqueue template style on AMP pages.
	 *
	 * @since    2.0.1
	 */
	public static function amp_style() {
		// Get AMP specific CSS.
		ob_start();
		include( WPRMP_DIR . 'dist/amp.css' );
		$css = ob_get_contents();
		ob_end_clean();

		// Get rid of !important flags.
		$css = str_ireplace( ' !important', '', $css );
		$css = str_ireplace( '!important', '', $css );

		echo $css;
	}
}

WPRMP_Assets::init();
