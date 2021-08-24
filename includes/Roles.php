<?php
/**
 * Roles class.
 *
 * @since 0.1.0
 */

namespace Masteriyo;

class Roles {
	/**
	 * Retun all roles.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public static function get_all() {
		return apply_filters(
			'masteriyo_user_roles',
			array(
				'masteriyo_manager'    => array(
					'display_name' => esc_html__( 'Masteriyo Manager', 'masteriyo' ),
					'capabilities' => Capabilities::get_manager_capabilities(),
				),
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
