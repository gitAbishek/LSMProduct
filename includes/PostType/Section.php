<?php
/**
 * Sections class.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\PostType;
 */

namespace ThemeGrill\Masteriyo\PostType;

/**
 * Sections class.
 */
class Section extends PostType {
	/**
	 * Post slug.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $slug = 'section';

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->labels = array(
			'name'                  => _x( 'Sections', 'Section General Name', 'masteriyo' ),
			'singular_name'         => _x( 'Section', 'Section Singular Name', 'masteriyo' ),
			'menu_name'             => __( 'Sections', 'masteriyo' ),
			'name_admin_bar'        => __( 'Section', 'masteriyo' ),
			'archives'              => __( 'Section Archives', 'masteriyo' ),
			'attributes'            => __( 'Section Attributes', 'masteriyo' ),
			'parent_item_colon'     => __( 'Parent Section:', 'masteriyo' ),
			'all_items'             => __( 'All Sections', 'masteriyo' ),
			'add_new_item'          => __( 'Add New Item', 'masteriyo' ),
			'add_new'               => __( 'Add New', 'masteriyo' ),
			'new_item'              => __( 'New Section', 'masteriyo' ),
			'edit_item'             => __( 'Edit Section', 'masteriyo' ),
			'update_item'           => __( 'Update Section', 'masteriyo' ),
			'view_item'             => __( 'View Section', 'masteriyo' ),
			'view_items'            => __( 'View Sections', 'masteriyo' ),
			'search_items'          => __( 'Search Section', 'masteriyo' ),
			'not_found'             => __( 'Not found', 'masteriyo' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'masteriyo' ),
			'featured_image'        => __( 'Featured Image', 'masteriyo' ),
			'set_featured_image'    => __( 'Set featured image', 'masteriyo' ),
			'remove_featured_image' => __( 'Remove featured image', 'masteriyo' ),
			'use_featured_image'    => __( 'Use as featured image', 'masteriyo' ),
			'insert_into_item'      => __( 'Insert into section', 'masteriyo' ),
			'uploaded_to_this_item' => __( 'Uploaded to this section', 'masteriyo' ),
			'items_list'            => __( 'Sections list', 'masteriyo' ),
			'items_list_navigation' => __( 'Sections list navigation', 'masteriyo' ),
			'filter_items_list'     => __( 'Filter sections list', 'masteriyo' ),
		);

		$this->args = array(
			'label'               => __( 'Sections', 'masteriyo' ),
			'description'         => __( 'Sections Description', 'masteriyo' ),
			'labels'              => $this->labels,
			'supports'            => array( 'title', 'editor', 'author', 'comments', 'custom-fields', 'post-formats' ),
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
