<?php
/**
 * Masteriyo Course Functions
 *
 * Functions for course specific things.
 *
 * @package Masteriyo\Functions
 * @version 0.1.0
 */

use ThemeGrill\Masteriyo\Constants;
use ThemeGrill\Masteriyo\Query\UserCourseQuery;

/**
 * For a given course, and optionally price/qty, work out the price with tax excluded, based on store settings.
 *
 * @since  0.1.0
 * @param  Course $course MASTERIYO_Course object.
 * @param  array      $args Optional arguments to pass course quantity and price.
 * @return float|string Price with tax excluded, or an empty string if price calculation failed.
 */
function masteriyo_get_price_excluding_tax( $course, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'qty'   => '',
			'price' => '',
		)
	);

	$price = '' !== $args['price'] ? max( 0.0, (float) $args['price'] ) : $course->get_price();
	$qty   = '' !== $args['qty'] ? max( 0.0, (float) $args['qty'] ) : 1;

	if ( '' === $price ) {
		return '';
	} elseif ( empty( $qty ) ) {
		return 0.0;
	}

	$line_price   = $price * $qty;
	$return_price = $line_price;

	return apply_filters( 'masteriyo_get_price_excluding_tax', $return_price, $qty, $course );
}

/**
 * Check whether the current user can enroll the course.
 *
 * @since 0.1.0
 *
 * @param int|ThemeGrill\Masteriyo\Models\Course $course Course object or Course ID.
 * @param int|ThemeGrill\Masteriyo\Models\User $user User object or User ID.
 *
 * @return bool
 */
function masteriyo_can_course_be_enrolled( $course, $user = null ) {
	$can_be_enrolled = false;

	if ( is_null( $user ) ) {
		$user = masteriyo_get_current_user();
	}

	$course_id = is_a( $course, 'ThemeGrill\Masteriyo\Models\Course' ) ? $course->get_id() : $course;
	$user_id   = is_a( $user, 'ThemeGrill\Masteriyo\Models\User' ) ? $user->get_id() : $user;

	$query = new UserCourseQuery(
		array(
			'course_id' => $course_id,
			'user_id'   => $user_id,
			'per_page'  => 1,
		)
	);

	$user_course = $query->get_user_courses();

	if ( ! empty( $user_course ) ) {
		$order = $user_course[0]->get_order();

		$can_be_enrolled = 'completed' === $order->get_status();
	}

	return apply_filters( 'masteriyo_can_course_be_enrolled', $can_be_enrolled, $course, $user );
}

/**
 * Get masteriyo access modes.
 *
 * @since 0.1.0
 * @return string
 */
function masteriyo_get_course_access_modes() {
	return apply_filters(
		'masteriyo_course_access_modes',
		array(
			'open',
			'need_registration',
			'one_time',
			'recurring',
			'close',
		)
	);
}
