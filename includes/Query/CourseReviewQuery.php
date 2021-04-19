<?php
/**
 * Class for parameter-based Course Review querying
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
class CourseReviewQuery extends ObjectQuery {

	/**
	 * Valid query vars for courses reviews.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {
		return array_merge(
			parent::get_default_query_vars(),
			array()
		);
	}

	/**
	 * Get courses reviews matching the current query vars.
	 *
	 * @since 0.0.1
	 *
	 * @return array|Model Course review objects
	 */
	public function get_courses_reviews() {
		$args    = apply_filters( 'masteriyo_course_review_object_query_args', $this->get_query_vars() );
		$results = masteriyo( 'course_review.store' )->query( $args );
		return apply_filters( 'masteriyo_course_review_object_query', $results, $args );
	}
}
