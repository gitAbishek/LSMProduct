<?php
/**
 * Checkout page shortcode.
 *
 * @since 0.1.0
 * @class CheckoutShortcode
 * @package Masteriyo\Shortcodes
 */

namespace Masteriyo\Shortcodes;

use Masteriyo\Abstracts\Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Checkout page shortcode.
 */
class CheckoutShortcode extends Shortcode {

	/**
	 * Shortcode tag.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $tag = 'masteriyo_checkout';

	/**
	 * Shortcode attributes with default values.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $attributes = array(
		'user_id' => null,
	);

	/**
	 * Get shortcode content.
	 *
	 * @since  0.1.0
	 *
	 * @return string
	 */
	public function get_content() {
		global $wp;

		$data = $this->get_attributes();

		// Bail early if the cart is null.
		if ( is_null( masteriyo( 'cart' ) ) ) {
			return;
		}

		// Handle checkout actions.
		if ( ! empty( $wp->query_vars['order-pay'] ) ) {
			$this->order_pay( $wp->query_vars['order-pay'] );
		} elseif ( isset( $wp->query_vars['order-received'] ) ) {
			$this->order_received( $wp->query_vars['order-received'] );
		} else {
			$this->checkout();
		}
	}

	/**
	 * Show the checkout.
	 *
	 * @return void
	 */
	private function checkout() {
		// Bail early if the checkout is in admin.
		if ( is_admin() ) {
			return;
		}

		// Check cart has contents.
		if ( masteriyo( 'cart' )->is_empty() && ! is_customize_preview() && apply_filters( 'masteriyo_checkout_redirect_empty_cart', true ) ) {
			return;
		}

		// Check cart contents for errors.
		do_action( 'masteriyo_check_cart_items' );

		// Calculate total.s
		masteriyo( 'cart' )->calculate_totals();

		// Get checkout object.
		$checkout = masteriyo( 'checkout' );

		if ( is_null( $checkout ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( empty( $_POST ) ) {
			masteriyo_clear_notices();
		}

		masteriyo_get_template(
			'checkout/form-checkout.php',
			array(
				'checkout' => $checkout,
			)
		);
	}

	/**
	 * Show thank you page.
	 *
	 * @since 0.1.0
	 *
	 * @param integer $order_id Order ID.
	 */
	public function order_received( $order_id = 0 ) {
		$order = false;

		// Get the order.
		$order_id = apply_filters( 'masteriyo_thankyou_order_id', absint( $order_id ) );
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$order_key = isset( $_GET['key'] ) && empty( $_GET['key'] ) ? '' : masteriyo_clean( wp_unslash( $_GET['key'] ) );
		$order_key = apply_filters( 'masteriyo_thankyou_order_key', $order_key );

		if ( $order_id > 0 ) {
			$order = masteriyo_get_order( $order_id );
			if ( ! $order || ! hash_equals( $order->get_order_key(), $order_key ) ) {
				$order = false;
			}
		}

		// Empty awaiting payment session.
		masteriyo( 'session' )->remove( 'order_awaiting_payment' );

		// In case order is created from admin, but paid by the actual customer, store the ip address of the payer
		// when they visit the payment confirmation page.
		if ( $order && $order->is_created_via( 'admin' ) ) {
			$order->set_customer_ip_address( masteriyo_get_current_ip_address() );
			$order->save();
		}

		// Empty current cart.
		masteriyo( 'cart' )->clear();

		masteriyo_get_template( 'checkout/thankyou.php', array( 'order' => $order ) );
	}
}
