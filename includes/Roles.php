<?php
/**
 * Roles class.
 *
 * @since 1.0.0
 */

namespace Masteriyo;

class Roles {
	/**
	 * Return all roles.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_all() {
		/**
		 * Filters the user roles.
		 *
		 * @since 1.0.0
		 *
		 * @param array $roles List of roles.
		 */
		return apply_filters(
			'masteriyo_user_roles',
			array(
				// 'masteriyo_manager'    => array(
				// 	'display_name' => esc_html__( 'Masteriyo Manager', 'masteriyo' ),
				// 	'capabilities' => Capabilities::get_manager_capabilities(),
				// ),
				'masteriyo_instructor' => array(
					'display_name' => esc_html__( 'Masteriyo Instructor', 'masteriyo' ),
					'capabilities' => Capabilities::get_instructor_capabilities(),
				),
				'masteriyo_student'    => array(
					'display_name' => esc_html__( 'Masteriyo Student', 'masteriyo' ),
					'capabilities' => Capabilities::get_student_capabilities(),
				),
			)
		);
	}
}
