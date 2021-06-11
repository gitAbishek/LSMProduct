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
 * @param int|Course $course Course object or Course ID.
 * @param int|User $user User object or User ID.
 *
 * @return bool
 */
function masteriyo_is_course_can_be_enrolled( $course, $user = null ) {
	$course_id = is_a( $course, 'ThemeGrill\Masteriyo\Models\Course' ) ? $course->get_id() : $course;
	$user_id   = is_a( $user, 'ThemeGrill\Masteriyo\Models\User' ) ? $user->get_id() : $user;

	if ( is_null( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	// Check whether the course is bought or not.
	$orders = masteriyo_get_orders(
		array(
			'limit'       => -1,
			'customer_id' => $user,
		)
	);

	$order_ids = array_map(
		function( $order ) {
			return $order->get_id();
		},
		$orders
	);
}
