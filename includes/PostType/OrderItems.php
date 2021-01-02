<?php
/**
 * OrderItems class.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\PostType;
 */

namespace ThemeGrill\Masteriyo\PostType;

/**
 * OrderItems class.
 */
class OrderItems extends PostType {
	/**
	 * Post slug.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $slug = 'masteriyo_order_item';

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->labels = array(
			'name'                  => _x( 'Order Items', 'Order General Name', 'masteriyo' ),
			'singular_name'         => _x( 'Order Item', 'Order Item Singular Name', 'masteriyo' ),
			'menu_name'             => __( 'Order Items', 'masteriyo' ),
			'name_admin_bar'        => __( 'Order Item', 'masteriyo' ),
			'archives'              => __( 'Order Item Archives', 'masteriyo' ),
			'attributes'            => __( 'Order Item Attributes', 'masteriyo' ),
			'parent_item_colon'     => __( 'Parent Order Item:', 'masteriyo' ),
			'all_items'             => __( 'All Order Items', 'masteriyo' ),
			'add_new_item'          => __( 'Add New Item', 'masteriyo' ),
			'add_new'               => __( 'Add New', 'masteriyo' ),
			'new_item'              => __( 'New Order Item', 'masteriyo' ),
			'edit_item'             => __( 'Edit Order Item', 'masteriyo' ),
			'update_item'           => __( 'Update Order Item', 'masteriyo' ),
			'view_item'             => __( 'View Order Item', 'masteriyo' ),
			'view_items'            => __( 'View Order Items', 'masteriyo' ),
			'search_items'          => __( 'Search Order Item', 'masteriyo' ),
			'not_found'             => __( 'Not found', 'masteriyo' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'masteriyo' ),
			'featured_image'        => __( 'Featured Image', 'masteriyo' ),
			'set_featured_image'    => __( 'Set featured image', 'masteriyo' ),
			'remove_featured_image' => __( 'Remove featured image', 'masteriyo' ),
			'use_featured_image'    => __( 'Use as featured image', 'masteriyo' ),
			'insert_into_item'      => __( 'Insert into order', 'masteriyo' ),
			'uploaded_to_this_item' => __( 'Uploaded to this order', 'masteriyo' ),
			'items_list'            => __( 'Order Items list', 'masteriyo' ),
			'items_list_navigation' => __( 'Order Items list navigation', 'masteriyo' ),
			'filter_items_list'     => __( 'Filter orders list', 'masteriyo' ),
		);

		$this->args = array(
			'label'               => __( 'Order Items', 'masteriyo' ),
			'description'         => __( 'Order Items Description', 'masteriyo' ),
			'labels'              => $this->labels,
			'supports'            => array( 'title', 'editor', 'author', 'custom-fields', 'post-formats' ),
			'taxonomies'          => array(),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'show_in_rest'        => true,
			'has_archive'         => true,
			'map_meta_cap'        => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'can_export'          => true,
			'delete_with_user'    => true,
		);
	}
}
