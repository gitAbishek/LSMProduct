<?php
/**
 * Class for parameter-based Course querying
 *
 * @package  ThemeGrill\Masteriyo\Query
 * @version 0.1.0
 * @since   0.1.0
 */

namespace ThemeGrill\Masteriyo\Query;

use ThemeGrill\Masteriyo\Abstracts\ObjectQuery;

defined( 'ABSPATH' ) || exit;

/**
 * Course query class.
 */
class CourseQuery extends ObjectQuery {

	/**
	 * Valid query vars for courses.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {
		return array_merge(
			parent::get_default_query_vars(),
			array(
				'status'            => array( 'draft', 'pending', 'private', 'publish' ),
				'limit'             => get_option( 'posts_per_page' ),
				'include'           => array(),
				'date_created'      => '',
				'date_modified'     => '',
				'featured'          => '',
				'visibility'        => '',
				'price'             => '',
				'regular_price'     => '',
				'sale_price'        => '',
				'date_on_sale_from' => '',
				'date_on_sale_to'   => '',
				'category'          => array(),
				'tag'               => array(),
				'difficulty'        => array(),
				'average_rating'    => '',
				'review_count'      => '',
			)
		);
	}

	/**
	 * Get courses matching the current query vars.
	 *
	 * @since 0.0.1
	 *
	 * @return array|Model Course objects
	 */
	public function get_courses() {
		$args    = apply_filters( 'masteriyo_course_object_query_args', $this->get_query_vars() );
		$results = masteriyo('course.store' )->query( $args );
		return apply_filters( 'masteriyo_course_object_query', $results, $args );
	}
}
