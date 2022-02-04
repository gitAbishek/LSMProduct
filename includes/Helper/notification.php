<?php
/**
 * Notification utilities.
 */

/**
 * Get notification.
 *
 * @since x.x.x
 *
 * @param int|Masteriyo\Models\Notification $notification Notification ID or object.
 * @return Masteriyo\Models\Notification
 */
function masteriyo_get_notification( $notification ) {
	if ( is_a( $notification, 'Masteriyo\Database\Model' ) ) {
		$id = $notification->get_id();
	} else {
		$id = absint( $notification );
	}

	try {
		$notification_obj = masteriyo( 'notification' );
		$store            = masteriyo( 'notification.store' );

		$notification_obj->set_id( $id );
		$store->read( $notification_obj );
	} catch ( \Exception $e ) {
		$notification_obj = null;
	}

	return apply_filters( 'masteriyo_get_notification', $notification_obj, $notification );
}
