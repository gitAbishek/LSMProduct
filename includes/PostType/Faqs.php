<?php
/**
 * Faqs class.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\PostType;
 */

namespace ThemeGrill\Masteriyo\PostType;

/**
 * Faqs class.
 */
class Faqs extends PostType {
	/**
	 * Post slug.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $slug = 'faq';

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->labels = array(
			'name'                  => _x( 'Faqs', 'Faq General Name', 'masteriyo' ),
			'singular_name'         => _x( 'Faq', 'Faq Singular Name', 'masteriyo' ),
			'menu_name'             => __( 'Faqs', 'masteriyo' ),
			'name_admin_bar'        => __( 'Faq', 'masteriyo' ),
			'archives'              => __( 'Faq Archives', 'masteriyo' ),
			'attributes'            => __( 'Faq Attributes', 'masteriyo' ),
			'parent_item_colon'     => __( 'Parent Faq:', 'masteriyo' ),
			'all_items'             => __( 'All Faqs', 'masteriyo' ),
			'add_new_item'          => __( 'Add New Item', 'masteriyo' ),
			'add_new'               => __( 'Add New', 'masteriyo' ),
			'new_item'              => __( 'New Faq', 'masteriyo' ),
			'edit_item'             => __( 'Edit Faq', 'masteriyo' ),
			'update_item'           => __( 'Update Faq', 'masteriyo' ),
			'view_item'             => __( 'View Faq', 'masteriyo' ),
			'view_items'            => __( 'View Faqs', 'masteriyo' ),
			'search_items'          => __( 'Search Faq', 'masteriyo' ),
			'not_found'             => __( 'Not found', 'masteriyo' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'masteriyo' ),
			'featured_image'        => __( 'Featured Image', 'masteriyo' ),
			'set_featured_image'    => __( 'Set featured image', 'masteriyo' ),
			'remove_featured_image' => __( 'Remove featured image', 'masteriyo' ),
			'use_featured_image'    => __( 'Use as featured image', 'masteriyo' ),
			'insert_into_item'      => __( 'Insert into Faq', 'masteriyo' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Faq', 'masteriyo' ),
			'items_list'            => __( 'Faqs list', 'masteriyo' ),
			'items_list_navigation' => __( 'Faqs list navigation', 'masteriyo' ),
			'filter_items_list'     => __( 'Filter Faqs list', 'masteriyo' ),
		);

		$this->args = array(
			'label'               => __( 'Faqs', 'masteriyo' ),
			'description'         => __( 'Faqs Description', 'masteriyo' ),
			'labels'              => $this->labels,
			'supports'            => array( 'title', 'editor', 'author', 'page-attributes' ),
			'taxonomies'          => array(),
			'hierarchical'        => true,
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
