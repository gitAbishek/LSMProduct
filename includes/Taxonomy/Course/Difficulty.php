<?php
/**
 * Courses Course difficulties.
 */

namespace ThemeGrill\Masteriyo\Taxonomy\Course;

use ThemeGrill\Masteriyo\Taxonomy\Taxonomy;

class Difficulty extends Taxonomy {
	/**
	 * Taxonomy.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $taxonomy = 'course_difficulty';


	/**
	 * Post type the taxonomy belongs to.
	 *
	 * @since 0.1.0
	 */
	protected $post_type = 'course';

	/**
	 * Get settings.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	protected function get_args() {
		return apply_filters(
			'masteriyo_taxonomy_args_course_difficulty',
			array(
				'hierarchical'      => false,
				'label'             => __( 'Course Difficulties', 'masteriyo' ),
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_tag_cloud'    => true,
				'query_var'         => true,
				'rewrite'           => array(
					'slug'         => \masteriyo_get_permalink_structure('course_difficulty_rewrite_slug'),
					'with_front'   => false,
					'hierarchical' => true,
				),
				'labels' => array(
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
				),
			)
		);
	}
}
