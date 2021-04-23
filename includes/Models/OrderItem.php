<?php
/**
 * OrderItem model.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Models;
 */

namespace ThemeGrill\Masteriyo\Models;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Repository\OrderItemRepository;
use ThemeGrill\Masteriyo\Helper\Utils;
use ThemeGrill\Masteriyo\Cache\CacheInterface;

defined( 'ABSPATH' ) || exit;

/**
 * OrderItem model.
 *
 * @since 0.1.0
 */
class OrderItem extends Model {

	/**
	 * This is the name of this object type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $object_type = 'order_item';

	/**
	 * Cache group.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $cache_group = 'order_items';

	/**
	 * Stores order item data.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $data = array(
		'order_id'   => '',
		'course_id'  => '',
		'name'       => '',
		'type'       => '',
		'quantity'   => 0,
		'tax'        => '',
		'total'      => '',
	);

	/**
	 * Get the order item if ID
	 *
	 * @since 0.1.0
	 *
	 * @param OrderItemRepository $order_item_repository Order Repository.
	 */
	public function __construct( OrderItemRepository $order_item_repository ) {
		$this->repository = $order_item_repository;
	}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the course ID.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_course_id( $context = 'view' ) {
		return $this->get_prop( 'course_id', $context );
	}

	/**
	 * Get the order ID.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_order_id( $context = 'view' ) {
		return $this->get_prop( 'order_id', $context );
	}

	/**
	 * Get the product name.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_name( $context = 'view' ) {
		return $this->get_prop( 'name', $context );
	}

	/**
	 * Get the product type.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_type( $context = 'view' ) {
		return $this->get_prop( 'type', $context );
	}

	/**
	 * Get the products quantity.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_quantity( $context = 'view' ) {
		return $this->get_prop( 'quantity', $context );
	}

	/**
	 * Get the product specific tax.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_tax( $context = 'view' ) {
		return $this->get_prop( 'tax', $context );
	}

	/**
	 * Get the total amount.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_total( $context = 'view' ) {
		return $this->get_prop( 'total', $context );
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set order id.
	 *
	 * @since 0.1.0
	 *
	 * @param string $order_id Product ID.
	 */
	public function set_order_id( $order_id ) {
		$this->set_prop( 'order_id', $order_id );
	}

	/**
	 * Set course id.
	 *
	 * @since 0.1.0
	 *
	 * @param string $course_id Course ID.
	 */
	public function set_course_id( $course_id ) {
		$this->set_prop( 'course_id', $course_id );
	}

	/**
	 * Set the product name.
	 *
	 * @since 0.1.0
	 *
	 * @param string $name Product ID.
	 */
	public function set_name( $name ) {
		$this->set_prop( 'name', $name );
	}

	/**
	 * Set the product type.
	 *
	 * @since 0.1.0
	 *
	 * @param string $type Product ID.
	 */
	public function set_type( $type ) {
		$this->set_prop( 'type', $type );
	}

	/**
	 * Set the product quantity.
	 *
	 * @since 0.1.0
	 *
	 * @param string $quantity Product ID.
	 */
	public function set_quantity( $quantity ) {
		$this->set_prop( 'quantity', $quantity );
	}

	/**
	 * Set the product tax.
	 *
	 * @since 0.1.0
	 *
	 * @param string $tax Product ID.
	 */
	public function set_tax( $tax ) {
		$this->set_prop( 'tax', $tax );
	}

	/**
	 * Set total amount.
	 *
	 * @since 0.1.0
	 *
	 * @param double $total Total amount.
	 */
	public function set_total( $total ) {
		$this->set_prop( 'total', $total );
	}
}
