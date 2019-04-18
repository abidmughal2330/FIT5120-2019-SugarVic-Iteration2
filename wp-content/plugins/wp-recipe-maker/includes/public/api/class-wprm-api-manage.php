<?php
/**
 * Manage recipes in the WordPress REST API.
 *
 * @link       http://bootstrapped.ventures
 * @since      4.1.0
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/public
 */

/**
 * Manage recipes in the WordPress REST API.
 *
 * @since      4.1.0
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRM_Api_Manage {

	/**
	 * Register actions and filters.
	 *
	 * @since    4.1.0
	 */
	public static function init() {
		add_action( 'rest_api_init', array( __CLASS__, 'api_register_data' ) );
	}

	/**
	 * Register data for the REST API.
	 *
	 * @since    4.1.0
	 */
	public static function api_register_data() {
		if ( function_exists( 'register_rest_field' ) ) { // Prevent issue with Jetpack.
			register_rest_route( 'wp-recipe-maker/v1', '/manage/recipes', array(
				'callback' => array( __CLASS__, 'api_manage_recipes' ),
				'methods' => 'POST',
				'permission_callback' => array( __CLASS__, 'api_required_permissions' ),
			));
		}
	}

	/**
	 * Required permissions for the API.
	 *
	 * @since 4.1.0
	 */
	public static function api_required_permissions() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Handle manage recipes call to the REST API.
	 *
	 * @since 4.1.0
	 * @param WP_REST_Request $request Current request.
	 */
	public static function api_manage_recipes( $request ) {
		// Parameters.
		$params = $request->get_params();

		$page = isset( $params['page'] ) ? intval( $params['page'] ) : 0;
		$page_size = isset( $params['pageSize'] ) ? intval( $params['pageSize'] ) : 25;
		$sorted = isset( $params['sorted'] ) ? $params['sorted'] : array( array( 'id' => 'id', 'desc' => true ) );
		$filtered = isset( $params['filtered'] ) ? $params['filtered'] : array();

		// Starting query args.
		$args = array(
			'post_type' => WPRM_POST_TYPE,
			'posts_per_page' => $page_size,
			'offset' => $page * $page_size,
			'meta_query' => array(
				'relation' => 'AND',
			),
			'tax_query' => array(),
		);


		// Post status.
		$args['post_status'] = array( 'publish', 'future', 'draft', 'private' );
		if ( ! WPRM_Addons::is_active( 'recipe-submission' ) ) {
			$args['post_status'][] = 'pending';
		}


		// Order.
		$args['order'] = $sorted[0]['desc'] ? 'DESC' : 'ASC';
		switch( $sorted[0]['id'] ) {
			case 'name':
				$args['orderby'] = 'title';
				break;
			default:
			 	$args['orderby'] = 'ID';
		}

		// Filter.
		if ( $filtered ) {
			foreach ( $filtered as $filter ) {
				$value = trim( $filter['value'] );
				switch( $filter['id'] ) {
					case 'id':
						$args['wprm_search_id'] = $value;
						break;
					case 'name':
						$args['wprm_search_title'] = $value;
						break;
					case 'parent_post_id':
						if ( 'all' !== $value ) {
							$compare = 'yes' === $value ? 'EXISTS' : 'NOT EXISTS';

							$args['meta_query'][] = array(
								'key' => 'wprm_parent_post_id',
								'compare' => $compare,
							);
						}
						break;
					default:
						// Assume it's a taxonomy if it doesn't match anything else.
						if ( 'all' !== $value ) {
							$taxonomy = 'wprm_' . $filter['id'];

							if ( 'none' === $value ) {
								$args['tax_query'][] = array(
									'taxonomy' => $taxonomy,
									'operator' => 'NOT EXISTS',
								);
							} else {
								$args['tax_query'][] = array(
									'taxonomy' => $taxonomy,
									'field' => 'term_id',
									'terms' => intval( $value ),
								);
							}
						}
				}
			}
		}

		// Order by rating.
		// if ( 4 === $datatable['orderby'] ) {
		// 	$args['orderby'] = 'meta_value_num';
		// 	$args['meta_key'] = 'wprm_rating_average';
		// }

		add_filter( 'posts_where', array( __CLASS__, 'api_manage_recipes_query_where' ), 10, 2 );
		$query = new WP_Query( $args );
		remove_filter( 'posts_where', array( __CLASS__, 'api_manage_recipes_query_where' ), 10, 2 );

		$recipes = array();
		$posts = $query->posts;
		foreach ( $posts as $post ) {
			$recipe = WPRM_Recipe_Manager::get_recipe( $post );

			if ( ! $recipe ) {
				continue;
			}

			$recipes[] = $recipe->get_data_manage();
		}

		return array(
			'recipes' => $recipes,
			'pages' => ceil( $query->found_posts / $page_size ),
		);
	}

	/**
	 * Filter the where query.
	 *
	 * @since 4.1.0
	 */
	public static function api_manage_recipes_query_where( $where, $wp_query ) {
		global $wpdb;

		$id_search = $wp_query->get( 'wprm_search_id' );
		if ( $id_search ) {
			$where .= ' AND ' . $wpdb->posts . '.ID LIKE \'%' . esc_sql( like_escape( $id_search ) ) . '%\'';
		}

		$title_search = $wp_query->get( 'wprm_search_title' );
		if ( $title_search ) {
			$where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( like_escape( $title_search ) ) . '%\'';
		}

		return $where;
	}
}

WPRM_Api_Manage::init();
