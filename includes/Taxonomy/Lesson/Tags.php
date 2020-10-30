<?php
/**
 * Lessons Lesson tags.
 */

namespace ThemeGrill\Masteriyo\Taxonomy\Lesson;

use ThemeGrill\Masteriyo\Taxonomy\Taxonomy;

class Tags extends Taxonomy {

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		parent::__construct( 'lesson_tag' );
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
			'name'                       => _x( 'Lesson Tags', 'Taxonomy General Name', 'masteriyo' ),
			'singular_name'              => _x( 'Lesson Tag', 'Taxonomy Singular Name', 'masteriyo' ),
			'menu_name'                  => __( 'Lesson Tag', 'masteriyo' ),
			'all_items'                  => __( 'All Lesson Tags', 'masteriyo' ),
			'parent_item'                => __( 'Parent Lesson Tag', 'masteriyo' ),
			'parent_item_colon'          => __( 'Parent Lesson Tag:', 'masteriyo' ),
			'new_item_name'              => __( 'New Lesson Tag Name', 'masteriyo' ),
			'add_new_item'               => __( 'Add New Lesson Tag', 'masteriyo' ),
			'edit_item'                  => __( 'Edit Lesson Tag', 'masteriyo' ),
			'update_item'                => __( 'Update Lesson Tag', 'masteriyo' ),
			'view_item'                  => __( 'View Lesson Tag', 'masteriyo' ),
			'separate_items_with_commas' => __( 'Separate lesson tags with commas', 'masteriyo' ),
			'add_or_remove_items'        => __( 'Add or remove lesson tags', 'masteriyo' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'masteriyo' ),
			'popular_items'              => __( 'Popular Lesson Tags', 'masteriyo' ),
			'search_items'               => __( 'Search Lesson Tags', 'masteriyo' ),
			'not_found'                  => __( 'Not Found', 'masteriyo' ),
			'no_terms'                   => __( 'No lesson tags', 'masteriyo' ),
			'items_list'                 => __( 'Lesson Tags list', 'masteriyo' ),
			'items_list_navigation'      => __( 'Lesson Tags list navigation', 'masteriyo' ),
		);
	}
}
