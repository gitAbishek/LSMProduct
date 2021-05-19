<?php

/**
 * Class order item repository.
 *
 * @package ThemeGrill\Masteriyo\Abstracts
 * @since 0.1.0
 * @version 0.1.0
 */

namespace ThemeGrill\Masteriyo\Abstracts;

use ThemeGrill\Masteriyo\Repository\AbstractRepository;
use ThemeGrill\Masteriyo\Contracts\OrderItemRepository as OrderItemRepositoryInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Order Item repository.
 */
abstract class OrderItemRepository extends AbstractRepository {

	/**
	 * Meta type. This should match up with
	 * the types available at https://developer.wordpress.org/reference/functions/add_metadata/.
	 * WP defines 'post', 'user', 'comment', and 'term'.
	 *
	 * @var string
	 */
	protected $meta_type = 'order_item';

	/**
	 * This only needs set if you are using a custom metadata type (for example payment tokens.
	 * This should be the name of the field your table uses for associating meta with objects.
	 * For example, in payment_tokenmeta, this would be payment_token_id.
	 *
	 * @var string
	 */
	protected $object_id_field_for_meta = 'order_item_id';

	/**
	 * Create a new order item in the database.
	 *
	 * @since 0.1.0
	 * @param OrderItem $item Order item object.
	 */
	public function create( &$item ) {
		global $wpdb;

		$is_success = $wpdb->insert(
			$wpdb->prefix . 'masteriyo_order_items',
			apply_filters(
				'masteriyo_new_order_item',
				array(
					'order_item_name' => $item->get_name(),
					'order_item_type' => $item->get_type(),
					'order_id'        => $item->get_order_id(),
				),
				$item
			)
		);

		if ( $is_success && $wpdb->insert_id ) {
			$item->set_id( $wpdb->insert_id );
			$this->update_custom_table_meta( $item, true );
			$item->save_meta_data();
			$item->apply_changes();
			$this->clear_cache( $item );

			do_action( 'masteriyo_new_order_item', $item->get_id(), $item, $item->get_order_id() );
		}

	}

	/**
	 * Update a order item in the database.
	 *
	 * @since 0.1.0
	 * @param OrderItem $item Order item object.
	 */
	public function update( Model &$item ) {
		global $wpdb;

		$changes = $item->get_changes();

		if ( array_intersect( array( 'order_item_name', 'order_id' ), array_keys( $changes ) ) ) {
			$wpdb->update(
				$wpdb->prefix . 'masteriyo_order_items',
				array(
					'order_item_name' => $item->get_name(),
					'order_item_type' => $item->get_type(),
					'order_id'        => $item->get_order_id(),
				),
				array( 'order_item_id' => $item->get_id() )
			);
		}

		$this->update_custom_table_meta( $item );
		$item->save_meta_data();
		$item->apply_changes();
		$this->clear_cache( $item );

		do_action( 'masteriyo_update_order_item', $item->get_id(), $item, $item->get_order_id() );
	}

	/**
	 * Remove an order item from the database.
	 *
	 * @since 0.1.0
	 * @param OrderItem $item Order item object.
	 * @param array         $args Array of args to pass to the delete method.
	 */
	public function delete( &$item, $args = array() ) {
		if ( $item->get_id() ) {
			global $wpdb;
			do_action( 'masteriyo_before_delete_order_item', $item->get_id() );
			$wpdb->delete( $wpdb->prefix . 'masteriyo_order_items', array( 'order_item_id' => $item->get_id() ) );
			$wpdb->delete( $wpdb->prefix . 'masteriyo_order_itemmeta', array( 'order_item_id' => $item->get_id() ) );
			do_action( 'masteriyo_delete_order_item', $item->get_id() );
			$this->clear_cache( $item );
		}
	}

	/**
	 * Read a order item from the database.
	 *
	 * @since 0.1.0
	 *
	 * @param OrderItem $item Order item object.
	 *
	 * @throws Exception If invalid order item.
	 */
	public function read( &$item ) {
		global $wpdb;

		$item->set_defaults();

		// Get from cache if available.
		$data = wp_cache_get( 'item-' . $item->get_id(), 'order-items' );

		if ( false === $data ) {
			$data = $wpdb->get_row( $wpdb->prepare( "SELECT order_id, name FROM {$wpdb->prefix}masteriyo_order_items WHERE order_item_id = %d LIMIT 1;", $item->get_id() ) );
			wp_cache_set( 'item-' . $item->get_id(), $data, 'order-items' );
		}

		if ( ! $data ) {
			throw new Exception( __( 'Invalid order item.', 'masteriyo' ) );
		}

		$item->set_props(
			array(
				'order_id'        => $data->order_id,
				'order_item_name' => $data->name,
			)
		);
		$item->read_meta_data();
	}

	/**
	 * Clear meta cache.
	 *
	 * @param OrderItem $item Order item object.
	 */
	public function clear_cache( &$item ) {
		wp_cache_delete( 'item-' . $item->get_id(), 'masteriyo-order-items' );
		wp_cache_delete( 'order-items-' . $item->get_order_id(), 'masteriyo-orders' );
		wp_cache_delete( $item->get_id(), $this->meta_type . '_meta' );
	}
}
