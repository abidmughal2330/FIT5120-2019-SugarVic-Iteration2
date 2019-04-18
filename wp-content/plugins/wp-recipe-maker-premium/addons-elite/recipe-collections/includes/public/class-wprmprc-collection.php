<?php
/**
 * Represents a recipe collection.
 *
 * @link       http://bootstrapped.ventures
 * @since      4.1.0
 *
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-collections
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-collections/includes/public
 */

/**
 * Represents a recipe collection.
 *
 * @since      4.1.0
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-collections
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-collections/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMPRC_Collection {
/**
	 * WP_Post object associated with this collection post type.
	 *
	 * @since	4.1.0
	 * @access	private
	 * @var		object	$post WP_Post object of this collection post type.
	 */
	private $post;

	/**
	 * Metadata associated with this collection post type.
	 *
	 * @since	4.1.0
	 * @access	private
	 * @var		array $meta Collection metadata.
	 */
	private $meta = false;

	/**
	 * Get new collection object from associated post.
	 *
	 * @since	4.1.0
	 * @param	object $post WP_Post object for this collection post type.
	 */
	public function __construct( $post ) {
		$this->post = $post;
	}

	/**
	 * Get collection data.
	 *
	 * @since	4.1.0
	 */
	public function get_data() {
		$collection = array();

		// Technical Fields.
		$collection['id'] = $this->id();
		$collection['name'] = $this->name();
		$collection['nbrItems'] = $this->nbr_items();
		$collection['columns'] = $this->columns();
		$collection['groups'] = $this->groups();
		$collection['items'] = $this->items();

		return $collection;
	}

	/**
	 * Get metadata value.
	 *
	 * @since	4.1.0
	 * @param	mixed $field Metadata field to retrieve.
	 * @param	mixed $default Default to return if metadata is not set.
	 */
	public function meta( $field, $default ) {
		if ( ! $this->meta ) {
			$this->meta = get_post_custom( $this->id() );
		}

		if ( isset( $this->meta[ $field ] ) ) {
			return $this->meta[ $field ][0];
		}

		return $default;
	}

	/**
	 * Try to unserialize as best as possible.
	 *
	 * @since	4.1.0
	 * @param	mixed $maybe_serialized Potentially serialized data.
	 */
	public function unserialize( $maybe_serialized ) {
		$unserialized = @maybe_unserialize( $maybe_serialized );

		if ( false === $unserialized ) {
			$maybe_serialized = preg_replace('/\s+/', ' ', $maybe_serialized );
			$unserialized = unserialize( preg_replace_callback( '!s:(\d+):"(.*?)";!', array( $this, 'regex_replace_serialize' ), $maybe_serialized ) );
		}

		return $unserialized;
	}

	/**
	 * Callback for regex to fix serialize issues.
	 *
	 * @since	4.1.0
	 * @param	mixed $match Regex match.
	 */
	public function regex_replace_serialize( $match ) {
		return ( $match[1] == strlen( $match[2] ) ) ? $match[0] : 's:' . strlen( $match[2] ) . ':"' . $match[2] . '";';
	}

	/**
	 * Get the collection ID.
	 *
	 * @since	4.1.0
	 */
	public function id() {
		return $this->post->ID;
	}

	/**
	 * Get the collection name.
	 *
	 * @since	4.1.0
	 */
	public function name() {
		return $this->post->post_title;
	}

	/**
	 * Get the collection number of items.
	 *
	 * @since	4.1.0
	 */
	public function nbr_items() {
		return $this->meta( 'wprm_nbr_items', 0 );
	}

	/**
	 * Get the collection columns.
	 *
	 * @since	4.1.0
	 */
	public function columns() {
		return self::unserialize(  $this->meta( 'wprm_columns', array(
			array(
				'id' => 0,
				'name' => __( 'Recipes', 'wp-recipe-maker-premium' ),
			),
		) ) );
	}

	/**
	 * Get the collection groups.
	 *
	 * @since	4.1.0
	 */
	public function groups() {
		return self::unserialize(  $this->meta( 'wprm_groups', array(
			array(
				'id' => 0,
				'name' => '',
			),
		) ) );
	}

	/**
	 * Get the collection items.
	 *
	 * @since	4.1.0
	 */
	public function items() {
		return self::unserialize( $this->meta( 'wprm_items', array(
			'0-0' => array(),
		) ) );
	}
}
