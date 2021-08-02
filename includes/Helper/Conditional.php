<?php
/**
 * Conditional functions.
 *
 * @since 0.1.0
 */

use ThemeGrill\Masteriyo\Constants;
use ThemeGrill\Masteriyo\Models\CourseReview;
use ThemeGrill\Masteriyo\Query\UserCourseQuery;

if ( ! function_exists( 'masteriyo_is_filtered' ) ) {
	/**
	 * masteriyo_Is_filtered - Returns true when filtering products using layered nav or price sliders.
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	function masteriyo_is_filtered() {
		return apply_filters( 'masteriyo_is_filtered', false );
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
		return 'yes' === get_option( 'masteriyo_registration_is_generate_username', 'no' );
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
		return 'yes' === get_option( 'masteriyo_registration_is_generate_password', 'no' );
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
		return 'yes' === get_option( 'masteriyo_registration_is_auth_new_user', 'yes' );
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
	return masteriyo_get_setting( 'advance.debug.debug' );
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
	return 'toplevel_page_masteriyo' === $screen->id ? true : false;
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

if ( ! function_exists( 'masteriyo_prices_include_tax' ) ) {

	/**
	 * Are prices inclusive of tax?
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	function masteriyo_prices_include_tax() {
		if ( ! masteriyo_is_tax_enabled() ) {
			return false;
		}

		$prices_include_tax = get_option( 'masteriyo_prices_include_tax' ) === 'yes';
		return apply_filters( 'masteriyo_prices_include_tax', $prices_include_tax );
	}
}

/**
 * Validates a phone number using a regular expression.
 *
 * @since 0.1.0
 *
 * @param  string $phone Phone number to validate.
 * @return bool
 */
function masteriyo_is_phone( $phone ) {
	if ( 0 < strlen( trim( preg_replace( '/[\s\#0-9_\-\+\/\(\)\.]/', '', $phone ) ) ) ) {
		return false;
	}

	return true;
}

/**
 * Checks for a valid postcode.
 *
 * @since 0.1.0
 *
 * @param  string $postcode Postcode to validate.
 * @param  string $country Country to validate the postcode for.
 * @return bool
 */
function masteriyo_is_postcode( $postcode, $country ) {
	if ( strlen( trim( preg_replace( '/[\s\-A-Za-z0-9]/', '', $postcode ) ) ) > 0 ) {
		return false;
	}

	switch ( $country ) {
		case 'AT':
			$valid = (bool) preg_match( '/^([0-9]{4})$/', $postcode );
			break;
		case 'BA':
			$valid = (bool) preg_match( '/^([7-8]{1})([0-9]{4})$/', $postcode );
			break;
		case 'BE':
			$valid = (bool) preg_match( '/^([0-9]{4})$/i', $postcode );
			break;
		case 'BR':
			$valid = (bool) preg_match( '/^([0-9]{5})([-])?([0-9]{3})$/', $postcode );
			break;
		case 'CH':
			$valid = (bool) preg_match( '/^([0-9]{4})$/i', $postcode );
			break;
		case 'DE':
			$valid = (bool) preg_match( '/^([0]{1}[1-9]{1}|[1-9]{1}[0-9]{1})[0-9]{3}$/', $postcode );
			break;
		case 'ES':
		case 'FR':
		case 'IT':
			$valid = (bool) preg_match( '/^([0-9]{5})$/i', $postcode );
			break;
		case 'GB':
			$valid = masteriyo_is_gb_postcode( $postcode );
			break;
		case 'HU':
			$valid = (bool) preg_match( '/^([0-9]{4})$/i', $postcode );
			break;
		case 'IE':
			$valid = (bool) preg_match( '/([AC-FHKNPRTV-Y]\d{2}|D6W)[0-9AC-FHKNPRTV-Y]{4}/', masteriyo_normalize_postcode( $postcode ) );
			break;
		case 'IN':
			$valid = (bool) preg_match( '/^[1-9]{1}[0-9]{2}\s{0,1}[0-9]{3}$/', $postcode );
			break;
		case 'JP':
			$valid = (bool) preg_match( '/^([0-9]{3})([-]?)([0-9]{4})$/', $postcode );
			break;
		case 'PT':
			$valid = (bool) preg_match( '/^([0-9]{4})([-])([0-9]{3})$/', $postcode );
			break;
		case 'PR':
		case 'US':
			$valid = (bool) preg_match( '/^([0-9]{5})(-[0-9]{4})?$/i', $postcode );
			break;
		case 'CA':
			// CA Postal codes cannot contain D,F,I,O,Q,U and cannot start with W or Z. https://en.wikipedia.org/wiki/Postal_codes_in_Canada#Number_of_possible_postal_codes.
			$valid = (bool) preg_match( '/^([ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ])([\ ])?(\d[ABCEGHJKLMNPRSTVWXYZ]\d)$/i', $postcode );
			break;
		case 'PL':
			$valid = (bool) preg_match( '/^([0-9]{2})([-])([0-9]{3})$/', $postcode );
			break;
		case 'CZ':
		case 'SK':
			$valid = (bool) preg_match( '/^([0-9]{3})(\s?)([0-9]{2})$/', $postcode );
			break;
		case 'NL':
			$valid = (bool) preg_match( '/^([1-9][0-9]{3})(\s?)(?!SA|SD|SS)[A-Z]{2}$/i', $postcode );
			break;
		case 'SI':
			$valid = (bool) preg_match( '/^([1-9][0-9]{3})$/', $postcode );
			break;
		case 'LI':
			$valid = (bool) preg_match( '/^(94[8-9][0-9])$/', $postcode );
			break;
		default:
			$valid = true;
			break;
	}

	return apply_filters( 'masteriyo_validate_postcode', $valid, $postcode, $country );
}


/**
 * Check if is a GB postcode.
 *
 * @since 0.1.0
 *
 * @param  string $to_check A postcode.
 * @return bool
 */
function masteriyo_is_gb_postcode( $to_check ) {

	// Permitted letters depend upon their position in the postcode.
	// https://en.wikipedia.org/wiki/Postcodes_in_the_United_Kingdom#Validation.
	$alpha1 = '[abcdefghijklmnoprstuwyz]'; // Character 1.
	$alpha2 = '[abcdefghklmnopqrstuvwxy]'; // Character 2.
	$alpha3 = '[abcdefghjkpstuw]';         // Character 3 == ABCDEFGHJKPSTUW.
	$alpha4 = '[abehmnprvwxy]';            // Character 4 == ABEHMNPRVWXY.
	$alpha5 = '[abdefghjlnpqrstuwxyz]';    // Character 5 != CIKMOV.

	$pcexp = array();

	// Expression for postcodes: AN NAA, ANN NAA, AAN NAA, and AANN NAA.
	$pcexp[0] = '/^(' . $alpha1 . '{1}' . $alpha2 . '{0,1}[0-9]{1,2})([0-9]{1}' . $alpha5 . '{2})$/';

	// Expression for postcodes: ANA NAA.
	$pcexp[1] = '/^(' . $alpha1 . '{1}[0-9]{1}' . $alpha3 . '{1})([0-9]{1}' . $alpha5 . '{2})$/';

	// Expression for postcodes: AANA NAA.
	$pcexp[2] = '/^(' . $alpha1 . '{1}' . $alpha2 . '[0-9]{1}' . $alpha4 . ')([0-9]{1}' . $alpha5 . '{2})$/';

	// Exception for the special postcode GIR 0AA.
	$pcexp[3] = '/^(gir)(0aa)$/';

	// Standard BFPO numbers.
	$pcexp[4] = '/^(bfpo)([0-9]{1,4})$/';

	// c/o BFPO numbers.
	$pcexp[5] = '/^(bfpo)(c\/o[0-9]{1,3})$/';

	// Load up the string to check, converting into lowercase and removing spaces.
	$postcode = strtolower( $to_check );
	$postcode = str_replace( ' ', '', $postcode );

	// Assume we are not going to find a valid postcode.
	$valid = false;

	// Check the string against the six types of postcodes.
	foreach ( $pcexp as $regexp ) {
		if ( preg_match( $regexp, $postcode, $matches ) ) {
			// Remember that we have found that the code is valid and break from loop.
			$valid = true;
			break;
		}
	}

	return $valid;
}

if ( ! function_exists( 'masteriyo_is_ajax' ) ) {

	/**
	 * masteriyo_is_ajax - Returns true when the page is loaded via ajax.
	 *
	 * @return bool
	 */
	function masteriyo_is_ajax() {
		return function_exists( 'wp_doing_ajax' ) ? wp_doing_ajax() : Constants::is_defined( 'DOING_AJAX' );
	}
}


/**
 * Validate course id.
 *
 * @since 0.1.0.
 *
 * @param int $course_id Course Id.
 * @return boolean|WP_Error
 */
function masteriyo_is_course( $course_id ) {
	if ( ! is_numeric( $course_id ) ) {
		return new \WP_Error( 'rest_invalid_type', 'course is not of type integer' );
	}

	$course_id = absint( $course_id );
	$course    = get_post( $course_id );

	if ( is_null( $course ) || 'course' !== $course->post_type ) {
		return new \WP_Error( 'rest_invalid_course', 'invalid course id' );
	}

	return true;
}

if ( ! function_exists( 'masteriyo_is_current_user_admin' ) ) {
	/**
	 * Check if the current user is admin.
	 *
	 * @since 0.1.0
	 *
	 * @return boolean
	 */
	function masteriyo_is_current_user_admin() {
		return in_array( 'administrator', wp_get_current_user()->roles, true );
	}
}

if ( ! function_exists( 'masteriyo_is_current_user_manager' ) ) {
	/**
	 * Check if the current user is masteriyo manager.
	 *
	 * @since 0.1.0
	 *
	 * @return boolean
	 */
	function masteriyo_is_current_user_manager() {
		return in_array( 'masteriyo_manager', wp_get_current_user()->roles, true );
	}
}

if ( ! function_exists( 'masteriyo_is_current_user_student' ) ) {
	/**
	 * Check if the current user is masteriyo student.
	 *
	 * @since 0.1.0
	 *
	 * @return boolean
	 */
	function masteriyo_is_current_user_student() {
		return in_array( 'masteriyo_student', wp_get_current_user()->roles, true );
	}
}

if ( ! function_exists( 'masteriyo_is_current_user_instructor' ) ) {
	/**
	 * Check if the current user is masteriyo instructor.
	 *
	 * @since 0.1.0
	 *
	 * @return boolean
	 */
	function masteriyo_is_current_user_instructor() {
		return in_array( 'masteriyo_instructor', wp_get_current_user()->roles, true );
	}
}

if ( ! function_exists( 'masteriyo_is_add_payment_method_page' ) ) {

	/**
	 * Returns true when viewing the add payment method page.
	 *
	 * @since 0.1.0
	 * @return bool
	 */
	function masteriyo_is_add_payment_method_page() {
		global $wp;

		$page_id = masteriyo_get_page_id( 'myaccount' );

		return ( $page_id && is_page( $page_id ) && ( isset( $wp->query_vars['payment-methods'] ) || isset( $wp->query_vars['add-payment-method'] ) ) );
	}
}

if ( ! function_exists( 'masteriyo_is_current_user_post_author' ) ) {
	/**
	 * Check if the current user is the author of a post.
	 *
	 * @since 0.1.0
	 *
	 * @param integer|string $post_id Post ID.
	 *
	 * @return boolean
	 */
	function masteriyo_is_current_user_post_author( $post_id ) {
		$post = get_post( absint( $post_id ) );

		if ( is_a( $post, \WP_Post::class ) ) {
			return get_current_user_id() === absint( $post->post_author );
		}
		return false;
	}
}

if ( ! function_exists( 'masteriyo_is_tax_enabled' ) ) {

	/**
	 * Are store-wide taxes enabled?
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	function masteriyo_is_tax_enabled() {
		return false;
	}
}

if ( ! function_exists( 'masteriyo_is_checkout_page' ) ) {

	/**
	 * Return true if the current page is checkout page.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function masteriyo_is_checkout_page() {
		global $post;

		if ( ! ( is_page() && is_singular() ) ) {
			return false;
		}

		$page_id = masteriyo_get_page_id( 'checkout' );

		return $post->ID === $page_id;
	}
}

if ( ! function_exists( 'masteriyo_is_user_enrolled_in_course' ) ) {
	/**
	 * Check if a user is enrolled in a course.
	 *
	 * @since 0.1.0
	 *
	 * @param integer|string $course_id
	 * @param integer|string $user_id
	 *
	 * @return boolean
	 */
	function masteriyo_is_user_enrolled_in_course( $course_id, $user_id = null ) {
		;
		if ( is_null( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$course = masteriyo_get_course( $course_id );
		$user   = masteriyo_get_user( $user_id );

		if ( is_null( $course ) || is_wp_error( $user ) ) {
			return false;
		}

		$query = new UserCourseQuery(
			array(
				'course_id' => $course->get_id(),
				'user_id'   => $user->get_id(),
				'type'      => 'course',
			)
		);

		$user_courses = $query->get_user_courses();

		if ( empty( $user_courses ) ) {
			return false;
		}
		return $user_courses[0]->get_status() !== '';
	}
}

if ( ! function_exists( 'masteriyo_is_current_user_enrolled_in_course' ) ) {
	/**
	 * Check if the current logged in user is enrolled in a course.
	 *
	 * @since 0.1.0
	 *
	 * @param integer|string $course_id
	 *
	 * @return boolean
	 */
	function masteriyo_is_current_user_enrolled_in_course( $course_id ) {
		return masteriyo_is_user_enrolled_in_course( $course_id, get_current_user_id() );
	}
}

if ( ! function_exists( 'masteriyo_current_user_can_edit_course_review' ) ) {
	/**
	 * Check if the current logged in user can edit a course review.
	 *
	 * @since 0.1.0
	 *
	 * @param int|string|WP_Comment|CourseReview $course_review_id
	 *
	 * @return boolean
	 */
	function masteriyo_current_user_can_edit_course_review( $course_review_id = 0 ) {
		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
		}
		$review = masteriyo_get_course_review( $course_review_id );

		if ( is_null( $review ) ) {
			return false;
		}
		return get_current_user_id() === $review->get_author_id();
	}
}


if ( ! function_exists( 'masteriyo_is_interactive_page' ) ) {
	/**
	 * Return true if the page is interactive.
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	function masteriyo_is_interactive_page() {
		return isset( $_GET['masteriyo-page'] ) && 'interactive' === $_GET['masteriyo-page' ]; // phpcs:ignore
	}
}

/**
 * Check whether the post type debug is enabbled or not.
 *
 * @since 0.1.0
 *
 * @return bool
 */
function masteriyo_is_post_type_debug_enabled() {
	return (bool) Constants::get( 'MASTERIYO_POST_TYPE_DEBUG' );
}

/**
 * Is masteriyo template debug enabled.
 *
 * @since 0.1.0
 *
 * @return bool
 */
function masteriyo_is_template_debug_enabled() {
	return masteriyo_get_setting( 'advance.debug.template_debug' );
}
