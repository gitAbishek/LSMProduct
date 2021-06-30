<?php
/**
 * Masteriyo Quiz Functions
 *
 * Functions for quiz specific things.
 *
 * @package Masteriyo\Functions
 * @version 0.1.0
 */

/**
 * Get quiz question.
 *
 * @since  0.1.0
 *
 * @param integer $quiz_id Quiz ID.
 * @param string  $by String e.g 'post_parent.
 * @return object
 */
function masteriyo_get_quiz_questions( $quiz_id, $by ) {

	$args = array(
		'post_type'     => 'question',
		'post_per_page' => -1,
		'post_status'   => 'publish',
	);

	switch ( $by ) {

		case 'post_parent':
			$args[ $by ] = $quiz_id;
			break;

		default:
			break;
	}

	$query = new \WP_Query( $args );

	return $query;

}

/**
 * @param int $post_id
 *
 * @return bool|false|int
 *
 * Get current post id or given post id
 *
 * @since 0.1.0
 */
function get_post_id( $post_id = 0 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
		if ( ! $post_id ) {
			return false;
		}
	}

	return $post_id;
}

/**
 * Determine if there is any started quiz exists.
 *
 * @since 0.1.0
 *
 * @param int $quiz_id
 *
 * @return array|null|object|void
 */
function masteriyo_is_quiz_started( $quiz_id = 0 ) {
	global $wpdb;

	$quiz_id = get_post_id( $quiz_id );
	$user_id = get_current_user_id();

	$is_started = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT *
			FROM 	{$wpdb->prefix}masteriyo_quiz_attempts
			WHERE 	user_id =  %d
					AND quiz_id = %d
					AND attempt_status = %s;
			",
			$user_id,
			$quiz_id,
			'attempt_started'
		)
	);

	return $is_started;
}

/**
 * Get quiz attempt data according to attempt id and after attempt ended.
 *
 * @since 0.1.0
 *
 * @param int $quiz Quiz ID.
 * @param int $attempt_id User Attempt ID.
 */
function masteriyo_get_quiz_attempt_ended_data( $quiz_id = 0, $attempt_id ) {
	global $wpdb;

	$quiz_id = get_post_id( $quiz_id );
	$user_id = get_current_user_id();

	$attempt_data = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT *
			FROM 	{$wpdb->prefix}masteriyo_quiz_attempts
			WHERE 	attempt_id = %d
					AND user_id =  %d
					AND quiz_id = %d
					AND attempt_status = %s;
			",
			$attempt_id,
			$user_id,
			$quiz_id,
			'attempt_ended'
		)
	);

	return $attempt_data;
}

/**
 *
 * Get all of the attempts by an user of a quiz.
 *
 * @since 0.1.0
 *
 * @param int $quiz_id
 * @param int $user_id
 *
 * @return array|bool|null|object
 */

function masteriyo_get_all_quiz_attempts( $quiz_id = 0, $user_id = 0 ) {
	global $wpdb;

	$quiz_id = get_post_id( $quiz_id );
	$user_id = $user_id ? $user_id : get_current_user_id();

	$attempts = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT *
			FROM 	{$wpdb->prefix}masteriyo_quiz_attempts
			WHERE 	quiz_id = %d
					AND user_id = %d;
			",
			$quiz_id,
			$user_id
		)
	);

	if ( is_array( $attempts ) && count( $attempts ) ) {
		return $attempts;
	}

	return false;
}

/**
 * Fetch quiz attempts.
 *
 * @since 0.1.0
 *
 * @param array $query_vars Query vars.
 * @return QuizAttempt[]
 */
function masteriyo_get_quiz_attempts( $query_vars ) {
	global $wpdb;

	$search_criteria = array();
	$sql[]           = "SELECT * FROM {$wpdb->base_prefix}masteriyo_quiz_attempts";

	// Construct where clause part.
	if ( ! empty( $query_vars['attempt_id'] ) ) {
		$search_criteria[] = $wpdb->prepare( 'attempt_id = %d', $query_vars['attempt_id'] );
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
