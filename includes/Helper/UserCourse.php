<?php
/**
 * User course functions.
 *
 * @since 0.1.0
 * @version 0.1.0
 * @package ThemeGrill\Masteriyo\Helper
 */

/**
 * Get user course.
 *
 * @since 0.1.0
 *
 * @param int $user_course_id User course ID.
 * @return ThemeGrill\Masteriyo\Models\UserCourse|NULL
 */
function masteriyo_get_user_course( $user_course_id ) {
	try {
		$user_course = masteriyo( 'user-course' );
		$user_course->set_id( $user_course_id );

		$user_course_repo = masteriyo( 'user-course.store' );
		$user_course_repo->read( $user_course );

		return $user_course;
	} catch ( \Except $e ) {
		return null;
	}
}