<?php
/**
 * Quiz attempt repository.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Repository
 */

namespace ThemeGrill\Masteriyo\Repository;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Models\QuizAttempt;

/**
 * Quiz attempt repository class.
 *
 * @since 0.1.0
 */
class QuizAttemptRepository extends AbstractRepository implements RepositoryInterface {

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $internal_meta_keys = array();

	/**
	 * Create a quiz attempt in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $quiz_attempt Quiz attempt object.
	 */
	public function create( Model &$quiz_attempt ) {

		global $wpdb;

		if ( ! $quiz_attempt->get_started_at( 'edit' ) ) {
			$quiz_attempt->set_started_at( current_time( 'mysql', true ) );
		}

		$result = $wpdb->insert(
			$wpdb->base_prefix . 'masteriyo_quiz_attempts',
			apply_filters(
				'masteriyo_new_quiz_attempt_data',
				array(
					'course_id'                => $quiz_attempt->get_course_id( 'edit' ),
					'quiz_id'                  => $quiz_attempt->get_quiz_id( 'edit' ),
					'user_id'                  => $quiz_attempt->get_user_id( 'edit' ),
					'total_questions'          => $quiz_attempt->get_total_questions( 'edit' ),
					'total_answered_questions' => $quiz_attempt->get_total_answered_questions( 'edit' ),
					'total_marks'              => $quiz_attempt->get_total_marks( 'edit' ),
					'earned_marks'             => $quiz_attempt->get_earned_marks( 'edit' ),
					'info'                     => $quiz_attempt->get_info( 'edit' ),
					'status'                   => $quiz_attempt->get_status( 'edit' ),
					'started_at'               => gmdate( 'Y-m-d H:i:s', $quiz_attempt->get_started_at( 'edit' )->getTimestamp() ),
					'ended_at'                 => gmdate( 'Y-m-d H:i:s', $quiz_attempt->get_ended_at( 'edit' )->getTimestamp() ),
				),
				$quiz_attempt
			),
			array( '%d', '%d', '%d', '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s' )
		);

		if ( $result && $wpdb->insert_id ) {
			$quiz_attempt->set_id( $wpdb->insert_id );
			$quiz_attempt->apply_changes();

			do_action( 'masteriyo_new_quiz_attempt', $quiz_attempt->get_id(), $quiz_attempt );
		}

	}

	/**
	 * Read a quiz attempe from the database.
	 *
	 * @since 0.1.0
	 *
	 * @param QuizAttempt $quiz_attempt Quiz attempt object.
	 *
	 * @throws Exception If invalid quiz attempt object.
	 */
	public function read( &$quiz_attempt ) {
		global $wpdb;

		$result = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}masteriyo_quiz_attempts WHERE attempt_id = %d;",
				$quiz_attempt->get_id()
			)
		);

		if ( ! $result ) {
			throw new \Exception( __( 'Invalid quiz attempt.', 'masteriyo' ) );
		}

		$quiz_attempt->set_props(
			array(
				'course_id'                => $result->course_id,
				'quiz_id'                  => $result->quiz_id,
				'user_id'                  => $result->user_id,
				'total_questions'          => $result->total_questions,
				'total_answered_questions' => $result->total_answered_questions,
				'total_marks'              => $result->total_marks,
				'earned_marks'             => $result->earned_marks,
				'info'                     => $result->info,
				'status'                   => $result->status,
				'started_at'               => $this->string_to_timestamp( $result->attempt_started_at ),
				'ended_at'                 => $this->string_to_timestamp( $result->atttempt_ended_at ),
			)
		);

		$quiz_attempt->set_object_read( true );

		do_action( 'masteriyo_quiz_attempt_read', $quiz_attempt->get_id(), $quiz_attempt );
	}

	/**
	 * Update a quiz attempt in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $quiz Quiz attempt object.
	 *
	 * @return void
	 */
	public function update( Model &$quiz_attempt ) {
		global $wpdb;

		$changes = $quiz_attempt->get_changes();

		$quiz_attempt_data_keys = array(
			'course_id',
			'quiz_id',
			'user_id',
			'total_questions',
			'total_answered_questions',
			'total_marks',
			'earned_marks',
			'info',
			'status',
			'started_at',
			'ended_at',
		);

		if ( array_intersect( $quiz_attempt_data_keys, array_keys( $changes ) ) ) {
			$wpdb->update(
				$wpdb->prefix . 'masteriyo_quiz_attempts',
				array(
					'course_id'                => $quiz_attempt->get_course_id( 'edit' ),
					'quiz_id'                  => $quiz_attempt->get_quiz_id( 'edit' ),
					'user_id'                  => $quiz_attempt->get_user_id( 'edit' ),
					'total_questions'          => $quiz_attempt->get_total_questions( 'edit' ),
					'total_answered_questions' => $quiz_attempt->get_total_answered_questions( 'edit' ),
					'total_marks'              => $quiz_attempt->get_total_marks( 'edit' ),
					'earned_marks'             => $quiz_attempt->get_earned_marks( 'edit' ),
					'info'                     => $quiz_attempt->get_info( 'edit' ),
					'attempt_status'           => $quiz_attempt->get_status( 'edit' ),
					'attempt_started_at'       => gmdate( 'Y-m-d H:i:s', $quiz_attempt->get_started_at( 'edit' )->getTimestamp() ),
					'atttempt_ended_at'        => gmdate( 'Y-m-d H:i:s', $quiz_attempt->get_ended_at( 'edit' )->getTimestamp() ),
				),
				array( 'attempt_id' => $quiz_attempt->get_id() )
			);
		}

		$quiz_attempt->apply_changes();

		do_action( 'masteriyo_update_quiz_attempt', $quiz_attempt->get_id(), $quiz_attempt );
	}

	/**
	 * Delete a quiz attempt from the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $quiz_attempt Quiz attempt object.
	 * @param array $args   Array of args to pass.alert-danger
	 */
	public function delete( Model &$quiz_attempt, $args = array() ) {
		global $wpdb;

		if ( $quiz_attempt->get_id() ) {
			do_action( 'masteriyo_before_delete_quiz_attempts', $quiz_attempt->get_id() );

			$wpdb->delete( $wpdb->base_prefix . 'masteriyo_quiz_attempts', array( 'attempt_id' => $quiz_attempt->get_id() ) );

			do_action( 'masteriyo_after_delete_quiz_attempt', $quiz_attempt->get_id() );

		}
	}

	/**
	 * Fetch quiz attempts.
	 *
	 * @since 0.1.0
	 *
	 * @param array $query_vars Query vars.
	 * @return QuizAttempt[]
	 */
	public function query( $query_vars ) {
		global $wpdb;

		$search_criteria = array();
		$sql[]           = "SELECT * FROM {$wpdb->base_prefix}masteriyo_quiz_attempts";

		// Construct where clause part.
		if ( ! empty( $query_vars['course_id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'course_id = %d', $query_vars['course_id'] );
		}

		if ( ! empty( $query_vars['quiz_id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'item_id = %d', $query_vars['quiz_id'] );
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

		$ids = wp_list_pluck( $quiz_attempt, 'attempt_id' );

		return array_filter( array_map( 'masteriyo_get_quiz_attempt', $ids ) );
	}

}
