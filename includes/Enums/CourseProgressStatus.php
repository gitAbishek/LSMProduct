<?php
/**
 * Course progress status enums.
 *
 * @since x.x.x
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Course progress status enum class.
 *
 * @since x.x.x
 */
class CourseProgressStatus {
	/**
	 * Course progress started status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const STARTED = 'started';

	/**
	 * Course progress progress status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const PROGRESS = 'progress';

	/**
	 * Course progress completed status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const COMPLETED = 'completed';

	/**
	 * Return course progress statuses.
	 *
	 * @since x.x.x
	 *
	 * @return array
	 */
	public static function all() {
		return array_unique(
			apply_filters(
				'masteriyo_course_progress_statuses',
				array(
					self::STARTED,
					self::PROGRESS,
					self::COMPLETED,
				)
			)
		);
	}
}
