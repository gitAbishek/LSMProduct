<?php
/**
 * Course progress functions.
 *
 * @since 0.1.0
 * @package ThemeGrill\Masteriyo\Helper
 */


/**
 * Get course progress.
 *
 * @since 0.1.0
 *
 * @param ThemeGrill\Masteriyo\Models\CourseProgress|int $course_progress_id Course progress ID.
 *
 * @return ThemeGrill\Masteriyo\Models\CourseProgress\WP_Error
 */
function masteriyo_get_course_progress( $course_progress ) {
	if ( is_a( $course_progress, 'ThemeGrill\Masteriyo\Database\Model' ) ) {
		$id = $course_progress->get_id();
	} else {
		$id = absint( $course_progress );
	}

	try {
		$course_progress_obj = masteriyo( 'course-progress' );
		$course_progress_obj->set_id( $id );
		$course_progress_obj_repo = masteriyo( 'course-progress.store' );
		$course_progress_obj_repo->read( $course_progress_obj );

		return $course_progress_obj;
	} catch ( \ModelException $e ) {
		$course_progress_obj = new \WP_Error( $e->getErrorCode(), $e->getMessage(), $e->getErrorData() );
	}

	return apply_filters( 'masteriyo_get_course_progress', $course_progress_obj, $course_progress );
}

/**
 * Get course progress item.
 *
 * @since 0.1.0
 *
 * @param int $course_progress_item Course progress ID.
 *
 * @return ThemeGrill\Masteriyo\Models\CourseProgress|WP_Error
 */
function masteriyo_get_course_progress_item( $course_progress_item ) {
	if ( is_a( $course_progress_item, 'ThemeGrill\Masteriyo\Database\Model' ) ) {
		$item_id = $course_progress_item->get_id();
	} else {
		$item_id = (int) $course_progress_item;
	}

	try {
		$item = masteriyo( 'course-progress-item' );
		$item->set_id( $item_id );

		$item_repo = masteriyo( 'course-progress-item.store' );
		$item_repo->read( $item );

		return $item;
	} catch ( \ModelException $e ) {
		$item = new \WP_Error( $e->getCode(), $e->getMessage(), $e->getErrorData() );
	}

	return apply_filters( 'masteriyo_get_course_progress_item', $item, $course_progress_item );
}
