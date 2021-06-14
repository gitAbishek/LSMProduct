<?php
/**
 * User activity functions.
 *
 * @since 0.1.0
 * @package ThemeGrill\Masteriyo\Helper
 */


/**
 * Get user activity.
 *
 * @since 0.1.0
 *
 * @param int $user_activity_id User activity ID.
 *
 * @return UserActivity
 */
function masteriyo_get_user_activity( $user_activity_id ) {
	$user_activity = masteriyo( 'user-activity' );
	$user_activity->set_id( $user_activity_id );

	$user_activity_repo = masteriyo( 'user-activity.store' );
	$user_activity_repo->read( $user_activity );

	return $user_activity;
}

/**
 * Get course progress.
 *
 * @since 0.1.0
 *
 * @param int $course_progress_id Course progress ID.
 *
 * @return UserActivity
 */
function masteriyo_get_course_progress( $course_progress_id ) {
	try {
		$course_progress = masteriyo( 'course-progress' );
		$course_progress->set_id( $course_progress_id );

		$course_progress_repo = masteriyo( 'course-progress.store' );
		$course_progress_repo->read( $course_progress );
	} catch ( \Exception $e ) {
		return null;
	}

	return $course_progress;
}
