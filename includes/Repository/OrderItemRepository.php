<?php
/**
 * OrderItemRepository class.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Repository;
 */

namespace ThemeGrill\Masteriyo\Repository;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Models\OrderItem;

/**
 * OrderItemRepository class.
 */
class OrderItemRepository extends AbstractRepository implements RepositoryInterface {

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 3.0.0
	 * @var array
	 */
	protected $internal_meta_keys = array(
		'_order_id'   => 'order_id',
		'_product_id' => 'product_id',
		'_name'       => 'name',
		'_type'       => 'type',
		'_quantity'   => 'quantity',
		'_tax'        => 'tax',
		'_total'      => 'total',
	);

	/**
	 * Create an order item in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $order_item Order Item object.
	 */
	public function create( Model &$order_item ) {
		$id = wp_insert_post(
			apply_filters(
				'masteriyo_new_order_item_data',
				array(
					'post_type'      => 'masteriyo_order_item',
					'post_status'    => 'publish',
					'post_author'    => get_current_user_id(),
					'post_title'     => __( 'Order Item', 'masteriyo' ),
					'post_content'   => __( 'Order Item', 'masteriyo' ),
					'post_excerpt'   => __( 'Order Item', 'masteriyo' ),
					'comment_status' => 'closed',
					'ping_status'    => 'closed',
				),
				$order_item
			)
		);

		if ( $id && ! is_wp_error( $id ) ) {
			$order_item->set_id( $id );
			$this->update_post_meta( $order_item, true );
			// TODO Invalidate caches.

			$order_item->save_meta_data();
			$order_item->apply_changes();

			do_action( 'masteriyo_new_order_item', $id, $order_item );
		}
	}

	/**
	 * Read an order item.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $order_item Cource object.
	 *
	 * @throws \Exception If invalid order item.
	 */
	public function read( Model &$order_item ) {
		$order_item_post = get_post( $order_item->get_id() );

		if ( ! $order_item->get_id() || ! $order_item_post || 'masteriyo_order_item' !== $order_item_post->post_type ) {
			throw new \Exception( __( 'Invalid order item.', 'masteriyo' ) );
		}

		$this->read_order_data( $order_item );
		$this->read_extra_data( $order_item );
		$order_item->set_object_read( true );

		do_action( 'masteriyo_order_item_read', $order_item->get_id(), $order_item );
	}

	/**
	 * Update an order item in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $order_item Order Item object.
	 *
	 * @return void
	 */
	public function update( Model &$order_item ) {
		$changes = $order_item->get_changes();

		// Only update post modified time to record this save event.
		$GLOBALS['wpdb']->update(
			$GLOBALS['wpdb']->posts,
			array(
				'post_modified'     => current_time( 'mysql' ),
				'post_modified_gmt' => current_time( 'mysql', true ),
			),
			array(
				'ID' => $order_item->get_id(),
			)
		);
		clean_post_cache( $order_item->get_id() );

		$this->update_post_meta( $order_item );

		$order_item->apply_changes();

		do_action( 'masteriyo_update_order_item', $order_item->get_id(), $order_item );
	}

	/**
	 * Delete an order item from the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $order_item Order Item object.
	 * @param  array $args Array of args to pass to the delete method.
	 */
	public function delete( Model &$order_item, $args = array() ) {
		$id          = $order_item->get_id();
		$object_type = $order_item->get_object_type();

		if ( ! $id ) {
			return;
		}

		do_action( 'masteriyo_before_delete_' . $object_type, $id, $order_item );
		wp_delete_post( $id );
		$order_item->set_id( 0 );
		do_action( 'masteriyo_after_delete_' . $object_type, $id, $order_item );
	}

	/**
	 * Read order item data. Can be overridden by child classes to load other props.
	 *
	 * @since 0.1.0
	 *
	 * @param OrderItem $order_item Order Item object.
	 */
	protected function read_order_data( &$order_item ) {
		$id          = $order_item->get_id();
		$meta_values = $this->read_meta( $order_item );

		$set_props = array();

		$meta_values = array_reduce(
			$meta_values,
			function ( $result, $meta_value ) {
				$result[ $meta_value->key ][] = $meta_value->value;
				return $result;
			},
			array()
		);

		foreach ( $this->internal_meta_keys as $meta_key => $prop ) {
			$meta_value         = isset( $meta_values[ $meta_key ][0] ) ? $meta_values[ $meta_key ][0] : null;
			$set_props[ $prop ] = maybe_unserialize( $meta_value ); // get_post_meta only unserializes single values.
		}

		$order_item->set_props( $set_props );
	}

	/**
	 * Read extra data associated with the order item.
	 *
	 * @since 0.1.0
	 *
	 * @param OrderItem $order_item Order Item object.
	 */
	protected function read_extra_data( &$order_item ) {
		$meta_values = $this->read_meta( $order_item );

		foreach ( $order_item->get_extra_data_keys() as $key ) {
			$function = 'set_' . $key;

			if ( is_callable( array( $order_item, $function ) )
				&& isset( $meta_values[ '_' . $key ] ) ) {
				$order_item->{$function}( $meta_values[ '_' . $key ] );
			}
		}
	}
}
