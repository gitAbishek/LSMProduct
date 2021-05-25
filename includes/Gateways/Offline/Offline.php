<?php
/**
 * Class Offline payment gateway.
 *
 * @package ThemeGrill\Masteriyo\Gateways
 */

namespace ThemeGrill\Masteriyo\Gateways\Offline;

defined( 'ABSPATH' ) || exit;

use ThemeGrill\Masteriyo\Constants;
use ThemeGrill\Masteriyo\Abstracts\PaymentGateway;

/**
 * Cash on Delivery Gateway.
 *
 * Provides a Cash on Delivery Payment Gateway.
 *
 * @since 0.1.0
 * @class       Offline
 * @extends     PaymentGateway
 */
class Offline extends PaymentGateway {

	/**
	 * Constructor for the gateway.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		// Setup general properties.
		$this->setup_properties();

		// // Load the settings.
		$this->init_settings();

		// // Get settings.
		$this->title        = $this->get_option( 'title' );
		$this->description  = $this->get_option( 'description' );
		$this->instructions = $this->get_option( 'instructions' );

		add_action( 'masteriyo_update_options_payment_gateways_' . $this->get_id(), array( $this, 'process_admin_options' ) );
		add_action( 'masteriyo_thankyou_' . $this->get_id(), array( $this, 'thankyou_page' ) );
		add_filter( 'masteriyo_payment_complete_order_status', array( $this, 'change_payment_complete_order_status' ), 10, 3 );

		// Customer Emails.
		add_action( 'masteriyo_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
	}

	/**
	 * Setup general properties for the gateway.
	 *
	 * @since 0.1.0
	 */
	protected function setup_properties() {
		$this->id                 = 'offline';
		$this->icon               = apply_filters( 'masteriyo_offline_icon', '' );
		$this->method_title       = __( 'Offline', 'masteriyo' );
		$this->method_description = __( 'Have your customers pay with cash (or by other means) upon delivery.', 'masteriyo' );
		$this->has_fields         = false;
	}

	/**
	 * Checks to see whether or not the admin settings are being accessed by the current request.
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	private function is_accessing_settings() {
		if ( is_admin() ) {
			// phpcs:disable WordPress.Security.NonceVerification
			if ( ! isset( $_REQUEST['page'] ) || 'masteriyo-settings' !== $_REQUEST['page'] ) {
				return false;
			}
			if ( ! isset( $_REQUEST['tab'] ) || 'checkout' !== $_REQUEST['tab'] ) {
				return false;
			}
			if ( ! isset( $_REQUEST['section'] ) || 'offlne' !== $_REQUEST['section'] ) {
				return false;
			}
			// phpcs:enable WordPress.Security.NonceVerification

			return true;
		}

		if ( Constants::is_true( 'REST_REQUEST' ) ) {
			global $wp;

			if ( isset( $wp->query_vars['rest_route'] ) && false !== strpos( $wp->query_vars['rest_route'], '/payment_gateways' ) ) {
				return true;
			}
		}

		return false;
	}
	/**
	 * Indicates whether a rate exists in an array of canonically-formatted rate IDs that activates this gateway.
	 *
	 * @since  0.1.0
	 *
	 * @param array $rate_ids Rate ids to check.
	 * @return boolean
	 */
	private function get_matching_rates( $rate_ids ) {
		// First, match entries in 'method_id:instance_id' format. Then, match entries in 'method_id' format by stripping off the instance ID from the candidates.
		return array_unique( array_merge( array_intersect( $this->enable_for_methods, $rate_ids ), array_intersect( $this->enable_for_methods, array_unique( array_map( 'masteriyo_get_string_before_colon', $rate_ids ) ) ) ) );
	}

	/**
	 * Process the payment and return the result.
	 *
	 * @since 0.1.0
	 *
	 * @param int $order_id Order ID.
	 * @return array
	 */
	public function process_payment( $order_id ) {
		$order = masteriyo_get_order( $order_id );

		if ( $order->get_total() > 0 ) {
			// Mark as processing or on-hold (payment won't be taken until delivery).
			$status = apply_filters( 'masteriyo_offline_process_payment_order_status', 'on-hold', $order );
			$order->set_status( $status );
		} else {
			$order->payment_complete();
		}

		// Remove cart.
		masteriyo( 'cart' )->clear();

		// Return thankyou redirect.
		return array(
			'result'   => 'success',
			'redirect' => $this->get_return_url( $order ),
		);
	}

	/**
	 * Output for the order received page.
	 *
	 * @since 0.1.0
	 */
	public function thankyou_page() {
		if ( $this->instructions ) {
			echo wp_kses_post( wpautop( wptexturize( $this->instructions ) ) );
		}
	}

	/**
	 * Change payment complete order status to completed for Offline orders.
	 *
	 * @since  0.1.0
	 *
	 * @param  string         $status Current order status.
	 * @param  int            $order_id Order ID.
	 * @param  Order|false $order Order object.
	 * @return string
	 */
	public function change_payment_complete_order_status( $status, $order_id = 0, $order = false ) {
		if ( $order && 'offline' === $order->get_payment_method() ) {
			$status = 'completed';
		}
		return $status;
	}

	/**
	 * Add content to the Masteriyo emails.
	 *
	 * @since 0.1.0
	 *
	 * @param Order $order Order object.
	 * @param bool     $sent_to_admin  Sent to admin.
	 * @param bool     $plain_text Email format: plain text or HTML.
	 */
	public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
		if ( $this->instructions && ! $sent_to_admin && $this->id === $order->get_payment_method() ) {
			echo wp_kses_post( wpautop( wptexturize( $this->instructions ) ) . PHP_EOL );
		}
	}
}
