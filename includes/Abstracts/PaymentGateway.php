<?php
/**
 * Abstract payment gateway
 *
 * Hanldes generic payment gateway functionality which is extended by idividual payment gateways.
 *
 * @class PaymentGateway
 * @version 0.1.0
 * @package ThemeGrill|masteriyo\Abstracts
 */

namespace ThemeGrill\Masteriyo\Abstracts;

use ThemeGrill\Masteriyo\Constants;

defined( 'ABSPATH' ) || exit;

/**
 * Masteriyo Payment Gateway class.
 *
 * Extended by individual payment gateways to handle payments.
 *
 * @class       PaymentGateway
 * @version     0.1.0
 * @package     ThemeGrill\Masteriyo\Abstracts
 */
abstract class PaymentGateway {

	/**
	 * Payment gateway id.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 * Set if the place order button should be renamed on selection.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $order_button_text;

	/**
	 * Yes or no based on whether the method is enabled.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $enabled = 'yes';

	/**
	 * Payment method title for the frontend.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * Payment method description for the frontend.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $description;

	/**
	 * Chosen payment method id.
	 *
	 * @since 0.1.0
	 *
	 * @var bool
	 */
	protected $chosen;

	/**
	 * Gateway title.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $method_title = '';

	/**
	 * Gateway description.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $method_description = '';

	/**
	 * True if the gateway shows fields on the checkout.
	 *
	 * @since 0.1.0
	 *
	 * @var bool
	 */
	protected $has_fields;

	/**
	 * Countries this gateway is allowed for.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $countries;

	/**
	 * Available for all counties or specific.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $availability;

	/**
	 * Icon for the gateway.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $icon;

	/**
	 * Supported features such as 'default_credit_card_form', 'refunds'.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $supports = array( 'course' );

	/**
	 * Maximum transaction amount, zero does not define a maximum.
	 *
	 * @since 0.1.0
	 *
	 * @var int
	 */
	protected $max_amount = 0;

	/**
	 * Optional URL to view a transaction.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $view_transaction_url = '';

	/**
	 * Optional label to show for "new payment method" in the payment
	 * method/token selection radio selection.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $new_method_label = '';

	/**
	 * Pay button ID if supported.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $pay_button_id = '';

	/**
	 * Getters.
	 */

	/**
	 * Get the payment gatewy id.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Get order button text.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_order_button_text() {
		return apply_filters( 'masteriyo_gateway_order_button_text', $this->order_button_text, $this );
	}

	/**
	 * Check whether the gateway is enabled or not.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function is_enabled() {
		return apply_filters( 'masteriyo_gateway_enabled', $this->enabled, $this );
	}

	/**
	 * Get payment method title for the frontend.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_title() {
		return apply_filters( 'masteriyo_gateway_title', $this->title, $this );
	}

	/**
	 * Get payment method description for the frontend.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_description() {
		return apply_filters( 'masteriyo_gateway_description', $this->description, $this );
	}

	/**
	 * Is the payment method choosen?
	 *
	 * @since 0.1.0
	 *
	 * @return boolean
	 */
	public function is_chosen() {
		return apply_filters( 'masteriyo_gateway_chosen', $this->chosen, $this );
	}

	/**
	 * Get gateway title.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_method_title() {
		return apply_filters( 'masteriyo_gateway_method_title', $this->method_title, $this );
	}

	/**
	 * Get gateway description.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_method_description() {
		return apply_filters( 'masteriyo_gateway_method_description', $this->method_description, $this );
	}

	/**
	 * True if the gateway shows fields on the checkout.
	 *
	 * @since 0.1.0
	 *
	 * @return boolean
	 */
	public function has_fields() {
		return apply_filters( 'masteriyo_gateway_has_fields', $this->has_fields, $this );
	}

	/**
	 * Get countries the gateway is allowed for.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_countries() {
		return apply_filters( 'masteriyo_gateway_countries', $this->countries, $this );
	}

	/**
	 * Get available for all countries or specific.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_availability() {
		return apply_filters( 'masteriyo_gateway_availability', $this->availability, $this );
	}

	/**
	 * Get gateway icon.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_icon() {
		$icon_url = '<img href="%s" alt="%s" />';
		$icon     = $this->icon ? sprintf( $icon_url, esc_url( $this->icon ), esc_attr( $this->get_title() ) ) : '';

		return apply_filters( 'masteriyo_gateway_icon', $icon, $this );
	}

	/**
	 * Get supported features such as 'default_credit_card_form', 'refunds'.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	public function get_supports() {
		return apply_filters( 'masteriyo_gateway_supports', $this->supports, $this );
	}

	/**
	 * Get maximum transaction amount, zero does not define a maximum.
	 *
	 * @since 0.1.0
	 *
	 * @var int
	 */
	public function get_max_amount() {
		return apply_filters( 'masteriyo_gateway_max_amount', $this->max_amount, $this );
	}

	/**
	 * Get option url to view a transaciton.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public function get_view_transaction_url() {
		return apply_filters( 'masteriyo_gateway_view_transaction_url', $this->view_transaction_url, $this );
	}

	/**
	 * Get optional label to show for "new payment method" in the payment
	 * method/token selection radio selection.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public function get_new_method_label() {
		return apply_filters( 'masteriyo_gateway_new_method_label', $this->new_method_label, $this );
	}

	/**
	 * Get pay button ID if supported.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public function get_pay_button_id() {
		return apply_filters( 'masteriyo_gateway_pay_button_id', $this->pay_button_id, $this );
	}

	/**
	 * Setters.
	 */

	/**
	 * Set order button text.
	 *
	 * @since 0.1.0
	 *
	 * @param string $order_button_text Order button text.
	 */
	public function set_order_button_text( $order_button_text ) {
		$this->order_button_text = $order_button_text;
	}

	/**
	 * Check whether the gateway is enabled or not.
	 *
	 * @since 0.1.0
	 *
	 * @param boolean $enabled
	 */
	public function set_enabled( $enabled ) {
		$this->enabled = masteriyo_string_to_bool( $enabled );
	}

	/**
	 * Set payment method title for the frontend.
	 *
	 * @since 0.1.0
	 *
	 * @param string $title Payment method title for the frontend.
	 */
	public function set_title( $title ) {
		$this->title = $title;
	}

	/**
	 * Set payment method description for the frontend.
	 *
	 * @since 0.1.0
	 *
	 * @param string $description Payment method description for the frontend.
	 */
	public function set_description( $description ) {
		$this->description = $description;
	}

	/**
	 * Is the payment method choosen?
	 *
	 * @since 0.1.0
	 *
	 * @param boolean $chosen
	 */
	public function set_chosen( $chosen ) {
		$this->chosen = masteriyo_string_to_bool( $chosen );
	}

	/**
	 * Set gateway title.
	 *
	 * @since 0.1.0
	 *
	 * @param string $title Gateway title.
	 */
	public function set_method_title( $title ) {
		$this->method_title = $title;
	}

	/**
	 * Set gateway description.
	 *
	 * @since 0.1.0
	 *
	 * @param string $description Gateway description.
	 */
	public function set_method_description( $description ) {
		$this->method_description = $description;
	}

	/**
	 * True if the gateway shows fields on the checkout.
	 *
	 * @since 0.1.0
	 *
	 * @param boolean $has_fields
	 */
	public function set_has_fields( $has_fields ) {
		$this->has_fields = masteriyo_string_to_bool( $has_fields );
	}

	/**
	 * Set countries the gateway is allowed for.
	 *
	 * @since 0.1.0
	 *
	 * @param array $countries List of countries the gateway is allowed for.
	 */
	public function set_countries( $countries ) {
		$this->countries = (array) $countries;
	}

	/**
	 * Set available for all countries or specific.
	 *
	 * @since 0.1.0
	 *
	 * @param array $availability List of available countries or specific.
	 */
	public function set_availability( $availability ) {
		$this->availability = (array) $availability;
	}

	/**
	 * Set gateway icon.
	 *
	 * @since 0.1.0
	 *
	 * @param string $icon Gateway icon.
	 */
	public function set_icon( $icon ) {
		$this->icon = $icon;
	}

	/**
	 * Set supported features such as 'default_credit_card_form', 'refunds'.
	 *
	 * @since 0.1.0
	 *
	 * @param array $supports List of supported features.
	 */
	public function set_supports( $supports ) {
		$this->supports = (array) $supports;
	}

	/**
	 * Set maximum transaction amount, zero does not define a maximum.
	 *
	 * @since 0.1.0
	 *
	 * @param int $max_amount Maximum transaction amount.
	 */
	public function set_max_amount( $max_amount ) {
		$this->max_amount = (int) $max_amount;
	}

	/**
	 * Set option url to view a transaciton.
	 *
	 * @since 0.1.0
	 *
	 * @param string $url Transaction url.
	 */
	public function set_view_transaction_url( $url ) {
		$this->view_transaction_url = $url;
	}

	/**
	 * Set optional label to show for "new payment method" in the payment
	 * method/token selection radio selection.
	 *
	 * @since 0.1.0
	 *
	 * @param string $label New payment method label.
	 */
	public function set_new_method_label( $label ) {
		$this->new_method_label = $label;
	}

	/**
	 * Set pay button ID if supported.
	 *
	 * @since 0.1.0
	 *
	 * @param string $id Pay button id.
	 */
	public function set_pay_button_id( $pay_button_id ) {
		$this->pay_button_id = $pay_button_id;
	}

	/**
	 * Other methods.
	 */

	/**
	 * Init settings for gateways.
	 *
	 * @since 0.1.0
	 */
	public function init_settings() {
		$this->enabled = $this->get_option( 'enable', false );
	}

	/**
	 * Return whether or not this gateway still requires setup to function.
	 *
	 * When this gateway is toggled on via AJAX, if this returns true a
	 * redirect will occur to the settings page instead.
	 *
	 * @since 0.1.0
	 * @return bool
	 */
	public function needs_setup() {
		return false;
	}

	/**
	 * Get the return url (thank you page).
	 *
	 * @since 0.1.0
	 *
	 * @param Order|null $order Order object.
	 * @return string
	 */
	public function get_return_url( $order = null ) {
		if ( $order ) {
			$return_url = $order->get_checkout_order_received_url();
		} else {
			$return_url = masteriyo_get_endpoint_url( 'order-received', '', masteriyo_get_checkout_url() );
		}

		return apply_filters( 'masteriyo_get_return_url', $return_url, $order );
	}

	/**
	 * Get a link to the transaction on the 3rd party gateway site (if applicable).
	 *
	 * @since 0.1.0
	 *
	 * @param  Order $order the order object.
	 * @return string transaction URL, or empty string.
	 */
	public function get_transaction_url( $order ) {
		$return_url     = '';
		$transaction_id = $order->get_transaction_id();

		if ( ! empty( $this->view_transaction_url ) && ! empty( $transaction_id ) ) {
			$return_url = sprintf( $this->view_transaction_url, $transaction_id );
		}

		return apply_filters( 'masteriyo_get_transaction_url', $return_url, $order, $this );
	}

	/**
	 * Get the order total in checkout and pay_for_order.
	 *
	 * @since 0.1.0
	 *
	 * @return float
	 */
	protected function get_order_total() {
		$total    = 0;
		$order_id = absint( get_query_var( 'order-pay' ) );

		// Gets order total from "pay for order" page.
		if ( 0 < $order_id ) {
			$order = masteriyo_get_order( $order_id );
			if ( $order ) {
				$total = (float) $order->get_total();
			}

			// Gets order total from cart/checkout.
		} elseif ( 0 < masteriyo( 'cart' )->get_total() ) {
			$total = (float) masteriyo( 'cart' )->get_total();
		}

		return $total;
	}

	/**
	 * Check if the gateway is available for use.
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	public function is_available() {
		$is_available = $this->enabled;

		if ( masteriyo( 'cart' ) && 0 < $this->get_order_total() && 0 < $this->max_amount && $this->max_amount < $this->get_order_total() ) {
			$is_available = false;
		}

		return $is_available;
	}

	/**
	 * Set as current gateway.
	 *
	 * Set this as the current gateway.
	 */
	public function set_current() {
		$this->chosen = true;
	}

	/**
	 * Validate frontend fields.
	 *
	 * Validate payment fields on the frontend.
	 *
	 * @return bool
	 */
	public function validate_fields() {
		return true;
	}

	/**
	 * If There are no payment fields show the description if set.
	 * Override this in your gateway if you have some.
	 */
	public function payment_fields() {
		$description = $this->get_description();

		if ( $description ) {
			echo wpautop( wptexturize( $description ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Check if a gateway supports a given feature.
	 *
	 * Gateways should override this to declare support (or lack of support) for a feature.
	 *
	 * @since 0.1.0
	 *
	 * @param string $feature string The name of a feature to test support for.
	 * @return bool True if the gateway supports the feature, false otherwise.
	 */
	public function supports( $feature ) {
		return apply_filters( 'masteriyo_payment_gateway_supports', in_array( $feature, $this->supports, true ), $feature, $this );
	}

	/**
	 * Can the order be refunded via this gateway?
	 *
	 * Should be extended by gateways to do their own checks.
	 *
	 * @since 0.1.0
	 *
	 * @param  Order $order Order object.
	 * @return bool If false, the automatic refund button is hidden in the UI.
	 */
	public function can_refund_order( $order ) {
		return $order && $this->supports( 'refund' );
	}

	/**
	 * Get option from DB.
	 *
	 * Gets an option from the settings API, using defaults if necessary to prevent undefined notices.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $key Option key.
	 * @param  mixed  $default Value when empty.
	 * @return string The value specified for the option or a default value for the option.
	 */
	public function get_option( $key, $default = null ) {
		$option_name = 'payments.' . $this->get_name() . '_' . $key;

		return masteriyo_get_setting_value( $option_name, $default );
	}
}
