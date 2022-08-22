<?php
/**
 * Course price type enums.
 *
 * @since x.x.x
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Course price type enum class.
 *
 * @since x.x.x
 */
class CoursePriceType {
	/**
	 * Free price type.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const FREE = 'free';

	/**
	 * Paid price type.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const PAID = 'paid';

	/**
	 * Get all course price types.
	 *
	 * @since x.x.x
	 * @static
	 *
	 * @return array
	 */
	public static function all() {
		/**
		 * Filters course price types.
		 *
		 * @since x.x.x
		 *
		 * @param string[] $price_types Course price types.
		 */
		$types = apply_filters(
			'masteriyo_course_price_types',
			array(
				self::FREE,
				self::PAID,
			)
		);

		return array_unique( $types );
	}
}
