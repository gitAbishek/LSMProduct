<?php
/**
 * User Course status enums.
 *
 * @since x.x.x
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * User course status enum class.
 *
 * @since x.x.x
 */
class UserCourseStatus {
	/**
	 * User course any status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const ANY = 'any';

	/**
	 * User course enrolled status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const ENROLLED = 'enrolled';

	/**
	 * User course active status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const ACTIVE = 'active';

	/**
	 * User course inactive status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const INACTIVE = 'inactive';

	/**
	 * Return user course statuses.
	 *
	 * @since x.x.x
	 *
	 * @return array
	 */
	public static function all() {
		/**
		 * Filters statuses for user course.
		 *
		 * @since 1.0.0
		 *
		 * @param array $statuses The statuses for user course.
		 */
		$statuses = apply_filters(
			'masteriyo_user_course_statuses',
			array(
				self::ACTIVE,
				self::INACTIVE,
			)
		);

		return array_unique( $statuses );
	}
}
