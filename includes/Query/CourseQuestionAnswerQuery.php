<?php
/**
 * Class for parameter-based Course question-answer querying
 *
 * @package  Masteriyo\Query
 * @version 0.1.0
 * @since   0.1.0
 */

namespace Masteriyo\Query;

use Masteriyo\Abstracts\ObjectQuery;

defined( 'ABSPATH' ) || exit;

/**
 * Course question-answer query class.
 */
class CourseQuestionAnswerQuery extends ObjectQuery {

	/**
	 * Valid query vars for courses question-answers.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {
		return array_merge(
			parent::get_default_query_vars(),
			array(
				'course_id' => '',
			)
		);
	}

	/**
	 * Get courses question-answers matching the current query vars.
	 *
	 * @since 0.0.1
	 *
	 * @return array|Model Course review objects
	 */
	public function get_course_qas() {
		$args    = apply_filters( 'masteriyo_course_qa_object_query_args', $this->get_query_vars() );
		$results = masteriyo( 'course-qa.store' )->query( $args );
		return apply_filters( 'masteriyo_course_qa_object_query', $results, $args );
	}
}
