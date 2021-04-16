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

	masteriyo('notice')->add( $message, $notice_type, $data );
}

/**
 * Print all notice.
 *
 * @since 0.1.0
 */
function masteriyo_display_all_notices() {
	masteriyo('notice')->display_all();
}
