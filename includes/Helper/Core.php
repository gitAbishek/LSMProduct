<?php
/**
 * Core functions.
 *
 * @since 0.1.0
 */

/**
 * Get course id.
 *
 * @param int $course_id Course id.
 * @return void
 */
function masteriyo_get_course( $course_id ) {
	global $masteriyo_container;

	$course_repo = $masteriyo_container->get( 'course_repository' );
	$course      = $masteriyo_container->get( 'course' );
	$course->set_id( $course_id );
	$course_repo->read( $course );

	return $course;
}
