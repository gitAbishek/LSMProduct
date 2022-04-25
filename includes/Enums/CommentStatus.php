<?php
/**
 * Comment status enums.
 *
 * @since 1.4.10
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Comment status enum class.
 *
 * @since 1.4.10
 */
class CommentStatus {
	/**
	 * Comment all status.
	 *
	 * @since 1.4.10
	 * @var string
	 */
	const ALL = 'all';

	/**
	 * Comment approved status.
	 *
	 * @since 1.4.10
	 * @var string
	 */
	const APPROVED = '1';

	/**
	 * Comment hold status.
	 *
	 * @since 1.4.10
	 * @var string
	 */
	const HOLD = '0';

	/**
	 * Comment masteriyo spam status.
	 *
	 * @since 1.4.10
	 * @var string
	 */
	const SPAM = 'spam';

	/**
	 * Comment trash status.
	 *
	 * @since 1.4.10
	 * @var string
	 */
	const TRASH = 'trash';

	/**
	 * Comment approved status in readable/string format.
	 *
	 * @since 1.4.10
	 * @var string
	 */
	const APPROVED_STR = 'approved';

	/**
	 * Comment hold status in readable/string format.
	 *
	 * @since 1.4.10
	 * @var string
	 */
	const HOLD_STR = 'hold';

	/**
	 * Return all the comment statuses.
	 *
	 * @since 1.4.10
	 *
	 * @return array
	 */
	public static function all() {
		return array_unique(
			apply_filters(
				'masteriyo_comment_statuses',
				array(
					self::HOLD,
					self::APPROVED,
					self::SPAM,
					self::TRASH,
				)
			)
		);
	}

	/**
	 * Return all the comment statuses in readable format.
	 *
	 * @since 1.4.10
	 *
	 * @return array
	 */
	public static function readable() {
		return array_unique(
			apply_filters(
				'masteriyo_readable_comment_statuses',
				array(
					self::HOLD_STR,
					self::APPROVED_STR,
					self::SPAM,
					self::TRASH,
				)
			)
		);
	}
}
