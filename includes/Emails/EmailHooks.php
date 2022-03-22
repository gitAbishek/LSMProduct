<?php
/**
 * EmailHooks class.
 *
 * @package Masteriyo\Emails
 *
 * @since 1.0.0
 */

namespace Masteriyo\Emails;

use Masteriyo\Enums\OrderStatus;

defined( 'ABSPATH' ) || exit;

/**
 * EmailHooks Class.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Emails
 */
class EmailHooks {
	/**
	 * Register email hooks.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		add_action( 'masteriyo_created_customer', array( self::class, 'trigger_new_user_email' ) );
		add_action( 'masteriyo_new_order', array( self::class, 'trigger_new_order_email' ), 10, 2 );
		add_action( 'masteriyo_order_status_changed', array( self::class, 'trigger_order_status_change_email' ), 10, 3 );
	}

	/**
	 * Trigger new user email.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $user
	 */
	public static function trigger_new_user_email( $user ) {
		masteriyo( 'email.customer-new-account' )->trigger( $user );
	}

	/**
	 * Trigger new order email.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $id
	 */
	public static function trigger_new_order_email( $id, $order ) {
		masteriyo( 'email.new-order' )->trigger( $order );
	}

	/**
	 * Trigger emails on order status change.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $id
	 * @param string $old_status
	 * @param string $new_status
	 */
	public static function trigger_order_status_change_email( $id, $old_status, $new_status ) {
		$email_handlers = array(
			OrderStatus::CANCELLED => 'email.order-cancelled',
			OrderStatus::COMPLETED => 'email.order-completed',
			OrderStatus::ON_HOLD   => 'email.order-onhold',
		);

		if ( ! isset( $email_handlers[ $new_status ] ) ) {
			return;
		}

		masteriyo( $email_handlers[ $new_status ] )->trigger( $id );
	}
}
