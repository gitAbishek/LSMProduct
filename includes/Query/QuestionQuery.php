<?php
/**
 * Class for parameter-based Question querying
 *
 * @package  Masteriyo\Query
 * @version 1.0.0
 * @since   1.0.0
 */

namespace Masteriyo\Query;

use Masteriyo\Abstracts\ObjectQuery;

defined( 'ABSPATH' ) || exit;

/**
 * Question query class.
 */
class QuestionQuery extends ObjectQuery {

	/**
	 * Valid query vars for questions.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {
		return array_merge(
			parent::get_default_query_vars(),
			array(
				'slug'            => '',
				'date_created'    => null,
				'date_modified'   => null,
				'status'          => array( 'draft', 'pending', 'private', 'publish' ),
				'menu_order'      => '',
				'parent_id'       => '',
				'course_id'       => '',
				'type'            => '',
				'answer_required' => '',
				'randomize'       => '',
				'points'          => '',
			)
		);
	}

	/**
	 * Get questions matching the current query vars.
	 *
	 * @since 1.0.0
	 *
	 * @return array|Model Question objects
	 */
	public function get_questions() {
		$args    = apply_filters( 'masteriyo_question_object_query_args', $this->get_query_vars() );
		$results = masteriyo( 'question.store' )->query( $args );
		return apply_filters( 'masteriyo_question_object_query', $results, $args );
	}
}
