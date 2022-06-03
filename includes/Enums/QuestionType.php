<?php
/**
 * Question type enums.
 *
 * @since x.x.x
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Question type enum class.
 *
 * @since x.x.x
 */
class QuestionType {
	/**
	 * True False question type.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const TRUE_FALSE = 'true-false';

	/**
	 * Single Choice question type.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const SINGLE_CHOICE = 'single-choice';

	/**
	 * Multiple Choice question type.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const MULTIPLE_CHOICE = 'multiple-choice';

	/**
	 * Get all question types.
	 *
	 * @since x.x.x
	 * @static
	 *
	 * @return array
	 */
	public static function all() {
		/**
		 * Filter question types.
		 *
		 * @since 1.0.0
		 * @param string[] $types Question types.
		 */
		$types = apply_filters(
			'masteriyo_question_types',
			array(
				self::TRUE_FALSE,
				self::SINGLE_CHOICE,
				self::MULTIPLE_CHOICE,
			)
		);

		return array_unique( $types );
	}
}
