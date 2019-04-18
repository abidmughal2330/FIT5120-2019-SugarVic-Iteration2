<?php
/**
 * Handle the Recipe Collections API.
 *
 * @link       http://bootstrapped.ventures
 * @since      4.1.0
 *
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-collections
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-collections/includes/public
 */

/**
 * Handle the Recipe Collections API.
 *
 * @since      4.1.0
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-collections
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-collections/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMPRC_Api {

	/**
	 * Register actions and filters.
	 *
	 * @since    4.1.0
	 */
	public static function init() {
		add_action( 'rest_api_init', array( __CLASS__, 'api_register_data' ) );
		add_action( 'rest_insert_' . WPRMPRC_POST_TYPE, array( __CLASS__, 'api_insert_update_recipe_collection' ), 10, 3 );
	}

	/**
	 * Register data for the REST API.
	 *
	 * @since    4.1.0
	 */
	public static function api_register_data() {
		if ( function_exists( 'register_rest_field' ) ) { // Prevent issue with Jetpack.
			register_rest_field( WPRMPRC_POST_TYPE, 'collection', array(
				'get_callback'    => array( __CLASS__, 'api_get_recipe_collection_data' ),
				'update_callback' => null,
				'schema'          => null,
			));

			register_rest_route( 'wp-recipe-maker/v1', '/recipe-collections/user/(?P<id>\d+)', array(
				'callback' => array( __CLASS__, 'api_get_user_collections' ),
				'methods' => 'GET',
				'args' => array(
					'id' => array(
						'validate_callback' => array( __CLASS__, 'api_validate_numeric' ),
					),
				),
			));
			register_rest_route( 'wp-recipe-maker/v1', '/recipe-collections/user/(?P<id>\d+)', array(
				'callback' => array( __CLASS__, 'api_save_user_collections' ),
				'methods' => 'POST',
				'args' => array(
					'id' => array(
						'validate_callback' => array( __CLASS__, 'api_validate_numeric' ),
					),
				),
			));
			register_rest_route( 'wp-recipe-maker/v1', '/recipe-collections/recipes', array(
				'callback' => array( __CLASS__, 'api_search_recipes' ),
				'methods' => 'POST',
			));
			register_rest_route( 'wp-recipe-maker/v1', '/recipe-collections/recipe/(?P<id>\d+)', array(
				'callback' => array( __CLASS__, 'api_get_recipe' ),
				'methods' => 'GET',
				'args' => array(
					'id' => array(
						'validate_callback' => array( __CLASS__, 'api_validate_numeric' ),
					),
				),
			));
			register_rest_route( 'wp-recipe-maker/v1', '/recipe-collections/ingredients', array(
				'callback' => array( __CLASS__, 'api_get_ingredients' ),
				'methods' => 'POST',
			));
			register_rest_route( 'wp-recipe-maker/v1', '/recipe-collections/nutrition', array(
				'callback' => array( __CLASS__, 'api_get_nutrition' ),
				'methods' => 'POST',
			));
			register_rest_route( 'wp-recipe-maker/v1', '/recipe-collections/inbox', array(
				'callback' => array( __CLASS__, 'api_save_to_inbox' ),
				'methods' => 'POST',
			));
			register_rest_route( 'wp-recipe-maker/v1', '/recipe-collections/save', array(
				'callback' => array( __CLASS__, 'api_save_to_collections' ),
				'methods' => 'POST',
			));
		}
	}

	/**
	 * Validate ID in API call.
	 *
	 * @since 4.1.0
	 * @param mixed           $param Parameter to validate.
	 * @param WP_REST_Request $request Current request.
	 * @param mixed           $key Key.
	 */
	public static function api_validate_numeric( $param, $request, $key ) {
		return is_numeric( $param );
	}

	/**
	 * Handle recipe collection calls to the REST API.
	 *
	 * @since 4.1.0
	 * @param array           $object Details of current post.
	 * @param mixed           $field_name Name of field.
	 * @param WP_REST_Request $request Current request.
	 */
	public static function api_get_recipe_collection_data( $object, $field_name, $request ) {
		$collection = WPRMPRC_Manager::get_collection( $object['id'] );

		if ( ! $collection ) {
			return false;
		}

		return $collection->get_data();
	}

	/**
	 * Handle recipe collection calls to the REST API.
	 *
	 * @since 4.1.0
	 * @param WP_Post         $post     Inserted or updated post object.
	 * @param WP_REST_Request $request  Request object.
	 * @param bool            $creating True when creating a post, false when updating.
	 */
	public static function api_insert_update_recipe_collection( $post, $request, $creating ) {
		$params = $request->get_params();
		$collection = isset( $params['collection'] ) ? WPRMPRC_Manager::sanitize_collection( $params['collection'] ) : array();
		$collection_id = $post->ID;

		WPRMPRC_Manager::update_collection( $collection_id, $collection );
	}

	/**
	 * Handle get user collections call to the REST API.
	 *
	 * @since 4.1.0
	 * @param WP_REST_Request $request Current request.
	 */
	public static function api_get_user_collections( $request ) {
		$user_id = intval( $request['id'] );

		if ( $user_id !== get_current_user_id() && ! current_user_can( 'edit_others_posts' ) ) {
			return false;
		}

		return WPRMPRC_Manager::get_user_collections( $user_id );;
	}

	/**
	 * Handle save user collections call to the REST API.
	 *
	 * @since 4.1.0
	 * @param WP_REST_Request $request Current request.
	 */
	public static function api_save_user_collections( $request ) {
		$user_id = intval( $request['id'] );

		$params = $request->get_params();
		$collections = isset( $params['collections'] ) ? $params['collections'] : false;

		if ( $user_id !== get_current_user_id() && ! current_user_can( 'edit_others_posts' ) ) {
			return false;
		}

		if ( $collections ) {
			WPRMPRC_Manager::save_user_collections( $collections, $user_id );
		}

		return $collections;
	}

	/**
	 * Handle get search recipes call to the REST API.
	 *
	 * @since 4.1.0
	 * @param WP_REST_Request $request Current request.
	 */
	public static function api_search_recipes( $request ) {
		$recipes = array();

		// Parameters.
		$params = $request->get_params();
		$search = isset( $params['search'] ) ? $params['search'] : '';

		// Search query.
		$args = array(
			'post_type' => WPRM_POST_TYPE,
			'post_status' => 'publish',
			'posts_per_page' => 10,
			's' => $search,
		);

		$query = new WP_Query( $args );

		// Loop over posts.
		$posts = $query->posts;
		foreach ( $posts as $post ) {
			$recipe = WPRM_Recipe_Manager::get_recipe( $post );
			$recipes[] = WPRMPRC_Manager::get_collections_data_for_recipe( $recipe );
		}

		return $recipes;
	}

	/**
	 * Handle get recipe call to the REST API.
	 *
	 * @since 4.1.0
	 * @param WP_REST_Request $request Current request.
	 */
	public static function api_get_recipe( $request ) {
		$recipe = WPRM_Recipe_Manager::get_recipe( $request['id'] );

		if ( $recipe ) {
			$template_mode = WPRM_Settings::get( 'recipe_template_mode' );
			$template_slug = WPRM_Settings::get( 'recipe_collections_template_' . $template_mode );

			$html = '';
			$template = WPRM_Template_Manager::get_template_by_slug( $template_slug );
			$style = WPRM_Template_Manager::get_template_css( $template );

			if ( $style ) {
				$html .= '<style type="text/css">' . $style . '</style>';
			}
			$html .= '<div id="wprm-recipe-container-' . esc_attr( $recipe->id() ) . '" class="wprm-recipe-container" data-recipe-id="' . esc_attr( $recipe->id() ) . '">';
			$html .= WPRM_Template_Manager::get_template( $recipe, 'single', $template_slug );
			$html .= '</div>';

			return array(
				'html' => $html,
			);
		} else {
			return array(
				'html' => false,
			);
		}
	}

	/**
	 * Handle get ingredients call to the REST API.
	 *
	 * @since 4.1.0
	 * @param WP_REST_Request $request Current request.
	 */
	public static function api_get_ingredients( $request ) {
		$recipe_data = array();

		// Parameters.
		$params = $request->get_params();
		$recipes = isset( $params['recipes'] ) ? array_map( 'intval', $params['recipes'] ) : array();

		foreach ( $recipes as $recipe_id ) {
			$recipe = WPRM_Recipe_Manager::get_recipe( $recipe_id );

			$servings = intval( $recipe->servings() ) ? intval( $recipe->servings() ) : 1;
			$ingredients = $recipe->ingredients_without_groups();

			if ( WPRM_Settings::get( 'recipe_collections_shopping_list_links') ) {
				foreach ( $ingredients as $index => $ingredient ) {
					$ingredients[ $index ]['link'] = WPRMP_Ingredient_Links::get_ingredient_link( $ingredient['id'] );
				}
			}

			$recipe_data[ $recipe_id ] = array(
				'servings' => $servings,
				'ingredients' => $ingredients,
			);
		}

		return $recipe_data;
	}

	/**
	 * Handle get nutrition call to the REST API.
	 *
	 * @since 4.1.0
	 * @param WP_REST_Request $request Current request.
	 */
	public static function api_get_nutrition( $request ) {
		$recipe_data = array();

		// Parameters.
		$params = $request->get_params();
		$recipes = isset( $params['recipes'] ) ? array_map( 'intval', $params['recipes'] ) : array();

		foreach ( $recipes as $recipe_id ) {
			$recipe = WPRM_Recipe_Manager::get_recipe( $recipe_id );

			$recipe_data[ $recipe_id ] = array(
				'nutrition' => $recipe->nutrition(),
			);
		}

		return $recipe_data;
	}

	/**
	 * Handle save to inbox call to the REST API.
	 *
	 * @since 4.1.0
	 * @param WP_REST_Request $request Current request.
	 */
	public static function api_save_to_inbox( $request ) {
		// Parameters.
		$params = $request->get_params();
		$recipe_id = isset( $params['recipeId'] ) ? intval( $params['recipeId'] ) : 0;
		$servings = isset( $params['servings'] ) && false !== $params['servings'] ? intval( $params['servings'] ) : false;

		$recipe = WPRM_Recipe_Manager::get_recipe( $recipe_id );

		if ( $recipe ) {
			$collections = WPRMPRC_Manager::get_user_collections();

			$recipe_data = WPRMPRC_Manager::get_collections_data_for_recipe( $recipe );

			// Get unique ID.
			$max_id = max( array_map( function( $item ) { return intval( $item['id'] ); }, $collections['inbox']['items']['0-0'] ) );
			$recipe_data['id'] = false === $max_id ? 0 : $max_id + 1;

			if ( false !== $servings ) {
				$recipe_data['servings'] = $servings;
			}

			$collections['inbox']['nbrItems']++;
			$collections['inbox']['items']['0-0'][] = $recipe_data;

			WPRMPRC_Manager::save_user_collections( $collections );
		}

		return true;
	}

	/**
	 * Handle save to collections call to the REST API.
	 *
	 * @since 4.1.0
	 * @param WP_REST_Request $request Current request.
	 */
	public static function api_save_to_collections( $request ) {
		// Parameters.
		$params = $request->get_params();
		$collectionId = isset( $params['collectionId'] ) ? intval( $params['collectionId'] ) : 0;

		$collection = WPRMPRC_Manager::get_collection( $collectionId );

		if ( $collection ) {
			$collection_data = $collection->get_data();
			$collections = WPRMPRC_Manager::get_user_collections();

			// Get unique ID.
			$max_id = max( array_map( function( $c ) { return intval( $c['id'] ); }, $collections['user'] ) );
			$collection_data['id'] = false === $max_id ? 0 : $max_id + 1;

			$collections['user'][] = $collection_data;

			WPRMPRC_Manager::save_user_collections( $collections );
		}

		return true;
	}
}

WPRMPRC_Api::init();
