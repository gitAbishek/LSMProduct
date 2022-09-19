<?php
/**
 * Course progress post type enums.
 *
 * @since x.x.x
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Course progress post type enum class.
 *
 * @since x.x.x
 */
class CourseProgressPostType {
	/**
	 * Course progress lesson post type.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const LESSON = 'mto-lesson';

	/**
	 * Course progress quiz post type.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const QUIZ = 'mto-quiz';

	/**
	 * Return all course progress post types.
	 *
	 * @since x.x.x
	 *
	 * @return array
	 */
	public static function all() {
		return array_unique(
			/**
			 * Filters course progress post types.
			 *
			 * @since x.x.x
			 *
			 * @param string[] $post_types Course progress post types.
			 */
			apply_filters(
				'masteriyo_course_progress_post_types',
				array(
					self::LESSON,
					self::QUIZ,
				)
			)
		);
	}

	/**
	 * Return item type from post type.
	 *
	 * @since x.x.x
	 *
	 * @param string $type Post type.
	 * @return string
	 */
	public function to_item_type( $type ) {
		if ( masteriyo_starts_with( $type, 'mto-' ) ) {
			$type = str_replace( 'mto-', '', $type );
		}

		return $type;
	}
}
