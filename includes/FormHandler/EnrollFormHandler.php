<?php
/**
 * Handle enroll form.
 *
 * @package ThemeGrill\Masetriyo\Classes\
 */

namespace ThemeGrill\Masteriyo\FormHandler;

defined( 'ABSPATH' ) || exit;

/**
 * Enroll class.
 */
class EnrollFormHandler {

	public function __construct() {
		add_action( 'wp_loaded', array( $this, 'enroll' ), 20 );
	}

	/**
	 * Handle enroll.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function enroll() {
		if ( ! isset( $_REQUEST['enroll'] ) || ! is_numeric( wp_unslash( $_REQUEST['enroll'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			return;
		}

		masteriyo_nocache_headers();

		$course_id      = apply_filters( 'masteriyo_enroll_course_id', absint( wp_unslash( $_REQUEST['enroll'] ) ) );  // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$adding_to_cart = masteriyo_get_course( $course_id );

		if ( is_null( $adding_to_cart ) ) {
			return;
		}

		$was_added_to_cart = $this->add_to_cart_handler_simple( $course_id );

		if ( $was_added_to_cart ) {
			$url = apply_filters( 'masteriyo_add_to_cart_redirect', $url, $adding_to_cart );

			if ( $url ) {
				wp_safe_redirect( $url );
				exit;
			} elseif ( 'yes' === get_option( 'masteriyo_cart_redirect_after_add' ) ) {
				$url = apply_filters( 'masteriyo_cart_redirect_after_add', masteriyo_get_checkout_url(), $adding_to_cart );
				wp_safe_redirect( $url );
				exit;
			}
		}
	}

	/**
	 * Handle adding simple courses to the cart.
	 *
	 * @since 0.1.0
	 * @param int $course_id Course ID to add to the cart.
	 * @return bool success or not
	 */
	protected function add_to_cart_handler_simple( $course_id ) {
		$quantity          = isset( $_REQUEST['quantity'] ) ? masteriyo_stock_amount( wp_unslash( $_REQUEST['quantity'] ) ) : 1; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$passed_validation = apply_filters( 'masteriyo_add_to_cart_validation', true, $course_id, $quantity );

		if ( $passed_validation && false !== masteriyo( 'cart' )->add_to_cart( $course_id, $quantity ) ) {
			return true;
		}
		return false;
	}
}
