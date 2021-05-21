<?php
/**
 * Masteriyo Payment Gateways
 *
 * Loads payment gateways via hooks for use in the store.
 *
 * @version 0.1.0
 * @package ThemeGrill\Masteriyo\Classes
 */

namespace ThemeGrill\Masteriyo;

use ThemeGrill\Masteriyo\Session\Session;

defined( 'ABSPATH' ) || exit;

/**
 * Payment gateways class.
 */
class PaymentGateways {

	/**
	 * Payment gateway classes.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	public $payment_gateways = array();

	/**
	 * Session class.
	 *
	 * @since 0.1.0
	 *
	 * @var ThemeGrill\Masteriyo\Session\Session
	 */
	private $session;

	/**
	 * Initialize payment gateways.
	 *
	 * @since 0.1.0
	 */
	public function __construct( Session $session ) {
		$this->session = $session;

		$this->init();
	}

	/**
	 * Load gateways and hook in functions.
	 *
	 * @since 0.1.0
	 */
	public function init() {
		$load_gateways = array(
			'ThemeGrill\Masteriyo\Gateways\Offline\Offline',
			// 'ThemeGrill\Masteriyo\Gateways\Paypal\Paypal',
		);

		// Filter.
		$load_gateways = apply_filters( 'masteriyo_payment_gateways', $load_gateways );

		// Filter whether the payment class exists or not.
		$gateways = array_filter(
			$load_gateways,
			function( $load_gateway ) {
				return is_string( $load_gateway ) && class_exists( $load_gateway );
			}
		);

		// Create instance of the class
		$gateways = array_map(
			function( $gateway ) {
				return new $gateway();
			},
			$gateways
		);

		// Filter whether the payment instances are extended from PaymentGateway class.
		$gateways = (array) array_filter( $gateways, array( $this, 'filter_valid_gateway_class' ) );

		// Load gateways in order.
		foreach ( $gateways as $gateway ) {
			// Add to end of the array.
			$this->payment_gateways[] = $gateway;
		}

		ksort( $this->payment_gateways );
	}

	/**
	 * Get gateways.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function payment_gateways() {
		$available_gateways = array();

		if ( count( $this->payment_gateways ) > 0 ) {
			foreach ( $this->payment_gateways as $gateway ) {
				$available_gateways[ $gateway->get_method_title() ] = $gateway;
			}
		}

		return $available_gateways;
	}

	/**
	 * Get array of registered gateway ids
	 *
	 * @since 0.1.0
	 * @return array of strings
	 */
	public function get_payment_gateway_ids() {
		return wp_list_pluck( $this->payment_gateways, 'id' );
	}

	/**
	 * Get available gateways.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_available_payment_gateways() {
		$available_gateways = array();

		foreach ( $this->payment_gateways as $gateway ) {
			if ( $gateway->is_available() ) {
				if ( ! masteriyo_is_add_payment_method_page() ) {
					$available_gateways[ $gateway->get_method_title() ] = $gateway;
				} elseif ( $gateway->supports( 'add_payment_method' ) || $gateway->supports( 'tokenization' ) ) {
					$available_gateways[ $gateway->get_method_title() ] = $gateway;
				}
			}
		}

		$available_gateways = (array) apply_filters( 'masteriyo_available_payment_gateways', $available_gateways );
		$available_gateways = array_filter( $available_gateways, array( $this, 'filter_valid_gateway_class' ) );

		$available_gateway_keys = array_values(
			array_map(
				function( $gateway ) {
					return $gateway->get_id();
				},
				$available_gateways
			)
		);

		return array_combine( $available_gateway_keys, $available_gateways );
	}

	/**
	 * Callback for array filter. Returns true if gateway is of correct type.
	 *
	 * @since 0.1.0
	 *
	 * @param object $gateway Gateway to check.
	 * @return bool
	 */
	protected function filter_valid_gateway_class( $gateway ) {
		return $gateway && is_a( $gateway, 'ThemeGrill\Masteriyo\Abstracts\PaymentGateway' );
	}

	/**
	 * Set the current, active gateway.
	 *
	 * @since 0.1.0
	 *
	 * @param array $gateways Available payment gateways.
	 */
	public function set_current_gateway( $gateways ) {
		// Be on the defensive.
		if ( ! is_array( $gateways ) || empty( $gateways ) ) {
			return;
		}

		$current_gateway = false;

		if ( $this->session ) {
			$current = $this->session->get( 'chosen_payment_method' );

			if ( $current && isset( $gateways[ $current ] ) ) {
				$current_gateway = $gateways[ $current ];
			}
		}

		if ( ! $current_gateway ) {
			$current_gateway = current( $gateways );
		}

		// Ensure we can make a call to set_current() without triggering an error.
		if ( $current_gateway && is_callable( array( $current_gateway, 'set_current' ) ) ) {
			$current_gateway->set_current();
		}
	}

	/**
	 * Save options in admin.
	 *
	 * @since 0.1.0
	 */
	public function process_admin_options() {
		$gateway_order = isset( $_POST['gateway_order'] ) ? masteriyo_clean( wp_unslash( $_POST['gateway_order'] ) ) : ''; // WPCS: input var ok, CSRF ok.
		$order         = array();

		if ( is_array( $gateway_order ) && count( $gateway_order ) > 0 ) {
			$loop = 0;
			foreach ( $gateway_order as $gateway_id ) {
				$order[ esc_attr( $gateway_id ) ] = $loop;
				$loop++;
			}
		}

		update_option( 'masteriyo_gateway_order', $order );
	}
}