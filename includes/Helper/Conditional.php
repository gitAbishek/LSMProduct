<?php
/**
 * Conditional functions.
 *
 * @since 0.1.0
 */

if ( ! function_exists( 'masteriyo_is_filtered' ) ) {
	/**
	 * masteriyo_Is_filtered - Returns true when filtering products using layered nav or price sliders.
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	function masteriyo_is_filtered() {
		return apply_filters( 'masteriyo__is_filtered',  false);
	}
}

if ( ! function_exists( 'masteriyo_is_load_login_form_assets' ) ) {
	/**
	 * Check if assets for login form should be loaded.
	 *
	 * @since 0.1.0
	 *
	 * @return boolean
	 */
	function masteriyo_is_load_login_form_assets() {
		return ! is_user_logged_in() && masteriyo_is_myaccount_page();
	}
}
