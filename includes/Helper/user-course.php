<?php
/**
 * User course functions.
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package Masteriyo\Helper
 */

use Masteriyo\Query\UserCourseQuery;

/**
 * Get user course.
 *
 * @since 1.0.0
 *
 * @param int $user_course_id User course ID.
 * @return Masteriyo\Models\UserCourse|NULL
 */
function masteriyo_get_user_course( $user_course_id ) {
	try {
		$user_course = masteriyo( 'user-course' );
		$user_course->set_id( $user_course_id );

		$user_course_repo = masteriyo( 'user-course.store' );
		$user_course_repo->read( $user_course );

		return $user_course;
	} catch ( \Exception $e ) {
		return null;
	}
}

/**
 * Get list of status for user course.
 *
 * @since 1.0.0
 *
 * @return array
 */
function masteriyo_get_user_course_statuses() {
	$statuses = array(
		'active' => array(
			'label' => _x( 'Active', 'User Course status', 'masteriyo' ),
		),
	);

	return apply_filters( 'masteriyo_user_course_statuses', $statuses );
}

/**
 * Count enrolled users in a course.
 *
 * @since 1.0.0
 *
 * @return integer
 */
function masteriyo_count_enrolled_users( $course_id ) {
	$query                = new UserCourseQuery(
		array(
			'course_id' => $course_id,
			'status'    => array( 'active', 'enrolled' ),
		)
	);
	$enrolled_users_count = count( $query->get_user_courses() );

	return apply_filters( 'masteriyo_count_enrolled_users', $enrolled_users_count, $course_id, $query );
}

/**
 * Get the number of active courses.
 *
 * @since 1.0.0
 *
 * @param \Masteriyo\Models\User|int $user User.
 *
 * @return int
 */
function masteriyo_get_active_courses_count( $user ) {
	global $wpdb;

	$user_id = is_a( $user, 'Masteriyo\Models\User' ) ? $user->get_id() : $user;

	$count = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(*) FROM {$wpdb->prefix}masteriyo_user_activities
			WHERE user_id = %d AND activity_type = 'course_progress'
			AND ( activity_status = 'started' OR activity_status = 'progress' )  AND parent_id = 0",
			$user_id
		)
	);

	return $count;
}

/**
 * Get the number of user courses.
 *
 * @since 1.0.0
 *
 * @return int
 */
function masteriyo_get_user_courses_count_by_course( $course ) {
	global $wpdb;

	$course_id = is_a( $course, 'Masteriyo\Models\Course' ) ? $course->get_id() : $course;

	$count = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(*) FROM {$wpdb->prefix}masteriyo_user_items
			WHERE item_id = %d AND item_type = 'user_course'",
			$course_id
		)
	);

	return $count;
}
