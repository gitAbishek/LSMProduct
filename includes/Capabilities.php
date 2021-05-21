<?php
/**
 * Capabilities class.
 *
 * @since 0.1.0
 */

namespace ThemeGrill\Masteriyo;

/**
 * Capabilities class.
 */
class Capabilities {
	/**
	 * Get masteriyo student capabilites.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public static function get_student_capabilities() {
		$caps_info = apply_filters(
			'masteriyo_manager_core_caps_info',
			array(
				'course'            => array( 'read' ),
				'section'           => array( 'read' ),
				'lesson'            => array( 'read' ),
				'faq'               => array( 'read' ),
				'quiz'              => array( 'read' ),
				'question'          => array( 'read' ),
				'course_review'     => array( 'publish', 'read', 'edit_plural', 'delete_plural' ),
				'order'             => array( 'publish', 'read', 'delete' ),
				'users'             => array( 'read' ),
			)
		);
		$core_capabilities = self::map_caps( $caps_info );

		return apply_filters(
			'masteriyo_get_student_capabilities',
			array_merge(
				array_fill_keys( array_unique( $core_capabilities ), true ),
				get_role( 'contributor' )->capabilities
			)
		);
	}

	/**
	 * Get masteriyo instructor capabilites.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public static function get_instructor_capabilities() {
		$caps_info = apply_filters(
			'masteriyo_instructor_core_caps_info',
			array(
				'course'            => array( 'publish', 'read', 'edit', 'delete' ),
				'section'           => array( 'publish', 'read', 'edit', 'delete' ),
				'lesson'            => array( 'publish', 'read', 'edit', 'delete' ),
				'faq'               => array( 'publish', 'read', 'edit', 'delete' ),
				'quiz'              => array( 'publish', 'read', 'edit', 'delete' ),
				'question'          => array( 'publish', 'read', 'edit', 'delete' ),
				'course_review'     => array( 'read', 'edit_plural', 'delete_plural' ),
				'users'             => array( 'read' ),
			)
		);
		$core_capabilities = self::map_caps( $caps_info );

		return apply_filters(
			'masteriyo_get_instructor_capabilities',
			array_merge(
				array_fill_keys( array_unique( $core_capabilities ), true ),
				get_role( 'author' )->capabilities
			)
		);
	}

	/**
	 * Get masteriyo manager capabilites.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public static function get_manager_capabilities() {
		$caps_info = apply_filters(
			'masteriyo_manager_core_caps_info',
			array(
				'course'            => array( 'publish', 'read', 'edit', 'delete', 'delete_others' ),
				'section'           => array( 'publish', 'read', 'edit', 'delete', 'delete_others' ),
				'lesson'            => array( 'publish', 'read', 'edit', 'delete', 'delete_others' ),
				'faq'               => array( 'publish', 'read', 'edit', 'delete', 'delete_others' ),
				'quiz'              => array( 'publish', 'read', 'edit', 'delete', 'delete_others' ),
				'question'          => array( 'publish', 'read', 'read_others', 'edit', 'delete', 'delete_others' ),
				'order'             => array( 'read', 'read_others', 'edit', 'edit_others', 'delete', 'delete_others' ),
				'masteriyo_setting' => array( 'manage_singular' ),
				'course_review'     => array( 'read', 'edit_plural', 'edit_others', 'delete_plural', 'delete_others' ),
				'users'             => array( 'read' ),
			)
		);
		$core_capabilities = self::map_caps( $caps_info );

		return apply_filters(
			'masteriyo_get_manager_capabilities',
			array_merge(
				array_fill_keys( array_unique( $core_capabilities ), true ),
				get_role( 'editor' )->capabilities
			)
		);
	}

	/**
	 * Get admin's core capabilites.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public static function get_admin_capabilities() {
		$caps_info = apply_filters(
			'masteriyo_admin_core_caps_info',
			array(
				'course'            => array( 'read', 'delete', 'delete_others' ),
				'section'           => array( 'read', 'delete', 'delete_others' ),
				'lesson'            => array( 'read', 'delete', 'delete_others' ),
				'faq'               => array( 'read', 'delete', 'delete_others' ),
				'quiz'              => array( 'read', 'delete', 'delete_others' ),
				'question'          => array( 'read', 'delete', 'delete_others' ),
				'order'             => array( 'read', 'read_others', 'edit', 'edit_others', 'delete', 'delete_others' ),
				'masteriyo_setting' => array( 'manage_singular' ),
				'course_review'     => array( 'read', 'edit_plural', 'edit_others', 'delete_plural', 'delete_others' ),
				'users'             => array( 'create', 'read', 'edit', 'delete' ),
			)
		);
		$core_capabilities = self::map_caps( $caps_info );

		return apply_filters(
			'masteriyo_get_administrator_capabilities',
			array_fill_keys( array_unique( $core_capabilities ), true )
		);
	}

	/**
	 * Get capability types.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	protected static function get_capability_types() {
		$cap_types = array(
			'course'            => array( 'course', 'courses' ),
			'section'           => array( 'section', 'sections' ),
			'lesson'            => array( 'lesson', 'lessons' ),
			'faq'               => array( 'faq', 'faqs' ),
			'question'          => array( 'question', 'questions' ),
			'quiz'              => array( 'quiz', 'quizzes' ),
			'order'             => array( 'order', 'orders' ),
			'masteriyo_setting' => array( 'masteriyo_setting' ),
			'course_review'     => array( 'course_review', 'course_reviews' ),
			'users'             => array( 'user', 'users' ),
		);

		return apply_filters( 'masteriyo_cap_types', $cap_types );
	}

	/**
	 * Map cap types to a list of capabilities.
	 *
	 * @since 0.1.0
	 *
	 * @param array $caps_info
	 *
	 * @return array
	 */
	protected static function map_caps( $caps_info ) {
		$caps = array();
		$all_cap_types = self::get_capability_types();

		foreach ( $caps_info as $cap_type => $permissions ) {
			if ( empty( $all_cap_types[ $cap_type ] ) ) {
				continue;
			}
			$cap_types = $all_cap_types[ $cap_type ];
			$singular_type = $cap_types[0];
			$plural_type = isset( $cap_types[1] ) ? $cap_types[1] : $singular_type;

			foreach ( $permissions as $permission_type ) {
				if ( 'edit' === $permission_type ) {
					$caps[] = "edit_{$singular_type}";
					$caps[] = "edit_{$plural_type}";
					$caps[] = "edit_published_{$plural_type}";
					continue;
				}
				if ( 'edit_plural' === $permission_type ) {
					$caps[] = "edit_{$plural_type}";
					continue;
				}
				if ( 'delete' === $permission_type ) {
					$caps[] = "delete_{$singular_type}";
					$caps[] = "delete_{$plural_type}";
					$caps[] = "delete_published_{$plural_type}";
					continue;
				}
				if ( 'delete_plural' === $permission_type ) {
					$caps[] = "delete_{$plural_type}";
					continue;
				}
				if ( 'manage_singular' === $permission_type ) {
					$caps[] = "manage_{$singular_type}";
					continue;
				}
				$caps[] = "{$permission_type}_{$plural_type}";
			}
		}
		return $caps;
	}
}
