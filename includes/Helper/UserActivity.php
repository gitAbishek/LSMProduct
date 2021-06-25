<?php
/**
 * User activity functions.
 *
 * @since 0.1.0
 * @package ThemeGrill\Masteriyo\Helper
 */


/**
 * Get user activity.
 *
 * @since 0.1.0
 *
 * @param int $user_activity_id User activity ID.
 *
 * @return UserActivity
 */
function masteriyo_get_user_activity( $user_activity_id ) {
	$user_activity = masteriyo( 'user-activity' );
	$user_activity->set_id( $user_activity_id );

	$user_activity_repo = masteriyo( 'user-activity.store' );
	$user_activity_repo->read( $user_activity );

	return $user_activity;
}
