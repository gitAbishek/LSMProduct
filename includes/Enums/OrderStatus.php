<?php
/**
 * Order status enums.
 *
 * @since x.x.x
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Order status enum class.
 *
 * @since x.x.x
 */
class OrderStatus extends PostStatus {
	/**
	 * Order processing status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const PROCESSING = 'processing';

	/**
	 * Order pending status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const PENDING = 'pending';

	/**
	 * Order on-hold status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const ON_HOLD = 'on-hold';

	/**
	 * Order completed status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const COMPLETED = 'completed';

	/**
	 * Order masteriyo cancelled status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const CANCELLED = 'cancelled';

	/**
	 * Order refunded status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const REFUNDED = 'refunded';

	/**
	 * Order failed status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const FAILED = 'failed';

	/**
	 * Return all order statuses.
	 *
	 * @since x.x.x
	 *
	 * @return array
	 */
	public static function all() {
		return array_unique(
			apply_filters(
				'masteriyo_order_statuses',
				array(
					self::PROCESSING,
					self::PENDING,
					self::ON_HOLD,
					self::COMPLETED,
					self::CANCELLED,
					self::FAILED,
					self::REFUNDED,
				)
			)
		);
	}
}
