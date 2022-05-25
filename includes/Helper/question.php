<?php
/**
 * Question functions.
 *
 * @since 1.0.0
 */


/**
 * Get questions
 *
 * @since 1.0.0
 *
 * @param array $args Query arguments.
 *
 * @return Masteriyo\Models\Question[]
 */
function masteriyo_get_questions( $args = array() ) {
	$questions = masteriyo( 'query.questions' )->set_args( $args )->get_questions();

	/**
	 * Filters queried question objects.
	 *
	 * @since 1.0.0
	 *
	 * @param Masteriyo\Models\Question[] $questions Queried question objects.
	 * @param array $query_args Query args.
	 */
	return apply_filters( 'masteriyo_get_questions', $questions, $args );
}

/**
 * Get question.
 *
 * @since 1.0.0
 *
 * @param int|Masteriyo\Models\Question|WP_Post $question Question id or Question Model or Post.
 *
 * @return Masteriyo\Models\Question|null
 */
function masteriyo_get_question( $question ) {
	if ( is_int( $question ) ) {
		$id = $question;
	} else {
		$id = is_a( $question, '\WP_Post' ) ? $question->ID : $question->get_id();
	}

	$type           = get_post_meta( $id, '_type', true );
	$question_obj   = masteriyo( empty( $type ) ? 'question' : "question.{$type}" );
	$question_store = masteriyo( 'question.store' );

	if ( is_a( $question, 'Masteriyo\Models\Question' ) ) {
		$id = $question->get_id();
	} elseif ( is_a( $question, 'WP_Post' ) ) {
		$id = $question->ID;
	} else {
		$id = $question;
	}

	try {
		$id = absint( $id );
		$question_obj->set_id( $id );
		$question_store->read( $question_obj );
	} catch ( \Exception $e ) {
		return null;
	}

	/**
	 * Filters question object.
	 *
	 * @since 1.0.0
	 *
	 * @param Masteriyo\Models\Question|null $question_obj The question object.
	 * @param int|Masteriyo\Models\Question|WP_Post $question Question id or Question Model or Post.
	 */
	return apply_filters( 'masteriyo_get_question', $question_obj, $question );
}


/**
 * Get the number of questions of a quiz.
 *
 * @since 1.0.0
 *
 * @param int|Question|WP_Post $question Question id or Question Model or Post.
 *
 * @return int\WP_Error
 */
function masteriyo_get_questions_count_by_quiz( $quiz ) {
	global $wpdb;

	$quiz_obj = masteriyo_get_quiz( $quiz );

	// Bail early if there is error.
	if ( is_wp_error( $quiz_obj ) ) {
		return $quiz_obj;
	}

	$count = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(ID) FROM {$wpdb->prefix}posts WHERE post_type = %s AND post_parent = %d",
			'mto-question',
			$quiz_obj->get_id()
		)
	);

	return absint( $count );
}
