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
 * Check whether the current user can start taking the course.
 *
 * @since 0.1.0
 *
 * @param int|ThemeGrill\Masteriyo\Models\Course $course Course object or Course ID.
 * @param int|ThemeGrill\Masteriyo\Models\User $user User object or User ID.
 *
 * @return bool
 */
function masteriyo_can_start_course( $course, $user = null ) {
	$can_start_course = false;
	$user             = is_null( $user ) ? masteriyo_get_current_user() : $user;
	$course_id        = is_a( $course, 'ThemeGrill\Masteriyo\Models\Course' ) ? $course->get_id() : $course;
	$user_id          = is_a( $user, 'ThemeGrill\Masteriyo\Models\User' ) ? $user->get_id() : $user;

	$query = new UserCourseQuery(
		array(
			'course_id' => $course_id,
			'user_id'   => $user_id,
			'per_page'  => 1,
		)
	);

	$course = masteriyo_get_course( $course_id );

	if ( ! is_null( $course ) ) {
		if ( 'open' === $course->get_access_mode() ) {
			$can_start_course = true;
		} else {
			$user_course = current( $query->get_user_courses() );

			if ( $user_course ) {
				$order            = $user_course->get_order();
				$can_start_course = 'completed' === $order->get_status();
			}
		}
	}

	return apply_filters( 'masteriyo_can_start_course', $can_start_course, $course, $user );
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

/**
 * Get the placeholder image.
 *
 * Uses wp_get_attachment_image if using an attachment ID handle responsiveness.
 *
 * @since 0.1.0
 *
 * @param string       $size Image size.
 * @param string|array $attr Optional. Attributes for the image markup. Default empty.
 * @return string
 */
function masteriyo_placeholder_img( $size = 'masteriyo_thumbnail', $attr = '' ) {
	$dimensions        = masteriyo_get_image_size( $size );
	$placeholder_image = get_option( 'masteriyo_placeholder_image', 0 );

	$default_attr = array(
		'class' => 'masteriyo-placeholder wp-post-image',
		'alt'   => __( 'Placeholder', 'masteriyo' ),
	);

	$attr = wp_parse_args( $attr, $default_attr );

	if ( wp_attachment_is_image( $placeholder_image ) ) {
		$image_html = wp_get_attachment_image(
			$placeholder_image,
			$size,
			false,
			$attr
		);
	} else {
		$image      = masteriyo_placeholder_img_src( $size );
		$hwstring   = image_hwstring( $dimensions['width'], $dimensions['height'] );
		$attributes = array();

		foreach ( $attr as $name => $value ) {
			$attribute[] = esc_attr( $name ) . '="' . esc_attr( $value ) . '"';
		}

		$image_html = '<img src="' . esc_url( $image ) . '" ' . $hwstring . implode( ' ', $attribute ) . '/>';
	}

	return apply_filters( 'masteriyo_placeholder_img', $image_html, $size, $dimensions );
}


/**
 * Get the placeholder image URL either from media, or use the fallback image.
 *
 * @since 0.1.0
 *
 * @param string $size Thumbnail size to use.
 * @return string
 */
function masteriyo_placeholder_img_src( $size = 'masteriyo_thumbnail' ) {
	$src               = masteriyo_get_plugin_url() . '/assets/images/placeholder.png';
	$placeholder_image = get_option( 'masteriyo_placeholder_image', 0 );

	if ( ! empty( $placeholder_image ) ) {
		if ( is_numeric( $placeholder_image ) ) {
			$image = wp_get_attachment_image_src( $placeholder_image, $size );

			if ( ! empty( $image[0] ) ) {
				$src = $image[0];
			}
		} else {
			$src = $placeholder_image;
		}
	}

	return apply_filters( 'masteriyo_placeholder_img_src', $src );
}
