<?php
/**
 * Manage the Recipe Collections posts.
 *
 * @link       http://bootstrapped.ventures
 * @since      4.1.0
 *
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-collections
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-collections/includes/public
 */

/**
 * Manage the Recipe Collections posts.
 *
 * @since      4.1.0
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-collections
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-collections/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMPRC_Manager {

	/**
	 * Collections that have already been requested for easy subsequent access.
	 *
	 * @since	4.1.0
	 * @access	private
	 * @var		array $collections Array containing collections that have already been requested for easy access.
	 */
	private static $collections = array();

	/**
	 * Get collection object by ID.
	 *
	 * @since 	4.1.0
	 * @param	mixed $post_or_collection_id ID or Post Object for the collection we want.
	 */
	public static function get_collection( $post_or_collection_id ) {
		$collection_id = is_object( $post_or_collection_id ) && $post_or_collection_id instanceof WP_Post ? $post_or_collection_id->ID : intval( $post_or_collection_id );

		// Only get new collection object if it hasn't been retrieved before.
		if ( ! array_key_exists( $collection_id, self::$collections ) ) {
			$post = is_object( $post_or_collection_id ) && $post_or_collection_id instanceof WP_Post ? $post_or_collection_id : get_post( intval( $post_or_collection_id ) );

			if ( $post instanceof WP_Post && WPRMPRC_POST_TYPE === $post->post_type ) {
				$collection = new WPRMPRC_Collection( $post );
			} else {
				$collection = false;
			}

			self::$collections[ $collection_id ] = $collection;
		}

		return self::$collections[ $collection_id ];
	}

	/**
	 * Get defaults collections.
	 *
	 * @since 	4.1.0
	 */
	public static function get_default_collections() {
		return array(
			'inbox' => array(
				'id' => 0,
				'name' => WPRM_Settings::get('recipe_collections_inbox_name'),
				'nbrItems' => 0,
				'columns' => array(
					array(
						'id' => 0,
						'name' => __( 'Recipes', 'wp-recipe-maker-premium' ),
					),
				),
				'groups' => array(
					array(
						'id' => 0,
						'name' => '',
					),
				),
				'items' => array(
					'0-0' => array()
				),
			),
			'user' => array(),
		);
	}

	/**
	 * Get collections for the current user.
	 *
	 * @since 	4.1.0
	 * @param	mixed $user_id User ID to get the collections for.
	 */
	public static function get_user_collections( $user_id = false ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		$collections = false;

		// If user is logged in, find their collections.
		if ( $user_id ) {
			$collections = get_user_meta( $user_id, 'wprm-recipe-collections', true );
		}

		// Set default if none found.
		if ( ! $collections ) {
			$collections = self::get_default_collections();
		}

		return $collections;
	}

	/**
	 * Save collections for the current user.
	 *
	 * @since 	4.1.0
	 * @param	mixed $collections Collections to save.
	 * @param	mixed $user_id User ID to save the collections for.
	 */
	public static function save_user_collections( $collections, $user_id = false ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		if ( $user_id ) {
			update_user_meta( $user_id, 'wprm-recipe-collections', $collections );
		}

		return $collections;
	}

	/**
	 * Get data to use in collections for a particular recipe.
	 *
	 * @since 	4.1.0
	 * @param	mixed $recipe Recipe to get the collections data for.
	 */
	public static function get_collections_data_for_recipe( $recipe ) {
		$servings = intval( $recipe->servings() ) ? intval( $recipe->servings() ) : 1;

		return array(
			'type' => 'recipe',
			'recipeId' => $recipe->id(),
			'name' => $recipe->name(),
			'image' => $recipe->image_url( array( 300, 300 ) ),
			'servings' => $servings,
			'servingsUnit' => $recipe->servings_unit(),
			'parent_id' => $recipe->parent_post_id(),
			'parent_url' => $recipe->parent_url(),
		);
	}

	/**
	 * Invalidate cached collection.
	 *
	 * @since	4.1.0
	 * @param	int $collection_id ID of the collection to invalidate.
	 */
	public static function invalidate_collection( $collection_id ) {
		if ( array_key_exists( $collection_id, self::$collections ) ) {
			unset( self::$collections[ $collection_id ] );
		}
	}

	/**
	 * Sanitize collection array.
	 *
	 * @since	4.1.0
	 * @param	array $collection Array containing all collection input data.
	 */
	public static function sanitize_collection( $collection ) {
		$sanitized_collection = array();

		// Text fields.
		$sanitized_collection['name'] = isset( $collection['name'] ) ? sanitize_text_field( $collection['name'] ) : '';
		
		// Numbers.
		$sanitized_collection['nbrItems'] = isset( $collection['nbrItems'] ) ? intval( $collection['nbrItems'] ) : 0;

		// Arrays.
		$sanitized_collection['columns'] = isset( $collection['columns'] ) ? $collection['columns'] : array( array( 'id' => 0, 'name' => __( 'Recipes', 'wp-recipe-maker-premium' ) ) );
		$sanitized_collection['groups'] = isset( $collection['groups'] ) ? $collection['groups'] : array( array( 'id' => 0, 'name' => '' ) );
		$sanitized_collection['items'] = isset( $collection['items'] ) ? $collection['items'] : array( '0-0' => array() );

		return $sanitized_collection;
	}

	/**
	 * Create a new collection.
	 *
	 * @since	4.1.0
	 * @param	array $recipe Recipe fields to save.
	 */
	public static function create_collection( $collection = array() ) {
		$post = array(
			'post_type' => WPRMPRC_POST_TYPE,
			'post_status' => 'publish',
		);

		$collection_id = wp_insert_post( $post );
		self::update_collection( $collection_id, self::sanitize_collection( $collection ) );

		return $collection_id;
	}

	/**
	 * Save collection fields.
	 *
	 * @since	4.1.0
	 * @param	int   $id Post ID of the collection.
	 * @param	array $collection Collection fields to save.
	 */
	public static function update_collection( $id, $collection ) {
		// Post Fields.
		$post = array(
			'ID'	      => $id,
			'post_title'  => $collection['name'],
			'post_name'	  => 'wprm-collection-' . sanitize_title( $collection['name'] ),
		);
		wp_update_post( $post );

		// Meta Fields.
		update_post_meta( $id, 'wprm_nbr_items', $collection['nbrItems'] );
		update_post_meta( $id, 'wprm_columns', $collection['columns'] );
		update_post_meta( $id, 'wprm_groups', $collection['groups'] );
		update_post_meta( $id, 'wprm_items', $collection['items'] );

		self::invalidate_collection( $id );
	}
}
