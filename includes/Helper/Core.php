<?php
/**
 * Core functions.
 *
 * @since 0.1.0
 */

/**
 * Get course.
 *
 * @since 0.1.0
 *
 * @param int|Course|WP_Post $course Course id or Course Model or Post.
 * @return Course|null
 */
function mto_get_course( $course ) {
	$course_obj   = masteriyo( 'course' );
	$course_store = masteriyo( 'course.store' );

	if ( is_a( $course, 'ThemeGrill\Masteriyo\Models\Course' ) ) {
		$id = $course->get_id();
	} elseif ( is_a( $course, 'ThemeGrill\Masteriyo\Models\Course' ) ) {
		$id = $course->ID;
	} else {
		$id = $course;
	}

	$id   = absint( $id );
	$post = get_post( $id );

	if ( is_null( $post ) ) {
		return null;
	}

	$course_obj->set_id( $id );
	$course_store->read( $course_obj );
	return $course_obj;
}

/**
 * Get lesson.
 *
 * @since 0.1.0
 *
 * @param int|Lesson|WP_Post $lesson Lesson id or Lesson Model or Post.
 * @return Lesson|null
 */
function mto_get_lesson( $lesson ) {
	$lesson_obj   = masteriyo( 'lesson' );
	$lesson_store = masteriyo( 'lesson.store' );

	if ( is_a( $lesson, 'ThemeGrill\Masteriyo\Models\Lesson' ) ) {
		$id = $lesson->get_id();
	} elseif ( is_a( $lesson, 'ThemeGrill\Masteriyo\Models\Lesson' ) ) {
		$id = $lesson->ID;
	} else {
		$id = $lesson;
	}

	$id   = absint( $id );
	$post = get_post( $id );

	if ( is_null( $post ) ) {
		return null;
	}

	$lesson_obj->set_id( $id );
	$lesson_store->read( $lesson_obj );
	return $lesson_obj;
}

/**
 * Get section.
 *
 * @since 0.1.0
 *
 * @param int|Section|WP_Post $section Section id or Section Model or Post.
 * @return Section|null
 */
function mto_get_section( $section ) {
	$section_obj   = masteriyo( 'section' );
	$section_store = masteriyo( 'section.store' );

	if ( is_a( $section, 'ThemeGrill\Masteriyo\Models\Section' ) ) {
		$id = $section->get_id();
	} elseif ( is_a( $section, 'ThemeGrill\Masteriyo\Models\Section' ) ) {
		$id = $section->ID;
	} else {
		$id = $section;
	}

	$id   = absint( $id );
	$post = get_post( $id );

	if ( is_null( $post ) ) {
		return null;
	}

	$section_obj->set_id( $id );
	$section_store->read( $section_obj );
	return $section_obj;
}

/**
 * Get quiz.
 *
 * @since 0.1.0
 *
 * @param int|Quiz|WP_Post $quiz Quiz id or Quiz Model or Post.
 * @return Quiz|null
 */
function mto_get_quiz( $quiz ) {
	$quiz_obj   = masteriyo( 'quiz' );
	$quiz_store = masteriyo( 'quiz.store' );

	if ( is_a( $quiz, 'ThemeGrill\Masteriyo\Models\Quiz' ) ) {
		$id = $quiz->get_id();
	} elseif ( is_a( $quiz, 'ThemeGrill\Masteriyo\Models\Quiz' ) ) {
		$id = $quiz->ID;
	} else {
		$id = $quiz;
	}

	$id   = absint( $id );
	$post = get_post( $id );

	if ( is_null( $post ) ) {
		return null;
	}

	$quiz_obj->set_id( $id );
	$quiz_store->read( $quiz_obj );
	return $quiz_obj;
}

/**
 * Get order.
 *
 * @since 0.1.0
 *
 * @param int|Order|WP_Post $order Order id or Order Model or Post.
 * @return Order|null
 */
function mto_get_order( $order ) {
	$order_obj   = masteriyo( 'order' );
	$order_store = masteriyo( 'order.store' );

	if ( is_a( $order, 'ThemeGrill\Masteriyo\Models\Order' ) ) {
		$id = $order->get_id();
	} elseif ( is_a( $order, 'ThemeGrill\Masteriyo\Models\Order' ) ) {
		$id = $order->ID;
	} else {
		$id = $order;
	}

	$id   = absint( $id );
	$post = get_post( $id );

	if ( is_null( $post ) ) {
		return null;
	}

	$order_obj->set_id( $id );
	$order_store->read( $order_obj );
	return $order_obj;
}

/**
 * Get question.
 *
 * @since 0.1.0
 *
 * @param int|Question|WP_Post $question Question id or Question Model or Post.
 * @return Question|null
 */
function mto_get_question( $question ) {
	$question_obj   = masteriyo( 'question' );
	$question_store = masteriyo( 'question.store' );

	if ( is_a( $question, 'ThemeGrill\Masteriyo\Models\Question' ) ) {
		$id = $question->get_id();
	} elseif ( is_a( $question, 'ThemeGrill\Masteriyo\Models\Question' ) ) {
		$id = $question->ID;
	} else {
		$id = $question;
	}

	$id   = absint( $id );
	$post = get_post( $id );

	if ( is_null( $post ) ) {
		return null;
	}

	$question_obj->set_id( $id );
	$question_store->read( $question_obj );
	return $question_obj;
}
