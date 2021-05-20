<?php
/**
 * Abstracr order
 *
 * @package ThemeGrill\Masteriyo\Order
 * @since 0.1.0
 */

namespace ThemeGrill\Masteriyo\Abstracts;

defined( 'ABSPATH' ) || exit;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Traits\ItemTotals;
use ThemeGrill\Masteriyo\Repository\OrderRepository;

/**
 * Abstract class order.
 *
 * @since 0.1.0
 */
abstract class Order extends Model {
	use ItemTotals;

	/**
	 * This is the name of this object type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $object_type = 'order';

	/**
	 * Post type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $post_type = 'mto-order';

	/**
	 * Cache group.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $cache_group = 'orders';

	/**
	 * Order items will be stored here, sometimes before they persist in the DB.
	 *
	 * @since 0.1.0
	 * @var array
	 */
	protected $items = array();

	/**
	 * Data array.
	 *
	 * @since 0.1.0
	 */
	/**
	 * Stores order data.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $data = array(
		'parent_id'          => 0,
		'status'             => '',
		'currency'           => '',
		'version'            => '',
		'prices_include_tax' => false,
		'date_created'       => null,
		'date_modified'      => null,
		'total'              => 0,
	);


	/**
	 * Order items that need deleting are stored here.
	 *
	 * @since 0.1.0
	 * @var array
	 */
	protected $items_to_delete = array();

	/**
	 * Get the order if ID
	 *
	 * @since 0.1.0
	 *
	 * @param OrderRepository $order_repository Order Repository.
	 */
	public function __construct( OrderRepository $order_repository ) {
		parent::__construct();

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

	/**
	 * Get items for this order.
	 *
	 * @return array
	 */
	public function get_order_items() {
		return masteriyo_get_order_items( array( 'order_id' => $this->get_id() ) );
	}

	/**
	 * Get internal type.
	 *
	 * @return string
	 */
	public function get_type() {
		return $this->post_type;
	}

	/**
	 * Get all class data in array format.
	 *
	 * @since 3.0.0
	 * @return array
	 */
	public function get_data() {
		return array_merge(
			array(
				'id' => $this->get_id(),
			),
			$this->data,
			array(
				'meta_data'    => $this->get_meta_data(),
				'course_lines' => $this->get_items( 'course' ),
			)
		);
	}

	/**
	 * Checks the order status against a passed in status.
	 *
	 * @since  0.1.0
	 *
	 * @param array|string $status Status to check.
	 *
	 * @return bool
	 */
	public function has_status( $status ) {
		return apply_filters( 'masteriyo_order_has_status', ( is_array( $status ) && in_array( $this->get_status(), $status, true ) ) || $this->get_status() === $status, $this, $status );
	}


	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get order parent id.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int
	 */
	public function get_parent_id( $context ) {
		return $this->get_prop( 'parent_id', $context );
	}

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
	 * Get version.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_version( $context = 'view' ) {
		return $this->get_prop( 'version', $context );
	}

	/**
	 * Check whether the prices include tax.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return boolean
	 */
	public function get_prices_include_tax( $context = 'view' ) {
		return $this->get_prop( 'prices_include_tax', $context );
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
	 * Return array of values for calculations.
	 *
	 * @param string $field Field name to return.
	 *
	 * @return array Array of values.
	 */
	protected function get_values_for_total( $field ) {
		$items = array_map(
			function ( $item ) use ( $field ) {
				$value = 0;

				if ( is_callable( array( $item, "get_${field}" ) ) ) {
					$value = masteriyo_add_number_precision( $item->{"get_${field}"}(), false );
				}

				return $value;
			},
			array_values( $this->get_items() )
		);

		return $items;
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set order parent id.
	 *
	 * @since 0.1.0
	 *
	 * @return int
	 */
	public function set_parent_id( $parent_id ) {
		if ( $parent_id && ( $parent_id === $this->get_id() || is_null( masteriyo_get_order( $parent_id ) ) ) ) {
			$this->error( 'order_invalid_parent_id', __( 'Invalid parent ID', 'masteriyo' ) );
		}

		$this->set_prop( 'parent_id', absint( $parent_id ) );
	}

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
	 * Set version.
	 *
	 * @since 0.1.0
	 *
	 * @param string $version Version.
	 */
	public function set_version( $version ) {
		$this->set_prop( 'version', $version );
	}

	/**
	 * Set price include tax.
	 *
	 * @param boolean $value
	 */
	public function set_prices_include_tax( $value ) {
		$this->set_prop( 'prices_include_tax', masteriyo_string_to_bool( $value ) );
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
	 * Set order total.
	 *
	 * @since 0.1.0
	 *
	 * @param double $total order total.
	 */
	public function set_total( $total ) {
		$this->set_prop( 'total', $total );
	}

	/*
	|--------------------------------------------------------------------------
	| CRUD methods
	|--------------------------------------------------------------------------

	/**
	 * Save data to the database.
	 *
	 * @since 3.0.0
	 * @return int order ID
	 */
	public function save() {
		if ( ! $this->repository ) {
			return $this->get_id();
		}

		try {
			/**
			 * Trigger action before saving to the DB. Allows you to adjust object props before save.
			 *
			 * @param Model          $this The object being saved.
			 * @param Repository $repository THe data store persisting the data.
			 */
			do_action( 'masteriyo_before_' . $this->object_type . '_object_save', $this, $this->repository );

			if ( $this->get_id() ) {
				$this->repository->update( $this );
			} else {
				$this->repository->create( $this );
			}

			$this->save_items();

			/**
			 * Trigger action after saving to the DB.
			 *
			 * @param Model          $this The object being saved.
			 * @param Repository $repository THe data store persisting the data.
			 */
			do_action( 'masteriyo_after_' . $this->object_type . '_object_save', $this, $this->repository );

		} catch ( Exception $e ) {
			// Log error.
			error_log( $e->get_message() );
		}

		return $this->get_id();
	}

	/**
	 * Save all order items which are part of this order.
	 *
	 * @since 0.1.0
	 */
	protected function save_items() {
		$items_changed = false;

		foreach ( $this->items_to_delete as $item ) {
			$item->delete();
			$items_changed = true;
		}
		$this->items_to_delete = array();

		// Add/save items.
		foreach ( $this->items as $item_group => $items ) {
			if ( is_array( $items ) ) {
				$items = array_filter( $items );
				foreach ( $items as $item_key => $item ) {
					$item->set_order_id( $this->get_id() );

					$item_id = $item->save();

					// If ID changed (new item saved to DB)...
					if ( $item_id !== $item_key ) {
						$this->items[ $item_group ][ $item_id ] = $item;

						unset( $this->items[ $item_group ][ $item_key ] );

						$items_changed = true;
					}
				}
			}
		}

		if ( $items_changed ) {
			delete_transient( 'masteriyo_order_' . $this->get_id() . '_needs_processing' );
		}
	}

	/**
	 * Remove all line items (products, coupons, shipping, taxes) from the order.
	 *
	 * @since 0.1.0
	 *
	 * @param string $type Order item type. Default null.
	 */
	public function remove_order_items( $type = null ) {
		if ( ! empty( $type ) ) {
			$this->repository->delete_items( $this, $type );

			$group = $this->type_to_group( $type );

			if ( $group ) {
				unset( $this->items[ $group ] );
			}
		} else {
			$this->repository->delete_items( $this );
			$this->items = array();
		}
	}

	/**
	 * Convert a type to a types group.
	 *
	 * @since 0.1.0
	 *
	 * @param string $type type to lookup.
	 * @return string
	 */
	protected function type_to_group( $type ) {
		$type_to_group = apply_filters(
			'masteriyo_order_type_to_group',
			array(
				'course'   => 'course_lines',
				'tax'      => 'tax_lines',
				'shipping' => 'shipping_lines',
				'fee'      => 'fee_lines',
				'coupon'   => 'coupon_lines',
			)
		);
		return isset( $type_to_group[ $type ] ) ? $type_to_group[ $type ] : '';
	}

	/**
	 * Return an array of items/products within this order.
	 *
	 * @since 0.1.0
	 *
	 * @param string|array $types Types of line items to get (array or string).
	 * @return OrderItem[]
	 */
	public function get_items( $types = 'course' ) {
		$items = array();
		$types = array_filter( (array) $types );

		foreach ( $types as $type ) {
			$group = $this->type_to_group( $type );

			if ( $group ) {
				if ( ! isset( $this->items[ $group ] ) ) {
					$this->items[ $group ] = array_filter( $this->repository->read_items( $this, $type ) );
				}
				// Don't use array_merge here because keys are numeric.
				$items = $items + $this->items[ $group ];
			}
		}

		return apply_filters( 'masteriyo_order_get_items', $items, $this, $types );
	}

	/**
	 * Gets the count of order items of a certain type.
	 *
	 * @since 0.1.0
	 *
	 * @param string $item_type Item type to lookup.
	 * @return int|string
	 */
	public function get_item_count( $item_type = '' ) {
		$items = $this->get_items( empty( $item_type ) ? 'course' : $item_type );
		$count = 0;

		foreach ( $items as $item ) {
			$count += $item->get_quantity();
		}

		return apply_filters( 'masteriyo_get_item_count', $count, $item_type, $this );
	}

	/**
	 * Get an order item object, based on its type.
	 *
	 * @since  0.1.0
	 * @param  int  $item_id ID of item to get.
	 * @param  bool $load_from_db Prior to 3.2 this item was loaded direct from WC_Order_Factory, not this object. This param is here for backwards compatility with that. If false, uses the local items variable instead.
	 * @return OrderItem|false
	 */
	public function get_item( $item_id, $load_from_db = true ) {
		if ( $load_from_db ) {
			return WC_Order_Factory::get_order_item( $item_id );
		}

		// Search for item id.
		if ( $this->items ) {
			foreach ( $this->items as $group => $items ) {
				if ( isset( $items[ $item_id ] ) ) {
					return $items[ $item_id ];
				}
			}
		}

		// Load all items of type and cache.
		$type = $this->repository->get_type( $this, $item_id );

		if ( ! $type ) {
			return false;
		}

		$items = $this->get_items( $type );

		return ! empty( $items[ $item_id ] ) ? $items[ $item_id ] : false;
	}

	/**
	 * Get key for where a certain item type is stored in _items.
	 *
	 * @since  0.1.0
	 * @param  string $item object Order item (product, shipping, fee, coupon, tax).
	 * @return string
	 */
	protected function get_items_key( $item ) {
		if ( is_a( $item, '\ThemeGrill\Masteriyo\Models\Order\OrderItemCourse' ) ) {
			return 'course_lines';
		}

		return apply_filters( 'masteriyo_get_items_key', '', $item );
	}

	/**
	 * Remove item from the order.
	 *
	 * @since 0.1.0
	 *
	 * @param int $item_id Item ID to delete.
	 * @return false|void
	 */
	public function remove_item( $item_id ) {
		$item      = $this->get_item( $item_id, false );
		$items_key = $item ? $this->get_items_key( $item ) : false;

		if ( ! $items_key ) {
			return false;
		}

		// Unset and remove later.
		$this->items_to_delete[] = $item;
		unset( $this->items[ $items_key ][ $item->get_id() ] );
	}

	/**
	 * Adds an order item to this order. The order item will not persist until save.
	 *
	 * @since 0.1.0
	 * @param OrderItem $item Order item object (product, shipping, fee, coupon, tax).
	 * @return false|void
	 */
	public function add_item( $item ) {
		$items_key = $this->get_items_key( $item );

		if ( ! $items_key ) {
			return false;
		}

		// Make sure existing items are loaded so we can append this new one.
		if ( ! isset( $this->items[ $items_key ] ) ) {
			$this->items[ $items_key ] = $this->get_items( $item->get_type() );
		}

		// Set parent.
		$item->set_order_id( $this->get_id() );

		// Append new row with generated temporary ID.
		$item_id = $item->get_id();

		if ( $item_id ) {
			$this->items[ $items_key ][ $item_id ] = $item;
		} else {
			$this->items[ $items_key ][ 'new:' . $items_key . count( $this->items[ $items_key ] ) ] = $item;
		}
	}

	/**
	 * Helper function.
	 * If you add all items in this order in cart again, this would be the cart subtotal (assuming all other settings are same).
	 *
	 * @since 0.1.0
	 *
	 * @return float Cart subtotal.
	 */
	protected function get_cart_subtotal_for_order() {
		return masteriyo_remove_number_precision(
			$this->get_rounded_items_total(
				$this->get_values_for_total( 'subtotal' )
			)
		);
	}

	/**
	 * Helper function.
	 * If you add all items in this order in cart again, this would be the cart total (assuming all other settings are same).
	 *
	 * @since 0.1.0
	 *
	 * @return float Cart total.
	 */
	protected function get_cart_total_for_order() {
		return masteriyo_remove_number_precision(
			$this->get_rounded_items_total(
				$this->get_values_for_total( 'total' )
			)
		);
	}

	/**
	 * Calculate totals by looking at the contents of the order. Stores the totals and returns the orders final total.
	 *
	 * @since 0.1.0
	 * @param  bool $and_taxes Calc taxes if true.
	 * @return float calculated grand total.
	 */
	public function calculate_totals( $and_taxes = true ) {
		do_action( 'masteriyo_order_before_calculate_totals', $and_taxes, $this );

		$cart_subtotal = $this->get_cart_subtotal_for_order();
		$cart_total    = $this->get_cart_total_for_order();

		$this->set_total( masteriyo_round( $cart_total, masteriyo_get_price_decimals() ) );

		do_action( 'masteriyo_order_after_calculate_totals', $and_taxes, $this );

		$this->save();

		return $this->get_total();
	}
}
