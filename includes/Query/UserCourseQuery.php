<?php
/**
 * Class for parameter-based user course query.
 *
 * @package  Masteriyo\Query
 * @version 1.0.0
 * @since   1.0.0
 */

namespace Masteriyo\Query;

use Masteriyo\Abstracts\ObjectQuery;

defined( 'ABSPATH' ) || exit;

/**
 * User course query class.
 */
class UserCourseQuery extends ObjectQuery {

	/**
	 * Valid query vars for user course.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {
		return array_merge(
			parent::get_default_query_vars(),
			array(
				'user_id'       => 0,
				'course_id'     => 0,
				'status'        => 'any',
				'date_start'    => null,
				'date_modified' => null,
				'date_end'      => null,
				'orderby'       => 'id',
			)
		);
	}

	/**
	 * Get user courses matching the current query vars.
	 *
	 * @since 1.0.0
	 *
	 * @return Masteriyo\Models\UserCourse[] User course objects.
	 */
	public function get_user_courses() {
		$args    = apply_filters( 'masteriyo_user_course_object_query_args', $this->get_query_vars() );
		$results = masteriyo( 'user-course.store' )->query( $args );
		return apply_filters( 'masteriyo_user_course_object_query', $results, $args );
	}
}

