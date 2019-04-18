<?php
/**
 * Handle the recipe submission manage page.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.1.0
 *
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-submission
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-submission/includes/admin
 */

/**
 * Handle the recipe submission manage page.
 *
 * @since      2.1.0
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-submission
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-submission/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMPRS_Manage {

	/**
	 * Register actions and filters.
	 *
	 * @since	2.1.0
	 */
	public static function init() {
		add_filter( 'wprm_manage_tabs', array( __CLASS__, 'manage_tabs' ), 20 );
		add_filter( 'wprm_manage_datatable_data', array( __CLASS__, 'manage_datatable_data' ), 10, 3 );

		add_action( 'wprm_manage_page', array( __CLASS__, 'manage_page' ) );

		add_action( 'wp_ajax_wprm_delete_recipe_submission', array( __CLASS__, 'ajax_delete_recipe_submission' ) );
		add_action( 'wp_ajax_wprm_approve_recipe_submission', array( __CLASS__, 'ajax_approve_recipe_submission' ) );
		add_action( 'wp_ajax_wprm_approve_add_recipe_submission', array( __CLASS__, 'ajax_approve_add_recipe_submission' ) );
	}

	/**
	 * Add recipe submissions to the manage tabs.
	 *
	 * @since	2.1.0
	 * @param	array $tabs Manage tabs.
	 */
	public static function manage_tabs( $tabs ) {
		$count_posts = wp_count_posts( WPRM_POST_TYPE );

		$tabs['recipe_submissions'] = __( 'Recipe Submissions', 'wp-recipe-maker-premium' ) . ' (' . $count_posts->pending . ')';
		return $tabs;
	}

	/**
	 * Manage page to output.
	 *
	 * @since	2.1.0
	 * @param	mixed $sub Sub manage page to display.
	 */
	public static function manage_page( $sub ) {
		if ( 'recipe_submissions' === $sub ) {
			require_once( WPRMPRS_DIR . 'templates/admin/manage/recipe-submission.php' );
		}
	}

	/**
	 * Datatable for recipe submissions.
	 *
	 * @since	2.1.0
	 * @param	mixed $data Data for the datatable.
	 * @param	mixed $table Table we are filtering the data for.
	 * @param	mixed $datatable Datatable request values.
	 */
	public static function manage_datatable_data( $data, $table, $datatable ) {
		if ( 'wprm-manage-recipe-submissions' === $table ) {
			$data = self::get_datatable( $datatable );
		}

		return $data;
	}

	/**
	 * Get the data to display in the datatable.
	 *
	 * @since	2.1.0
	 * @param	array $datatable Datatable request values.
	 */
	public static function get_datatable( $datatable ) {
		$data = array();

		$orderby_options = array(
			0 => 'ID',
			1 => 'date',
			2 => 'title',
		);
		$orderby = isset( $orderby_options[ $datatable['orderby'] ] ) ? $orderby_options[ $datatable['orderby'] ] : $orderby_options[0];

		$args = array(
				'post_type' => WPRM_POST_TYPE,
				'post_status' => 'pending',
				'orderby' => $orderby,
				'order' => $datatable['order'],
				'posts_per_page' => $datatable['length'],
				'offset' => $datatable['start'],
				's' => $datatable['search'],
		);

		$query = new WP_Query( $args );

		$posts = $query->posts;
		foreach ( $posts as $post ) {
			$recipe = WPRM_Recipe_Manager::get_recipe( $post );

			if ( ! $recipe ) {
				continue;
			}

			$user = '';
			$user_data = maybe_unserialize( $recipe->meta( 'wprm_submission_user', false ) );

			if ( $user_data ) {
				$user_parts = array();

				if ( $user_data['id'] ) {
					$name = '<a href="' . get_edit_user_link( $user_data['id'] ) . '">#' . $user_data['id'] . '</a>';

					if ( $user_data['name'] ) {
						$name .= ' - ' . $user_data['name'];
					} else {
						$user_info = get_userdata( $user_data['id'] );
						$name .= ' - ' . $user_info->display_name;
					}

					$user_parts[] = $name;
				} elseif ( $user_data['name'] ) {
					$user_parts[] = $user_data['name'];
				}

				if ( $user_data['email'] ) {
					$user_parts[] = $user_data['email'];
				}

				$user = implode( '<br/>', $user_parts );
			}

			$data[] = array(
				$recipe->id(),
				get_the_date( 'Y/m/d', $recipe->id() ),
				$user,
				'<span id="wprm-manage-recipe-submissions-name-' . esc_attr( $recipe->id() ) . '">' . $recipe->name() . '</span>',
				'<a href="#" class="wprm-manage-recipe-submissions-edit" data-id="' . esc_attr( $recipe->id() ) . '">Edit</a> | <a href="#" class="wprm-manage-recipe-submissions-approve" data-id="' . esc_attr( $recipe->id() ) . '">Approve</a> | <a href="#" class="wprm-manage-recipe-submissions-approve-add" data-id="' . esc_attr( $recipe->id() ) . '">Approve & Add to new Post</a> | <a href="#" class="wprm-manage-recipe-submissions-delete" data-id="' . esc_attr( $recipe->id() ) . '">Delete</a>',
			);
		}

		return array(
			'draw' => $datatable['draw'],
			'recordsTotal' => $query->found_posts,
			'recordsFiltered' => $query->found_posts,
			'data' => $data,
		);
	}

	/**
	 * Delete recipe submission through AJAX.
	 *
	 * @since	2.1.0
	 */
	public static function ajax_delete_recipe_submission() {
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

	/**
	 * Approve recipe submission through AJAX.
	 *
	 * @since	2.1.0
	 */
	public static function ajax_approve_recipe_submission() {
		if ( check_ajax_referer( 'wprm', 'security', false ) ) {
			$recipe_id = isset( $_POST['recipe_id'] ) ? intval( $_POST['recipe_id'] ) : 0; // Input var okay.

			if ( $recipe_id ) {
				$recipe = array(
					'ID'          	=> $recipe_id,
					'post_status' 	=> 'draft',
				);
				wp_update_post( $recipe );
			}
		}

		wp_die();
	}

	/**
	 * Approve and add recipe submission to post through AJAX.
	 *
	 * @since	2.1.0
	 */
	public static function ajax_approve_add_recipe_submission() {
		if ( check_ajax_referer( 'wprm', 'security', false ) ) {
			$recipe_id = isset( $_POST['recipe_id'] ) ? intval( $_POST['recipe_id'] ) : 0; // Input var okay.

			if ( $recipe_id ) {
				$post = array(
					'post_type' => 'post',
					'post_status' => 'draft',
					'post_content' => '[wprm-recipe id="' . $recipe_id . '"]',
				);

				$post = apply_filters( 'wprm_recipe_submission_approve_add_post', $post, $recipe_id );
				$post_id = wp_insert_post( $post );

				wp_send_json_success(array(
					'edit_link' => get_edit_post_link( $post_id, '' ),
				));
			}
		}

		wp_die();
	}
}

WPRMPRS_Manage::init();
