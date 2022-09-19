<?php
/**
 * Course progress item type enums.
 *
 * @since x.x.x
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Course progress item type enum class.
 *
 * @since x.x.x
 */
class CourseProgressItemType {
	/**
	 * Course progress lesson item type.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const LESSON = 'lesson';

	/**
	 * Course progress quiz item type.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const QUIZ = 'quiz';


	/**
	 * Return all course progress item types.
	 *
	 * @since x.x.x
	 *
	 * @return array
	 */
	public static function all() {
		return array_unique(
			/**
			 * Filters course progress item types.
			 *
			 * @since x.x.x
			 *
			 * @param string[] $item_types Course progress item types.
			 */
			apply_filters(
				'masteriyo_course_progress_item_types',
				array(
					self::LESSON,
					self::QUIZ,
				)
			)
		);
	}

	/**
	 * Return post type from item type.
	 *
	 * @since x.x.x
	 *
	 * @param string $type Course progress item type.
	 *
	 * @return string.
	 */
	public function to_post_type( $type ) {
		if ( ! masteriyo_starts_with( $type, 'mto-' ) ) {
			$type = 'mto-' . $type;
		}

		return $type;
	}
}
