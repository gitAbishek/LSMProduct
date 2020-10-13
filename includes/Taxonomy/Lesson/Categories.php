<?php
/**
 * Lessons Lesson categories.
 */

namespace ThemeGrill\Masteriyo\Taxonomy\Lesson;

use ThemeGrill\Masteriyo\Taxonomy\Taxonomy;

class Categories extends Taxonomy {

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		parent::__construct( 'lesson_cat' );
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
		parent::register( 'lessons' );
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
			'name'                       => _x( 'Lesson Categories', 'Taxonomy General Name', 'masteriyo' ),
			'singular_name'              => _x( 'Lesson Category', 'Taxonomy Singular Name', 'masteriyo' ),
			'menu_name'                  => __( 'Lesson Category', 'masteriyo' ),
			'all_items'                  => __( 'All Lesson Categories', 'masteriyo' ),
			'parent_item'                => __( 'Parent Lesson Category', 'masteriyo' ),
			'parent_item_colon'          => __( 'Parent Lesson Category:', 'masteriyo' ),
			'new_item_name'              => __( 'New Lesson Category Name', 'masteriyo' ),
			'add_new_item'               => __( 'Add New Lesson Category', 'masteriyo' ),
			'edit_item'                  => __( 'Edit Lesson Category', 'masteriyo' ),
			'update_item'                => __( 'Update Lesson Category', 'masteriyo' ),
			'view_item'                  => __( 'View Lesson Category', 'masteriyo' ),
			'separate_items_with_commas' => __( 'Separate lesson categories with commas', 'masteriyo' ),
			'add_or_remove_items'        => __( 'Add or remove lesson categories', 'masteriyo' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'masteriyo' ),
			'popular_items'              => __( 'Popular Lesson Categories', 'masteriyo' ),
			'search_items'               => __( 'Search Lesson Categories', 'masteriyo' ),
			'not_found'                  => __( 'Not Found', 'masteriyo' ),
			'no_terms'                   => __( 'No lesson categories', 'masteriyo' ),
			'items_list'                 => __( 'Lesson Categories list', 'masteriyo' ),
			'items_list_navigation'      => __( 'Lesson Categories list navigation', 'masteriyo' ),
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
		$args = parent::get_args( $labels );
		$args['hierarchial'] = true;
		return $args;
	}
}
