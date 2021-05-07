<?php
/**
 * Handle checkout form.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masetriyo\Classes
 */

namespace ThemeGrill\Masteriyo\FormHandler;

defined( 'ABSPATH' ) || exit;

/**
 * Checkout class.
 */
class CheckoutFormHandler {

	/**
	 * Constructor
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		add_action( 'wp_loaded', array( $this, 'checkout' ), 20 );
	}

	/**
	 * Process checkout form.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function checkout() {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( isset( $_POST['masteriyo_checkout_place_order'] ) || isset( $_POST['masteriyo_checkout_update_totals'] ) ) {
			masteriyo_nocache_headers();

			if ( masteriyo( 'cart' )->is_empty() ) {
				wp_safe_redirect( masteriyo_get_course_list_url() );
				exit;
			}

			masteriyo( 'checkout' )->process_checkout();
		}
	}
}