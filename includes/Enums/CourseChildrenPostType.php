<?php
/**
 * Course children post type enums.
 *
 * @since x.x.x
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Course children post type enum class.
 *
 * @since x.x.x
 */
class CourseChildrenPostType {
	/**
	 * Course children section post type.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const SECTION = 'mto-section';

	/**
	 * Course children lesson post type.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const LESSON = 'mto-lesson';

	/**
	 * Course children quiz post type.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const QUIZ = 'mto-quiz';

	/**
	 * Return all course children post types.
	 *
	 * @since x.x.x
	 *
	 * @return array
	 */
	public static function all() {
		return array_unique(
			/**
			 * Filters course children post types.
			 *
			 * @since x.x.x
			 *
			 * @param string[] $post_types Course children post types.
			 */
			apply_filters(
				'masteriyo_course_children_post_types',
				array(
					self::SECTION,
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
