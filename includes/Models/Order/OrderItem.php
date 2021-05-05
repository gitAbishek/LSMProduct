<?php
/**
 * OrderItem model.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Models;
 */

namespace ThemeGrill\Masteriyo\Models\Order;

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
		'order_id' => 0,
		'name'     => '',
	);

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

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
	 * Get order item type.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_type() {
		return '';
	}

	/**
	 * Get quantity.
	 *
	 * @since 0.1.0
	 *
	 * @return int
	 */
	public function get_quantity() {
		return 1;
	}

	/**
	 * Get parent order object.
	 *
	 * @since 0.1.0
	 *
	 * @return Order
	 */
	public function get_order() {
		return masteriyo_get_order( $this->get_order_id() );
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
		$this->set_prop( 'order_id', absint( $order_id ) );
	}

	/**
	 * Set the product name.
	 *
	 * @since 0.1.0
	 *
	 * @param string $name Product ID.
	 */
	public function set_name( $name ) {
		$this->set_prop( 'name', wp_check_invalid_utf8( $name ) );
	}

	/*
	|--------------------------------------------------------------------------
	| Other Methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * OrderItem type checking.
	 *
	 * @since 0.1.0
	 *
	 * @param  string|array $type Type.
	 * @return boolean
	 */
	public function is_type( $type ) {
		return is_array( $type ) ? in_array( $this->get_type(), $type, true ) : $type === $this->get_type();
	}
}
