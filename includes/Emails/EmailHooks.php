<?php
/**
 * EmailHooks class.
 *
 * @package Masteriyo\Emails
 *
 * @since 0.1.0
 */

namespace Masteriyo\Emails;

defined( 'ABSPATH' ) || exit;

/**
 * EmailHooks Class.
 *
 * @since 0.1.0
 *
 * @package Masteriyo\Emails
 */
class EmailHooks {
	/**
	 * Register email hooks.
	 *
	 * @since 0.1.0
	 */
	public static function init() {
		add_action( 'masteriyo_new_order', array( self::class, 'trigger_new_order_email' ), 10, 2 );
		add_action( 'masteriyo_order_status_changed', array( self::class, 'trigger_order_status_change_email' ), 10, 3 );
		add_action( 'masteriyo_course_progress_status_changed', array( self::class, 'trigger_course_complete_email' ), 10, 4 );
		add_action( 'masteriyo_user_course_status_changed', array( self::class, 'trigger_course_enrolled_email' ), 10, 4 );
	}

	/**
	 * Trigger new order email.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $id
	 */
	public static function trigger_new_order_email( $id, $order ) {
		masteriyo( 'email.new-order' )->trigger( $order );
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

	/**
	 * Trigger emails on course complete.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $id
	 * @param string $old_status
	 * @param string $new_status
	 * @param UserCourse $user_course
	 */
	public static function trigger_course_enrolled_email( $id, $old_status, $new_status, $user_course ) {
		if ( 'enrolled' !== $new_status ) {
			return;
		}

		masteriyo( 'email.course-enrolled' )->trigger( $user_course->get_course_id(), $user_course->get_user_id() );
	}

	/**
	 * Trigger emails on course complete.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $id
	 * @param string $old_status
	 * @param string $new_status
	 * @param CourseProgress $course_progress
	 */
	public static function trigger_course_complete_email( $id, $old_status, $new_status, $course_progress ) {
		if ( 'completed' !== $new_status ) {
			return;
		}

		masteriyo( 'email.course-completed' )->trigger( $course_progress->get_course_id(), $course_progress->get_user_id() );
	}
}
