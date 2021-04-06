<?php
/**
 * Conditional functions.
 *
 * @since 0.1.0
 */

use ThemeGrill\Masteriyo\Constants;

if ( ! function_exists( 'masteriyo_is_filtered' ) ) {
	/**
	 * masteriyo_Is_filtered - Returns true when filtering products using layered nav or price sliders.
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	function masteriyo_is_filtered() {
		return apply_filters( 'masteriyo__is_filtered', false);
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

/**
 * What type of request is this?
 *
 * @since 0.1.0
 *
 * @param  string $type admin, ajax, cron or frontend.
 * @return bool
 */
function masteriyo_is_request( $type ) {
	switch ( $type ) {
		case 'admin':
			return is_admin();
		case 'ajax':
			return defined( 'DOING_AJAX' );
		case 'cron':
			return defined( 'DOING_CRON' );
		case 'frontend':
			return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' ) && ! masteriyo_is_rest_api_request();
	}
}

/**
 * Returns true if the request is a non-legacy REST API request.
 *
 * Legacy REST requests should still run some extra code for backwards compatibility.
 *
 * @todo: replace this function once core WP function is available: https://core.trac.wordpress.org/ticket/42061.
 *
 * @since 0.1.0
 *
 * @return bool
 */
function masteriyo_is_rest_api_request() {
	if ( empty( $_SERVER['REQUEST_URI'] ) ) {
		return false;
	}

	$rest_prefix         = trailingslashit( rest_get_url_prefix() );
	$is_rest_api_request = ( false !== strpos( $_SERVER['REQUEST_URI'], $rest_prefix ) ); // phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

	return apply_filters( 'masteriyo_is_rest_api_request', $is_rest_api_request );
}

/**
 * Is masteriyo in debug enabled.
 *
 * @since 0.1.0
 *
 * @return bool
 */
function masteriyo_is_debug_enabled() {
	return (bool) Constants::get( 'MASTERIYO_DEBUG' );
}

/**
 * Is masteriyo admin page.
 *
 * @since 0.1.0
 *
 * @return bool
 */
function masteriyo_is_admin_page() {
	if ( ! is_admin() ) {
		return false;
	}

	$screen = get_current_screen();
	return 'toplevel_page_masteriyo' === $screen->id ? true: false;
}


/**
 * Check if the current page is a single course page.
 *
 * @since 0.1.0
 *
 * @return boolean
 */
function masteriyo_is_single_course_page() {
	return is_singular( 'course' );
}

/**
 * Check if the current page is a single course page.
 *
 * @since 0.1.0
 *
 * @return boolean
 */
function masteriyo_is_course_list_page() {
	return is_post_type_archive( 'course' );
}
