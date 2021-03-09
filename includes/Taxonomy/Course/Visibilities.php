<?php
/**
 * Courses Course visibilities.
 */

namespace ThemeGrill\Masteriyo\Taxonomy\Course;

use ThemeGrill\Masteriyo\Taxonomy\Taxonomy;

class Visibilities extends Taxonomy {

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		parent::__construct( 'course_visibility' );
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
		parent::register( 'course' );
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
			'name'                       => _x( 'Course Visibilities', 'Taxonomy General Name', 'masteriyo' ),
			'singular_name'              => _x( 'Course Visibility', 'Taxonomy Singular Name', 'masteriyo' ),
			'menu_name'                  => __( 'Course Visibility', 'masteriyo' ),
			'all_items'                  => __( 'All Course Visibilities', 'masteriyo' ),
			'parent_item'                => __( 'Parent Course Visibility', 'masteriyo' ),
			'parent_item_colon'          => __( 'Parent Course Visibility:', 'masteriyo' ),
			'new_item_name'              => __( 'New Course Visibility Name', 'masteriyo' ),
			'add_new_item'               => __( 'Add New Course Visibility', 'masteriyo' ),
			'edit_item'                  => __( 'Edit Course Visibility', 'masteriyo' ),
			'update_item'                => __( 'Update Course Visibility', 'masteriyo' ),
			'view_item'                  => __( 'View Course Visibility', 'masteriyo' ),
			'separate_items_with_commas' => __( 'Separate course visibilities with commas', 'masteriyo' ),
			'add_or_remove_items'        => __( 'Add or remove course visibilities', 'masteriyo' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'masteriyo' ),
			'popular_items'              => __( 'Popular Course Visibilities', 'masteriyo' ),
			'search_items'               => __( 'Search Course Visibilities', 'masteriyo' ),
			'not_found'                  => __( 'Not Found', 'masteriyo' ),
			'no_terms'                   => __( 'No course visibilities', 'masteriyo' ),
			'items_list'                 => __( 'Course Visibilities list', 'masteriyo' ),
			'items_list_navigation'      => __( 'Course Visibilities list navigation', 'masteriyo' ),
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
		$args                = parent::get_args( $labels );
		$args['hierarchial'] = true;
		return $args;
	}
}
