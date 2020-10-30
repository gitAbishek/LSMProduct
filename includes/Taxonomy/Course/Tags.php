<?php
/**
 * Courses Course tags.
 */

namespace ThemeGrill\Masteriyo\Taxonomy\Course;

use ThemeGrill\Masteriyo\Taxonomy\Taxonomy;

class Tags extends Taxonomy {

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		parent::__construct( 'course_tag' );
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
	 * Default labels.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_labels() {
		return array(
			'name'                       => _x( 'Course Tags', 'Taxonomy General Name', 'masteriyo' ),
			'singular_name'              => _x( 'Course Tag', 'Taxonomy Singular Name', 'masteriyo' ),
			'menu_name'                  => __( 'Course Tag', 'masteriyo' ),
			'all_items'                  => __( 'All Course Tags', 'masteriyo' ),
			'parent_item'                => __( 'Parent Course Tag', 'masteriyo' ),
			'parent_item_colon'          => __( 'Parent Course Tag:', 'masteriyo' ),
			'new_item_name'              => __( 'New Course Tag Name', 'masteriyo' ),
			'add_new_item'               => __( 'Add New Course Tag', 'masteriyo' ),
			'edit_item'                  => __( 'Edit Course Tag', 'masteriyo' ),
			'update_item'                => __( 'Update Course Tag', 'masteriyo' ),
			'view_item'                  => __( 'View Course Tag', 'masteriyo' ),
			'separate_items_with_commas' => __( 'Separate course tags with commas', 'masteriyo' ),
			'add_or_remove_items'        => __( 'Add or remove course tags', 'masteriyo' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'masteriyo' ),
			'popular_items'              => __( 'Popular Course Tags', 'masteriyo' ),
			'search_items'               => __( 'Search Course Tags', 'masteriyo' ),
			'not_found'                  => __( 'Not Found', 'masteriyo' ),
			'no_terms'                   => __( 'No course tags', 'masteriyo' ),
			'items_list'                 => __( 'Course Tags list', 'masteriyo' ),
			'items_list_navigation'      => __( 'Course Tags list navigation', 'masteriyo' ),
		);
	}
}
