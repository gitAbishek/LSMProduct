<?php
/**
 * Notification level enums.
 *
 * @since x.x.x
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Notification level enum class.
 *
 * @since x.x.x
 */
class NotificationLevel {
	/**
	 * Notification success level.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const SUCCESS = 'success';

	/**
	 * Notification error level.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const ERROR = 'error';

	/**
	 * Notification warning level.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const WARNING = 'warning';

	/**
	 * Notification information level.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const INFO = 'info';
}
