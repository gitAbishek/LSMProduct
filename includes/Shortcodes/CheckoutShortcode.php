<?php
/**
 * Checkout page shortcode.
 *
 * @since 0.1.0
 * @class CheckoutShortcode
 * @package ThemeGrill\Masteriyo\Shortcodes
 */

namespace ThemeGrill\Masteriyo\Shortcodes;

use ThemeGrill\Masteriyo\Abstracts\Shortcode;

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
		$data = $this->get_attributes();

		if ( is_null( masteriyo( 'cart' ) ) ) {
			return;
		}

		$this->checkout();
	}

	/**
	 * Show the checkout.
	 *
	 * @return void
	 */
	private function checkout() {
		// Check cart has contents.
		if ( masteriyo( 'cart' )->is_empty() && ! is_customize_preview() && apply_filters( 'woocommerce_checkout_redirect_empty_cart', true ) ) {
			return;
		}

		// Check cart contents for errors.
		do_action( 'woocommerce_check_cart_items' );

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
}
