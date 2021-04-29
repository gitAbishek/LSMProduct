<?php

/**
 * Add a notice.
 *
 * @since 0.1.0
 *
 * @param string $message     The text to display in the notice.
 * @param string $notice_type Optional. The name of the notice type - either error, success or notice.
 * @param array  $data        Optional notice data.
 */
function masteriyo_add_notice( $message, $notice_type = 'success', $data = array() ) {
	if ( ! did_action( 'masteriyo_init' ) ) {
		Utils::doing_it_wrong( __FUNCTION__, __( 'This function should not be called before masteriyo_init.', 'masteriyo' ), '0.1.0' );
		return;
	}
	$message = apply_filters( 'masteriyo_add_notice_' . $notice_type, $message );

	masteriyo( 'notice' )->add( $message, $notice_type, $data );
}

/**
 * Print all notice.
 *
 * @since 0.1.0
 */
function masteriyo_display_all_notices() {
	masteriyo( 'notice' )->display_all();
}

/**
 * Get the count of notices added, either for all notices (default) or for one.
 * particular notice type specified by $notice_type.
 *
 * @since  0.1.0
 * @param  string $notice_type Optional. The name of the notice type - either error, success or notice.
 * @return int
 */
function masteriyo_notice_count( $notice_type = '' ) {
	if ( ! did_action( 'masteriyo_init' ) ) {
		Utils::doing_it_wrong( __FUNCTION__, __( 'This function should not be called before masteriyo_init.', 'masteriyo' ), '2.3' );
		return;
	}

	$notice_count = 0;
	$all_notices  = masteriyo( 'session' )->get( 'masteriyo_notices', array() );

	foreach ( $all_notices as $notice ) {
		if ( isset( $notice['type'] ) && $notice_type === $notice['type'] ) {
			++$notice_count;
		}
	}

	return $notice_count;
}

/**
 * Check whether the notice exists.
 *
 * @since 0.1.0
 *
 * @param string $id Notice id.
 * @param string $type Notice type.
 * @return boolean
 */
function masteriyo_notice_exists( $id, $type ) {
	$notice = masteriyo( 'notice' );

	if ( is_null( $notice ) ) {
		return;
	}

	$message = $notice->get_by_id( $id, $type );

	return ! empty( $message );
}

/**
 * Get the notice message by id.
 *
 * @since 0.1.0
 *
 * @param string $id Notice id.
 * @param string $type Notice type.
 * @return boolean
 */
function masteriyo_notice_by_id( $id, $type ) {
	$notice = masteriyo( 'notice' );

	if ( is_null( $notice ) ) {
		return;
	}

	$message = $notice->get_by_id( $id, $type );

	return $message;
}
