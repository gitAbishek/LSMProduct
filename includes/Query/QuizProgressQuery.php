<?php
/**
 * Class for parameter-based quiz progress query.
 *
 * @package  ThemeGrill\Masteriyo\Query
 * @version 0.1.0
 * @since   0.1.0
 */

namespace ThemeGrill\Masteriyo\Query;

use ThemeGrill\Masteriyo\Abstracts\ObjectQuery;

defined( 'ABSPATH' ) || exit;

/**
 * Quiz query class.
 */
class QuizProgressQuery extends ObjectQuery {

	/**
	 * Valid query vars for quiz progress.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {
		return array_merge(
			parent::get_default_query_vars(),
			array(
				'user_id'       => 0,
				'quiz_id'       => 0,
				'activity_type' => 'quiz',
				'status'        => 'any',
				'date_start'    => null,
				'date_update'   => null,
				'date_complete' => null,
			)
		);
	}

	/**
	 * Get quiz progress matching the current query vars.
	 *
	 * @since 0.0.1
	 *
	 * @return array|Model Quiz objects
	 */
	public function get_quiz_progress() {
		$args    = apply_filters( 'masteriyo_quiz_progress_object_query_args', $this->get_query_vars() );
		$results = masteriyo( 'quiz-progress.store' )->query( $args );
		return apply_filters( 'masteriyo_quiz_progress_object_query', $results, $args );
	}
}
