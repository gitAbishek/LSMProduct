<?php
/**
 * Class for parameter-based Course querying
 *
 * @package  Masteriyo\Query
 * @version 1.0.0
 * @since   1.0.0
 */

namespace Masteriyo\Query;

use Masteriyo\Abstracts\ObjectQuery;

defined( 'ABSPATH' ) || exit;

/**
 * Course query class.
 */
class CourseQuery extends ObjectQuery {

	/**
	 * Valid query vars for courses.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {
		return array_merge(
			parent::get_default_query_vars(),
			array(
				'type'              => 'mto-course',
				'status'            => array( 'draft', 'pending', 'private', 'publish' ),
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
				'course'            => '',
			)
		);
	}

	/**
	 * Get courses matching the current query vars.
	 *
	 * @since 1.0.0
	 *
	 * @return array|Model Course objects
	 */
	public function get_courses() {
		$args    = apply_filters( 'masteriyo_course_object_query_args', $this->get_query_vars() );
		$results = masteriyo( 'course.store' )->query( $args );
		return apply_filters( 'masteriyo_course_object_query', $results, $args );
	}
}
