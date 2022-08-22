<?php
/**
 * Depreciated functions.
 *
 * @since x.x.x
 */

/**
 * Get masteriyo access modes.
 *
 * @since 1.0.0
 * @deprecated x.x.x
 * @return string
 */
function masteriyo_get_course_access_modes() {
	masteriyo_deprecated_function( 'masteriyo_get_course_access_modes', 'x.x.x', 'CourseAccessMode:all()' );

	/**
	 * Filters course access modes.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $access_modes Course access modes.
	 */
	return apply_filters(
		'masteriyo_course_access_modes',
		array(
			'open',
			'need_registration',
			'one_time',
			'recurring',
			'close',
		)
	);
}
