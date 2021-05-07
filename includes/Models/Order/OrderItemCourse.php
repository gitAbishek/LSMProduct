<?php
/**
 * Order Line Item (course)
 *
 * @package ThemeGrill\Masteriyo\Classes
 * @version 0.1.0
 * @since   0.1.0
 */

namespace ThemeGrill\Masteriyo\Models\Order;

defined( 'ABSPATH' ) || exit;

use ThemeGrill\Masteriyo\Repository\OrderItemCourseRepository;

/**
 * Order item course class.
 */
class OrderItemCourse extends OrderItem {

	/**
	 * Stores order item data.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $extra_data = array(
		'course_id' => 0,
		'quantity'  => 1,
		'subtotal'  => 0,
		'total'     => 0,
	);

	/**
	 * Get the order item if ID
	 *
	 * @since 0.1.0
	 *
	 * @param OrderItemCourseRepository $repository Order Repository.
	 */
	public function __construct( OrderItemCourseRepository $repository ) {
		$this->repository = $repository;
	}


	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the course ID.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_course_id( $context = 'view' ) {
		return $this->get_prop( 'course_id', $context );
	}

	/**
	 * Get the course type.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_type( $context = 'view' ) {
		return 'course';
	}

	/**
	 * Get the courses quantity.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_quantity( $context = 'view' ) {
		return $this->get_prop( 'quantity', $context );
	}

	/**
	 * Get the sub total amount.
	 *
	 * @since  0.1.0
	 *r
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_subtotal( $context = 'view' ) {
		return $this->get_prop( 'subtotal', $context );
	}

	/**
	 * Get the total amount.
	 *
	 * @since  0.1.0
	 *r
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_total( $context = 'view' ) {
		return $this->get_prop( 'total', $context );
	}

	/**
	 * Get the associated course.
	 *
	 * @return WC_Course|bool
	 */
	public function get_course() {
		$course = wc_get_course( $this->get_course_id() );

		// Backwards compatible filter from WC_Order::get_course_from_item().
		if ( has_filter( 'masteriyo_get_course_from_item' ) ) {
			$course = apply_filters( 'masteriyo_get_course_from_item', $course, $this, $this->get_order() );
		}

		return apply_filters( 'masteriyo_order_item_course', $course, $this );
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set course id.
	 *
	 * @since 0.1.0
	 *
	 * @param string $course_id Course ID.
	 */
	public function set_course_id( $course_id ) {
		if ( $course_id > 0 && 'course' !== get_post_type( absint( $course_id ) ) ) {
			$this->error( 'order_item_course_invalid_course_id', __( 'Invalid course ID', 'masteriyo' ) );
		}
		$this->set_prop( 'course_id', absint( $course_id ) );
	}

	/**
	 * Set the course type.
	 *
	 * @since 0.1.0
	 *
	 * @param string $type course ID.
	 */
	public function set_type( $type ) {
		$this->set_prop( 'type', $type );
	}

	/**
	 * Set the course quantity.
	 *
	 * @since 0.1.0
	 *
	 * @param string $quantity course ID.
	 */
	public function set_quantity( $quantity ) {
		$this->set_prop( 'quantity', masteriyo_stock_amount( $quantity ) );
	}

	/**
	 * Line subtotal (before discounts).
	 *
	 * @since 0.1.0
	 *
	 * @param string $sub_total Subtotal.
	 */
	public function set_subtotal( $sub_total ) {
		$sub_total = masteriyo_format_decimal( $sub_total );

		if ( ! is_numeric( $sub_total ) ) {
			$sub_total = 0;
		}

		$this->set_prop( 'subtotal', $sub_total );
	}

	/**
	 * Setline total amount (after discounts).
	 *
	 * @since 0.1.0
	 *
	 * @param double $total Total amount.
	 */
	public function set_total( $total ) {
		$total = masteriyo_format_decimal( $total );

		if ( ! is_numeric( $total ) ) {
			$total = 0;
		}

		$this->set_prop( 'total', $total );

		// Subtotal cannot be less than total.
		if ( '' === $this->get_subtotal() || $this->get_subtotal() < $this->get_total() ) {
			$this->set_subtotal( $total );
		}
	}
}