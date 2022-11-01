<?php
/**
 * Comment type enums.
 *
 * @since x.x.x
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

class CommentType {
	/**
	 * Course review type.
	 *
	 * @since x.x.x
	 *
	 * @var string
	 */
	const COURSE_REVIEW = 'mto_course_review';

	/**
	 * Order note type.
	 *
	 * @since x.x.x
	 *
	 * @var string
	 */
	const ORDER_NOTE = 'mto_order_note';

	/**
	 * Course Q&A type.
	 *
	 * @since x.x.x
	 *
	 * @var string
	 */
	const COURSE_QA = 'mto_course_qa';

	/**
	 * Get all comment types.
	 *
	 * @since x.x.x
	 * @static
	 *
	 * @return array
	 */
	public static function all() {
		/**
		 * Filters comment types.
		 *
		 * @since x.x.x
		 *
		 * @param string[] $comment_types
		 */
		$types = apply_filters(
			'masteriyo_comment_types',
			array(
				self::COURSE_REVIEW,
				self::ORDER_NOTE,
				self::COURSE_QA,
			)
		);

		return array_unique( $types );
	}
}
