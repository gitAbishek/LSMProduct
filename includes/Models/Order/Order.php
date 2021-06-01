<?php
/**
 * Order model.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Models;
 */

namespace ThemeGrill\Masteriyo\Models\Order;

use ThemeGrill\Masteriyo\Helper\Utils;
use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Abstracts\Order as AbstractOrder;
use ThemeGrill\Masteriyo\Cache\CacheInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Order model (post type).
 *
 * @since 0.1.0
 */
class Order extends AbstractOrder {

	/**
	 * Stores data about status changes so relevant hooks can be fired.
	 *
	 * @since 0.1.0
	 *
	 * @var bool|array
	 */
	protected $status_transition = false;

	/**
	 * Stores order data.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $data = array(
		// Abstract order props.
		'parent_id'            => 0,
		'status'               => '',
		'currency'             => '',
		'version'              => '',
		'prices_include_tax'   => false,
		'date_created'         => null,
		'date_modified'        => null,
		'total'                => 0,

		// Order props.
		'expiry_date'          => '',
		'customer_id'          => null,
		'payment_method'       => '',
		'payment_method_title' => '',
		'transaction_id'       => '',
		'date_paid'            => '',
		'date_completed'       => '',
		'created_via'          => '',
		'customer_ip_address'  => '',
		'customer_user_agent'  => '',
		'order_key'            => '',
		'customer_note'        => '',
		'cart_hash'            => '',

		// Billing details.
		'billing_first_name'   => '',
		'billing_last_name'    => '',
		'billing_company'      => '',
		'billing_address_1'    => '',
		'billing_address_2'    => '',
		'billing_city'         => '',
		'billing_postcode'     => '',
		'billing_country'      => '',
		'billing_state'        => '',
		'billing_email'        => '',
		'billing_phone'        => '',
	);

	/**
	 * Get object type.
	 *
	 * @since 0.1.0
	 */
	public function get_object_type() {
		return 'mto-order';
	}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the expiry date.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_expiry_date( $context = 'view' ) {
		return $this->get_prop( 'expiry_date', $context );
	}

	/**
	 * Get customer/user ID.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_customer_id( $context = 'view' ) {
		return $this->get_prop( 'customer_id', $context );
	}

	/**
	 * Get the payment method.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_payment_method( $context = 'view' ) {
		return $this->get_prop( 'payment_method', $context );
	}

	/**
	 * Get the transaction id.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_transaction_id( $context = 'view' ) {
		return $this->get_prop( 'transaction_id', $context );
	}

	/**
	 * Get the date of the payment.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_date_paid( $context = 'view' ) {
		return $this->get_prop( 'date_paid', $context );
	}

	/**
	 * Get the date of order completion.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_date_completed( $context = 'view' ) {
		return $this->get_prop( 'date_completed', $context );
	}

	/**
	 * Get the order creation method. It might be admin, checkout, or any other way.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_created_via( $context = 'view' ) {
		return $this->get_prop( 'created_via', $context );
	}

	/**
	 * Get the customer IP address.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_customer_ip_address( $context = 'view' ) {
		return $this->get_prop( 'customer_ip_address', $context );
	}

	/**
	 * Get the customer's user agent.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_customer_user_agent( $context = 'view' ) {
		return $this->get_prop( 'customer_user_agent', $context );
	}


	/**
	 * Get order_key.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_order_key( $context = 'view' ) {
		return $this->get_prop( 'order_key', $context );
	}

	/**
	 * Get customer_note.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_customer_note( $context = 'view' ) {
		return $this->get_prop( 'customer_note', $context );
	}

	/**
	 * Get cart_hash.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_cart_hash( $context = 'view' ) {
		return $this->get_prop( 'cart_hash', $context );
	}

	/**
	 * Get user's billing first name.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_billing_first_name( $context = 'view' ) {
		return $this->get_prop( 'billing_first_name', $context );
	}

	/**
	 * Get user's billing last name.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_billing_last_name( $context = 'view' ) {
		return $this->get_prop( 'billing_last_name', $context );
	}

	/**
	 * Get user's billing company.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_billing_company( $context = 'view' ) {
		return $this->get_prop( 'billing_company', $context );
	}

	/**
	 * Get user's billing address.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_billing_address( $context = 'view' ) {
		return $this->get_billing_address_1( $context );
	}

	/**
	 * Get user's billing address 1.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_billing_address_1( $context = 'view' ) {
		return $this->get_prop( 'billing_address_1', $context );
	}

	/**
	 * Get user's billing address 1.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_billing_address_2( $context = 'view' ) {
		return $this->get_prop( 'billing_address_2', $context );
	}

	/**
	 * Get user's billing city.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_billing_city( $context = 'view' ) {
		return $this->get_prop( 'billing_city', $context );
	}

	/**
	 * Get user's billing post code.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_billing_postcode( $context = 'view' ) {
		return $this->get_prop( 'billing_postcode', $context );
	}

	/**
	 * Get user's billing country.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_billing_country( $context = 'view' ) {
		return $this->get_prop( 'billing_country', $context );
	}

	/**
	 * Get user's billing state.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_billing_state( $context = 'view' ) {
		return $this->get_prop( 'billing_state', $context );
	}

	/**
	 * Get user's billing email.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_billing_email( $context = 'view' ) {
		return $this->get_prop( 'billing_email', $context );
	}

	/**
	 * Get user's billing phone number.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_billing_phone( $context = 'view' ) {
		return $this->get_prop( 'billing_phone', $context );
	}

	/**
	 * Alias for get_customer_id().
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return integer
	 */
	public function get_user_id( $context = 'view' ) {
		return $this->get_customer_id( $context );
	}

	/**
	 * Get the customer associated with the order. False for guests.
	 *
	 * @since 0.1.0
	 *
	 * @return User|false
	 */
	public function get_customer() {
		return $this->get_customer_id() ? masteriyo_get_user( $this->get_customer_id() ) : false;
	}

	/**
	 * Alias for get_customer().
	 *
	 * @since 0.1.0
	 *
	 * @return User|false
	 */
	public function get_user() {
		return $this->get_customer();
	}

	/**
	 * Get payment method title.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_payment_method_title( $context = 'view' ) {
		return $this->get_prop( 'payment_method_title', $context );
	}

	/**
	 * Returns the order billing address in raw, non-formatted way.
	 *
	 * @since 0.1.0
	 *
	 * @return array The stored address after filter.
	 */
	public function get_address() {
		return apply_filters(
			'masteriyo_get_order_address',
			array(
				'first_name' => $this->get_billing_first_name(),
				'last_name'  => $this->get_billing_last_name(),
				'company'    => $this->get_billing_company(),
				'address_1'  => $this->get_billing_address_1(),
				'address_2'  => $this->get_billing_address_2(),
				'city'       => $this->get_billing_city(),
				'postcode'   => $this->get_billing_postcode(),
				'country'    => $this->get_billing_country(),
				'state'      => $this->get_billing_state(),
				'email'      => $this->get_billing_email(),
				'phone'      => $this->get_billing_phone(),
			),
			$this
		);
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set order expiry date.
	 *
	 * @since 0.1.0
	 *
	 * @param string|integer|null $expiry_date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_expiry_date( $expiry_date ) {
		$this->set_prop( 'expiry_date', $expiry_date );
	}

	/**
	 * Set customer/user ID.
	 *
	 * @since 0.1.0
	 *
	 * @param integer $id Customer/User ID.
	 */
	public function set_customer_id( $id ) {
		$this->set_prop( 'customer_id', absint( $id ) );
	}

	/**
	 * Set payment method.
	 *
	 * @since 0.1.0
	 *
	 * @param string|PaymentGateway $payment_method Payment method.
	 */
	public function set_payment_method( $payment_method ) {
		if ( is_a( $payment_method, 'ThemeGrill\Masteriyo\Abstracts\PaymentGateway' ) ) {
			$payment_method = $payment_method->get_method_title();
		}

		$this->set_prop( 'payment_method', $payment_method );
	}

	/**
	 * Set transaction ID.
	 *
	 * @since 0.1.0
	 *
	 * @param string $transaction_id Transaction ID.
	 */
	public function set_transaction_id( $transaction_id ) {
		$this->set_prop( 'transaction_id', $transaction_id );
	}

	/**
	 * Set date of payment.
	 *
	 * @since 0.1.0
	 *
	 * @param string $date_paid Date.
	 */
	public function set_date_paid( $date_paid ) {
		$this->set_prop( 'date_paid', $date_paid );
	}

	/**
	 * Set date of order completion.
	 *
	 * @since 0.1.0
	 *
	 * @param string $date_completed Date.
	 */
	public function set_date_completed( $date_completed ) {
		$this->set_prop( 'date_completed', $date_completed );
	}

	/**
	 * Set method of order creation. Like admin, checkout etc.
	 *
	 * @since 0.1.0
	 *
	 * @param string $created_via Method.
	 */
	public function set_created_via( $created_via ) {
		$this->set_prop( 'created_via', $created_via );
	}

	/**
	 * Set customer's IP address.
	 *
	 * @since 0.1.0
	 *
	 * @param string $customer_ip_address IP address.
	 */
	public function set_customer_ip_address( $customer_ip_address ) {
		$this->set_prop( 'customer_ip_address', $customer_ip_address );
	}

	/**
	 * Set customer's user agent.
	 *
	 * @since 0.1.0
	 *
	 * @param string $customer_user_agent User agent.
	 */
	public function set_customer_user_agent( $customer_user_agent ) {
		$this->set_prop( 'customer_user_agent', $customer_user_agent );
	}

	/**
	 * Set order_key.
	 *
	 * @since 0.1.0
	 *
	 * @param string $order_key order_key.
	 */
	public function set_order_key( $order_key ) {
		$this->set_prop( 'order_key', $order_key );
	}

	/**
	 * Set customer note.
	 *
	 * @since 0.1.0
	 *
	 * @param string $customer_note Customer note.
	 */
	public function set_customer_note( $customer_note ) {
		$this->set_prop( 'customer_note', $customer_note );
	}

	/**
	 * Set cart_hash.
	 *
	 * @since 0.1.0
	 *
	 * @param string $cart_hash cart_hash.
	 */
	public function set_cart_hash( $cart_hash ) {
		$this->set_prop( 'cart_hash', $cart_hash );
	}

	/**
	 * Set user's billing first name.
	 *
	 * @since 0.1.0
	 *
	 * @param string $first_name User's billing first name.
	 * @return void
	 */
	public function set_billing_first_name( $first_name ) {
		$this->set_prop( 'billing_first_name', $first_name );
	}

	/**
	 * Set user's billing last name.
	 *
	 * @since 0.1.0
	 *
	 * @param string $last_name User's billing last name.
	 * @return void
	 */
	public function set_billing_last_name( $last_name ) {
		$this->set_prop( 'billing_last_name', $last_name );
	}

	/**
	 * Set user's billing company.
	 *
	 * @since 0.1.0
	 *
	 * @param string $company User's billing company.
	 * @return void
	 */
	public function set_billing_company( $company ) {
		$this->set_prop( 'billing_company', $company );
	}

	/**
	 * Set user's billing address_1.
	 *
	 * @since 0.1.0
	 *
	 * @param string $address_1 User's billing address_1.
	 * @return void
	 */
	public function set_billing_address_1( $address_1 ) {
		$this->set_prop( 'billing_address_1', $address_1 );
	}

	/**
	 * Set user's billing address_2.
	 *
	 * @since 0.1.0
	 *
	 * @param string $address_2 User's billing address_2.
	 * @return void
	 */
	public function set_billing_address_2( $address_2 ) {
		$this->set_prop( 'billing_address_2', $address_2 );
	}

	/**
	 * Set user's billing city.
	 *
	 * @since 0.1.0
	 *
	 * @param string $city User's billing city.
	 */
	public function set_billing_city( $city ) {
		$this->set_prop( 'billing_city', $city );
	}

	/**
	 * Set user's billing post code.
	 *
	 * @since 0.1.0
	 *
	 * @param string $postcode User's billing post code.
	 */
	public function set_postcode( $postcode ) {
		$this->set_prop( 'billing_postcode', $postcode );
	}


	/**
	 * Set user's billing country.
	 *
	 * @since 0.1.0
	 *
	 * @param string $country User's country.
	 */
	public function set_billing_country( $country ) {
		$this->set_prop( 'billing_country', $country );
	}

	/**
	 * Set user's billing state.
	 *
	 * @since 0.1.0
	 *
	 * @param string $state User's billing state.
	 */
	public function set_billing_state( $state ) {
		$this->set_prop( 'billing_state', $state );
	}

	/**
	 * Set user's billing email.
	 *
	 * @since 0.1.0
	 *
	 * @param string $email User's billing email.
	 */
	public function set_billing_email( $email ) {
		$this->set_prop( 'billing_email', $email );
	}

	/**
	 * Set user's billing phone.
	 *
	 * @since 0.1.0
	 *
	 * @param string $phone User's billing phone.
	 */
	public function set_billing_phone( $phone ) {
		$this->set_prop( 'billing_phone', $phone );
	}

	/**
	 * Set payment method title.
	 *
	 * @since 0.1.0
	 *
	 * @param string $value Payment method title.
	 */
	public function set_payment_method_title( $value ) {
		$this->set_prop( 'payment_method_title', $value );
	}

	/**
	 * Maybe set date paid.
	 *
	 * Sets the date paid variable when transitioning to the payment complete
	 * order status. This is either processing or completed. This is not filtered
	 * to avoid infinite loops e.g. if loading an order via the filter.
	 *
	 * Date paid is set once in this manner - only when it is not already set.
	 * This ensures the data exists even if a gateway does not use the
	 * `payment_complete` method.
	 *
	 * @since 0.1.0
	 */
	public function maybe_set_date_paid() {
		// This logic only runs if the date_paid prop has not been set yet.
		if ( ! $this->get_date_paid( 'edit' ) ) {
			$payment_completed_status = apply_filters( 'masteriyo_payment_complete_order_status', $this->needs_processing() ? 'processing' : 'completed', $this->get_id(), $this );

			if ( $this->has_status( $payment_completed_status ) ) {
				// If payment complete status is reached, set paid now.
				$this->set_date_paid( time() );

			} elseif ( 'processing' === $payment_completed_status && $this->has_status( 'completed' ) ) {
				// If payment complete status was processing, but we've passed that and still have no date, set it now.
				$this->set_date_paid( time() );
			}
		}
	}

	/**
	 * Maybe set date completed.
	 *
	 * Sets the date completed variable when transitioning to completed status.
	 *
	 * @since 0.1.0
	 */
	protected function maybe_set_date_completed() {
		if ( $this->has_status( 'completed' ) ) {
			$this->set_date_completed( time() );
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Conditionals
	|--------------------------------------------------------------------------
	|
	| Checks if a condition is true or false.
	|
	*/

	/**
	 * Returns true if the order has a billing address.
	 *
	 * @since 0.1.0
	 *
	 * @return boolean
	 */
	public function has_billing_address() {
		return $this->get_billing_address_1() || $this->get_billing_address_2();
	}

	/**
	 * Check if an order key is valid.
	 *
	 * @since 0.1.0
	 *
	 * @param string $key Order key.
	 *
	 * @return bool
	 */
	public function key_is_valid( $key ) {
		return hash_equals( $this->get_order_key(), $key );
	}

	/**
	 * See if order matches cart_hash.
	 *
	 * @since 0.1.0
	 *
	 * @param string $cart_hash Cart hash.
	 *
	 * @return bool
	 */
	public function has_cart_hash( $cart_hash = '' ) {
		return hash_equals( $this->get_cart_hash(), $cart_hash ); // @codingStandardsIgnoreLine
	}

	/**
	 * Checks if an order can be edited, specifically for use on the Edit Order screen.
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	public function is_editable() {
		return apply_filters( 'masteriyo_order_is_editable', in_array( $this->get_status(), array( 'mto-pending', 'mto-on-hold' ), true ), $this );
	}

	/**
	 * Returns if an order has been paid for based on the order status.
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	public function is_paid() {
		return apply_filters( 'masteriyo_order_is_paid', $this->has_status( masteriyo_get_is_paid_statuses() ), $this );
	}

	/**
	 * Checks if an order needs payment, based on status and order total.
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	public function needs_payment() {
		$valid_order_statuses = apply_filters( 'masteriyo_valid_order_statuses_for_payment', array( 'pending', 'failed' ), $this );
		return apply_filters( 'masteriyo_order_needs_payment', ( $this->has_status( $valid_order_statuses ) && $this->get_total() > 0 ), $this, $valid_order_statuses );
	}

	/**
	 * When a payment is complete this function is called.
	 *
	 * Most of the time this should mark an order as 'processing' so that admin can process/post the items.
	 * If the cart contains only downloadable items then the order is 'completed' since the admin needs to take no action.
	 * Stock levels are reduced at this point.
	 * Sales are also recorded for products.
	 * Finally, record the date of payment.
	 *
	 * @since 0.1.0
	 *
	 * @param string $transaction_id Optional transaction id to store in post meta.
	 * @return bool success
	 */
	public function payment_complete( $transaction_id = '' ) {
		if ( ! $this->get_id() ) { // Order must exist.
			return false;
		}

		try {
			do_action( 'masteriyo_pre_payment_complete', $this->get_id() );

			if ( ! is_null( masteriyo( 'session' ) ) ) {
				masteriyo( 'session' )->put( 'order_awaiting_payment', false );
			}

			$statuses = apply_filters(
				'masteriyo_valid_order_statuses_for_payment_complete',
				array( 'on-hold', 'pending', 'failed', 'cancelled' ),
				$this
			);

			if ( $this->has_status( $statuses ) ) {
				if ( ! empty( $transaction_id ) ) {
					$this->set_transaction_id( $transaction_id );
				}

				if ( ! $this->get_date_paid( 'edit' ) ) {
					$this->set_date_paid( time() );
				}

				$order_status   = $this->needs_processing() ? 'processing' : 'completed';
				$payment_status = apply_filters( 'masteriyo_payment_complete_order_status', $order_status, $this->get_id(), $this );

				$this->set_status( $payment_status );
				$this->save();

				do_action( 'masteriyo_payment_complete', $this->get_id() );
			} else {
				do_action( 'masteriyo_payment_complete_order_status_' . $this->get_status(), $this->get_id() );
			}
		} catch ( Exception $e ) {
			return false;
		}
		return true;
	}

	/**
	 * See if the order needs processing before it can be completed.
	 *
	 * @since 0.1.0
	 * @return bool
	 */
	public function needs_processing() {
		return false;
	}

	/**
	 * Set order status.
	 *
	 * @since 0.1.0
	 * @param string $new_status    Status to change the order to. No internal masteriyo- prefix is required.
	 * @param string $note          Optional note to add.
	 * @param bool   $manual_update Is this a manual order status change?.
	 * @return array
	 */
	public function set_status( $new_status, $note = '', $manual_update = false ) {
		$result = parent::set_status( $new_status );

		if ( true === $this->object_read && ! empty( $result['from'] ) && $result['from'] !== $result['to'] ) {
			$this->status_transition = array(
				'from'   => ! empty( $this->status_transition['from'] ) ? $this->status_transition['from'] : $result['from'],
				'to'     => $result['to'],
				'note'   => $note,
				'manual' => (bool) $manual_update,
			);

			if ( $manual_update ) {
				do_action( 'masteriyo_order_edit_status', $this->get_id(), $result['to'] );
			}

			$this->maybe_set_date_paid();
			$this->maybe_set_date_completed();
		}

		return $result;
	}

	/**
	 * Get amount already refunded.
	 *
	 * @since 0.1.0
	 * @return string
	 */
	public function get_total_refunded() {
		$cache_key   = masteriyo( 'cache' )->get_prefix( 'orders' ) . 'total_refunded' . $this->get_id();
		$cached_data = masteriyo( 'cache' )->get( $cache_key, $this->cache_group );

		if ( false !== $cached_data ) {
			return $cached_data;
		}

		$total_refunded = $this->repository->get_total_refunded( $this );

		masteriyo( 'cache' )->set( $cache_key, $total_refunded, $this->cache_group );

		return $total_refunded;
	}

	/**
	 * Gets order total - formatted for display.
	 *
	 * @since 0.1.0
	 *
	 * @param string $tax_display      Type of tax display.
	 * @param bool   $display_refunded If should include refunded value.
	 *
	 * @return string
	 */
	public function get_formatted_order_total( $tax_display = '', $display_refunded = true ) {
		$formatted_total = masteriyo_price( $this->get_total(), array( 'currency' => $this->get_currency() ) );
		$order_total     = $this->get_total();
		$total_refunded  = $this->get_total_refunded();
		$tax_string      = '';

		// Tax for inclusive prices.
		if ( masteriyo_is_tax_enabled() && 'incl' === $tax_display ) {
			$tax_string_array = array();
			$tax_totals       = $this->get_tax_totals();

			if ( 'itemized' === get_option( 'masteriyo_tax_total_display' ) ) {
				foreach ( $tax_totals as $code => $tax ) {
					$tax_amount         = ( $total_refunded && $display_refunded ) ? masteriyo_price( masteriyo_round( $tax->amount - $this->get_total_tax_refunded_by_rate_id( $tax->rate_id ) ), array( 'currency' => $this->get_currency() ) ) : $tax->formatted_amount;
					$tax_string_array[] = sprintf( '%s %s', $tax_amount, $tax->label );
				}
			} elseif ( ! empty( $tax_totals ) ) {
				$tax_amount         = ( $total_refunded && $display_refunded ) ? $this->get_total_tax() - $this->get_total_tax_refunded() : $this->get_total_tax();
				$tax_string_array[] = sprintf( '%s %s', masteriyo_price( $tax_amount, array( 'currency' => $this->get_currency() ) ), masteriyo( 'countries' )->tax_or_vat() );
			}

			if ( ! empty( $tax_string_array ) ) {
				/* translators: %s: taxes */
				$tax_string = ' <small class="includes_tax">' . sprintf( __( '(includes %s)', 'masteriyo' ), implode( ', ', $tax_string_array ) ) . '</small>';
			}
		}

		if ( $total_refunded && $display_refunded ) {
			$formatted_total = '<del aria-hidden="true">' . wp_strip_all_tags( $formatted_total ) . '</del> <ins>' . masteriyo_price( $order_total - $total_refunded, array( 'currency' => $this->get_currency() ) ) . $tax_string . '</ins>';
		} else {
			$formatted_total .= $tax_string;
		}

		/**
		 * Filter Masteriyo formatted order total.
		 *
		 * @since 0.1.0
		 *
		 * @param string   $formatted_total  Total to display.
		 * @param Order $order            Order data.
		 * @param string   $tax_display      Type of tax display.
		 * @param bool     $display_refunded If should include refunded value.
		 */
		return apply_filters( 'masteriyo_get_formatted_order_total', $formatted_total, $this, $tax_display, $display_refunded );
	}

	/**
	 * Gets the order number for display (by default, order ID).
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_order_number() {
		return (string) apply_filters( 'masteriyo_order_number', $this->get_id(), $this );
	}

	/**
	 * Check if order has been created via admin, checkout, or in another way.
	 *
	 * @since 0.1.0
	 * @param string $modus Way of creating the order to test for.
	* @return bool
	 */
	public function is_created_via( $modus ) {
		return apply_filters( 'masteriyo_order_is_created_via', $modus === $this->get_created_via(), $this, $modus );
	}

	/*
	|--------------------------------------------------------------------------
	| URLs and Endpoints
	|--------------------------------------------------------------------------
	*/

	/**
	 * Generates a URL for the thanks page (order received).
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_checkout_order_received_url() {
		$order_received_url = masteriyo_get_endpoint_url( 'order-received', $this->get_id(), masteriyo_get_checkout_url() );
		$order_received_url = add_query_arg( 'key', $this->get_order_key(), $order_received_url );

		return apply_filters( 'masteriyo_get_checkout_order_received_url', $order_received_url, $this );
	}

	/**
	 * Generates a URL so that a customer can cancel their (unpaid - pending) order.
	 *
	 * @since 0.1.0
	 *
	 * @param string $redirect Redirect URL.
	 * @return string
	 */
	public function get_cancel_order_url( $redirect = '' ) {
		return apply_filters(
			'masteriyo_get_cancel_order_url',
			wp_nonce_url(
				add_query_arg(
					array(
						'cancel_order' => 'true',
						'order'        => $this->get_order_key(),
						'order_id'     => $this->get_id(),
						'redirect'     => $redirect,
					),
					$this->get_cancel_endpoint()
				),
				'masteriyo-cancel_order'
			)
		);
	}


	/**
	 * Generates a raw (unescaped) cancel-order URL for use by payment gateways.
	 *
	 * 2since 0.1.0
	 *
	 * @param string $redirect Redirect URL.
	 * @return string The unescaped cancel-order URL.
	 */
	public function get_cancel_order_url_raw( $redirect = '' ) {
		return apply_filters(
			'masteriyo_get_cancel_order_url_raw',
			add_query_arg(
				array(
					'cancel_order' => 'true',
					'order'        => $this->get_order_key(),
					'order_id'     => $this->get_id(),
					'redirect'     => $redirect,
					'_wpnonce'     => wp_create_nonce( 'masteriyo-cancel_order' ),
				),
				$this->get_cancel_endpoint()
			)
		);
	}

	/**
	 * Helper method to return the cancel endpoint.
	 *
	 * @since 0.1.0
	 *
	 * @return string the cancel endpoint; either the cart page or the home page.
	 */
	public function get_cancel_endpoint() {
		$cancel_endpoint = masteriyo_get_cart_url();
		if ( ! $cancel_endpoint ) {
			$cancel_endpoint = home_url();
		}

		if ( false === strpos( $cancel_endpoint, '?' ) ) {
			$cancel_endpoint = trailingslashit( $cancel_endpoint );
		}

		return $cancel_endpoint;
	}
}
