<?php
/**
 * Order helper functions.
 *
 * @since 1.0.0
 * @package Masteriyo\Helper
 */

use Masteriyo\Enums\OrderStatus;

/**
 * Get order.
 *
 * @since 1.0.0
 *
 * @param int|Order|WP_Post $order Order id or Order Model or Post.
 * @return Order|null
 */
function masteriyo_get_order( $order ) {
	$order_obj   = masteriyo( 'order' );
	$order_store = masteriyo( 'order.store' );

	if ( is_a( $order, 'Masteriyo\Models\Order\Order' ) ) {
		$id = $order->get_id();
	} elseif ( is_a( $order, 'WP_Post' ) ) {
		$id = $order->ID;
	} else {
		$id = $order;
	}

	try {
		$id = absint( $id );
		$order_obj->set_id( $id );
		$order_store->read( $order_obj );
	} catch ( \Exception $e ) {
		return null;
	}
	return apply_filters( 'masteriyo_get_order', $order_obj, $order );
}

/**
 * Get order item.
 *
 * @since 1.0.0
 *
 * @param int|OrderItem $order Order id or Order Model or Post.
 *
 * @return OrderItem|null
 */
function masteriyo_get_order_item( $order_item ) {
	$order_item_obj   = masteriyo( 'order-item.course' );
	$order_item_store = masteriyo( 'order-item.course.store' );

	try {
		if ( is_a( $order_item, 'Masteriyo\Models\Order\OrderItem' ) ) {
			$id = $order_item->get_id();
		} elseif ( is_a( $order_item, \stdClass::class ) ) {
			$id = $order_item->id;
		} else {
			$id = $order_item;
		}

		$id = absint( $id );
		$order_item_obj->set_id( $id );
		$order_item_store->read( $order_item_obj );
	} catch ( \Exception $e ) {
		return null;
	}
	return apply_filters( 'masteriyo_get_order_item', $order_item_obj, $order_item );
}

/**
 * Get list of status for order.
 *
 * @since 1.0.0
 *
 * @return array
 */
function masteriyo_get_order_statuses() {
	$order_statuses = array(
		'pending'   => array(
			'label'                     => _x( 'Pending payment', 'Order status', 'masteriyo' ),
			'public'                    => false,
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			// translators: %s: number of orders
			'label_count'               => _n_noop( 'Pending payment <span class="count">(%s)</span>', 'Pending payment <span class="count">(%s)</span>', 'masteriyo' ),
		),
		'on-hold'   => array(
			'label'                     => _x( 'On hold', 'Order status', 'masteriyo' ),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			/* translators: %s: number of orders */
			'label_count'               => _n_noop( 'On hold <span class="count">(%s)</span>', 'On hold <span class="count">(%s)</span>', 'masteriyo' ),
		),
		'completed' => array(
			'label'                     => _x( 'Completed', 'Order status', 'masteriyo' ),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			/* translators: %s: number of orders */
			'label_count'               => _n_noop( 'Completed <span class="count">(%s)</span>', 'Completed <span class="count">(%s)</span>', 'masteriyo' ),
		),
		'cancelled' => array(
			'label'                     => _x( 'Cancelled', 'Order status', 'masteriyo' ),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			/* translators: %s: number of orders */
			'label_count'               => _n_noop( 'Cancelled <span class="count">(%s)</span>', 'Cancelled <span class="count">(%s)</span>', 'masteriyo' ),
		),
		'refunded'  => array(
			'label'                     => _x( 'Refunded', 'Order status', 'masteriyo' ),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			/* translators: %s: number of orders */
			'label_count'               => _n_noop( 'Refunded <span class="count">(%s)</span>', 'Refunded <span class="count">(%s)</span>', 'masteriyo' ),
		),
		'failed'    => array(
			'label'                     => _x( 'Failed', 'Order status', 'masteriyo' ),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			/* translators: %s: number of orders */
			'label_count'               => _n_noop( 'Failed <span class="count">(%s)</span>', 'Failed <span class="count">(%s)</span>', 'masteriyo' ),
		),
	);

	return apply_filters( 'masteriyo_order_statuses', $order_statuses );
}

/**
 * See if a string is an order status.
 *
 * @since 1.0.0
 *
 * @param  string $maybe_status Status, including any masteriyo- prefix.
 * @return bool
 */
function masteriyo_is_order_status( $maybe_status ) {
	$order_statuses = masteriyo_get_order_statuses();
	return isset( $order_statuses[ $maybe_status ] );
}

/**
 * Get list of statuses which are consider 'paid'.
 *
 * @since  1.0.0
 * @return array
 */
function masteriyo_get_is_paid_statuses() {
	return apply_filters( 'masteriyo_order_is_paid_statuses', array( OrderStatus::PROCESSING, OrderStatus::COMPLETED ) );
}

/**
 * Get list of statuses which are consider 'pending payment'.
 *
 * @since  1.0.0
 * @return array
 */
function masteriyo_get_is_pending_statuses() {
	return apply_filters( 'masteriyo_order_is_pending_statuses', array( OrderStatus::PENDING ) );
}

/**
 * Get the nice name for an order status.
 *
 * @since  1.0.0
 * @param  string $status Status.
 * @return string
 */
function masteriyo_get_order_status_name( $status ) {
	$statuses = masteriyo_get_order_statuses();
	if ( isset( $statuses[ $status ] ) ) {
		return $statuses[ $status ]['label'];
	}

	return '';
}

/**
 * Get orders.
 *
 * @since 1.0.0
 *
 * @param array $args Query arguments.
 *
 * @return object|array[Order]
 */
function masteriyo_get_orders( $args = array() ) {
	$orders = masteriyo( 'query.orders' )->set_args( $args )->get_orders();

	return apply_filters( 'masteriyo_get_orders', $orders, $args );
}

/**
 * Get order items.
 *
 * @since 1.0.0
 *
 * @param array $args Query arguments.
 *
 * @return object|array[OrderItem]
 */
function masteriyo_get_order_items( $args = array() ) {
	$order_items = masteriyo( 'query.order-items' )->set_args( $args )->get_order_items();

	return apply_filters( 'masteriyo_get_order_items', $order_items, $args );
}

/**
 * Generate an order key with prefix.
 *
 * @since 1.0.0
 * @param string $key Order key without a prefix. By default generates a 13 digit secret.
 * @return string The order key.
 */
function masteriyo_generate_order_key( $key = '' ) {
	$key = trim( $key );
	$key = empty( $key ) ? wp_generate_password( 13, false ) : $key;

	return 'masteriyo_' . apply_filters( 'masteriyo_generate_order_key', 'order_' . $key );
}

/**
 * Finds an Order ID based on an order key.
 *
 * @since 1.0.0
 *
 * @param string $order_key An order key has generated by.
 * @return int The ID of an order, or 0 if the order could not be found.
 */
function masteriyo_get_order_id_by_order_key( $order_key ) {
	$data_store = masteriyo( 'order.store' );

	return $data_store->get_order_id_by_order_key( $order_key );
}

/**
 * Get Account > Orders columns.
 *
 * @since 1.0.0
 *
 * @return array
 */
function masteriyo_get_account_orders_columns() {
	$columns = apply_filters(
		'masteriyo_account_orders_columns',
		array(
			'order-number'  => __( 'Order', 'masteriyo' ),
			'order-date'    => __( 'Date', 'masteriyo' ),
			'order-status'  => __( 'Status', 'masteriyo' ),
			'order-total'   => __( 'Total', 'masteriyo' ),
			'order-actions' => __( 'Actions', 'masteriyo' ),
		)
	);
	return apply_filters( 'masteriyo_my_account_my_orders_columns', $columns );
}

/**
 * Get account orders actions.
 *
 * @since 1.0.0
 *
 * @param  int|Order $order Order instance or ID.
 *
 * @return array
 */
function masteriyo_get_account_orders_actions( $order ) {
	if ( ! is_object( $order ) ) {
		$order_id = absint( $order );
		$order    = masteriyo_get_order( $order_id );
	}

	$actions = array(
		'pay'    => array(
			'url'  => $order->get_checkout_payment_url(),
			'name' => __( 'Pay', 'masteriyo' ),
		),
		'view'   => array(
			'url'  => $order->get_view_order_url(),
			'url'  => '#',
			'name' => __( 'View', 'masteriyo' ),
		),
		'cancel' => array(
			'url'  => $order->get_cancel_order_url( masteriyo_get_page_permalink( 'account' ) ),
			'url'  => '#',
			'name' => __( 'Cancel', 'masteriyo' ),
		),
	);

	if ( ! $order->needs_payment() ) {
		unset( $actions['pay'] );
	}

	if ( ! in_array( $order->get_status(), apply_filters( 'masteriyo_valid_order_statuses_for_cancel', array( OrderStatus::PENDING, OrderStatus::FAILED ), $order ), true ) ) {
		unset( $actions['cancel'] );
	}

	return apply_filters( 'masteriyo_my_account_my_orders_actions', $actions, $order );
}
