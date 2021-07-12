<?php
/**
 * EmailHooks class.
 *
 * @package ThemeGrill\Masteriyo\Emails
 *
 * @since 0.1.0
 */

namespace ThemeGrill\Masteriyo\Emails;

defined( 'ABSPATH' ) || exit;

/**
 * EmailHooks Class.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Emails
 */
class EmailHooks {
	/**
	 * Register email hooks.
	 *
	 * @since 0.1.0
	 */
	public static function init() {
		add_action( 'masteriyo_new_order', array( self::class, 'trigger_new_order_email' ) );
		add_action( 'masteriyo_order_status_changed', array( self::class, 'trigger_order_status_change_email' ), 10, 3 );
	}

	/**
	 * Trigger new order email.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $id
	 */
	public static function trigger_new_order_email( $id ) {
		masteriyo('email.new-order')->trigger( $id );
	}

	/**
	 * Trigger emails on order status change.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $id
	 * @param string $old_status
	 * @param string $new_status
	 */
	public static function trigger_order_status_change_email( $id, $old_status, $new_status ) {
		$email_handlers = array(
			'cancelled'  => 'email.order-cancelled',
			'completed'  => 'email.order-completed',
			'on-hold'    => 'email.order-onhold',
			'processing' => 'email.order-processing',
		);

		if ( ! isset( $email_handlers[ $new_status ] ) ) {
			return;
		}

		masteriyo( $email_handlers[ $new_status ] )->trigger( $id );
	}
}
