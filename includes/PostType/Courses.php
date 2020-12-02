<?php
/**
 * Courses class.
 */

namespace ThemeGrill\Masteriyo\PostType;

class Courses extends PostType {
	/**
	 * Post slug.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $slug = 'course';

	public function __construct() {
		$this->labels = array(
			'name'                  => _x( 'Courses', 'Course General Name', 'masteriyo' ),
			'singular_name'         => _x( 'Course', 'Course Singular Name', 'masteriyo' ),
			'menu_name'             => __( 'Courses', 'masteriyo' ),
			'name_admin_bar'        => __( 'Course', 'masteriyo' ),
			'archives'              => __( 'Course Archives', 'masteriyo' ),
			'attributes'            => __( 'Course Attributes', 'masteriyo' ),
			'parent_item_colon'     => __( 'Parent Course:', 'masteriyo' ),
			'all_items'             => __( 'All Courses', 'masteriyo' ),
			'add_new_item'          => __( 'Add New Item', 'masteriyo' ),
			'add_new'               => __( 'Add New', 'masteriyo' ),
			'new_item'              => __( 'New Course', 'masteriyo' ),
			'edit_item'             => __( 'Edit Course', 'masteriyo' ),
			'update_item'           => __( 'Update Course', 'masteriyo' ),
			'view_item'             => __( 'View Course', 'masteriyo' ),
			'view_items'            => __( 'View Courses', 'masteriyo' ),
			'search_items'          => __( 'Search Course', 'masteriyo' ),
			'not_found'             => __( 'Not found', 'masteriyo' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'masteriyo' ),
			'featured_image'        => __( 'Featured Image', 'masteriyo' ),
			'set_featured_image'    => __( 'Set featured image', 'masteriyo' ),
			'remove_featured_image' => __( 'Remove featured image', 'masteriyo' ),
			'use_featured_image'    => __( 'Use as featured image', 'masteriyo' ),
			'insert_into_item'      => __( 'Insert into course', 'masteriyo' ),
			'uploaded_to_this_item' => __( 'Uploaded to this course', 'masteriyo' ),
			'items_list'            => __( 'Courses list', 'masteriyo' ),
			'items_list_navigation' => __( 'Courses list navigation', 'masteriyo' ),
			'filter_items_list'     => __( 'Filter courses list', 'masteriyo' ),
		);

		$this->args = array(
			'label'               => __( 'Courses', 'masteriyo' ),
			'description'         => __( 'Courses Description', 'masteriyo' ),
			'labels'              => $this->labels,
			'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'comments', 'custom-fields', 'page-attributes', 'post-formats' ),
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
