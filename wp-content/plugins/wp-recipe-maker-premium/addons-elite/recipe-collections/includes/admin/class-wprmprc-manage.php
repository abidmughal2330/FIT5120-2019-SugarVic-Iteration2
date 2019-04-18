<?php
/**
 * Handle the Recipe Collections manage page.
 *
 * @link       http://bootstrapped.ventures
 * @since      4.1.0
 *
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-collections
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-collections/includes/admin
 */

/**
 * Handle the Recipe Collections manage page.
 *
 * @since      4.1.0
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-collections
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-collections/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMPRC_Manage {

	/**
	 * Register actions and filters.
	 *
	 * @since    4.1.0
	 */
	public static function init() {
		add_filter( 'wprm_manage_tabs', array( __CLASS__, 'manage_tabs' ), 20 );
		add_filter( 'wprm_manage_datatable_data', array( __CLASS__, 'manage_datatable_data' ), 10, 3 );

		add_action( 'wprm_manage_page', array( __CLASS__, 'manage_page' ) );
		add_action( 'admin_menu', array( __CLASS__, 'add_submenu_page' ), 20 );
	}

	/**
	 * Add recipe collections to the manage tabs.
	 *
	 * @since	4.1.0
	 * @param	array $tabs Manage tabs.
	 */
	public static function manage_tabs( $tabs ) {
		$tabs['recipe_collections'] = __( 'Saved Collections', 'wp-recipe-maker-premium' );
		return $tabs;
	}

	/**
	 * Manage page to output.
	 *
	 * @since	4.1.0
	 * @param	mixed $sub Sub manage page to display.
	 */
	public static function manage_page( $sub ) {
		wp_localize_script( 'wprmp-admin', 'wprmprc_admin', array(
			'endpoints' => array(
				'collections' => get_rest_url( null, 'wp/v2/' . WPRMPRC_POST_TYPE ),
			),
			'api_nonce' => wp_create_nonce( 'wp_rest' ),
		));

		if ( 'recipe_collections' === $sub ) {
			require_once( WPRMPRC_DIR . 'templates/admin/manage/recipe-collections.php' );
		}
	}

	/**
	 * Add the edit saved collection page.
	 *
	 * @since	4.1.0
	 */
	public static function add_submenu_page() {
		add_submenu_page( null, __( 'WPRM Recipe Collections', 'wp-recipe-maker' ), __( 'Recipe Collections', 'wp-recipe-maker' ), 'manage_options', 'wprm_recipe_collections', array( __CLASS__, 'recipe_collections_page_template' ) );
	}

	/**
	 * Get the template for the edit saved collection page.
	 *
	 * @since	4.1.0
	 */
	public static function recipe_collections_page_template() {
		$id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : false;
		$duplicate = isset( $_GET['action'] ) ? 'duplicate' === $_GET['action'] : false;

		if ( ! $id ) {
			$id = WPRMPRC_Manager::create_collection();
		}

		$collection = WPRMPRC_Manager::get_collection( $id );

		if ( ! $collection ) {
			wp_die( 'Something went wrong.' );
		}

		if ( $duplicate ) {
			$duplicate_id = WPRMPRC_Manager::create_collection( $collection->get_data() );
			$collection = WPRMPRC_Manager::get_collection( $duplicate_id );
		}

		wp_localize_script( 'wprmp-admin', 'wprm_public', WPRM_Assets::localize_public() );
		wp_localize_script( 'wprmp-admin', 'wprmp_public', WPRMPRC_Assets::localize_public_data( array( 'endpoints' => array() ) ) );
		wp_localize_script( 'wprmp-admin', 'wprmprc_public', WPRMPRC_Assets::localize_shortcode_data() );
		wp_localize_script( 'wprmp-admin', 'wprmprc_admin', array(
			'collection' => $collection->get_data(),
			'manage_url' => admin_url( 'admin.php?page=wprecipemaker&sub=recipe_collections' ),
		) );

		echo '<div id="wprm-recipe-collections-manage-app" class="wrap">Loading...</div>';
	}

	/**
	 * Datatable for recipe collections.
	 *
	 * @since	4.1.0
	 * @param	mixed $data Data for the datatable.
	 * @param	mixed $table Table we are filtering the data for.
	 * @param	mixed $datatable Datatable request values.
	 */
	public static function manage_datatable_data( $data, $table, $datatable ) {
		if ( 'wprm-manage-recipe-collections' === $table ) {
			$data = self::get_datatable( $datatable );
		}

		return $data;
	}

	/**
	 * Get the data to display in the datatable.
	 *
	 * @since	4.1.0
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
			'post_type' => WPRMPRC_POST_TYPE,
			'post_status' => 'any',
			'orderby' => $orderby,
			'order' => $datatable['order'],
			'posts_per_page' => $datatable['length'],
			'offset' => $datatable['start'],
			's' => $datatable['search'],
		);

		// Order by number of items.
		if ( 3 === $datatable['orderby'] ) {
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = 'wprm_nbr_items';
		}

		$query = new WP_Query( $args );

		$posts = $query->posts;
		foreach ( $posts as $post ) {
			$collection = WPRMPRC_Manager::get_collection( $post );

			if ( ! $collection ) {
				continue;
			}

			$data[] = array(
				$collection->id(),
				get_the_date( 'Y/m/d', $collection->id() ),
				'<span id="wprm-manage-recipe-collections-name-' . esc_attr( $collection->id() ) . '">' . $collection->name() . '</span>',
				$collection->nbr_items(),
				'<a href="' . admin_url( 'admin.php?page=wprm_recipe_collections&id=' . $collection->id() ) . '" class="wprm-manage-recipe-collections-edit">Edit</a> | <a href="' . admin_url( 'admin.php?page=wprm_recipe_collections&action=duplicate&id=' . $collection->id() ) . '" class="wprm-manage-recipe-collections-duplicate">Duplicate</a> | <a href="#" class="wprm-manage-recipe-collections-delete" data-id="' . esc_attr( $collection->id() ) . '">Delete</a>',
			);
		}

		return array(
			'draw' => $datatable['draw'],
			'recordsTotal' => $query->found_posts,
			'recordsFiltered' => $query->found_posts,
			'data' => $data,
		);
	}
}

WPRMPRC_Manage::init();
