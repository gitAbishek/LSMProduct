<?php
/**
 * User course functions.
 *
 * @since 0.1.0
 * @version 0.1.0
 * @package ThemeGrill\Masteriyo\Helper
 */

use ThemeGrill\Masteriyo\Query\UserCourseQuery;

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

/**
 * Get list of status for user course.
 *
 * @since 0.1.0
 *
 * @return array
 */
function masteriyo_get_user_course_statuses() {
	$statuses = array(
		'active'   => array(
			'label' => _x( 'Active.', 'User Course status', 'masteriyo' ),
		),
		'enrolled' => array(
			'label' => _x( 'Enrolled.', 'User Course status', 'masteriyo' ),
		),
	);

	return apply_filters( 'masteriyo_user_course_statuses', $statuses );
}

/**
 * Count enrolled users in a course.
 *
 * @since 0.1.0
 *
 * @return integer
 */
function masteriyo_count_enrolled_users( $course_id ) {
	$query = new UserCourseQuery(
		array(
			'course_id' => $course_id,
			'status'    => 'enrolled',
		)
	);
	$enrolled_users_count = count( $query->get_user_courses() );

	return apply_filters( 'masteriyo_count_enrolled_users', $enrolled_users_count, $course_id, $query );
}
