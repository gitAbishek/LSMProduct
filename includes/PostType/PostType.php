<?php
/**
 * Abstract post type class.
 */

namespace ThemeGrill\Masteriyo\PostType;

class PostType {
	/**
	 * Post slug.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $slug;

	/**
	 * An array of labels for this post type. If not set, post labels are inherited for non-hierarchical types and page labels for hierarchical ones.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $labels;

	/**
	 * Array or string of arguments for registering a post type.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $args;

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param string $slug Post type slug.
	 * @param array $labels	An array of labels for this post type. If not set, post labels are inherited for non-hierarchical types and page labels for hierarchical ones.
	 * @param array $args	Array or string of arguments for registering a post type.
	 *
	 * @return Masteriyo\Masteriyo\PostType
	 */
	public function __construct( $slug, $labels = array(), $args = array() ) {
		$this->slug   = $slug;
		$this->labels = array_merge( $this->get_default_labels(), $labels );
		$this->args   = array_merge( $this->get_default_args( $this->labels ), $args );

		return $this;
	}

	/**
	 * Register post type.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function register() {
		register_post_type( $this->slug, $this->args );
	}

	/**
	 * Get label.
	 *
	 * @since 0.1.0
	 *
	 * @param string $label Label. (e.g. name, singular_name, menu_name, etc )
	 *
	 * @return mixed|null
	 */
	public function get_label( $lable ) {
		if ( isset( $this->labels[ $label ] ) ) {
			return $this->labels[ $label ];
		}

		return null;
	}

	/**
	 * Get label.
	 *
	 * @since 0.1.0
	 *
	 * @param string $arg Arguments. (e.g. label, supports, menu_position, etc )
	 *
	 * @return mixed|null
	 */
	public function get_arg( $arg ) {
		if ( isset( $this->args[ $arg ] ) ) {
			return $this->args[ $arg ];
		}

		return null;
	}

	/**
	 * Set label.
	 *
	 * @since 0.1.0
	 *
	 * @param string $label Label. (e.g. name, singular_name, menu_name, etc )
	 * @param string $value Label text/value.
	 * @param bool $strict	Strict check the label.(Default: true)
	 *
	 * @return Masteriyo\Masteriyo\PostType
	 */
	public function set_label( $label, $value, $strict = true ) {
		if ( $strict && ! isset( $this->labels[ $label ] ) ) {
			throw new Exception( 'Invalid label name.' );
		}

		$this->labels[ $label ] = $value;
		$this->args[ 'labels' ] = $this->labels;
		return $this;
	}

	/**
	 * Set args.
	 *
	 * @since 0.1.0
	 *
	 * @param string $arg Arguments. (e.g. label, supports, menu_position, etc )
	 * @param string $value Arguments value.
	 * @param bool $strict	Strict check the label.(Default: true)
	 *
	 * @return Masteriyo\Masteriyo\PostType
	 */
	public function set_args( $arg, $value, $strict = true ) {
		if ( $strict && ! isset( $this->supports[ $arg ] ) ) {
			throw new Exception( 'Invalid args name.' );
		}

		$this->args[ $arg ] = $value;
		return $this;
	}

	/**
	 * Default labels.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	protected function get_default_labels() {
		// phpcs:disable WordPress.WP.I18n.TextDomainMismatch
		return array(
			'name'                  => _x( 'Post Types', 'Post Type General Name', 'text_domain' ),
			'singular_name'         => _x( 'Post Type', 'Post Type Singular Name', 'text_domain' ),
			'menu_name'             => __( 'Post Types', 'text_domain' ),
			'name_admin_bar'        => __( 'Post Type', 'text_domain' ),
			'archives'              => __( 'Item Archives', 'text_domain' ),
			'attributes'            => __( 'Item Attributes', 'text_domain' ),
			'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
			'all_items'             => __( 'All Items', 'text_domain' ),
			'add_new_item'          => __( 'Add New Item', 'text_domain' ),
			'add_new'               => __( 'Add New', 'text_domain' ),
			'new_item'              => __( 'New Item', 'text_domain' ),
			'edit_item'             => __( 'Edit Item', 'text_domain' ),
			'update_item'           => __( 'Update Item', 'text_domain' ),
			'view_item'             => __( 'View Item', 'text_domain' ),
			'view_items'            => __( 'View Items', 'text_domain' ),
			'search_items'          => __( 'Search Item', 'text_domain' ),
			'not_found'             => __( 'Not found', 'text_domain' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
			'featured_image'        => __( 'Featured Image', 'text_domain' ),
			'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
			'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
			'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
			'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
			'items_list'            => __( 'Items list', 'text_domain' ),
			'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
			'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
		);
		// phpcs:enable
	}

	/**
	 * Get default args.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	protected function get_default_args( $labels ) {
		// phpcs:disable WordPress.WP.I18n.TextDomainMismatch
		return array(
			'label'                 => __ ( 'Post Type', 'text_domain' ),
			'description'           => __( 'Post Type Description', 'text_domain' ),
			'labels'                => $labels,
			'supports'              => false,
			'taxonomies'            => array( ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'post'
		);
		// phpcs:enable
	}
}
