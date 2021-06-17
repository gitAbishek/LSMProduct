<?php
/**
 * OrderRepository class.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Repository;
 */

namespace ThemeGrill\Masteriyo\Repository;

defined( 'ABSPATH' ) || exit;

use ThemeGrill\Masteriyo\Constants;
use ThemeGrill\Masteriyo\Contracts\OrderRepository as OrderRepositoryInterface;
use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Models\Order;

/**
 * OrderRepository class.
 */
class OrderRepository extends AbstractRepository implements RepositoryInterface, OrderRepositoryInterface {

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 3.0.0
	 * @var array
	 */
	protected $internal_meta_keys = array(
		'total'                => '_total',
		'currency'             => '_currency',
		'version'              => '_version',
		'expiry_date'          => '_expiry_date',
		'transaction_id'       => '_transaction_id',
		'date_paid'            => '_date_paid',
		'date_completed'       => '_date_completed',
		'created_via'          => '_created_via',
		'customer_id'          => '_customer_id',
		'customer_ip_address'  => '_customer_ip_address',
		'customer_user_agent'  => '_customer_user_agent',
		'customer_note'        => '_customer_note',
		'payment_method'       => '_payment_method',
		'payment_method_title' => '_payment_method_title',
		'order_key'            => '_order_key',
		'cart_hash'            => '_cart_hash',
		'prices_include_tax'   => '_prices_include_tax',
		'billing_first_name'   => '_billing_first_name',
		'billing_last_name'    => '_billing_last_name',
		'billing_company'      => '_billing_company',
		'billing_address_1'    => '_billing_address_1',
		'billing_address_2'    => '_billing_address_2',
		'billing_city'         => '_billing_city',
		'billing_usercode'     => '_billing_usercode',
		'billing_country'      => '_billing_country',
		'billing_state'        => '_billing_state',
		'billing_email'        => '_billing_email',
		'billing_phone'        => '_billing_phone',
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
			$order->set_date_created( time() );
		}

		if ( '' === $order->get_order_key() ) {
			$order->set_order_key( masteriyo_generate_order_key() );
		}

		$order->set_currency( $order->get_currency() ? $order->get_currency() : masteriyo_get_currency() );
		$order->set_version( Constants::get( 'MASTERIYO_VERSION' ) );

		$id = wp_insert_post(
			apply_filters(
				'masteriyo_new_order_data',
				array(
					'post_type'     => 'mto-order',
					'post_status'   => $order->get_status() ? $order->get_status() : 'pending',
					'post_author'   => 1,
					'post_title'    => $this->get_order_title(),
					'post_password' => $this->get_order_key( $order ),
					'ping_status'   => 'closed',
					'post_excerpt'  => $order->get_customer_note( 'edit' ),
					'post_date'     => gmdate( 'Y-m-d H:i:s', $order->get_date_created( 'edit' )->getOffsetTimestamp() ),
					'post_date_gmt' => gmdate( 'Y-m-d H:i:s', $order->get_date_created( 'edit' )->getTimestamp() ),
				),
				$order
			),
			true
		);

		if ( $id && ! is_wp_error( $id ) ) {
			$order->set_id( $id );
			$this->update_post_meta( $order, true );
			// TODO Invalidate caches.

			$order->save_meta_data();
			$order->apply_changes();
			$this->clear_caches( $order );

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

		if ( ! $order->get_id() || ! $order_post || 'mto-order' !== $order_post->post_type ) {
			throw new \Exception( __( 'Invalid order.', 'masteriyo' ) );
		}

		$order->set_props(
			array(
				'status'        => $order_post->post_status,
				'date_created'  => $this->string_to_timestamp( $order_post->post_date_gmt ),
				'date_modified' => $this->string_to_timestamp( $order_post->post_modified_gmt ),
				'customer_note' => $order_post->post_excerpt,
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
		$order->set_version( Constants::get( 'MASTERIYO_VERSION' ) );

		$changes = $order->get_changes();

		$post_data_keys = array(
			'status',
			'date_modified',
		);

		// Only update the post when the post data changes.
		if ( array_intersect( $post_data_keys, array_keys( $changes ) ) ) {
			$post_data = array(
				'post_status' => $order->get_status( 'edit' ),
				'post_type'   => 'mto-order',
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
	 * Delete an order from the database.r
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

		foreach ( $this->internal_meta_keys as $prop => $meta_key ) {
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

	/**
	 * Fetch orders.
	 *
	 * @since 0.1.0
	 *
	 * @param array $query_vars Query vars.
	 * @return Order[]
	 */
	public function query( $query_vars ) {
		$args = $this->get_wp_query_args( $query_vars );

		if ( ! empty( $args['errors'] ) ) {
			$query = (object) array(
				'posts'         => array(),
				'found_posts'   => 0,
				'max_num_pages' => 0,
			);
		} else {
			$query = new \WP_Query( $args );
		}

		if ( isset( $query_vars['return'] ) && 'objects' === $query_vars['return'] && ! empty( $query->posts ) ) {
			// Prime caches before grabbing objects.
			update_post_caches( $query->posts, array( 'mto-order' ) );
		}

		$orders = ( isset( $query_vars['return'] ) && 'ids' === $query_vars['return'] ) ? $query->posts : array_filter( array_map( 'masteriyo_get_order', $query->posts ) );

		if ( isset( $query_vars['paginate'] ) && $query_vars['paginate'] ) {
			return (object) array(
				'orders'        => $orders,
				'total'         => $query->found_posts,
				'max_num_pages' => $query->max_num_pages,
			);
		}

		return $orders;
	}

	/**
	 * Get valid WP_Query args from a OrderQuery's query variables.
	 *
	 * @since 0.1.0
	 * @param array $query_vars Query vars from a OrderQuery.
	 * @return array
	 */
	protected function get_wp_query_args( $query_vars ) {
		// Map customer id.
		if ( isset( $query_vars['customer_id'] ) ) {
			$query_vars['author'] = $query_vars['customer_id'];
			unset( $query_vars['customer_id'] );
		}

		// Add the 'mto-' prefix to status if needed.
		if ( ! empty( $query_vars['status'] ) ) {
			if ( is_array( $query_vars['status'] ) ) {
				foreach ( $query_vars['status'] as &$status ) {
					$status = masteriyo_is_order_status( 'mto-' . $status ) ? 'mto-' . $status : $status;
				}
			} else {
				$query_vars['status'] = masteriyo_is_order_status( 'mto-' . $query_vars['status'] ) ? 'mto-' . $query_vars['status'] : $query_vars['status'];
			}
		}

		$wp_query_args = parent::get_wp_query_args( $query_vars );

		if ( ! isset( $wp_query_args['date_query'] ) ) {
			$wp_query_args['date_query'] = array();
		}
		if ( ! isset( $wp_query_args['meta_query'] ) ) {
			$wp_query_args['meta_query'] = array(); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		}

		// Handle date queries.
		$date_queries = array(
			'date_created'   => 'post_date',
			'date_modified'  => 'post_modified',
			'date_paid'      => '_date_paid',
			'date_completed' => '_date_completed',
		);
		foreach ( $date_queries as $query_var_key => $db_key ) {
			if ( isset( $query_vars[ $query_var_key ] ) && '' !== $query_vars[ $query_var_key ] ) {

				// Remove any existing meta queries for the same keys to prevent conflicts.
				$existing_queries = wp_list_pluck( $wp_query_args['meta_query'], 'key', true );
				foreach ( $existing_queries as $query_index => $query_contents ) {
					unset( $wp_query_args['meta_query'][ $query_index ] );
				}

				$wp_query_args = $this->parse_date_for_wp_query( $query_vars[ $query_var_key ], $db_key, $wp_query_args );
			}
		}

		// Handle paginate.
		if ( ! isset( $query_vars['paginate'] ) || ! $query_vars['paginate'] ) {
			$wp_query_args['no_found_rows'] = true;
		}

		// Handle orderby.
		if ( isset( $query_vars['orderby'] ) && 'include' === $query_vars['orderby'] ) {
			$wp_query_args['orderby'] = 'post__in';
		}

		return apply_filters( 'masteriyo_order_data_store_cpt_get_orders_query', $wp_query_args, $query_vars, $this );
	}

	/**
	 * Read order items of a specific type from the database for this order.
	 *
	 * @since 0.1.0
	 *
	 * @param  Order $order Order object.
	 * @param  string $type Order item type.
	 * @return array
	 */
	public function read_items( $order, $type ) {
		$type        = trim( $type );
		$order_items = array();

		// Get from cache if available.
		if ( $order->get_id() ) {
			$order_items = masteriyo( 'cache' )->get( 'masteriyo-order-items-' . $order->get_id(), 'masteriyo-orders' );
		}

		if ( ! empty( $order_items ) ) {
			return $order_items;
		}

		// Fetch from database.
		$order_items = masteriyo_get_order_items(
			array(
				'order_id' => $order->get_id(),
			)
		);

		foreach ( $order_items as $item ) {
			masteriyo( 'cache' )->set( 'masteriyo-item-' . $item->get_id(), $item, 'masteriyo-order-items' );
		}

		if ( $order->get_id() ) {
			masteriyo( 'cache' )->set( 'masteriyo-order-items-' . $order->get_id(), $order_items, 'masteriyo-orders' );
		}

		return $order_items;
	}

	/**
	 * Remove all line items (orders) from the order.
	 *
	 * @since 0.1.0
	 *
	 * @param Order $order Order object.
	 * @param string $type Order item type. Default null.
	 */
	public function delete_items( $order, $type = null ) {
		$order_items_repo = masteriyo( 'order-item.store' );

		if ( ! empty( $type ) ) {
			$order_items_repo->delete_batch(
				array(
					'order_id' => $order->get_id(),
					'type'     => $type,
				)
			);
		} else {
			$order_items_repo->delete_batch( array( 'order_id' => $order->get_id() ) );
		}

		$this->clear_caches( $order );
	}

	/**
	 * Get token ids for an order.
	 *
	 * @since 0.1.0
	 *
	 * @param Order $order Order object.
	 * @return array
	 */
	public function get_payment_token_ids( $order ) {
		$token_ids = array_filter( (array) get_post_meta( $order->get_id(), '_payment_tokens', true ) );
		return $token_ids;
	}

	/**
	 * Update token ids for an order.
	 *
	 * @since 0.1.0
	 *
	 * @param Order $order Order object.
	 * @param array    $token_ids Payment token ids.
	 */
	public function update_payment_token_ids( $order, $token_ids ) {
		update_post_meta( $order->get_id(), '_payment_tokens', $token_ids );
	}

	/**
	 * Clear any caches.
	 *
	 * @since 0.1.0
	 *
	 * @param Order $order Order object.
	 */
	protected function clear_caches( &$order ) {
		clean_post_cache( $order->get_id() );
		// Delete shop order transients.
		masteriyo( 'cache' )->delete( 'masteriyo-order-items-' . $order->get_id(), 'masteriyo-orders' );
	}

	/**
	 * Get a title for the new post type.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	protected function get_order_title() {
		// phpcs:enable
		/* translators: %s: Order date */
		return sprintf( __( 'Order &ndash; %s', 'masteriyo' ), strftime( _x( '%1$b %2$d, %Y @ %I:%M %p', 'Order date parsed by strftime', 'masteriyo' ) ) );
		// phpcs:disable
	}

	/**
	 * Get order key.
	 *
	 * @since 0.1.0
	 *
	 * @param Order $order Order object.
	 * @return string
	 */
	protected function get_order_key( $order ) {
		return masteriyo_generate_order_key();
	}

	/**
	 * Get amount already refunded.
	 *
	 * @since 0.1.0
	 *
	 * @param  Order $order Order object.
	 * @return float
	 */
	public function get_total_refunded( $order ) {
		global $wpdb;

		$total = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT SUM( postmeta.meta_value )
				FROM $wpdb->postmeta AS postmeta
				INNER JOIN $wpdb->posts AS posts ON ( posts.post_type = 'mto-order-refund' AND posts.post_parent = %d )
				WHERE postmeta.meta_key = '_refund_amount'
				AND postmeta.post_id = posts.ID",
				$order->get_id()
			)
		);

		return floatval( $total );
	}
}
