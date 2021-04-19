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

if ( ! function_exists( 'masteriyo_registration_is_generate_username' ) ) {
	/**
	 * Check if the username should be gerenated for new users.
	 *
	 * @since 0.1.0
	 *
	 * @return boolean
	 */
	function masteriyo_registration_is_generate_username() {
		return 'yes' === masteriyo_get_setting_value( 'masteriyo_registration_is_generate_username', 'no' );
	}
}

if ( ! function_exists( 'masteriyo_registration_is_generate_password' ) ) {
	/**
	 * Check if the password should be gerenated for new users.
	 *
	 * @since 0.1.0
	 *
	 * @return boolean
	 */
	function masteriyo_registration_is_generate_password() {
		return 'yes' === masteriyo_get_setting_value( 'masteriyo_registration_is_generate_password', 'no' );
	}
}

if ( ! function_exists( 'masteriyo_registration_is_auth_new_user' ) ) {
	/**
	 * Check if new users should be logged in after registration.
	 *
	 * @since 0.1.0
	 *
	 * @return boolean
	 */
	function masteriyo_registration_is_auth_new_user() {
		return 'yes' === masteriyo_get_setting_value( 'masteriyo_registration_is_auth_new_user', 'yes' );
	}
}
