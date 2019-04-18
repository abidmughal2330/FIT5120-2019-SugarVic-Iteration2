<?php
/**
 * Handle the custom taxonomies.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.2.0
 *
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/includes/public
 */

/**
 * Handle the custom taxonomies.
 *
 * @since      1.2.0
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMP_Custom_Taxonomies {

	/**
	 * Register actions and filters.
	 *
	 * @since    1.2.0
	 */
	public static function init() {
		add_filter( 'wprm_recipe_taxonomies', array( __CLASS__, 'recipe_taxonomies' ) );
		add_filter( 'wprm_manage_tabs', array( __CLASS__, 'manage_tabs' ), 20 );

		add_action( 'wprm_manage_page', array( __CLASS__, 'manage_page' ) );
		add_action( 'admin_post_wprmp_add_custom_taxonomy', array( __CLASS__, 'form_add_custom_taxonomy' ) );

		add_action( 'wp_ajax_wprmp_edit_custom_taxonomy', array( __CLASS__, 'ajax_edit_custom_taxonomy' ) );
		add_action( 'wp_ajax_wprmp_delete_custom_taxonomy', array( __CLASS__, 'ajax_delete_custom_taxonomy' ) );
	}

	/**
	 * Add custom taxonomies to the recipe taxonomies.
	 *
	 * @since    1.2.0
	 * @param 	 array $taxonomies Recipe taxonomies.
	 */
	public static function recipe_taxonomies( $taxonomies ) {
		$custom_taxonomies = self::get_custom_taxonomies();
		return array_merge( $taxonomies, $custom_taxonomies );
	}

	/**
	 * Add custom tags to the manage tabs.
	 *
	 * @since    1.2.0
	 * @param 	 array $tabs Manage tabs.
	 */
	public static function manage_tabs( $tabs ) {
		$tabs['custom_taxonomies'] = __( 'Custom Taxonomies', 'wp-recipe-maker' );
		return $tabs;
	}

	/**
	 * Manage page to output.
	 *
	 * @since    1.2.0
	 * @param	 mixed $sub Sub manage page to display.
	 */
	public static function manage_page( $sub ) {
		if ( 'custom_taxonomies' === $sub ) {
			require_once( WPRMP_DIR . 'templates/admin/manage/custom-taxonomies.php' );
		}
	}

	/**
	 * Edit a custom taxonomy through AJAX.
	 *
	 * @since    1.2.0
	 */
	public static function ajax_edit_custom_taxonomy() {
		if ( check_ajax_referer( 'wprm', 'security', false ) ) {
			$key = isset( $_POST['key'] ) ? sanitize_key( wp_unslash( $_POST['key'] ) ) : 0; // Input var okay.
			$singular = isset( $_POST['singular'] ) ? sanitize_text_field( wp_unslash( $_POST['singular'] ) ) : ''; // Input var okay.
			$plural = isset( $_POST['plural'] ) ? sanitize_text_field( wp_unslash( $_POST['plural'] ) ) : ''; // Input var okay.

			// Get rid of wprm_ part.
			$key = substr( $key, 5 );
			self::edit_custom_taxonomy( $key, $singular, $plural );
		}

		wp_die();
	}

	/**
	 * Delete a custom taxonomy through AJAX.
	 *
	 * @since    1.2.0
	 */
	public static function ajax_delete_custom_taxonomy() {
		if ( check_ajax_referer( 'wprm', 'security', false ) ) {
			$key = isset( $_POST['key'] ) ? sanitize_key( wp_unslash( $_POST['key'] ) ) : 0; // Input var okay.

			// Get rid of wprm_ part.
			$key = substr( $key, 5 );
			self::delete_custom_taxonomy( $key );
		}

		wp_die();
	}

	/**
	 * Handle the create new custom taxonomy form.
	 *
	 * @since    1.2.0
	 */
	public static function form_add_custom_taxonomy() {
		if ( isset( $_POST['wprmp_custom_taxonomy'] ) && wp_verify_nonce( sanitize_key( $_POST['wprmp_custom_taxonomy'] ), 'wprmp_custom_taxonomy' ) ) { // Input var okay.
			$key = isset( $_POST['taxonomy_key'] ) ? sanitize_key( wp_unslash( $_POST['taxonomy_key'] ) ) : ''; // Input var okay.
			$singular = isset( $_POST['taxonomy_singular'] ) ? sanitize_text_field( wp_unslash( $_POST['taxonomy_singular'] ) ) : ''; // Input var okay.
			$plural = isset( $_POST['taxonomy_plural'] ) ? sanitize_text_field( wp_unslash( $_POST['taxonomy_plural'] ) ) : ''; // Input var okay.

			self::create_custom_taxonomy( $key, $singular, $plural );
		}
		wp_safe_redirect( admin_url( 'admin.php?page=wprecipemaker&sub=custom_taxonomies' ) );
		exit();
	}

	/**
	 * Create a new custom taxonomy.
	 *
	 * @since    1.2.0
	 * @param 	 mixed $key      Key for the custom taxonomy.
	 * @param 	 mixed $singular Singular name for the custom taxonomy.
	 * @param 	 mixed $plural   Plural name for the custom taxonomy.
	 */
	public static function create_custom_taxonomy( $key, $singular, $plural ) {
		if ( $key && $singular && $plural ) {
			$key = 'wprm_' . $key;

			if ( ! taxonomy_exists( $key ) ) {
				$taxonomies = self::get_custom_taxonomies();
				$taxonomies[ $key ] = array(
					'name' => $plural,
					'singular_name' => $singular,
				);
				update_option( 'wprm_custom_taxonomies', $taxonomies );
			}
		}
	}

	/**
	 * Edit a custom taxonomy.
	 *
	 * @since    1.2.0
	 * @param 	 mixed $key      Key for the custom taxonomy.
	 * @param 	 mixed $singular Singular name for the custom taxonomy.
	 * @param 	 mixed $plural   Plural name for the custom taxonomy.
	 */
	public static function edit_custom_taxonomy( $key, $singular, $plural ) {
		if ( $key && $singular && $plural ) {
			$key = 'wprm_' . $key;
			$taxonomies = self::get_custom_taxonomies();

			if ( array_key_exists( $key, $taxonomies ) ) {
				$taxonomies[ $key ] = array(
					'name' => $plural,
					'singular_name' => $singular,
				);
				update_option( 'wprm_custom_taxonomies', $taxonomies );
			}
		}
	}

	/**
	 * Delete a custom taxonomy.
	 *
	 * @since    1.2.0
	 * @param 	 mixed $key      Key for the custom taxonomy.
	 */
	public static function delete_custom_taxonomy( $key ) {
		if ( $key ) {
			$key = 'wprm_' . $key;
			$taxonomies = self::get_custom_taxonomies();

			if ( array_key_exists( $key, $taxonomies ) ) {
				unset( $taxonomies[ $key ] );
				update_option( 'wprm_custom_taxonomies', $taxonomies );
			}
		}
	}

	/**
	 * Get all custom taxonomies.
	 *
	 * @since    1.2.0
	 */
	public static function get_custom_taxonomies() {
		return get_option( 'wprm_custom_taxonomies', array() );
	}
}

WPRMP_Custom_Taxonomies::init();
