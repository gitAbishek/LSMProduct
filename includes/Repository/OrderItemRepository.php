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
	 * Meta type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $meta_type = 'order_item';

	/**
	 * This only needs set if you are using a custom metadata type (for example payment tokens.
	 * This should be the name of the field your table uses for associating meta with objects.
	 * For example, in payment_tokenmeta, this would be payment_token_id.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $object_id_field_for_meta = 'order_item_id';

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $internal_meta_keys = array();

	/**
	 * Create an order item in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $order_item Order Item object.
	 */
	public function create( Model &$order_item ) {
		global $wpdb;

		$is_success = $wpdb->insert(
			$this->get_table_name(),
			apply_filters(
				'masteriyo_new_course_data',
				array(
					'order_id'   => $order_item->get_order_id( 'edit' ),
					'course_id'  => $order_item->get_course_id( 'edit' ),
					'name'       => $order_item->get_name( 'edit' ),
					'type'       => $order_item->get_type( 'edit' ),
					'quantity'   => $order_item->get_quantity( 'edit' ),
					'tax'        => $order_item->get_tax( 'edit' ),
					'total'      => $order_item->get_total( 'edit' ),
				),
				$order_item
			)
		);

		if ( $is_success && $wpdb->insert_id ) {
			$order_item->set_id( $wpdb->insert_id );
			$this->update_custom_table_meta( $order_item, true );
			// TODO Invalidate caches.

			$order_item->save_meta_data();
			$order_item->apply_changes();

			do_action( 'masteriyo_new_order_item', $wpdb->insert_id, $order_item );
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
		if ( ! $order_item->get_id() ) {
			throw new \Exception( __( 'Invalid order item.', 'masteriyo' ) );
		}

		global $wpdb;

		$table_name = $this->get_table_name();

		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$table_name}
				WHERE id = %d
				LIMIT 1",
				$order_item->get_id()
			)
		);

		if ( ! is_array( $results ) || count( $results ) === 0 ) {
			throw new \Exception( __( 'Order item not found.', 'masteriyo' ) );
		}

		$order_item_obj = $results[0];

		$order_item->set_props( array(
			'order_id'   => $order_item_obj->order_id,
			'course_id' => $order_item_obj->course_id,
			'name'       => $order_item_obj->name,
			'type'       => $order_item_obj->type,
			'quantity'   => $order_item_obj->quantity,
			'tax'        => $order_item_obj->tax,
			'total'      => $order_item_obj->total,
		) );

		$this->read_order_item_data( $order_item );
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

		// Only update when there are changes.
		if ( count( $changes ) > 0 ) {
			$GLOBALS['wpdb']->update(
				$this->get_table_name(),
				array(
					'order_id'   => $order_item->get_order_id( 'edit' ),
					'course_id' => $order_item->get_course_id( 'edit' ),
					'name'       => $order_item->get_name( 'edit' ),
					'type'       => $order_item->get_type( 'edit' ),
					'quantity'   => $order_item->get_quantity( 'edit' ),
					'tax'        => $order_item->get_tax( 'edit' ),
					'total'      => $order_item->get_total( 'edit' ),
				),
				array(
					'id' => $order_item->get_id(),
				)
			);
		}

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

		$GLOBALS['wpdb']->delete(
			$this->get_table_name(),
			array(
				'id' => $order_item->get_id(),
			)
		);
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
	protected function read_order_item_data( &$order_item ) {
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

	/**
	 * Get table name.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_table_name() {
		return "{$GLOBALS['wpdb']->prefix}masteriyo_order_items";
	}
}
