<?php
/**
 * Class for parameter-based course progress item query.
 *
 * @package  Masteriyo\Query
 * @version 1.0.0
 * @since   1.0.0
 */

namespace Masteriyo\Query;

use Masteriyo\Abstracts\ObjectQuery;

defined( 'ABSPATH' ) || exit;

/**
 * Course progress item query class.
 */
class CourseProgressItemQuery extends ObjectQuery {

	/**
	 * Valid query vars for course progress.
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
				'progress_id'   => 0,
				'item_id'       => 0,
				'activity_type' => 'course',
				'status'        => 'any',
				'date_start'    => null,
				'date_update'   => null,
				'date_complete' => null,
				'orderby'       => 'id',
			)
		);
	}

	/**
	 * Get course progress item matching the current query vars.
	 *
	 * @since 0.0.1
	 *
	 * @return array|CourseProgressItem Course progress item objects
	 */
	public function get_course_progress_items() {
		$args    = apply_filters( 'masteriyo_course_progress_item_object_query_args', $this->get_query_vars() );
		$results = masteriyo( 'course-progress-item.store' )->query( $args );
		return apply_filters( 'masteriyo_course_progress_item_object_query', $results, $args );
	}
}
