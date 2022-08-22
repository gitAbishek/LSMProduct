<?php
/**
 * Course access mode enums.
 *
 * @since x.x.x
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Course access mode enum class.
 *
 * @since x.x.x
 */
class CourseAccessMode {
	/**
	 * Open access mode.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const OPEN = 'open';

	/**
	 * Need Registration access mode.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const NEED_REGISTRATION = 'need_registration';

	/**
	 * One time access mode.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const ONE_TIME = 'one_time';

	/**
	 * Recurring access mode.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const RECURRING = 'recurring';

	/**
	 * Close access mode.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const CLOSE = 'close';

	/**
	 * Get all course access modes.
	 *
	 * @since x.x.x
	 * @static
	 *
	 * @return array
	 */
	public static function all() {
		/**
		 * Filters course access modes.
		 *
		 * @since 1.0.0
		 *
		 * @param string[] $access_modes Course access modes.
		 */
		$modes = apply_filters(
			'masteriyo_course_access_modes',
			array(
				self::OPEN,
				self::NEED_REGISTRATION,
				self::ONE_TIME,
				self::RECURRING,
				self::CLOSE,
			)
		);

		return array_unique( $modes );
	}
}
