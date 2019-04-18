<?php
/**
 * Handle the manage page.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.7.0
 *
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/includes/admin
 */

/**
 * Handle the manage page.
 *
 * @since      1.7.0
 * @package    WP_Recipe_Maker_Premium
 * @subpackage WP_Recipe_Maker_Premium/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMP_Manage {

	/**
	 * Register actions and filters.
	 *
	 * @since    1.7.0
	 */
	public static function init() {
		add_action( 'wp_ajax_wprm_reset_user_ratings', array( __CLASS__, 'ajax_reset_user_ratings' ) );

		add_filter( 'wprm_manage_datatable_data', array( __CLASS__, 'manage_recipes_user_rating' ), 10, 2 );
		add_filter( 'wprm_manage_datatable_tooltip', array( __CLASS__, 'manage_recipes_tooltip' ), 10, 2 );
	}

	/**
	 * User ratings in recipes manage table.
	 *
	 * @since    1.7.0
	 * @param	 mixed $datatable Data for the datatable.
	 * @param	 mixed $table Table we are filtering the data for.
	 */
	public static function manage_recipes_user_rating( $datatable, $table ) {
		if ( 'wprm-manage-recipes' === $table && WPRM_Settings::get( 'features_user_ratings' ) ) {
			foreach ( $datatable['data'] as $index => $row ) {
				$recipe_id = intval( $datatable['data'][ $index ][0] );

				$user_ratings = WPRMP_User_Rating::get_ratings_for( $recipe_id );

				$count = 0;
				$total = 0;

				foreach ( $user_ratings as $user_rating ) {
					$count++;
					$total += intval( $user_rating->rating );
				}

				if ( $count ) {
					$average = ceil( $total / $count * 100 ) / 100;
					$space = $datatable['data'][ $index ][4] ? ' + ' : '';
					$datatable['data'][ $index ][4] .= $space . $average . ' <span class="wprm-manage-recipes-rating-details">(' . $count . ' ' . _n( 'vote', 'votes', $count, 'wp-recipe-maker' ) . ')</span>';

					if ( $space ) {
						$combined_average = get_post_meta( $recipe_id, 'wprm_rating_average', true );

						if ( $combined_average ) {
							$datatable['data'][ $index ][4] = $combined_average . ' = ' . $datatable['data'][ $index ][4];
						}
					}
				}
			}
		}

		return $datatable;
	}

	/**
	 * User ratings in recipes manage tooltip.
	 *
	 * @since    1.7.0
	 * @param	 mixed $tooltip Tooltip to output.
	 * @param	 mixed $table Table we are filtering the data for.
	 */
	public static function manage_recipes_tooltip( $tooltip, $table ) {
		if ( 'recipes' === $table && WPRM_Settings::get( 'features_user_ratings' ) ) {
			$tooltip .= '<br/><a href="#" class="wprm-manage-recipes-actions-reset-user-ratings">Reset User Ratings</a>';
		}

		return $tooltip;
	}

	/**
	 * Recipe user ratings through AJAX.
	 *
	 * @since    1.7.0
	 */
	public static function ajax_reset_user_ratings() {
		if ( check_ajax_referer( 'wprm', 'security', false ) ) {
			$recipe_id = isset( $_POST['recipe_id'] ) ? intval( $_POST['recipe_id'] ) : 0; // Input var okay.

			if ( $recipe_id ) {
				WPRM_Rating_Database::delete_ratings_for( $recipe_id );
			}
		}

		wp_die();
	}
}

WPRMP_Manage::init();
