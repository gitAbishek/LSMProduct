<?php
/**
 * OrderRepository class.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Repository;
 */

namespace ThemeGrill\Masteriyo\Repository;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Models\Order;

/**
 * OrderRepository class.
 */
class OrderRepository extends AbstractRepository implements RepositoryInterface {

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 3.0.0
	 * @var array
	 */
	protected $internal_meta_keys = array(
		'_total'         => 'total',
		'_discount'      => 'discount',
		'_currency'      => 'currency',
		'_product_ids'   => 'product_ids',
		'_expiry_date'   => 'expiry_date',
		'_date_created'  => 'date_created',
		'_date_modified' => 'date_modified',
		'_customer_id'   => 'customer_id',
	);

	/**
	 * Create a order in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $order order object.
	 */
	public function create( Model &$order ) {
		if ( ! $order->get_date_created( 'edit' ) ) {
			$order->set_date_created( current_time( 'mysql', true ) );
		}

		$id = wp_insert_post(
			apply_filters(
				'masteriyo_new_order_data',
				array(
					'post_type'      => 'masteriyo_order',
					'post_status'    => $order->get_status() ? $order->get_status() : 'pending',
					'post_author'    => get_current_user_id(),
					'post_title'     => __( 'Order', 'masteriyo' ),
					'post_content'   => __( 'Order', 'masteriyo' ),
					'post_excerpt'   => __( 'Order', 'masteriyo' ),
					'comment_status' => 'closed',
					'ping_status'    => 'closed',
					'post_date'      => $order->get_date_created( 'edit' ),
					'post_date_gmt'  => $order->get_date_created( 'edit' ),
				),
				$order
			)
		);

		if ( $id && ! is_wp_error( $id ) ) {
			$order->set_id( $id );
			$this->update_post_meta( $order, true );
			// TODO Invalidate caches.

			$order->save_meta_data();
			$order->apply_changes();

			do_action( 'masteriyo_new_order', $id, $order );
		}
	}

	/**
	 * Read an order.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $order Cource object.
	 *
	 * @throws \Exception If invalid order.
	 */
	public function read( Model &$order ) {
		$order_post = get_post( $order->get_id() );

		if ( ! $order->get_id() || ! $order_post || 'masteriyo_order' !== $order_post->post_type ) {
			throw new \Exception( __( 'Invalid order.', 'masteriyo' ) );
		}

		$order->set_props(
			array(
				'status'        => $order_post->post_status,
				'date_created'  => $order_post->post_date_gmt,
				'date_modified' => $order_post->post_modified_gmt,
			)
		);

		$this->read_order_data( $order );
		$this->read_extra_data( $order );
		$order->set_object_read( true );

		do_action( 'masteriyo_order_read', $order->get_id(), $order );
	}

	/**
	 * Update an order in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $order order object.
	 *
	 * @return void
	 */
	public function update( Model &$order ) {
		$changes = $order->get_changes();

		$post_data_keys = array(
			'status',
		);

		// Only update the post when the post data changes.
		if ( array_intersect( $post_data_keys, array_keys( $changes ) ) ) {
			$post_data = array(
				'post_status' => $order->get_status( 'edit' ),
				'post_type'   => 'masteriyo_order',
			);

			/**
			 * When updating this object, to prevent infinite loops, use $wpdb
			 * to update data, since wp_update_post spawns more calls to the
			 * save_post action.
			 *
			 * This ensures hooks are fired by either WP itself (admin screen save),
			 * or an update purely from CRUD.
			 */
			if ( doing_action( 'save_post' ) ) {
				// TODO Abstract the $wpdb WordPress class.
				$GLOBALS['wpdb']->update( $GLOBALS['wpdb']->posts, $post_data, array( 'ID' => $order->get_id() ) );
				clean_post_cache( $order->get_id() );
			} else {
				wp_update_post( array_merge( array( 'ID' => $order->get_id() ), $post_data ) );
			}
			$order->read_meta_data( true ); // Refresh internal meta data, in case things were hooked into `save_post` or another WP hook.
		} else { // Only update post modified time to record this save event.
			$GLOBALS['wpdb']->update(
				$GLOBALS['wpdb']->posts,
				array(
					'post_modified'     => current_time( 'mysql' ),
					'post_modified_gmt' => current_time( 'mysql', true ),
				),
				array(
					'ID' => $order->get_id(),
				)
			);
			clean_post_cache( $order->get_id() );
		}

		$this->update_post_meta( $order );

		$order->apply_changes();

		do_action( 'masteriyo_update_order', $order->get_id(), $order );
	}

	/**
	 * Delete an order from the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $order order object.
	 * @param array $args   Array of args to pass.alert-danger.
	 */
	public function delete( Model &$order, $args = array() ) {
		$id          = $order->get_id();
		$object_type = $order->get_object_type();

		$args = array_merge(
			array(
				'force_delete' => false,
			),
			$args
		);

		if ( ! $id ) {
			return;
		}

		if ( $args['force_delete'] ) {
			do_action( 'masteriyo_before_delete_' . $object_type, $id, $order );
			wp_delete_post( $id, true );
			$order->set_id( 0 );
			do_action( 'masteriyo_after_delete_' . $object_type, $id, $order );
		} else {
			do_action( 'masteriyo_before_trash_' . $object_type, $id, $order );
			wp_trash_post( $id );
			$order->set_status( 'trash' );
			do_action( 'masteriyo_before_trash_' . $object_type, $id, $order );
		}
	}

	/**
	 * Read order data. Can be overridden by child classes to load other props.
	 *
	 * @since 0.1.0
	 *
	 * @param Order $order order object.
	 */
	protected function read_order_data( &$order ) {
		$id          = $order->get_id();
		$meta_values = $this->read_meta( $order );

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

		$order->set_props( $set_props );
	}

	/**
	 * Read extra data associated with the order.
	 *
	 * @since 0.1.0
	 *
	 * @param Order $order Order object.
	 */
	protected function read_extra_data( &$order ) {
		$meta_values = $this->read_meta( $order );

		foreach ( $order->get_extra_data_keys() as $key ) {
			$function = 'set_' . $key;
			if ( is_callable( array( $order, $function ) )
				&& isset( $meta_values[ '_' . $key ] ) ) {
				$order->{$function}( $meta_values[ '_' . $key ] );
			}
		}
	}
}
