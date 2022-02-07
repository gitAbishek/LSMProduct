<?php
/**
 * Notification status enums.
 *
 * @since x.x.x
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Notification status enum class.
 *
 * @since x.x.x
 */
class NotificationStatus {
	/**
	 * Notification read status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const READ = 'read';

	/**
	 * Notification unread status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const UNREAD = 'unread';

	/**
	 * Return notification statuses.
	 *
	 * @since x.x.x
	 *
	 * @return array
	 */
	public static function all() {
		return array_unique(
			apply_filters(
				'masteriyo_notification_statuses',
				array(
					self::READ,
					self::UNREAD,
				)
			)
		);
	}
}
