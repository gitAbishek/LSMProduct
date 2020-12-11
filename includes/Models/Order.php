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
		'order_status'  => null,
		'status'        => false,
		'total'         => 0,
		'discount'      => 0,
		'currency'      => '',
		'product_ids'   => array(),
		'expiry_date'   => '',
		'date_created'  => null,
		'date_modified' => null,
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
	 * Product permalink.
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
	 * Get order order status.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_order_status( $context = 'view' ) {
		return $this->get_prop( 'order_status', $context );
	}

	/**
	 * Get post status.
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
	 * Get the discount.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return double
	 */
	public function get_discount( $context = 'view' ) {
		return $this->get_prop( 'discount', $context );
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
	 * Get the product ids.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return array
	 */
	public function get_product_ids( $context = 'view' ) {
		return $this->get_prop( 'product_ids', $context );
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

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set order order status.
	 *
	 * @since 0.1.0
	 *
	 * @param string $order_status Order status.
	 */
	public function set_order_status( $order_status ) {
		$this->set_prop( 'order_status', $order_status );
	}

	/**
	 * Set post status.
	 *
	 * @since 0.1.0
	 *
	 * @param string $status post status.
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
	 * Set order discount.
	 *
	 * @since 0.1.0
	 *
	 * @param double $discount order discount.
	 */
	public function set_discount( $discount ) {
		$this->set_prop( 'discount', $discount );
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
	 * Set ids of products in the order.
	 *
	 * @since 0.1.0
	 *
	 * @param array $product_ids order product_ids.
	 */
	public function set_product_ids( $product_ids ) {
		$this->set_prop( 'product_ids', $product_ids );
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
}
