<?php
/**
 * Courses Course difficulties.
 */

namespace ThemeGrill\Masteriyo\Taxonomy\Courses;

use ThemeGrill\Masteriyo\Taxonomy\Taxonomy;

class Difficulties extends Taxonomy {

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		parent::__construct( 'courses_difficulty' );
	}

	/**
	 * Register taxonomy.
	 *
	 * @since 0.1.0
	 *
	 * @param string|array $object_type (Required) Object type or array of object types with which the taxonomy should be associated.
	 *
	 * @return void
	 */
	public function register( $object_type = 'post' ) {
		parent::register( 'masteriyo_courses' );
	}

	/**
	 * Get labels.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_labels() {
		return array(
			'name'                       => _x( 'Course Difficulties', 'Taxonomy General Name', 'masteriyo' ),
			'singular_name'              => _x( 'Course Difficulty', 'Taxonomy Singular Name', 'masteriyo' ),
			'menu_name'                  => __( 'Course Difficulty', 'masteriyo' ),
			'all_items'                  => __( 'All Course Difficulties', 'masteriyo' ),
			'parent_item'                => __( 'Parent Course Difficulty', 'masteriyo' ),
			'parent_item_colon'          => __( 'Parent Course Difficulty:', 'masteriyo' ),
			'new_item_name'              => __( 'New Course Difficulty Name', 'masteriyo' ),
			'add_new_item'               => __( 'Add New Course Difficulty', 'masteriyo' ),
			'edit_item'                  => __( 'Edit Course Difficulty', 'masteriyo' ),
			'update_item'                => __( 'Update Course Difficulty', 'masteriyo' ),
			'view_item'                  => __( 'View Course Difficulty', 'masteriyo' ),
			'separate_items_with_commas' => __( 'Separate course difficulties with commas', 'masteriyo' ),
			'add_or_remove_items'        => __( 'Add or remove course difficulties', 'masteriyo' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'masteriyo' ),
			'popular_items'              => __( 'Popular Course Difficulties', 'masteriyo' ),
			'search_items'               => __( 'Search Course Difficulties', 'masteriyo' ),
			'not_found'                  => __( 'Not Found', 'masteriyo' ),
			'no_terms'                   => __( 'No course difficulties', 'masteriyo' ),
			'items_list'                 => __( 'Course Difficulties list', 'masteriyo' ),
			'items_list_navigation'      => __( 'Course Difficulties list navigation', 'masteriyo' ),
		);
	}


	/**
	 * Get args.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_args( $labels ) {
		return array(
			'labels'             => $labels,
			'description'        => '',
			'hierarchical'       => true,
			'public'             => true,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'show_in_nav_menus'  => true,
			'show_in_quick_edit' => true,
			'show_tagcloud'      => true,
		);
	}
}
