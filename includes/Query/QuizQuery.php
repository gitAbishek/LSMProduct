<?php
/**
 * Class for parameter-based Quiz querying
 *
 * @package  Masteriyo\Query
 * @version 1.0.0
 * @since   1.0.0
 */

namespace Masteriyo\Query;

use Masteriyo\Abstracts\ObjectQuery;

defined( 'ABSPATH' ) || exit;

/**
 * Quiz query class.
 */
class QuizQuery extends ObjectQuery {

	/**
	 * Valid query vars for quizes.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {
		return array_merge(
			parent::get_default_query_vars(),
			array(
				'slug'          => '',
				'date_created'  => null,
				'date_modified' => null,
				'status'        => array( 'draft', 'pending', 'private', 'publish' ),
				'parent_id'     => '',
				'course_id'     => '',
			)
		);
	}

	/**
	 * Get quizes matching the current query vars.
	 *
	 * @since 0.0.1
	 *
	 * @return array|Model quiz objects
	 */
	public function get_quizes() {
		$args    = apply_filters( 'masteriyo_quiz_object_query_args', $this->get_query_vars() );
		$results = masteriyo( 'quiz.store' )->query( $args );
		return apply_filters( 'masteriyo_quiz_object_query', $results, $args );
	}
}
