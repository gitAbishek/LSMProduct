<?php
/**
 * Order model.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Models;
 */

namespace ThemeGrill\Masteriyo\Models;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Repository\OrderRepository;
use ThemeGrill\Masteriyo\Helper\Utils;
use ThemeGrill\Masteriyo\Cache\CacheInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Order model (post type).
 *
 * @since 0.1.0
 */
class Order extends Model {

	/**
	 * This is the name of this object type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $object_type = 'masteriyo_order';

	/**
	 * Post type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $post_type = 'masteriyo_order';

	/**
	 * Cache group.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $cache_group = 'orders';

	/**
	 * Stores order data.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $data = array(
		'status'              => false,
		'total'               => 0,
		'currency'            => '',
		'expiry_date'         => '',
		'date_created'        => null,
		'date_modified'       => null,
		'customer_id'         => null,
		'payment_method'      => '',
		'transaction_id'      => '',
		'date_paid'           => '',
		'date_completed'      => '',
		'created_via'         => '',
		'customer_ip_address' => '',
		'customer_user_agent' => '',
	);

	/**
	 * Get the order if ID
	 *
	 * @since 0.1.0
	 *
	 * @param OrderRepository $order_repository Order Repository.
	 */
	public function __construct( OrderRepository $order_repository ) {
		$this->repository = $order_repository;
	}

	/*
	|--------------------------------------------------------------------------
	| Non-CRUD Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Order permalink.
	 *
	 * @return string
	 */
	public function get_permalink() {
		return get_permalink( $this->get_id() );
	}

	/**
	 * Returns the children IDs if applicable. Overridden by child classes.
	 *
	 * @return array of IDs
	 */
	public function get_children() {
		return array();
	}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get order status.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_status( $context = 'view' ) {
		return $this->get_prop( 'status', $context );
	}

	/**
	 * Get the total.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return double
	 */
	public function get_total( $context = 'view' ) {
		return $this->get_prop( 'total', $context );
	}

	/**
	 * Get the currency.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_currency( $context = 'view' ) {
		return $this->get_prop( 'currency', $context );
	}

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
	 * Get order created date.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_date_created( $context = 'view' ) {
		return $this->get_prop( 'date_created', $context );
	}

	/**
	 * Get order modified date.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_date_modified( $context = 'view' ) {
		return $this->get_prop( 'date_modified', $context );
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

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set order status.
	 *
	 * @since 0.1.0
	 *
	 * @param string $status Order status.
	 */
	public function set_status( $status ) {
		$this->set_prop( 'status', $status );
	}

	/**
	 * Set order total.
	 *
	 * @since 0.1.0
	 *
	 * @param double $total order total.
	 */
	public function set_total( $total ) {
		$this->set_prop( 'total', $total );
	}

	/**
	 * Set order currency.
	 *
	 * @since 0.1.0
	 *
	 * @param string $currency Money currency.
	 */
	public function set_currency( $currency ) {
		$this->set_prop( 'currency', $currency );
	}

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
	 * Set order created date.
	 *
	 * @since 0.1.0
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_date_created( $date = null ) {
		$this->set_prop( 'date_created', $date );
	}

	/**
	 * Set order modified date.
	 *
	 * @since 0.1.0
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_date_modified( $date = null ) {
		$this->set_prop( 'date_modified', $date );
	}

	/**
	 * Set customer/user ID.
	 *
	 * @since 0.1.0
	 *
	 * @param integer $id Customer/User ID.
	 */
	public function set_customer_id( $id ) {
		$this->set_prop( 'customer_id', $id );
	}

	/**
	 * Set payment method.
	 *
	 * @since 0.1.0
	 *
	 * @param string $payment_method Payment method.
	 */
	public function set_payment_method( $payment_method ) {
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
}
