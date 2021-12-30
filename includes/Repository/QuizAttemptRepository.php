<?php
/**
 * QuizAttempt Repository class.
 *
 * @since 1.3.2
 *
 * @package Masteriyo\Repository
 */

namespace Masteriyo\Repository;

use Masteriyo\QuizAttempt;
use Masteriyo\Database\Model;

/**
 * QuizAttempt Repository class.
 */
class QuizAttemptRepository extends AbstractRepository implements RepositoryInterface {
	/**
	 * create quiz attempt in database.
	 *
	 * @since 1.3.2
	 *
	 * @param Model $quiz_attempt Quiz attempt object.
	 */
	public function create( Model &$quiz_attempt ) {

	}

	/**
	 * Read a quiz attempt.
	 *
	 * @since 1.3.2
	 *
	 * @param Model $quiz_attempt quiz attempt object.
	 *
	 * @throws \Exception If invalid quiz attempt.
	 */
	public function read( Model &$quiz_attempt ) {
		global $wpdb;

		$data = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}masteriyo_quiz_attempts WHERE id = %d LIMIT 1;",
				$quiz_attempt->get_id()
			)
		);

		if ( ! $data ) {
			throw new \Exception( __( 'Invalid quiz attempt.', 'masteriyo' ) );
		}

		$quiz_attempt->set_props(
			array(
				'course_id'                => $data->course_id,
				'quiz_id'                  => $data->quiz_id,
				'user_id'                  => $data->user_id,
				'total_questions'          => $data->total_questions,
				'total_answered_questions' => $data->total_answered_questions,
				'total_marks'              => $data->total_marks,
				'total_attempts'           => $data->total_attempts,
				'total_correct_answers'    => $data->total_correct_answers,
				'total_incorrect_answers'  => $data->total_incorrect_answers,
				'earned_marks'             => $data->earned_marks,
				'answers'                  => $data->answers,
				'attempt_status'           => $data->attempt_status,
				'attempt_started_at'       => $data->attempt_started_at,
				'attempt_ended_at'         => $data->attempt_ended_at,
			)
		);
		$quiz_attempt->set_object_read( true );
	}

	/**
	 * Update a quiz attempt.
	 *
	 * @since 1.3.2
	 *
	 * @param Model $quiz_attempt quiz attempt object.
	 *
	 * @return void.
	 */
	public function update( Model &$quiz_attempt ) {

	}

	/**
	 * Delete a quiz attempt.
	 *
	 * @since 1.3.2
	 *
	 * @param Model $quiz_attempt quiz attempt object.
	 * @param array $args Array of args to pass.alert-danger.
	 */
	public function delete( Model &$quiz_attempt, $args = array() ) {

	}

	/**
	 * Fetch quiz attempts.
	 *
	 * @since 1.3.2
	 *
	 * @param array $query_vars Query vars.
	 * @return Masteriyo\Models\QuizAttempt[]
	 */
	public function query( $query_vars ) {
		global $wpdb;

		$search_criteria = array();
		$sql[]           = "SELECT * FROM {$wpdb->base_prefix}masteriyo_quiz_attempts";

		// Construct where clause part.
		if ( ! empty( $query_vars['id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'id = %d', $query_vars['id'] );
		}

		if ( ! empty( $query_vars['course_id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'course_id = %d', $query_vars['course_id'] );
		}

		if ( ! empty( $query_vars['quiz_id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'quiz_id = %d', $query_vars['quiz_id'] );
		}

		if ( ! empty( $query_vars['user_id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'user_id = %d', $query_vars['user_id'] );
		}

		if ( ! empty( $query_vars['status'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'attempt_status = %s', $query_vars['status'] );
		}

		if ( 1 <= count( $search_criteria ) ) {
			$criteria = implode( ' AND ', $search_criteria );
			$sql[]    = 'WHERE ' . $criteria;
		}

		// Construct order and order by part.
		$sql[] = 'ORDER BY ' . sanitize_sql_orderby( $query_vars['orderby'] . ' ' . $query_vars['order'] );

		// Construct limit part.
		$per_page = $query_vars['per_page'];

		if ( $query_vars['paged'] > 0 ) {
			$offset = ( $query_vars['paged'] - 1 ) * $per_page;
		}

		$sql[] = $wpdb->prepare( 'LIMIT %d, %d', $offset, $per_page );

		// Generate SQL from the SQL parts.
		$sql = implode( ' ', $sql ) . ';';

		// Fetch the results.
		$quiz_attempt = $wpdb->get_results( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		return (array) $quiz_attempt;
	}
}
