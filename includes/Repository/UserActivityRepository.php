<?php
/**
 * User Activity Repository.
 *
 * @package ThemeGrill\Masteriyo\Repository;
 */

namespace ThemeGrill\Masteriyo\Repository;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Models\UserActivity;
use ThemeGrill\Masteriyo\Helper\Number;

/**
 * User Activity repository class.
 */
class UserActivityRepository extends AbstractRepository {

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 3.0.0
	 * @var array
	 */
	protected $internal_meta_keys = array();

	/**
	 * Create a useractivity in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $user_activity UserActivity object.
	 */
	public function create( Model &$user_activity ) {
		global $wpdb;

		if ( ! $user_activity->get_date_start( 'edit' ) ) {
			$user_activity->set_date_start( current_time( 'mysql', true ) );
		}

		$table = $user_activity->get_table();

		$result = $wpdb->insert(
			$table,
			apply_filters(
				'masteriyo_new_user_activity_data',
				array(
					'user_id'           => $user_activity->get_user_id( 'edit' ),
					'item_id'           => $user_activity->get_item_id( 'edit' ),
					'item_type'         => $user_activity->get_item_type( 'edit' ),
					'activity_type'     => $user_activity->get_type( 'edit' ),
					'activity_status'   => $user_activity->get_status( 'edit' ),
					'activity_start'    => $user_activity->get_date_start( 'edit' ),
					'activity_update'   => $user_activity->get_date_update( 'edit' ),
					'activity_complete' => $user_activity->get_date_complete( 'edit' ),
				),
				$user_activity
			),
			array( '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s' )
		);

		if ( $result && $wpdb->inser_id ) {
			$user_activity->set_id( $id );
			$item->set_id( $wpdb->insert_id );
			$this->update_custom_table_meta( $item, true );
			$item->save_meta_data();
			$item->apply_changes();
			$this->clear_cache( $item );

			do_action( 'masteriyo_new_user_activity', $item->get_id(), $item, $item->get_order_id() );
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

		if ( array_intersect( array( 'user_activity_name', 'order_id' ), array_keys( $changes ) ) ) {
			$wpdb->update(
				$wpdb->prefix . 'masteriyo_user_activitys',
				array(
					'user_activity_name' => $item->get_name(),
					'user_activity_type' => $item->get_type(),
					'order_id'           => $item->get_order_id(),
				),
				array( 'user_activity_id' => $item->get_id() )
			);
		}

		$this->update_custom_table_meta( $item );
		$item->save_meta_data();
		$item->apply_changes();
		$this->clear_cache( $item );

		do_action( 'masteriyo_update_user_activity', $item->get_id(), $item, $item->get_order_id() );
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
			do_action( 'masteriyo_before_delete_user_activity', $item->get_id() );
			$wpdb->delete( $wpdb->prefix . 'masteriyo_user_activitys', array( 'user_activity_id' => $item->get_id() ) );
			$wpdb->delete( $wpdb->prefix . 'masteriyo_user_activitymeta', array( 'user_activity_id' => $item->get_id() ) );
			do_action( 'masteriyo_delete_user_activity', $item->get_id() );
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
			$data = $wpdb->get_row( $wpdb->prepare( "SELECT order_id, name FROM {$wpdb->prefix}masteriyo_user_activitys WHERE user_activity_id = %d LIMIT 1;", $item->get_id() ) );
			wp_cache_set( 'item-' . $item->get_id(), $data, 'order-items' );
		}

		if ( ! $data ) {
			throw new Exception( __( 'Invalid order item.', 'masteriyo' ) );
		}

		$item->set_props(
			array(
				'order_id'           => $data->order_id,
				'user_activity_name' => $data->name,
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

	/**
	 * Fetch courses.
	 *
	 * @since 0.1.0
	 *
	 * @param array $query_vars Query vars.
	 * @return OrderItem[]
	 */
	public function query( $query_vars ) {
		global $wpdb;

		$order_id = absint( $query_vars['order_id'] );

		$user_activitys = array();
		$order          = get_post( $order_id );

		if ( is_null( $order ) || 'mto-order' !== $order->post_type ) {
			return $user_activitys;
		}

		$items = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}masteriyo_user_activitys WHERE order_id = %d",
				$order_id
			)
		);

		$item_objects = array_filter( array_map( array( $this, 'get_user_activity_object' ), $items ) );

		$item_objects = array_filter(
			array_map(
				function( $item_object ) {
					return $this->get_user_activity_meta( $item_object );
				},
				$item_objects
			)
		);

		return $item_objects;
	}

	/**
	 * Get order item object.
	 *
	 * @since 0.1.0
	 *
	 * @param stdclass $item Order item
	 * @return OrderItem
	 */
	public function get_user_activity_object( $item ) {
		$type = trim( $item->user_activity_type );
		$type = empty( $type ) ? 'course' : $type;

		try {
			$item_obj = masteriyo( "order-item.{$type}" );
			$item_obj->set_id( $item->user_activity_id );
			$item_obj->set_props(
				array(
					'order_id' => $item->order_id,
					'name'     => $item->user_activity_name,
				)
			);
		} catch ( \Exception $error ) {
			error_log( $error->getMessage() );
		}

		return $item_obj;
	}

	/**
	 * Get order item meta.
	 *
	 * @since 0.1.0
	 *
	 * @param OrderItem $item Order item object.
	 * @param stdclass $item_metas List of all order item meta.
	 *
	 * @return
	 */
	public function get_user_activity_meta( $item ) {
		$meta_values = $this->read_meta( $item );

		foreach ( $meta_values  as $meta_value ) {
			$function = "set_{$meta_value->key}";

			if ( is_callable( array( $item, $function ) ) ) {
				$item->$function( maybe_unserialize( $meta_value->value ) );
			}
		}

		return $item;
	}
}
